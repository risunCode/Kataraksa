<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Fiksi (category_id: 1)
            [
                'category_id' => 1,
                'title'       => 'Laskar Pelangi',
                'author'      => 'Andrea Hirata',
                'isbn'        => '978-602-8519-93-4',
                'synopsis'    => 'Kisah inspiratif tentang perjuangan anak-anak Belitung dalam mengejar pendidikan di tengah keterbatasan.',
                'stock'       => 5,
                'available'   => 5,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 1,
                'title'       => 'Bumi Manusia',
                'author'      => 'Pramoedya Ananta Toer',
                'isbn'        => '978-979-9731-31-2',
                'synopsis'    => 'Novel sejarah yang mengisahkan kehidupan Minke, seorang pribumi yang berjuang melawan kolonialisme.',
                'stock'       => 3,
                'available'   => 3,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Non-Fiksi (category_id: 2)
            [
                'category_id' => 2,
                'title'       => 'Filosofi Teras',
                'author'      => 'Henry Manampiring',
                'isbn'        => '978-602-291-544-0',
                'synopsis'    => 'Buku tentang filsafat Stoa yang dikemas dengan gaya bahasa modern dan relevan untuk kehidupan sehari-hari.',
                'stock'       => 4,
                'available'   => 4,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 2,
                'title'       => 'Atomic Habits',
                'author'      => 'James Clear',
                'isbn'        => '978-602-455-470-5',
                'synopsis'    => 'Panduan praktis untuk membangun kebiasaan baik dan menghilangkan kebiasaan buruk.',
                'stock'       => 6,
                'available'   => 6,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Sains (category_id: 3)
            [
                'category_id' => 3,
                'title'       => 'A Brief History of Time',
                'author'      => 'Stephen Hawking',
                'isbn'        => '978-979-799-122-3',
                'synopsis'    => 'Penjelasan tentang kosmologi, lubang hitam, dan asal usul alam semesta dalam bahasa yang mudah dipahami.',
                'stock'       => 2,
                'available'   => 2,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 3,
                'title'       => 'Sapiens: A Brief History of Humankind',
                'author'      => 'Yuval Noah Harari',
                'isbn'        => '978-602-291-033-9',
                'synopsis'    => 'Sejarah evolusi manusia dari zaman purba hingga era modern.',
                'stock'       => 4,
                'available'   => 4,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Sejarah (category_id: 4)
            [
                'category_id' => 4,
                'title'       => 'Sejarah Indonesia Modern',
                'author'      => 'M.C. Ricklefs',
                'isbn'        => '978-979-461-689-5',
                'synopsis'    => 'Buku komprehensif tentang sejarah Indonesia dari masa kolonial hingga era reformasi.',
                'stock'       => 3,
                'available'   => 3,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 4,
                'title'       => 'Tan Malaka: Bapak Republik yang Dilupakan',
                'author'      => 'Harry A. Poeze',
                'isbn'        => '978-979-709-456-7',
                'synopsis'    => 'Biografi lengkap tentang Tan Malaka, tokoh pejuang kemerdekaan Indonesia.',
                'stock'       => 2,
                'available'   => 2,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            // Teknologi (category_id: 5)
            [
                'category_id' => 5,
                'title'       => 'Clean Code',
                'author'      => 'Robert C. Martin',
                'isbn'        => '978-013-235-088-4',
                'synopsis'    => 'Panduan menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.',
                'stock'       => 5,
                'available'   => 5,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'category_id' => 5,
                'title'       => 'The Pragmatic Programmer',
                'author'      => 'David Thomas & Andrew Hunt',
                'isbn'        => '978-020-161-622-4',
                'synopsis'    => 'Buku klasik tentang praktik terbaik dalam pengembangan perangkat lunak.',
                'stock'       => 3,
                'available'   => 3,
                'cover'       => null,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('books')->insertBatch($data);
    }
}
