<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Buku</h1>
        <p class="text-slate-500">Informasi lengkap buku</p>
    </div>
    <div class="flex gap-3">
        <a href="<?= base_url('/admin/books/edit/' . $book['id']) ?>" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
            <i data-lucide="edit" class="w-5 h-5"></i>
            Edit
        </a>
        <a href="<?= base_url('/admin/books') ?>" class="inline-flex items-center justify-center gap-2 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Book Cover -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <?php if (!empty($book['cover']) && file_exists(FCPATH . 'uploads/covers/' . $book['cover'])): ?>
                <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" alt="<?= esc($book['title']) ?>" class="w-full rounded-lg shadow-md">
            <?php else: ?>
                <div class="w-full aspect-[3/4] bg-emerald-700 rounded-lg flex items-center justify-center">
                    <i data-lucide="book" class="w-20 h-20 text-white opacity-50"></i>
                </div>
            <?php endif; ?>
            
            <!-- Stock Info -->
            <div class="mt-4 grid grid-cols-2 gap-4">
                <div class="bg-stone-50 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-slate-800"><?= $book['stock'] ?? 0 ?></p>
                    <p class="text-sm text-slate-500">Total Stok</p>
                </div>
                <div class="bg-emerald-50 rounded-lg p-3 text-center">
                    <p class="text-2xl font-bold text-emerald-700"><?= $book['available'] ?? 0 ?></p>
                    <p class="text-sm text-slate-500">Tersedia</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Details -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800"><?= esc($book['title']) ?></h2>
                    <p class="text-lg text-slate-500">oleh <?= esc($book['author']) ?></p>
                </div>
                <?php
                $available = $book['available'] ?? 0;
                $statusClass = $available > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700';
                $statusText = $available > 0 ? 'Tersedia' : 'Tidak Tersedia';
                ?>
                <span class="px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>"><?= $statusText ?></span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3 py-3 border-b border-slate-100">
                    <i data-lucide="folder" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-slate-500 w-32">Kategori</span>
                    <span class="font-medium text-slate-800"><?= esc($book['category_name'] ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-3 py-3 border-b border-slate-100">
                    <i data-lucide="hash" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-slate-500 w-32">ISBN</span>
                    <span class="font-medium text-slate-800"><?= esc($book['isbn'] ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-3 py-3 border-b border-slate-100">
                    <i data-lucide="calendar" class="w-5 h-5 text-slate-400"></i>
                    <span class="text-slate-500 w-32">Ditambahkan</span>
                    <span class="font-medium text-slate-800"><?= date('d F Y', strtotime($book['created_at'])) ?></span>
                </div>
            </div>

            <?php if (!empty($book['synopsis'])): ?>
            <div class="mt-6">
                <h3 class="font-semibold text-slate-800 mb-2">Sinopsis</h3>
                <p class="text-slate-600 leading-relaxed"><?= nl2br(esc($book['synopsis'])) ?></p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Borrowing History -->
        <?php if (!empty($borrowings)): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
            <h3 class="font-semibold text-slate-800 mb-4">Riwayat Peminjaman</h3>
            <div class="space-y-3">
                <?php foreach (array_slice($borrowings, 0, 5) as $borrow): ?>
                <div class="flex items-center justify-between py-2 border-b border-slate-100 last:border-0">
                    <div>
                        <p class="font-medium text-slate-800"><?= esc($borrow['member_name']) ?></p>
                        <p class="text-sm text-slate-500"><?= date('d M Y', strtotime($borrow['borrow_date'])) ?></p>
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
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>


