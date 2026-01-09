<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;
use App\Models\BorrowingModel;

/**
 * MemberController.php
 * Controller untuk CRUD anggota perpustakaan
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */
class MemberController extends BaseController
{
    protected MemberModel $memberModel;
    protected BorrowingModel $borrowingModel;

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->borrowingModel = new BorrowingModel();
    }

    /**
     * List semua anggota
     *
     * @return string
     */
    public function index(): string
    {
        $data = [
            'title'   => 'Manajemen Anggota',
            'members' => $this->memberModel->orderBy('name', 'ASC')->findAll(),
        ];

        return view('admin/members/index', $data);
    }

    /**
     * Form tambah anggota
     *
     * @return string
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Tambah Anggota',
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/members/create', $data);
    }

    /**
     * Simpan anggota baru (registered_at = today)
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]',
            'email'   => 'required|valid_email|max_length[100]|is_unique[members.email]',
            'phone'   => 'permit_empty|max_length[20]',
            'address' => 'permit_empty',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama anggota harus diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'email' => [
                'required'    => 'Email harus diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'phone' => [
                'max_length' => 'Nomor telepon maksimal 20 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'phone'         => $this->request->getPost('phone'),
            'address'       => $this->request->getPost('address'),
            'registered_at' => date('Y-m-d'), // Set registered_at = today
        ];

        // Save to database
        if ($this->memberModel->insert($data)) {
            return redirect()->to('/admin/members')->with('success', 'Anggota berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan anggota.');
    }

    /**
     * Form edit anggota
     *
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function edit(int $id)
    {
        $member = $this->memberModel->find($id);

        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Anggota tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Anggota',
            'member'     => $member,
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/members/edit', $data);
    }

    /**
     * Update anggota
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id)
    {
        $member = $this->memberModel->find($id);

        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Anggota tidak ditemukan.');
        }

        // Validation rules (email unique except current member)
        $rules = [
            'name'    => 'required|min_length[3]|max_length[100]',
            'email'   => "required|valid_email|max_length[100]|is_unique[members.email,id,{$id}]",
            'phone'   => 'permit_empty|max_length[20]',
            'address' => 'permit_empty',
        ];

        $messages = [
            'name' => [
                'required'   => 'Nama anggota harus diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
                'max_length' => 'Nama maksimal 100 karakter.',
            ],
            'email' => [
                'required'    => 'Email harus diisi.',
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah terdaftar.',
            ],
            'phone' => [
                'max_length' => 'Nomor telepon maksimal 20 karakter.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Prepare data
        $data = [
            'name'    => $this->request->getPost('name'),
            'email'   => $this->request->getPost('email'),
            'phone'   => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
        ];

        // Update database
        if ($this->memberModel->update($id, $data)) {
            return redirect()->to('/admin/members')->with('success', 'Anggota berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui anggota.');
    }

    /**
     * Hapus anggota (cek dulu apakah ada peminjaman aktif)
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id)
    {
        $member = $this->memberModel->find($id);

        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Anggota tidak ditemukan.');
        }

        // Check if member has active borrowings
        if ($this->borrowingModel->memberHasActiveBorrowings($id)) {
            return redirect()->to('/admin/members')->with('error', 'Anggota tidak dapat dihapus karena masih memiliki peminjaman aktif.');
        }

        // Delete from database
        if ($this->memberModel->delete($id)) {
            return redirect()->to('/admin/members')->with('success', 'Anggota berhasil dihapus.');
        }

        return redirect()->to('/admin/members')->with('error', 'Gagal menghapus anggota.');
    }

    /**
     * Detail anggota + history peminjaman
     *
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function show(int $id)
    {
        $member = $this->memberModel->find($id);

        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Anggota tidak ditemukan.');
        }

        // Get borrowing history for this member
        $borrowings = $this->borrowingModel->getBorrowingsByMember($id);

        // Calculate statistics
        $totalBorrowings = count($borrowings);
        $activeBorrowings = 0;
        $overdueBorrowings = 0;
        $returnedBorrowings = 0;

        foreach ($borrowings as $borrowing) {
            if ($borrowing['status'] === 'borrowed') {
                $activeBorrowings++;
                // Check if overdue
                if (strtotime($borrowing['due_date']) < strtotime(date('Y-m-d'))) {
                    $overdueBorrowings++;
                }
            } elseif ($borrowing['status'] === 'returned') {
                $returnedBorrowings++;
            } elseif ($borrowing['status'] === 'overdue') {
                $overdueBorrowings++;
            }
        }

        $data = [
            'title'      => 'Detail Anggota',
            'member'     => $member,
            'borrowings' => $borrowings,
            'stats'      => [
                'total'    => $totalBorrowings,
                'active'   => $activeBorrowings,
                'overdue'  => $overdueBorrowings,
                'returned' => $returnedBorrowings,
            ],
        ];

        return view('admin/members/show', $data);
    }
}
