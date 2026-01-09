<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Anggota</h1>
        <p class="text-slate-500">Informasi lengkap anggota perpustakaan</p>
    </div>
    <div class="flex gap-3">
        <a href="<?= base_url('/admin/members/edit/' . $member['id']) ?>" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
            <i data-lucide="edit" class="w-5 h-5"></i>
            Edit
        </a>
        <a href="<?= base_url('/admin/members') ?>" class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Member Profile -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
            <div class="w-24 h-24 bg-emerald-700 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                <?= strtoupper(substr($member['name'] ?? 'A', 0, 1)) ?>
            </div>
            <h2 class="text-xl font-bold text-slate-800"><?= esc($member['name']) ?></h2>
            <p class="text-slate-500"><?= esc($member['email']) ?></p>

            <div class="mt-6 space-y-3 text-left">
                <div class="flex items-center gap-3 py-2 border-b border-slate-100">
                    <i data-lucide="phone" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-slate-600"><?= esc($member['phone'] ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-3 py-2 border-b border-slate-100">
                    <i data-lucide="calendar" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-slate-600">Terdaftar: <?= date('d F Y', strtotime($member['registered_at'])) ?></span>
                </div>
                <div class="flex items-start gap-3 py-2">
                    <i data-lucide="map-pin" class="w-5 h-5 text-slate-400 mt-0.5"></i>
                    <span class="text-slate-600"><?= esc($member['address'] ?? '-') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrowing History -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-emerald-700"></i>
                Riwayat Peminjaman
            </h3>
            
            <?php if (!empty($borrowings)): ?>
            <div class="space-y-3">
                <?php foreach ($borrowings as $borrow): ?>
                <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                    <div>
                        <p class="font-medium text-slate-800"><?= esc($borrow['book_title']) ?></p>
                        <p class="text-sm text-slate-500"><?= date('d M Y', strtotime($borrow['borrow_date'])) ?> - <?= $borrow['return_date'] ? date('d M Y', strtotime($borrow['return_date'])) : 'Belum dikembalikan' ?></p>
                    </div>
                    <?php
                    $statusClass = match($borrow['status']) {
                        'borrowed' => 'bg-amber-100 text-amber-700',
                        'returned' => 'bg-sky-100 text-sky-700',
                        'overdue' => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-700'
                    };
                    $statusText = match($borrow['status']) {
                        'borrowed' => 'Dipinjam',
                        'returned' => 'Dikembalikan',
                        'overdue' => 'Terlambat',
                        default => $borrow['status']
                    };
                    ?>
                    <span class="px-2 py-1 rounded-full text-xs font-medium <?= $statusClass ?>"><?= $statusText ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="book-open" class="w-8 h-8 text-slate-400"></i>
                </div>
                <p class="text-slate-500">Belum ada riwayat peminjaman</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


