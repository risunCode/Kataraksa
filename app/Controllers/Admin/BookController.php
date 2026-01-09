<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\CategoryModel;

/**
 * BookController.php
 * Controller untuk CRUD buku
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */
class BookController extends BaseController
{
    protected BookModel $bookModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * List semua buku dengan kategori
     *
     * @return string
     */
    public function index(): string
    {
        $data = [
            'title' => 'Manajemen Buku',
            'books' => $this->bookModel->getBooksWithCategory(),
        ];

        return view('admin/books/index', $data);
    }

    /**
     * Form tambah buku (dropdown kategori)
     *
     * @return string
     */
    public function create(): string
    {
        $data = [
            'title'      => 'Tambah Buku',
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/books/create', $data);
    }

    /**
     * Simpan buku baru + upload cover ke public/uploads/covers/
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Validation rules
        $rules = [
            'category_id' => 'required|integer|is_not_unique[categories.id]',
            'title'       => 'required|min_length[2]|max_length[200]',
            'author'      => 'required|min_length[2]|max_length[100]',
            'isbn'        => 'permit_empty|max_length[20]',
            'synopsis'    => 'permit_empty',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'cover'       => 'permit_empty|is_image[cover]|max_size[cover,2048]|mime_in[cover,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        $messages = [
            'category_id' => [
                'required'      => 'Kategori harus dipilih.',
                'is_not_unique' => 'Kategori tidak ditemukan.',
            ],
            'title' => [
                'required'   => 'Judul buku harus diisi.',
                'min_length' => 'Judul minimal 2 karakter.',
            ],
            'author' => [
                'required'   => 'Nama penulis harus diisi.',
                'min_length' => 'Nama penulis minimal 2 karakter.',
            ],
            'stock' => [
                'required'              => 'Stok harus diisi.',
                'integer'               => 'Stok harus berupa angka.',
                'greater_than_equal_to' => 'Stok tidak boleh negatif.',
            ],
            'cover' => [
                'is_image' => 'File harus berupa gambar.',
                'max_size' => 'Ukuran gambar maksimal 2MB.',
                'mime_in'  => 'Format gambar harus JPG, PNG, atau WebP.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle cover upload
        $coverName = null;
        $cover = $this->request->getFile('cover');
        
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            $coverName = $cover->getRandomName();
            
            // Create directory if not exists
            $uploadPath = FCPATH . 'uploads/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $cover->move($uploadPath, $coverName);
        }

        // Prepare data
        $stock = (int) $this->request->getPost('stock');
        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'title'       => $this->request->getPost('title'),
            'author'      => $this->request->getPost('author'),
            'isbn'        => $this->request->getPost('isbn'),
            'synopsis'    => $this->request->getPost('synopsis'),
            'stock'       => $stock,
            'available'   => $stock, // Available = stock saat pertama kali ditambahkan
            'cover'       => $coverName,
        ];

        // Save to database
        if ($this->bookModel->insert($data)) {
            return redirect()->to('/admin/books')->with('success', 'Buku berhasil ditambahkan.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan buku.');
    }

    /**
     * Form edit buku
     *
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function edit(int $id)
    {
        $book = $this->bookModel->find($id);

        if (!$book) {
            return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');
        }

        $data = [
            'title'      => 'Edit Buku',
            'book'       => $book,
            'categories' => $this->categoryModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('admin/books/edit', $data);
    }

    /**
     * Update buku + handle upload cover baru
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update(int $id)
    {
        $book = $this->bookModel->find($id);

        if (!$book) {
            return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');
        }

        // Validation rules
        $rules = [
            'category_id' => 'required|integer|is_not_unique[categories.id]',
            'title'       => 'required|min_length[2]|max_length[200]',
            'author'      => 'required|min_length[2]|max_length[100]',
            'isbn'        => 'permit_empty|max_length[20]',
            'synopsis'    => 'permit_empty',
            'stock'       => 'required|integer|greater_than_equal_to[0]',
            'cover'       => 'permit_empty|is_image[cover]|max_size[cover,2048]|mime_in[cover,image/jpg,image/jpeg,image/png,image/webp]',
        ];

        $messages = [
            'category_id' => [
                'required'      => 'Kategori harus dipilih.',
                'is_not_unique' => 'Kategori tidak ditemukan.',
            ],
            'title' => [
                'required'   => 'Judul buku harus diisi.',
                'min_length' => 'Judul minimal 2 karakter.',
            ],
            'author' => [
                'required'   => 'Nama penulis harus diisi.',
                'min_length' => 'Nama penulis minimal 2 karakter.',
            ],
            'stock' => [
                'required'              => 'Stok harus diisi.',
                'integer'               => 'Stok harus berupa angka.',
                'greater_than_equal_to' => 'Stok tidak boleh negatif.',
            ],
            'cover' => [
                'is_image' => 'File harus berupa gambar.',
                'max_size' => 'Ukuran gambar maksimal 2MB.',
                'mime_in'  => 'Format gambar harus JPG, PNG, atau WebP.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle cover upload
        $coverName = $book['cover']; // Keep old cover by default
        $cover = $this->request->getFile('cover');
        
        if ($cover && $cover->isValid() && !$cover->hasMoved()) {
            // Delete old cover if exists
            if ($book['cover']) {
                $oldCoverPath = FCPATH . 'uploads/covers/' . $book['cover'];
                if (file_exists($oldCoverPath)) {
                    unlink($oldCoverPath);
                }
            }

            $coverName = $cover->getRandomName();
            
            // Create directory if not exists
            $uploadPath = FCPATH . 'uploads/covers/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $cover->move($uploadPath, $coverName);
        }

        // Calculate available based on stock change
        $newStock = (int) $this->request->getPost('stock');
        $oldStock = (int) $book['stock'];
        $oldAvailable = (int) $book['available'];
        
        // Borrowed count = old stock - old available
        $borrowedCount = $oldStock - $oldAvailable;
        
        // New available = new stock - borrowed count (minimum 0)
        $newAvailable = max(0, $newStock - $borrowedCount);

        // Prepare data
        $data = [
            'category_id' => $this->request->getPost('category_id'),
            'title'       => $this->request->getPost('title'),
            'author'      => $this->request->getPost('author'),
            'isbn'        => $this->request->getPost('isbn'),
            'synopsis'    => $this->request->getPost('synopsis'),
            'stock'       => $newStock,
            'available'   => $newAvailable,
            'cover'       => $coverName,
        ];

        // Update database
        if ($this->bookModel->update($id, $data)) {
            return redirect()->to('/admin/books')->with('success', 'Buku berhasil diperbarui.');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui buku.');
    }

    /**
     * Hapus buku + hapus file cover
     *
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete(int $id)
    {
        $book = $this->bookModel->find($id);

        if (!$book) {
            return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');
        }

        // Check if book has active borrowings
        $borrowingModel = new \App\Models\BorrowingModel();
        $activeBorrowings = $borrowingModel->where('book_id', $id)
                                           ->where('status', 'borrowed')
                                           ->countAllResults();

        if ($activeBorrowings > 0) {
            return redirect()->to('/admin/books')->with('error', 'Buku tidak dapat dihapus karena masih ada peminjaman aktif.');
        }

        // Delete cover file if exists
        if ($book['cover']) {
            $coverPath = FCPATH . 'uploads/covers/' . $book['cover'];
            if (file_exists($coverPath)) {
                unlink($coverPath);
            }
        }

        // Delete from database
        if ($this->bookModel->delete($id)) {
            return redirect()->to('/admin/books')->with('success', 'Buku berhasil dihapus.');
        }

        return redirect()->to('/admin/books')->with('error', 'Gagal menghapus buku.');
    }

    /**
     * Show book detail
     *
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function show(int $id)
    {
        $book = $this->bookModel->getBookWithCategory($id);

        if (!$book) {
            return redirect()->to('/admin/books')->with('error', 'Buku tidak ditemukan.');
        }

        // Get borrowing history for this book
        $borrowingModel = new \App\Models\BorrowingModel();
        $borrowings = $borrowingModel->select('borrowings.*, members.name as member_name')
                                     ->join('members', 'members.id = borrowings.member_id', 'left')
                                     ->where('borrowings.book_id', $id)
                                     ->orderBy('borrowings.created_at', 'DESC')
                                     ->findAll();

        $data = [
            'title'      => 'Detail Buku',
            'book'       => $book,
            'borrowings' => $borrowings,
        ];

        return view('admin/books/show', $data);
    }
}
