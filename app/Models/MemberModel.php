<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
    protected $table            = 'members';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'email', 'password', 'phone', 'address', 'registered_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'name'          => 'required|min_length[3]|max_length[100]',
        'email'         => 'required|valid_email|max_length[100]',
        'phone'         => 'permit_empty|max_length[20]',
        'address'       => 'permit_empty',
        'registered_at' => 'permit_empty|valid_date',
    ];

    protected $validationMessages = [
        'name' => [
            'required'   => 'Nama anggota harus diisi.',
            'min_length' => 'Nama minimal 3 karakter.',
            'max_length' => 'Nama maksimal 100 karakter.',
        ],
        'email' => [
            'required'    => 'Email harus diisi.',
            'valid_email' => 'Format email tidak valid.',
        ],
        'phone' => [
            'max_length' => 'Nomor telepon maksimal 20 karakter.',
        ],
        'registered_at' => [
            'valid_date' => 'Format tanggal tidak valid.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all active members (registered members)
     *
     * @return array
     */
    public function getActiveMembers(): array
    {
        return $this->where('registered_at IS NOT NULL')
                    ->where('registered_at <=', date('Y-m-d'))
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Find member by email
     *
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->where('email', $email)->first();
    }
}
