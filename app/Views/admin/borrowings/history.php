<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">History Peminjaman</h1>
        <p class="text-slate-500">Riwayat semua transaksi peminjaman</p>
    </div>
    <a href="<?= base_url('/admin/borrowings') ?>" class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
        Kembali
    </a>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center">
                <i data-lucide="layers" class="w-5 h-5 text-slate-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= $stats['total'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Total</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i data-lucide="clock" class="w-5 h-5 text-amber-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= $stats['borrowed'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Dipinjam</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-sky-100 rounded-lg flex items-center justify-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-sky-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= $stats['returned'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Dikembalikan</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-800"><?= $stats['overdue'] ?? 0 ?></p>
                <p class="text-sm text-slate-500">Terlambat</p>
            </div>
        </div>
    </div>
</div>

<!-- Filter Form -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="<?= base_url('/admin/borrowings/history') ?>" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <select name="filter" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
            <option value="all" <?= ($filter ?? '') == 'all' ? 'selected' : '' ?>>Semua Status</option>
            <option value="borrowed" <?= ($filter ?? '') == 'borrowed' ? 'selected' : '' ?>>Dipinjam</option>
            <option value="returned" <?= ($filter ?? '') == 'returned' ? 'selected' : '' ?>>Dikembalikan</option>
            <option value="overdue" <?= ($filter ?? '') == 'overdue' ? 'selected' : '' ?>>Terlambat</option>
        </select>
        <select name="member_id" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
            <option value="">Semua Anggota</option>
            <?php foreach ($members ?? [] as $member): ?>
                <option value="<?= $member['id'] ?>" <?= ($filters['member_id'] ?? '') == $member['id'] ? 'selected' : '' ?>><?= esc($member['name']) ?></option>
            <?php endforeach; ?>
        </select>
        <input type="date" name="start_date" value="<?= $filters['start_date'] ?? '' ?>" placeholder="Dari Tanggal" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
        <input type="date" name="end_date" value="<?= $filters['end_date'] ?? '' ?>" placeholder="Sampai Tanggal" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
        <button type="submit" class="bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
            <i data-lucide="filter" class="w-4 h-4 inline mr-2"></i>Filter
        </button>
    </form>
</div>

<!-- History Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Anggota</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Buku</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Tgl Pinjam</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Jatuh Tempo</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Tgl Kembali</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Status</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Keterlambatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (!empty($borrowings)): ?>
                    <?php foreach ($borrowings as $borrowing): ?>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800"><?= esc($borrowing['member_name'] ?? '-') ?></p>
                                <p class="text-sm text-slate-500"><?= esc($borrowing['member_email'] ?? '-') ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800 line-clamp-1"><?= esc($borrowing['book_title'] ?? '-') ?></p>
                                <p class="text-sm text-slate-500"><?= esc($borrowing['book_author'] ?? '-') ?></p>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 text-sm">
                                <?= date('d M Y', strtotime($borrowing['borrow_date'])) ?>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 text-sm">
                                <?= date('d M Y', strtotime($borrowing['due_date'])) ?>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 text-sm">
                                <?= $borrowing['return_date'] ? date('d M Y', strtotime($borrowing['return_date'])) : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-center">
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
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($borrowing['overdue_days'] > 0): ?>
                                    <span class="text-red-600 font-medium"><?= $borrowing['overdue_days'] ?> hari</span>
                                <?php else: ?>
                                    <span class="text-slate-400">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="history" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500">Tidak ada data history</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>


