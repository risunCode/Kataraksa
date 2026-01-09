```
 _  __      _                    _              
| |/ /     | |                  | |             
| ' /  __ _| |_ __ _ _ __ __ _| | _____  __ _ 
|  <  / _` | __/ _` | '__/ _` | |/ / __|/ _` |
| . \| (_| | || (_| | | | (_| |   <\__ \ (_| |
|_|\_\\__,_|\__\__,_|_|  \__,_|_|\_\___/\__,_|

"Satu Halaman Membuka Dunia, Satu Sistem Menjaga Semuanya"
```

# 📚 Kataraksa - Sistem Perpustakaan Digital

Selamat datang di **Kataraksa**! Sebuah sistem perpustakaan digital modern yang dirancang untuk memudahkan pengelolaan buku, peminjaman, dan pengembalian dengan antarmuka yang bersih dan user-friendly.

---

## 👋 Tentang Project

```php
<?php

$project = [
    'nama'      => 'Kataraksa',
    'dibuat'    => 'Dimas',
    'tujuan'    => 'Sertifikat Kompetensi (Serkom)',
    'institusi' => 'Universitas Bina Sarana Informatika',
    'tahun'     => 2026
];

echo "Terima kasih sudah mampir! 🎉";
```

Project ini dibuat sebagai tugas **Sertifikat Kompetensi** dan bersifat **open source**. Silakan gunakan sebagai referensi, template, atau dikembangkan lebih lanjut sesuai kebutuhan.

---

## 🛠️ Tech Stack

```php
<?php

$techStack = [
    'backend'    => 'CodeIgniter 4 (PHP 8+)',
    'database'   => 'MySQL',
    'frontend'   => 'TailwindCSS',
    'components' => 'JokoUI (jokoui.web.id)',
    'icons'      => 'Lucide Icons',
    'alerts'     => 'SweetAlert2'
];
```

| Layer | Teknologi |
|-------|-----------|
| Backend | CodeIgniter 4 (PHP 8+) |
| Database | MySQL |
| Frontend | TailwindCSS |
| UI Components | [JokoUI](https://www.jokoui.web.id) - Free Tailwind Components |
| Icons | Lucide Icons |
| Alert/Modal | SweetAlert2 |

---

## ✨ Fitur Utama

### 🌐 Halaman Public
```php
<?php

$fiturPublic = [
    'Landing page perpustakaan',
    'Katalog buku dengan search & filter',
    'Detail buku dan status ketersediaan',
    'Informasi perpustakaan'
];
```

### 🔐 Admin Panel
```php
<?php

$fiturAdmin = [
    'Dashboard statistik',
    'CRUD Buku & Kategori',
    'CRUD Anggota',
    'Transaksi Peminjaman',
    'Transaksi Pengembalian',
    'History dengan status keterlambatan',
    'Manajemen User (role-based)'
];
```

### 👥 Role-Based Access

```php
<?php

$roles = [
    'admin' => [
        'deskripsi' => 'Full access ke semua fitur',
        'akses'     => ['dashboard', 'buku', 'kategori', 'anggota', 'transaksi', 'user']
    ],
    'petugas' => [
        'deskripsi' => 'Kelola transaksi dan data master',
        'akses'     => ['dashboard', 'buku', 'kategori', 'anggota', 'transaksi']
    ],
    'anggota' => [
        'deskripsi' => 'Lihat katalog dan history sendiri',
        'akses'     => ['katalog', 'history_sendiri']
    ]
];
```

---

## 🚀 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/username/kataraksa.git
cd kataraksa
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp env .env
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan konfigurasi database:

```env
#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------

database.default.hostname = localhost
database.default.database = kataraksa
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

### 5. Buat Database
```sql
CREATE DATABASE kataraksa;
```

### 6. Jalankan Migration
```bash
php spark migrate
```

### 7. Jalankan Seeder (Opsional)
```bash
php spark db:seed DatabaseSeeder
```

### 8. Jalankan Server
```bash
php spark serve
```

### 9. Akses Aplikasi
```
http://localhost:8080
```

---

## 📁 Struktur Project

```
kataraksa/
├── app/
│   ├── Controllers/
│   │   ├── Home.php
│   │   ├── Auth.php
│   │   └── Admin/
│   │       ├── Dashboard.php
│   │       ├── BookController.php
│   │       ├── CategoryController.php
│   │       ├── MemberController.php
│   │       ├── BorrowingController.php
│   │       └── UserController.php
│   ├── Models/
│   │   ├── UserModel.php
│   │   ├── BookModel.php
│   │   ├── CategoryModel.php
│   │   ├── MemberModel.php
│   │   └── BorrowingModel.php
│   ├── Views/
│   │   ├── layouts/
│   │   ├── public/
│   │   ├── auth/
│   │   └── admin/
│   └── Filters/
│       ├── AuthFilter.php
│       └── RoleFilter.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── index.php
├── writable/
├── .env
├── composer.json
├── PROPOSAL.md
└── README.md
```

---

## 🗄️ Database Schema

```sql
-- Tabel Users (Admin & Petugas)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'petugas'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabel Members (Anggota Perpustakaan)
CREATE TABLE members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    registered_at DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabel Categories
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Tabel Books
CREATE TABLE books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    title VARCHAR(200),
    author VARCHAR(100),
    isbn VARCHAR(20),
    synopsis TEXT,
    stock INT,
    available INT,
    cover VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Tabel Borrowings (Peminjaman)
CREATE TABLE borrowings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    member_id INT,
    book_id INT,
    borrow_date DATE,
    due_date DATE,
    return_date DATE NULL,
    status ENUM('borrowed', 'returned', 'overdue'),
    notes TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);
```

---

## 🎨 Screenshots

> *Coming soon...*

---

## 🤝 Kontribusi

Kontribusi sangat diterima! Silakan:

1. Fork repository ini
2. Buat branch baru (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

---

## 📝 Lisensi

Project ini bersifat **open source** dan bebas digunakan untuk keperluan edukasi maupun pengembangan lebih lanjut.

---

## 📞 Kontak

Jika ada pertanyaan atau saran, silakan hubungi:

```php
<?php

$kontak = [
    'nama'      => 'Dimas',
    'institusi' => 'Universitas Bina Sarana Informatika',
    'project'   => 'Sertifikat Kompetensi 2026'
];
```

---

<div align="center">

**Made with ❤️ by Dimas**

Universitas Bina Sarana Informatika | 2026

*"Satu Halaman Membuka Dunia, Satu Sistem Menjaga Semuanya"*

</div>
