<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Peminjaman</h1>
        <p class="text-slate-500">Informasi lengkap transaksi peminjaman</p>
    </div>
    <a href="<?= base_url('/admin/borrowings') ?>" class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Borrowing Info -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
            <i data-lucide="repeat" class="w-5 h-5 text-emerald-700"></i>
            Informasi Peminjaman
        </h2>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                <span class="text-slate-500">Status</span>
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
                <span class="px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>"><?= $statusText ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                <span class="text-slate-500">Tanggal Pinjam</span>
                <span class="font-medium text-slate-800"><?= date('d F Y', strtotime($borrowing['borrow_date'])) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                <span class="text-slate-500">Jatuh Tempo</span>
                <span class="font-medium <?= $borrowing['is_overdue'] ? 'text-red-600' : 'text-slate-800' ?>"><?= date('d F Y', strtotime($borrowing['due_date'])) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                <span class="text-slate-500">Tanggal Kembali</span>
                <span class="font-medium text-slate-800"><?= $borrowing['return_date'] ? date('d F Y', strtotime($borrowing['return_date'])) : '-' ?></span>
            </div>
            <?php if ($borrowing['overdue_days'] > 0): ?>
            <div class="flex justify-between items-center py-3 border-b border-slate-100">
                <span class="text-slate-500">Keterlambatan</span>
                <span class="font-medium text-red-600"><?= $borrowing['overdue_days'] ?> hari</span>
            </div>
            <?php endif; ?>
            <?php if (!empty($borrowing['notes'])): ?>
            <div class="py-3">
                <span class="text-slate-500 block mb-2">Catatan</span>
                <p class="text-slate-800"><?= esc($borrowing['notes']) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <?php if ($borrowing['status'] === 'borrowed'): ?>
        <div class="mt-6 flex gap-3">
            <form action="<?= base_url('/admin/borrowings/return/' . $borrowing['id']) ?>" method="POST" class="flex-1">
                <button type="submit" class="w-full bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-2"></i>Kembalikan Buku
                </button>
            </form>
            <form action="<?= base_url('/admin/borrowings/extend/' . $borrowing['id']) ?>" method="POST">
                <button type="submit" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
                    <i data-lucide="calendar-plus" class="w-4 h-4 inline mr-2"></i>Perpanjang
                </button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <!-- Member & Book Info -->
    <div class="space-y-6">
        <!-- Member Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="user" class="w-5 h-5 text-emerald-700"></i>
                Informasi Anggota
            </h2>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-700 rounded-full flex items-center justify-center text-white text-xl font-bold">
                    <?= strtoupper(substr($borrowing['member_name'] ?? 'A', 0, 1)) ?>
                </div>
                <div>
                    <p class="font-semibold text-slate-800 text-lg"><?= esc($borrowing['member_name'] ?? '-') ?></p>
                    <p class="text-slate-500"><?= esc($borrowing['member_email'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <!-- Book Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="book" class="w-5 h-5 text-emerald-700"></i>
                Informasi Buku
            </h2>
            <div>
                <p class="font-semibold text-slate-800 text-lg"><?= esc($borrowing['book_title'] ?? '-') ?></p>
                <p class="text-slate-500">oleh <?= esc($borrowing['book_author'] ?? '-') ?></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


