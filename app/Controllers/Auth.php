<?php
/**
 * Auth.php
 * Controller untuk login dan logout
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MemberModel;
use App\Filters\RateLimitFilter;

class Auth extends BaseController
{
    protected $userModel;
    protected $memberModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->memberModel = new MemberModel();
        $this->session = session();
        helper('security');
    }

    /**
     * Tampilkan form login
     * 
     * @return string
     */
    public function index()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if ($this->session->get('isLoggedIn')) {
            $role = $this->session->get('role');
            if ($role === 'member') {
                return redirect()->to('/member/dashboard');
            }
            return redirect()->to('/admin/dashboard');
        }

        $data = [
            'title' => 'Login - Kataraksa',
        ];

        return view('auth/login', $data);
    }

    /**
     * Proses login
     * Validasi email & password, set session
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function login()
    {
        // Validasi input
        $rules = [
            'email' => [
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => 'Email harus diisi.',
                    'valid_email' => 'Format email tidak valid.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password harus diisi.',
                    'min_length' => 'Password minimal 6 karakter.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cari user berdasarkan email (admin/petugas)
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            // Verifikasi password untuk user (admin/petugas)
            if (!password_verify($password, $user['password'])) {
                // Record failed attempt
                RateLimitFilter::recordFailedLogin($this->request->getIPAddress());
                log_security_event('LOGIN_FAILED', ['email' => $email, 'reason' => 'wrong_password']);
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Email atau password salah.');
            }

            // Clear failed login attempts on success
            RateLimitFilter::clearLoginAttempts($this->request->getIPAddress());
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set session data untuk admin/petugas
            $sessionData = [
                'isLoggedIn'   => true,
                'user_id'      => $user['id'],
                'user_name'    => $user['name'],
                'user_email'   => $user['email'],
                'role'         => $user['role'],
                'login_time'   => time(),
                'ip_address'   => $this->request->getIPAddress(),
            ];

            $this->session->set($sessionData);
            
            // Log successful login
            log_security_event('LOGIN_SUCCESS', ['user_id' => $user['id'], 'email' => $user['email']]);

            // Redirect ke admin dashboard
            return redirect()->to('/admin/dashboard')
                            ->with('swal_welcome', $user['name']);
        }

        // Jika tidak ditemukan di users, cek di members table
        $member = $this->memberModel->findByEmail($email);

        if ($member) {
            // Cek apakah member punya password
            if (empty($member['password'])) {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Akun member belum diaktifkan. Silakan hubungi petugas perpustakaan.');
            }

            // Verifikasi password untuk member
            if (!password_verify($password, $member['password'])) {
                // Record failed attempt
                RateLimitFilter::recordFailedLogin($this->request->getIPAddress());
                log_security_event('LOGIN_FAILED', ['email' => $email, 'reason' => 'wrong_password', 'type' => 'member']);
                
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Email atau password salah.');
            }

            // Clear failed login attempts on success
            RateLimitFilter::clearLoginAttempts($this->request->getIPAddress());
            
            // Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Set session data untuk member
            $sessionData = [
                'isLoggedIn'   => true,
                'user_id'      => $member['id'],
                'user_name'    => $member['name'],
                'user_email'   => $member['email'],
                'role'         => 'member',
                'member_id'    => $member['id'],
                'login_time'   => time(),
                'ip_address'   => $this->request->getIPAddress(),
            ];

            $this->session->set($sessionData);
            
            // Log successful login
            log_security_event('LOGIN_SUCCESS', ['member_id' => $member['id'], 'email' => $member['email'], 'type' => 'member']);

            // Redirect ke member dashboard
            return redirect()->to('/member/dashboard')
                            ->with('swal_welcome', $member['name']);
        }

        // User tidak ditemukan di kedua table
        RateLimitFilter::recordFailedLogin($this->request->getIPAddress());
        log_security_event('LOGIN_FAILED', ['email' => $email, 'reason' => 'user_not_found']);
        
        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Email atau password salah.');
    }

    /**
     * Logout - destroy session dan redirect ke login
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function logout()
    {
        // Destroy session
        $this->session->destroy();

        // Redirect ke halaman login dengan pesan
        return redirect()->to('/login')
                        ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Alias for index - show login form
     * 
     * @return string
     */
    public function attemptLogin()
    {
        return $this->login();
    }

    /**
     * Register new member
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function register()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $phone = $this->request->getPost('phone');
        $password = $this->request->getPost('password');

        // Validate
        if (empty($name) || strlen($name) < 3) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nama minimal 3 karakter.']);
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email tidak valid.']);
        }

        if (empty($password) || strlen($password) < 6) {
            return $this->response->setJSON(['success' => false, 'message' => 'Password minimal 6 karakter.']);
        }

        // Check if email already exists
        $existingMember = $this->memberModel->findByEmail($email);
        if ($existingMember) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email sudah terdaftar.']);
        }

        // Also check in users table
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email sudah terdaftar.']);
        }

        // Create member
        try {
            $this->memberModel->insert([
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'phone' => $phone,
                'registered_at' => date('Y-m-d'),
            ]);

            log_security_event('MEMBER_REGISTERED', ['email' => $email]);

            return $this->response->setJSON(['success' => true, 'message' => 'Pendaftaran berhasil!']);
        } catch (\Exception $e) {
            log_message('error', 'Registration failed: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }
}
