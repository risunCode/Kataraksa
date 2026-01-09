<?= $this->extend('layouts/member') ?>

<?= $this->section('content') ?>

<!-- Welcome Section -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Selamat Datang, <?= esc(session()->get('user_name')) ?>! 👋</h2>
    <p class="text-slate-500 mt-1">Kelola peminjaman buku Anda dengan mudah.</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Active Borrowings -->
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Sedang Dipinjam</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1"><?= $activeBorrowings ?></p>
            </div>
            <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i data-lucide="book-open" class="w-7 h-7 text-emerald-600"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-3">Buku yang sedang Anda pinjam</p>
    </div>

    <!-- Overdue -->
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Terlambat</p>
                <p class="text-3xl font-bold <?= $overdueBorrowings > 0 ? 'text-red-600' : 'text-slate-800' ?> mt-1"><?= $overdueBorrowings ?></p>
            </div>
            <div class="w-14 h-14 <?= $overdueBorrowings > 0 ? 'bg-red-100' : 'bg-slate-100' ?> rounded-xl flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-7 h-7 <?= $overdueBorrowings > 0 ? 'text-red-600' : 'text-slate-400' ?>"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-3">Segera kembalikan!</p>
    </div>

    <!-- Total Borrowings -->
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Total Peminjaman</p>
                <p class="text-3xl font-bold text-slate-800 mt-1"><?= $totalBorrowings ?></p>
            </div>
            <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                <i data-lucide="history" class="w-7 h-7 text-blue-600"></i>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-3">Sepanjang waktu</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <a href="<?= base_url('/member/catalog') ?>" class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl p-6 text-white hover:shadow-lg transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="search" class="w-7 h-7"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Cari & Pinjam Buku</h3>
                <p class="text-emerald-100 text-sm">Jelajahi katalog perpustakaan</p>
            </div>
        </div>
    </a>

    <a href="<?= base_url('/member/borrowings') ?>" class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl p-6 text-white hover:shadow-lg transition-all group">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i data-lucide="list" class="w-7 h-7"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Lihat Semua Peminjaman</h3>
                <p class="text-blue-100 text-sm">Riwayat peminjaman lengkap</p>
            </div>
        </div>
    </a>
</div>

<!-- Current Borrowings -->
<div class="bg-white rounded-xl shadow-sm border border-stone-200">
    <div class="p-6 border-b border-stone-200">
        <h3 class="text-lg font-semibold text-slate-800">Buku yang Sedang Dipinjam</h3>
    </div>
    
    <?php 
    $currentBorrowings = array_filter($borrowings, fn($b) => $b['status'] === 'borrowed' || $b['status'] === 'overdue');
    ?>
    
    <?php if (empty($currentBorrowings)): ?>
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="book-x" class="w-10 h-10 text-stone-400"></i>
            </div>
            <h4 class="text-lg font-medium text-slate-600 mb-2">Belum Ada Peminjaman</h4>
            <p class="text-slate-400 mb-4">Anda belum meminjam buku apapun.</p>
            <a href="<?= base_url('/member/catalog') ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
                <i data-lucide="search" class="w-5 h-5"></i>
                Cari Buku
            </a>
        </div>
    <?php else: ?>
        <div class="divide-y divide-stone-100">
            <?php foreach ($currentBorrowings as $borrowing): ?>
                <?php 
                $isOverdue = $borrowing['status'] === 'overdue' || strtotime($borrowing['due_date']) < time();
                $daysLeft = ceil((strtotime($borrowing['due_date']) - time()) / (60 * 60 * 24));
                ?>
                <div class="p-6 flex items-center gap-4 hover:bg-stone-50 transition-colors">
                    <!-- Book Cover -->
                    <div class="w-16 h-20 bg-stone-100 rounded-lg overflow-hidden flex-shrink-0">
                        <?php if (!empty($borrowing['cover'])): ?>
                            <img src="<?= base_url('uploads/covers/' . $borrowing['cover']) ?>" alt="<?= esc($borrowing['book_title']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-emerald-600">
                                <i data-lucide="book" class="w-6 h-6 text-white/50"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Book Info -->
                    <div class="flex-1 min-w-0">
                        <h4 class="font-semibold text-slate-800 truncate"><?= esc($borrowing['book_title']) ?></h4>
                        <p class="text-sm text-slate-500"><?= esc($borrowing['book_author']) ?></p>
                        <div class="flex items-center gap-4 mt-2 text-xs">
                            <span class="text-slate-400">
                                <i data-lucide="calendar" class="w-3 h-3 inline mr-1"></i>
                                Dipinjam: <?= date('d M Y', strtotime($borrowing['borrow_date'])) ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Due Date Badge -->
                    <div class="text-right">
                        <?php if ($isOverdue): ?>
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                Terlambat <?= abs($daysLeft) ?> hari
                            </span>
                        <?php elseif ($daysLeft <= 2): ?>
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                <?= $daysLeft ?> hari lagi
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i data-lucide="calendar-check" class="w-3 h-3"></i>
                                <?= $daysLeft ?> hari lagi
                            </span>
                        <?php endif; ?>
                        <p class="text-xs text-slate-400 mt-1">
                            Jatuh tempo: <?= date('d M Y', strtotime($borrowing['due_date'])) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
