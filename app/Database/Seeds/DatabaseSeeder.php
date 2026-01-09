<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call all seeders in the correct order
        // Users first (for authentication)
        $this->call('UserSeeder');
        
        // Categories before Books (because Books has FK to Categories)
        $this->call('CategorySeeder');
        
        // Members before Borrowings (because Borrowings has FK to Members)
        $this->call('MemberSeeder');
        
        // Books after Categories
        $this->call('BookSeeder');
        
        // Note: BorrowingSeeder is not included by default
        // Add it manually if you need sample borrowing data
    }
}
