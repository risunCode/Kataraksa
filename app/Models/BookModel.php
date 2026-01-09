<?php
/**
 * BookModel.php
 * Model untuk data buku
 * 
 * Project: Kataraksa - Sistem Perpustakaan Digital
 * Author: Dimas
 */

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table            = 'books';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id', 
        'title', 
        'author', 
        'isbn', 
        'synopsis', 
        'stock', 
        'available', 
        'cover'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'category_id' => 'required|integer',
        'title'       => 'required|min_length[2]|max_length[200]',
        'author'      => 'required|min_length[2]|max_length[100]',
        'isbn'        => 'permit_empty|max_length[20]',
        'synopsis'    => 'permit_empty',
        'stock'       => 'required|integer|greater_than_equal_to[0]',
        'available'   => 'required|integer|greater_than_equal_to[0]',
        'cover'       => 'permit_empty|max_length[255]',
    ];

    protected $validationMessages = [
        'category_id' => [
            'required' => 'Kategori harus dipilih.',
            'integer'  => 'Kategori tidak valid.',
        ],
        'title' => [
            'required'   => 'Judul buku harus diisi.',
            'min_length' => 'Judul buku minimal 2 karakter.',
            'max_length' => 'Judul buku maksimal 200 karakter.',
        ],
        'author' => [
            'required'   => 'Penulis harus diisi.',
            'min_length' => 'Nama penulis minimal 2 karakter.',
            'max_length' => 'Nama penulis maksimal 100 karakter.',
        ],
        'isbn' => [
            'max_length' => 'ISBN maksimal 20 karakter.',
        ],
        'stock' => [
            'required'              => 'Stok harus diisi.',
            'integer'               => 'Stok harus berupa angka.',
            'greater_than_equal_to' => 'Stok tidak boleh negatif.',
        ],
        'available' => [
            'required'              => 'Jumlah tersedia harus diisi.',
            'integer'               => 'Jumlah tersedia harus berupa angka.',
            'greater_than_equal_to' => 'Jumlah tersedia tidak boleh negatif.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all books with category name
     *
     * @return array
     */
    public function getBooksWithCategory(): array
    {
        return $this->select('books.*, categories.name as category_name')
                    ->join('categories', 'categories.id = books.category_id', 'left')
                    ->orderBy('books.title', 'ASC')
                    ->findAll();
    }

    /**
     * Get book by ID with category name
     *
     * @param int $id
     * @return array|null
     */
    public function getBookWithCategory(int $id): ?array
    {
        return $this->select('books.*, categories.name as category_name')
                    ->join('categories', 'categories.id = books.category_id', 'left')
                    ->where('books.id', $id)
                    ->first();
    }

    /**
     * Get available books (available > 0)
     *
     * @return array
     */
    public function getAvailableBooks(): array
    {
        return $this->select('books.*, categories.name as category_name')
                    ->join('categories', 'categories.id = books.category_id', 'left')
                    ->where('books.available >', 0)
                    ->orderBy('books.title', 'ASC')
                    ->findAll();
    }

    /**
     * Decrease available stock when book is borrowed
     *
     * @param int $bookId
     * @return bool
     */
    public function decreaseAvailable(int $bookId): bool
    {
        $book = $this->find($bookId);
        if ($book && $book['available'] > 0) {
            return $this->update($bookId, ['available' => $book['available'] - 1]);
        }
        return false;
    }

    /**
     * Increase available stock when book is returned
     *
     * @param int $bookId
     * @return bool
     */
    public function increaseAvailable(int $bookId): bool
    {
        $book = $this->find($bookId);
        if ($book && $book['available'] < $book['stock']) {
            return $this->update($bookId, ['available' => $book['available'] + 1]);
        }
        return false;
    }

    /**
     * Search books by title or author
     *
     * @param string $keyword
     * @return array
     */
    public function searchBooks(string $keyword): array
    {
        return $this->select('books.*, categories.name as category_name')
                    ->join('categories', 'categories.id = books.category_id', 'left')
                    ->groupStart()
                        ->like('books.title', $keyword)
                        ->orLike('books.author', $keyword)
                        ->orLike('books.isbn', $keyword)
                    ->groupEnd()
                    ->orderBy('books.title', 'ASC')
                    ->findAll();
    }

    /**
     * Get books by category
     *
     * @param int $categoryId
     * @return array
     */
    public function getBooksByCategory(int $categoryId): array
    {
        return $this->select('books.*, categories.name as category_name')
                    ->join('categories', 'categories.id = books.category_id', 'left')
                    ->where('books.category_id', $categoryId)
                    ->orderBy('books.title', 'ASC')
                    ->findAll();
    }
}
