# Proposal Sistem Perpustakaan Digital

## 📋 Informasi Project
- **Nama:** Kataraksa
- **Slogan:** *"Satu Halaman Membuka Dunia, Satu Sistem Menjaga Semuanya"*
- **Tujuan:** Tugas Sertifikat Kompetensi (Serkom)
- **Institusi:** Universitas Bina Sarana Informatika
- **Dibuat oleh:** Dimas
- **Framework:** CodeIgniter 4
- **Database:** MySQL

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | CodeIgniter 4 (PHP 8+) |
| Database | MySQL |
| Frontend | TailwindCSS |
| UI Components | [JokoUI](https://www.jokoui.web.id) - Free Tailwind Components |
| Icons | Lucide Icons |
| Alert/Modal | SweetAlert2 |
| Auth | Session-based, Role-based Access |

**Design:** Modern, gradient hijau (inspired by JokoUI)

---

## 🎨 JokoUI Components Reference

Source: https://www.jokoui.web.id (56+ Free Components, Copy-paste ready)

### Application Components (untuk Admin Panel)

| Component | Jumlah | Kegunaan |
|-----------|--------|----------|
| Buttons | 4 varian | Tombol CRUD, submit, cancel, action |
| Cards | 4 varian | Card statistik dashboard, card buku |
| Alerts | 4 varian | Notifikasi sukses/error/warning |
| Forms | 4 varian | Form login, form pinjam, form CRUD |
| Badges | 3 varian | Status: Tersedia, Dipinjam, Terlambat |
| Avatars | 5 varian | Profile user & anggota |
| Progress | 4 varian | Progress bar di dashboard |
| Skeleton | - | Loading placeholder |

### Marketing Components (untuk Landing Page)

| Component | Jumlah | Kegunaan |
|-----------|--------|----------|
| Hero Sections | 3 varian | Hero landing page perpustakaan |
| CTAs | 3 varian | Call to action "Lihat Katalog" |
| Footers | 3 varian | Footer website |
| Testimonials | 5 varian | Review/testimoni pengunjung |
| FAQ | 2 varian | FAQ perpustakaan |

### Cara Pakai
1. Buka https://www.jokoui.web.id
2. Pilih component yang dibutuhkan
3. Copy code-nya
4. Paste ke view CI4
5. Customize warna sesuai color scheme

---

## 👥 Role & Hak Akses

| Role | Akses |
|------|-------|
| Admin | Full CRUD buku, kategori, anggota. Kelola peminjaman & pengembalian. Lihat semua history & laporan. |
| Petugas | Input peminjaman & pengembalian. Lihat data buku & anggota. |
| Anggota | Lihat katalog buku. Lihat history peminjaman sendiri. |

---

## 🌐 Halaman Public (Landing Page)

### Hero Section
- Judul & tagline perpustakaan
- Tombol CTA: "Lihat Katalog" / "Login"

### Katalog Buku
- Grid/list buku dengan cover, judul, penulis
- Search by judul/penulis
- Filter by kategori
- Badge status: Tersedia / Dipinjam

### Detail Buku
- Cover besar
- Info lengkap (judul, penulis, kategori, ISBN, sinopsis)
- Jumlah stok tersedia

### Info Perpustakaan
- Alamat, jam operasional
- Kontak (telp, email)

---

## 🔐 Halaman Admin Panel

### Dashboard
- Total buku
- Total anggota
- Peminjaman aktif (belum dikembalikan)
- Peminjaman terlambat
- Grafik sederhana (opsional)

### Manajemen Buku
- List semua buku (datatable)
- Tambah / Edit / Hapus buku
- Upload cover buku
- Field: judul, penulis, kategori, ISBN, sinopsis, stok, cover

### Manajemen Kategori
- CRUD kategori buku
- Field: nama kategori

### Manajemen Anggota
- List anggota (datatable)
- Tambah / Edit / Hapus anggota
- Field: nama, email, no_hp, alamat, tanggal_daftar

### Transaksi Peminjaman
- Form pinjam buku (pilih anggota, pilih buku, set tanggal pinjam)
- Auto-generate tanggal jatuh tempo (misal: +7 hari)
- Validasi: stok harus > 0
- Stok otomatis berkurang saat dipinjam

### Transaksi Pengembalian
- List peminjaman aktif
- Tombol "Kembalikan"
- Auto-hitung keterlambatan (jika lewat due date)
- Stok otomatis bertambah saat dikembalikan
- Catat tanggal pengembalian

### History Peminjaman
- List semua transaksi (aktif & selesai)
- Filter by anggota, status, tanggal
- Status: Dipinjam / Dikembalikan / Terlambat
- Info keterlambatan (berapa hari)

### Manajemen User (Admin Only)
- CRUD user sistem (admin, petugas)
- Field: nama, email, password, role

---

## 🗄️ Struktur Database

### Tabel `users`
| Field | Type | Keterangan |
|-------|------|------------|
| id | INT, PK, AI | |
| name | VARCHAR(100) | |
| email | VARCHAR(100), UNIQUE | |
| password | VARCHAR(255) | Hashed |
| role | ENUM('admin', 'petugas') | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel `members` (Anggota)
| Field | Type | Keterangan |
|-------|------|------------|
| id | INT, PK, AI | |
| name | VARCHAR(100) | |
| email | VARCHAR(100) | |
| phone | VARCHAR(20) | |
| address | TEXT | |
| registered_at | DATE | Tanggal daftar |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel `categories`
| Field | Type | Keterangan |
|-------|------|------------|
| id | INT, PK, AI | |
| name | VARCHAR(100) | |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel `books`
| Field | Type | Keterangan |
|-------|------|------------|
| id | INT, PK, AI | |
| category_id | INT, FK | |
| title | VARCHAR(200) | |
| author | VARCHAR(100) | |
| isbn | VARCHAR(20) | |
| synopsis | TEXT | |
| stock | INT | Jumlah total |
| available | INT | Jumlah tersedia |
| cover | VARCHAR(255) | Path gambar |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

### Tabel `borrowings` (Peminjaman)
| Field | Type | Keterangan |
|-------|------|------------|
| id | INT, PK, AI | |
| member_id | INT, FK | |
| book_id | INT, FK | |
| borrow_date | DATE | Tanggal pinjam |
| due_date | DATE | Batas kembali |
| return_date | DATE, NULL | Tanggal kembali (null = belum) |
| status | ENUM('borrowed', 'returned', 'overdue') | |
| notes | TEXT | Catatan opsional |
| created_at | TIMESTAMP | |
| updated_at | TIMESTAMP | |

---

## 🔄 Alur Sistem

### Alur Peminjaman
```
1. Petugas/Admin pilih anggota
2. Pilih buku (validasi stok > 0)
3. Set tanggal pinjam (default: hari ini)
4. Sistem auto-set due_date (+7 hari)
5. Simpan → stok buku berkurang 1
6. Status: "borrowed"
```

### Alur Pengembalian
```
1. Petugas/Admin cari peminjaman aktif
2. Klik "Kembalikan"
3. Sistem set return_date = hari ini
4. Cek keterlambatan (return_date > due_date)
5. Update status: "returned" atau "overdue"
6. Stok buku bertambah 1
```

### Cek Keterlambatan
```
Jika return_date > due_date:
  - Hari terlambat = return_date - due_date
  - Status = "overdue"
  - (Denda opsional, bisa ditambah nanti)
```

---

## 📁 Struktur Folder CI4

```
app/
├── Controllers/
│   ├── Home.php              # Landing page
│   ├── Auth.php              # Login, logout
│   ├── Admin/
│   │   ├── Dashboard.php
│   │   ├── BookController.php
│   │   ├── CategoryController.php
│   │   ├── MemberController.php
│   │   ├── BorrowingController.php
│   │   └── UserController.php
│   └── Member/
│       └── History.php       # History anggota (opsional)
├── Models/
│   ├── UserModel.php
│   ├── MemberModel.php
│   ├── CategoryModel.php
│   ├── BookModel.php
│   └── BorrowingModel.php
├── Views/
│   ├── layouts/
│   │   ├── public.php        # Layout landing
│   │   └── admin.php         # Layout admin panel
│   ├── public/
│   │   ├── home.php
│   │   ├── catalog.php
│   │   └── book_detail.php
│   ├── auth/
│   │   └── login.php
│   └── admin/
│       ├── dashboard.php
│       ├── books/
│       ├── categories/
│       ├── members/
│       ├── borrowings/
│       └── users/
└── Filters/
    ├── AuthFilter.php        # Cek login
    └── RoleFilter.php        # Cek role
```

---

## 🎨 Color Scheme & Design System

**Inspirasi:** [JokoUI](https://www.jokoui.web.id) - Gradient Hijau Modern

### Primary Colors (Green Gradient)
| Element | Tailwind Class | Hex Code |
|---------|----------------|----------|
| Gradient Start | green-400 | #4ade80 |
| Gradient End | emerald-600 | #059669 |
| Primary | emerald-600 | #059669 |
| Primary Hover | emerald-700 | #047857 |
| Primary Light | emerald-50 | #ecfdf5 |

### Neutral Colors
| Element | Tailwind Class | Hex Code |
|---------|----------------|----------|
| Background | slate-50 | #f8fafc |
| Card/Panel | white | #ffffff |
| Text Primary | slate-800 | #1e293b |
| Text Secondary | slate-500 | #64748b |
| Text Muted | slate-400 | #94a3b8 |
| Border | slate-200 | #e2e8f0 |
| Border Dark | slate-300 | #cbd5e1 |

### Status Colors
| Status | Tailwind Class | Hex Code | Kegunaan |
|--------|----------------|----------|----------|
| Success | emerald-500 | #10b981 | Berhasil, Tersedia |
| Warning | amber-500 | #f59e0b | Peringatan, Hampir Habis |
| Danger | red-600 | #dc2626 | Error, Terlambat |
| Info | sky-500 | #0ea5e9 | Informasi |

### Gradient Usage
```html
<!-- Hero Section Background -->
<div class="bg-gradient-to-r from-green-400 to-emerald-600">

<!-- Button Primary Gradient -->
<button class="bg-gradient-to-r from-green-400 to-emerald-600 hover:from-green-500 hover:to-emerald-700">

<!-- Card Accent Border -->
<div class="border-l-4 border-emerald-500">
```

### Component Styling Guide

#### Buttons
```html
<!-- Primary Button (Gradient) -->
<button class="bg-gradient-to-r from-green-400 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-500 hover:to-emerald-700 transition-all">
    Simpan
</button>

<!-- Secondary Button -->
<button class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all">
    Batal
</button>

<!-- Danger Button -->
<button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all">
    Hapus
</button>
```

#### Badges (Status Buku/Peminjaman)
```html
<!-- Tersedia -->
<span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-sm">Tersedia</span>

<!-- Dipinjam -->
<span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-sm">Dipinjam</span>

<!-- Terlambat -->
<span class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-sm">Terlambat</span>

<!-- Dikembalikan -->
<span class="bg-sky-100 text-sky-700 px-2 py-1 rounded-full text-sm">Dikembalikan</span>
```

#### Cards
```html
<!-- Card dengan Shadow -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <!-- Content -->
</div>

<!-- Card Statistik Dashboard -->
<div class="bg-gradient-to-r from-green-400 to-emerald-600 rounded-xl p-6 text-white">
    <h3 class="text-lg font-medium opacity-90">Total Buku</h3>
    <p class="text-3xl font-bold">1,234</p>
</div>
```

#### Form Inputs
```html
<!-- Input Field -->
<input type="text" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">

<!-- Select -->
<select class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
```

#### Sidebar Admin
```html
<!-- Active Menu Item -->
<a class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-lg font-medium">

<!-- Inactive Menu Item -->
<a class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 rounded-lg transition-all">
```

---

## 🔒 Security Hardening

### Overview
Sistem Kataraksa dilengkapi dengan multiple security layers untuk melindungi dari berbagai serangan cyber.

### Security Filters

| Filter | File | Fungsi |
|--------|------|--------|
| SecurityFilter | `app/Filters/SecurityFilter.php` | XSS, SQL Injection, Script blocking |
| RateLimitFilter | `app/Filters/RateLimitFilter.php` | Brute force prevention, rate limiting |
| AuthFilter | `app/Filters/AuthFilter.php` | Authentication check |
| RoleFilter | `app/Filters/RoleFilter.php` | Role-based access control |

### Attack Protection Matrix

| Attack Type | Protection Method | Status |
|-------------|-------------------|--------|
| XSS (Cross-Site Scripting) | Input sanitization, output escaping, pattern blocking | ✅ |
| SQL Injection | Parameterized queries, pattern blocking | ✅ |
| CSRF | Token validation (enable in production) | ⚠️ Ready |
| Brute Force | Rate limiting (5 attempts/15 min lockout) | ✅ |
| Session Hijacking | Session regeneration, IP binding | ✅ |
| File Upload RCE | Extension whitelist, .htaccess protection | ✅ |
| Path Traversal | Pattern blocking, filename sanitization | ✅ |
| Encoded Attacks | Base64/Hex/Unicode detection | ✅ |
| Scanner Tools | User-Agent blocking (sqlmap, nikto, etc) | ✅ |

### Security Headers

```
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

### Rate Limiting Configuration

| Parameter | Value | Deskripsi |
|-----------|-------|-----------|
| Max Requests | 60/menit | General rate limit |
| Login Attempts | 5x | Max failed login |
| Lockout Duration | 15 menit | Account lockout time |

### Security Helper Functions

| Function | Deskripsi |
|----------|-----------|
| `sanitize_input()` | Sanitasi string dari XSS |
| `sanitize_filename()` | Sanitasi filename |
| `validate_password_strength()` | Validasi kekuatan password |
| `is_safe_url()` | Validasi URL redirect |
| `log_security_event()` | Log security events |

### File Upload Security

```
public/uploads/
├── .htaccess           # Disable PHP execution
└── covers/
    └── .htaccess       # Image-only access
```

**Dokumentasi lengkap:** Lihat `SECURITY_HARDENING.md`

---

## ✅ Checklist Development (Task Breakdown)

### Phase 1: Setup & Foundation
| Task | File/Folder | Deskripsi | Priority |
|------|-------------|-----------|----------|
| Setup database config | `.env` | Konfigurasi koneksi MySQL | 🔴 High |
| Migration Users | `app/Database/Migrations/` | Tabel users (admin, petugas) | 🔴 High |
| Migration Members | `app/Database/Migrations/` | Tabel anggota perpustakaan | 🔴 High |
| Migration Categories | `app/Database/Migrations/` | Tabel kategori buku | 🔴 High |
| Migration Books | `app/Database/Migrations/` | Tabel buku | 🔴 High |
| Migration Borrowings | `app/Database/Migrations/` | Tabel peminjaman | 🔴 High |
| Seeder Users | `app/Database/Seeds/` | Data dummy admin & petugas | 🟡 Medium |
| Seeder Categories | `app/Database/Seeds/` | Data dummy kategori | 🟡 Medium |
| Seeder Books | `app/Database/Seeds/` | Data dummy buku | 🟡 Medium |
| Seeder Members | `app/Database/Seeds/` | Data dummy anggota | 🟡 Medium |
| Setup TailwindCSS | `public/assets/css/` | CDN atau build | 🔴 High |

### Phase 2: Auth & Layout
| Task | File/Folder | Deskripsi | Priority |
|------|-------------|-----------|----------|
| UserModel | `app/Models/UserModel.php` | Model untuk users | 🔴 High |
| Auth Controller | `app/Controllers/Auth.php` | Login, logout | 🔴 High |
| Auth Filter | `app/Filters/AuthFilter.php` | Cek session login | 🔴 High |
| Role Filter | `app/Filters/RoleFilter.php` | Cek role user | 🔴 High |
| Login View | `app/Views/auth/login.php` | Halaman login | 🔴 High |
| Admin Layout | `app/Views/layouts/admin.php` | Sidebar, navbar, footer | 🔴 High |
| Public Layout | `app/Views/layouts/public.php` | Header, footer public | 🟡 Medium |

### Phase 3: CRUD Master Data
| Task | File/Folder | Deskripsi | Priority |
|------|-------------|-----------|----------|
| CategoryModel | `app/Models/CategoryModel.php` | Model kategori | 🔴 High |
| CategoryController | `app/Controllers/Admin/CategoryController.php` | CRUD kategori | 🔴 High |
| Category Views | `app/Views/admin/categories/` | index, create, edit | 🔴 High |
| BookModel | `app/Models/BookModel.php` | Model buku | 🔴 High |
| BookController | `app/Controllers/Admin/BookController.php` | CRUD buku + upload | 🔴 High |
| Book Views | `app/Views/admin/books/` | index, create, edit | 🔴 High |
| MemberModel | `app/Models/MemberModel.php` | Model anggota | 🔴 High |
| MemberController | `app/Controllers/Admin/MemberController.php` | CRUD anggota | 🔴 High |
| Member Views | `app/Views/admin/members/` | index, create, edit | 🔴 High |
| UserController | `app/Controllers/Admin/UserController.php` | CRUD user (admin only) | 🟡 Medium |
| User Views | `app/Views/admin/users/` | index, create, edit | 🟡 Medium |

### Phase 4: Transaksi
| Task | File/Folder | Deskripsi | Priority |
|------|-------------|-----------|----------|
| BorrowingModel | `app/Models/BorrowingModel.php` | Model peminjaman | 🔴 High |
| BorrowingController | `app/Controllers/Admin/BorrowingController.php` | Pinjam, kembali, history | 🔴 High |
| Form Peminjaman | `app/Views/admin/borrowings/create.php` | Form pinjam buku | 🔴 High |
| List Peminjaman | `app/Views/admin/borrowings/index.php` | List aktif & history | 🔴 High |
| Proses Pengembalian | `app/Views/admin/borrowings/return.php` | Form/modal kembalikan | 🔴 High |
| Validasi Stok | `BorrowingController` | Cek stok sebelum pinjam | 🔴 High |
| Auto Due Date | `BorrowingController` | Set due_date +7 hari | 🔴 High |
| Hitung Keterlambatan | `BorrowingController` | Cek overdue saat return | 🔴 High |

### Phase 5: Public Page
| Task | File/Folder | Deskripsi | Priority |
|------|-------------|-----------|----------|
| Home Controller | `app/Controllers/Home.php` | Landing page | 🟡 Medium |
| Landing Page | `app/Views/public/home.php` | Hero, info, CTA | 🟡 Medium |
| Katalog Page | `app/Views/public/catalog.php` | List buku + search | 🟡 Medium |
| Detail Buku | `app/Views/public/book_detail.php` | Info lengkap buku | 🟡 Medium |
| Search & Filter | `Home Controller` | Query search, filter kategori | 🟡 Medium |

### Phase 6: Dashboard & Finishing
| Task | File/Folder | Deskripsi | Priority |
|------|-------------|-----------|----------|
| Dashboard Controller | `app/Controllers/Admin/Dashboard.php` | Statistik | 🟡 Medium |
| Dashboard View | `app/Views/admin/dashboard.php` | Cards statistik | 🟡 Medium |
| SweetAlert2 Integration | `Views` | Konfirmasi delete, notif | 🟢 Low |
| Responsive Check | `All Views` | Mobile friendly | 🟢 Low |
| Testing | - | Test semua fitur | 🟢 Low |

---

## 📋 Task Summary per File

### Controllers (9 files)
```
app/Controllers/
├── Home.php                    # Landing, katalog, detail buku
├── Auth.php                    # Login, logout
└── Admin/
    ├── Dashboard.php           # Statistik dashboard
    ├── BookController.php      # CRUD buku
    ├── CategoryController.php  # CRUD kategori
    ├── MemberController.php    # CRUD anggota
    ├── BorrowingController.php # Pinjam, kembali, history
    └── UserController.php      # CRUD user sistem
```

### Models (5 files)
```
app/Models/
├── UserModel.php       # Admin & petugas
├── MemberModel.php     # Anggota perpustakaan
├── CategoryModel.php   # Kategori buku
├── BookModel.php       # Data buku
└── BorrowingModel.php  # Transaksi peminjaman
```

### Views (estimasi 20+ files)
```
app/Views/
├── layouts/
│   ├── admin.php       # Layout admin (sidebar, navbar)
│   └── public.php      # Layout public (header, footer)
├── auth/
│   └── login.php       # Halaman login
├── public/
│   ├── home.php        # Landing page
│   ├── catalog.php     # Katalog buku
│   └── book_detail.php # Detail buku
└── admin/
    ├── dashboard.php   # Dashboard statistik
    ├── books/
    │   ├── index.php   # List buku
    │   ├── create.php  # Form tambah
    │   └── edit.php    # Form edit
    ├── categories/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    ├── members/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    ├── borrowings/
    │   ├── index.php   # List & history
    │   ├── create.php  # Form pinjam
    │   └── return.php  # Form/modal kembali
    └── users/
        ├── index.php
        ├── create.php
        └── edit.php
```

### Migrations (5 files)
```
app/Database/Migrations/
├── 2026-01-09-000001_CreateUsersTable.php
├── 2026-01-09-000002_CreateMembersTable.php
├── 2026-01-09-000003_CreateCategoriesTable.php
├── 2026-01-09-000004_CreateBooksTable.php
└── 2026-01-09-000005_CreateBorrowingsTable.php
```

### Seeds (5 files)
```
app/Database/Seeds/
├── UserSeeder.php
├── MemberSeeder.php
├── CategorySeeder.php
├── BookSeeder.php
└── DatabaseSeeder.php  # Main seeder (call semua)
```

### Filters (4 files)
```
app/Filters/
├── AuthFilter.php      # Cek login
├── RoleFilter.php      # Cek role (admin/petugas)
├── SecurityFilter.php  # XSS, SQL Injection, Script blocking
└── RateLimitFilter.php # Rate limiting, brute force prevention
```

### Helpers (1 file)
```
app/Helpers/
└── security_helper.php # Security helper functions
```

---

## 📝 Catatan Penting

### Untuk Agent/Developer:
1. **Urutan Development:** Ikuti phase 1-6 secara berurutan
2. **Color Scheme:** Gunakan gradient hijau (green-400 to emerald-600) untuk elemen utama
3. **Components:** Ambil dari JokoUI (https://www.jokoui.web.id) lalu customize warnanya
4. **Validasi:** Selalu validasi input di controller dan model
5. **Security:** Password harus di-hash, gunakan CSRF protection
6. **Upload:** Cover buku disimpan di `public/uploads/covers/`

### Fitur Opsional (Bonus):
- Denda keterlambatan (field `fine` di tabel borrowings)
- Member login untuk lihat history sendiri
- Export laporan PDF/Excel
- Notifikasi email jatuh tempo
- Dark mode toggle

### Konvensi Penamaan:
- Controller: PascalCase + "Controller" suffix (BookController)
- Model: PascalCase + "Model" suffix (BookModel)
- View: snake_case (book_detail.php)
- Route: kebab-case (/admin/books, /admin/book-categories)
- Database: snake_case (borrow_date, created_at)

---

## 🎯 Definition of Done

Project dianggap selesai jika:
- [x] Semua migration berhasil dijalankan
- [x] Login/logout berfungsi dengan role-based access
- [x] CRUD Buku, Kategori, Anggota, User berfungsi
- [x] Transaksi peminjaman & pengembalian berfungsi
- [x] Stok buku otomatis berkurang/bertambah
- [x] Status keterlambatan terdeteksi otomatis
- [x] Landing page & katalog public berfungsi
- [x] Dashboard menampilkan statistik
- [x] Responsive di mobile & desktop
- [x] Security hardening implemented (XSS, SQL Injection, Brute Force)
- [ ] Testing di production environment
- [ ] Enable CSRF protection

---

*Project Kataraksa - Ready for deployment!* 🚀
