<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateMemberPasswordSeeder extends Seeder
{
    public function run()
    {
        // Password hash untuk 'password123'
        $passwordHash = password_hash('password123', PASSWORD_DEFAULT);
        
        // Update semua member yang belum punya password
        $this->db->query("UPDATE members SET password = ? WHERE password IS NULL OR password = ''", [$passwordHash]);
        
        echo "Password updated for all members. Use 'password123' to login.\n";
    }
}
