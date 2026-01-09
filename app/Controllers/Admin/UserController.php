<?php
/**
 * UserController.php
 * Controller untuk CRUD user sistem (admin & petugas)
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }

    /**
     * List semua user (admin & petugas)
     * 
     * @return string
     */
    public function index()
    {
        $data = [
            'title' => 'Manajemen User - Kataraksa',
            'users' => $this->userModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/users/index', $data);
    }

    /**
     * Form tambah user
     * 
     * @return string
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah User - Kataraksa',
        ];

        return view('admin/users/create', $data);
    }

    /**
     * Simpan user baru (hash password)
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Validasi input
        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama harus diisi.',
                    'min_length' => 'Nama minimal 3 karakter.',
                    'max_length' => 'Nama maksimal 100 karakter.',
                ],
            ],
            'email' => [
                'rules'  => 'required|valid_email|max_length[100]|is_unique[users.email]',
                'errors' => [
                    'required'    => 'Email harus diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'max_length'  => 'Email maksimal 100 karakter.',
                    'is_unique'   => 'Email sudah terdaftar.',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => 'Password harus diisi.',
                    'min_length' => 'Password minimal 6 karakter.',
                ],
            ],
            'password_confirm' => [
                'rules'  => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi.',
                    'matches'  => 'Konfirmasi password tidak cocok.',
                ],
            ],
            'role' => [
                'rules'  => 'required|in_list[admin,petugas]',
                'errors' => [
                    'required' => 'Role harus dipilih.',
                    'in_list'  => 'Role tidak valid.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Simpan data dengan password yang di-hash
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
        ];

        // Skip validation di model karena sudah divalidasi di controller
        if ($this->userModel->skipValidation(true)->insert($data)) {
            return redirect()->to('/admin/users')
                           ->with('success', 'User berhasil ditambahkan.');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan user.');
    }

    /**
     * Form edit user
     * 
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function edit($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                           ->with('error', 'User tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit User - Kataraksa',
            'user'  => $user,
        ];

        return view('admin/users/edit', $data);
    }

    /**
     * Update user (password opsional)
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                           ->with('error', 'User tidak ditemukan.');
        }

        // Validasi input (password opsional saat update)
        $rules = [
            'name' => [
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => 'Nama harus diisi.',
                    'min_length' => 'Nama minimal 3 karakter.',
                    'max_length' => 'Nama maksimal 100 karakter.',
                ],
            ],
            'email' => [
                'rules'  => "required|valid_email|max_length[100]|is_unique[users.email,id,{$id}]",
                'errors' => [
                    'required'    => 'Email harus diisi.',
                    'valid_email' => 'Format email tidak valid.',
                    'max_length'  => 'Email maksimal 100 karakter.',
                    'is_unique'   => 'Email sudah terdaftar.',
                ],
            ],
            'role' => [
                'rules'  => 'required|in_list[admin,petugas]',
                'errors' => [
                    'required' => 'Role harus dipilih.',
                    'in_list'  => 'Role tidak valid.',
                ],
            ],
        ];

        // Tambahkan validasi password jika diisi
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = [
                'rules'  => 'min_length[6]',
                'errors' => [
                    'min_length' => 'Password minimal 6 karakter.',
                ],
            ];
            $rules['password_confirm'] = [
                'rules'  => 'matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi password tidak cocok.',
                ],
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Siapkan data update
        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        // Update password hanya jika diisi
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        // Skip validation di model karena sudah divalidasi di controller
        if ($this->userModel->skipValidation(true)->update($id, $data)) {
            return redirect()->to('/admin/users')
                           ->with('success', 'User berhasil diperbarui.');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui user.');
    }

    /**
     * Hapus user
     * Tidak bisa hapus diri sendiri
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')
                           ->with('error', 'User tidak ditemukan.');
        }

        // Cek apakah user mencoba menghapus diri sendiri
        $currentUserId = $this->session->get('userId');
        if ($id == $currentUserId) {
            return redirect()->to('/admin/users')
                           ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Hapus user
        if ($this->userModel->delete($id)) {
            return redirect()->to('/admin/users')
                           ->with('success', 'User berhasil dihapus.');
        }

        return redirect()->to('/admin/users')
                       ->with('error', 'Gagal menghapus user.');
    }
}
