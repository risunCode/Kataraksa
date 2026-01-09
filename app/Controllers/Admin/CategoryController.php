<?php
/**
 * CategoryController.php
 * Controller untuk CRUD kategori
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;
use App\Models\BookModel;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $bookModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->bookModel = new BookModel();
    }

    /**
     * List semua kategori
     * 
     * @return string
     */
    public function index()
    {
        $data = [
            'title'      => 'Manajemen Kategori - Kataraksa',
            'categories' => $this->categoryModel->getCategoriesWithBookCount(),
        ];

        return view('admin/categories/index', $data);
    }

    /**
     * Form tambah kategori
     * 
     * @return string
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Kategori - Kataraksa',
        ];

        return view('admin/categories/create', $data);
    }

    /**
     * Simpan kategori baru
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function store()
    {
        // Validasi input
        $rules = [
            'name' => [
                'rules'  => 'required|min_length[2]|max_length[100]|is_unique[categories.name]',
                'errors' => [
                    'required'   => 'Nama kategori harus diisi.',
                    'min_length' => 'Nama kategori minimal 2 karakter.',
                    'max_length' => 'Nama kategori maksimal 100 karakter.',
                    'is_unique'  => 'Nama kategori sudah ada.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Simpan data
        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if ($this->categoryModel->insert($data)) {
            return redirect()->to('/admin/categories')
                           ->with('success', 'Kategori berhasil ditambahkan.');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal menambahkan kategori.');
    }

    /**
     * Form edit kategori
     * 
     * @param int $id
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function edit($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/categories')
                           ->with('error', 'Kategori tidak ditemukan.');
        }

        $data = [
            'title'    => 'Edit Kategori - Kataraksa',
            'category' => $category,
        ];

        return view('admin/categories/edit', $data);
    }

    /**
     * Update kategori
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/categories')
                           ->with('error', 'Kategori tidak ditemukan.');
        }

        // Validasi input (is_unique dengan pengecualian id saat ini)
        $rules = [
            'name' => [
                'rules'  => "required|min_length[2]|max_length[100]|is_unique[categories.name,id,{$id}]",
                'errors' => [
                    'required'   => 'Nama kategori harus diisi.',
                    'min_length' => 'Nama kategori minimal 2 karakter.',
                    'max_length' => 'Nama kategori maksimal 100 karakter.',
                    'is_unique'  => 'Nama kategori sudah ada.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Update data
        $data = [
            'name' => $this->request->getPost('name'),
        ];

        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to('/admin/categories')
                           ->with('success', 'Kategori berhasil diperbarui.');
        }

        return redirect()->back()
                       ->withInput()
                       ->with('error', 'Gagal memperbarui kategori.');
    }

    /**
     * Hapus kategori
     * Cek dulu apakah ada buku yang menggunakan kategori ini
     * 
     * @param int $id
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id)
    {
        $category = $this->categoryModel->find($id);

        if (!$category) {
            return redirect()->to('/admin/categories')
                           ->with('error', 'Kategori tidak ditemukan.');
        }

        // Cek apakah ada buku yang menggunakan kategori ini
        $booksCount = $this->bookModel->where('category_id', $id)->countAllResults();

        if ($booksCount > 0) {
            return redirect()->to('/admin/categories')
                           ->with('error', "Kategori tidak dapat dihapus karena masih digunakan oleh {$booksCount} buku.");
        }

        // Hapus kategori
        if ($this->categoryModel->delete($id)) {
            return redirect()->to('/admin/categories')
                           ->with('success', 'Kategori berhasil dihapus.');
        }

        return redirect()->to('/admin/categories')
                       ->with('error', 'Gagal menghapus kategori.');
    }
}
