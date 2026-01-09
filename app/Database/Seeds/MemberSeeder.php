<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run()
    {
        // Password hash untuk 'password123'
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        
        $data = [
            [
                'name'          => 'Budi Santoso',
                'email'         => 'budi.santoso@email.com',
                'password'      => $passwordHash,
                'phone'         => '081234567890',
                'address'       => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'registered_at' => '2024-01-15',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Siti Rahayu',
                'email'         => 'siti.rahayu@email.com',
                'password'      => $passwordHash,
                'phone'         => '082345678901',
                'address'       => 'Jl. Sudirman No. 456, Jakarta Selatan',
                'registered_at' => '2024-02-20',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Ahmad Wijaya',
                'email'         => 'ahmad.wijaya@email.com',
                'password'      => $passwordHash,
                'phone'         => '083456789012',
                'address'       => 'Jl. Gatot Subroto No. 789, Bandung',
                'registered_at' => '2024-03-10',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Dewi Lestari',
                'email'         => 'dewi.lestari@email.com',
                'password'      => $passwordHash,
                'phone'         => '084567890123',
                'address'       => 'Jl. Diponegoro No. 321, Surabaya',
                'registered_at' => '2024-04-05',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'name'          => 'Rizky Pratama',
                'email'         => 'rizky.pratama@email.com',
                'password'      => $passwordHash,
                'phone'         => '085678901234',
                'address'       => 'Jl. Ahmad Yani No. 654, Yogyakarta',
                'registered_at' => '2024-05-12',
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('members')->insertBatch($data);
    }
}
