<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'role'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[100]',
        'email'    => 'required|valid_email|max_length[100]|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[admin,petugas]',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Nama harus diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
            'max_length' => 'Nama maksimal 100 karakter.',
        ],
        'email' => [
            'required'    => 'Email harus diisi.',
            'valid_email' => 'Format email tidak valid.',
            'is_unique'   => 'Email sudah terdaftar.',
        ],
        'password' => [
            'required'   => 'Password harus diisi.',
            'min_length' => 'Password minimal 6 karakter.',
        ],
        'role' => [
            'required' => 'Role harus dipilih.',
            'in_list'  => 'Role tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Find user by email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
