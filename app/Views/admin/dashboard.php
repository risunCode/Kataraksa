<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500">Selamat datang di panel admin Kataraksa</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Buku -->
    <div class="bg-emerald-700 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-100 text-sm font-medium">Total Buku</p>
                <p class="text-3xl font-bold mt-1"><?= number_format($totalBooks ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="book" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-emerald-100 text-sm">
            <i data-lucide="trending-up" class="w-4 h-4"></i>
            <span>Koleksi perpustakaan</span>
        </div>
    </div>

    <!-- Total Anggota -->
    <div class="bg-gradient-to-r from-sky-400 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sky-100 text-sm font-medium">Total Anggota</p>
                <p class="text-3xl font-bold mt-1"><?= number_format($totalMembers ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-sky-100 text-sm">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Anggota terdaftar</span>
        </div>
    </div>

    <!-- Peminjaman Aktif -->
    <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-100 text-sm font-medium">Peminjaman Aktif</p>
                <p class="text-3xl font-bold mt-1"><?= number_format($activeBorrowings ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="repeat" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-amber-100 text-sm">
            <i data-lucide="clock" class="w-4 h-4"></i>
            <span>Belum dikembalikan</span>
        </div>
    </div>

    <!-- Terlambat -->
    <div class="bg-gradient-to-r from-red-400 to-rose-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Terlambat</p>
                <p class="text-3xl font-bold mt-1"><?= number_format($overdueBorrowings ?? 0) ?></p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1 text-red-100 text-sm">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            <span>Perlu perhatian</span>
        </div>
    </div>
</div>

<!-- Quick Actions & Recent Activity -->
<div class="grid lg:grid-cols-3 gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Aksi Cepat</h2>
        <div class="space-y-3">
            <a href="<?= base_url('/admin/borrowings/create') ?>" class="flex items-center gap-3 p-3 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition-colors">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="font-medium">Peminjaman Baru</p>
                    <p class="text-sm text-emerald-700">Catat peminjaman buku</p>
                </div>
            </a>
            <a href="<?= base_url('/admin/books/create') ?>" class="flex items-center gap-3 p-3 bg-sky-50 text-sky-700 rounded-lg hover:bg-sky-100 transition-colors">
                <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="book-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="font-medium">Tambah Buku</p>
                    <p class="text-sm text-sky-600">Tambah koleksi baru</p>
                </div>
            </a>
            <a href="<?= base_url('/admin/members/create') ?>" class="flex items-center gap-3 p-3 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition-colors">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="font-medium">Tambah Anggota</p>
                    <p class="text-sm text-amber-600">Daftarkan anggota baru</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Borrowings -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-800">Peminjaman Terbaru</h2>
            <a href="<?= base_url('/admin/borrowings') ?>" class="text-emerald-700 hover:text-emerald-700 text-sm font-medium">
                Lihat Semua
            </a>
        </div>
        
        <?php if (!empty($recentBorrowings)): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-sm text-slate-500 border-b border-slate-200">
                        <th class="pb-3 font-medium">Anggota</th>
                        <th class="pb-3 font-medium">Buku</th>
                        <th class="pb-3 font-medium">Tanggal Pinjam</th>
                        <th class="pb-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <?php foreach ($recentBorrowings as $borrowing): ?>
                    <tr class="border-b border-slate-100">
                        <td class="py-3">
                            <p class="font-medium text-slate-800"><?= esc($borrowing['member_name'] ?? '-') ?></p>
                        </td>
                        <td class="py-3">
                            <p class="text-slate-600 line-clamp-1"><?= esc($borrowing['book_title'] ?? '-') ?></p>
                        </td>
                        <td class="py-3 text-slate-500">
                            <?= date('d M Y', strtotime($borrowing['borrow_date'])) ?>
                        </td>
                        <td class="py-3">
                            <?php
                            $statusClass = match($borrowing['status']) {
                                'borrowed' => 'bg-amber-100 text-amber-700',
                                'returned' => 'bg-sky-100 text-sky-700',
                                'overdue' => 'bg-red-100 text-red-700',
                                default => 'bg-slate-100 text-slate-700'
                            };
                            $statusText = match($borrowing['status']) {
                                'borrowed' => 'Dipinjam',
                                'returned' => 'Dikembalikan',
                                'overdue' => 'Terlambat',
                                default => $borrowing['status']
                            };
                            ?>
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                <?= $statusText ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="inbox" class="w-8 h-8 text-slate-400"></i>
            </div>
            <p class="text-slate-500">Belum ada data peminjaman</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
    <!-- Total Kategori -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i data-lucide="folder" class="w-6 h-6 text-purple-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= number_format($totalCategories ?? 0) ?></p>
                <p class="text-sm text-slate-500">Kategori</p>
            </div>
        </div>
    </div>

    <!-- Total User -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <i data-lucide="user-cog" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= number_format($totalUsers ?? 0) ?></p>
                <p class="text-sm text-slate-500">User Sistem</p>
            </div>
        </div>
    </div>

    <!-- Buku Tersedia -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                <i data-lucide="book-check" class="w-6 h-6 text-emerald-700"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= number_format($availableBooks ?? 0) ?></p>
                <p class="text-sm text-slate-500">Buku Tersedia</p>
            </div>
        </div>
    </div>

    <!-- Total Pengembalian -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-sky-100 rounded-lg flex items-center justify-center">
                <i data-lucide="check-circle" class="w-6 h-6 text-sky-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= number_format($returnedBorrowings ?? 0) ?></p>
                <p class="text-sm text-slate-500">Dikembalikan</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


