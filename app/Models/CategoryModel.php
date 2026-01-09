<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table            = 'categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[categories.name,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Nama kategori harus diisi.',
            'min_length' => 'Nama kategori minimal 2 karakter.',
            'max_length' => 'Nama kategori maksimal 100 karakter.',
            'is_unique'  => 'Nama kategori sudah ada.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all categories with book count
     *
     * @return array
     */
    public function getCategoriesWithBookCount(): array
    {
        return $this->select('categories.*, COUNT(books.id) as book_count')
                    ->join('books', 'books.category_id = categories.id', 'left')
                    ->groupBy('categories.id')
                    ->orderBy('categories.name', 'ASC')
                    ->findAll();
    }
}
