<?= $this->extend('layouts/member') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Peminjaman Saya</h2>
    <p class="text-slate-500 mt-1">Riwayat semua peminjaman buku Anda.</p>
</div>

<!-- Borrowings List -->
<div class="bg-white rounded-xl shadow-sm border border-stone-200">
    <?php if (empty($borrowings)): ?>
        <div class="p-12 text-center">
            <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="book-x" class="w-10 h-10 text-stone-400"></i>
            </div>
            <h4 class="text-lg font-medium text-slate-600 mb-2">Belum Ada Peminjaman</h4>
            <p class="text-slate-400 mb-4">Anda belum pernah meminjam buku.</p>
            <a href="<?= base_url('/member/catalog') ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
                <i data-lucide="search" class="w-5 h-5"></i>
                Cari Buku
            </a>
        </div>
    <?php else: ?>
        <!-- Filter Tabs -->
        <div class="p-4 border-b border-stone-200 flex gap-2 overflow-x-auto">
            <button onclick="filterBorrowings('all')" class="filter-btn active px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="all">
                Semua
            </button>
            <button onclick="filterBorrowings('borrowed')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="borrowed">
                Sedang Dipinjam
            </button>
            <button onclick="filterBorrowings('returned')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="returned">
                Dikembalikan
            </button>
            <button onclick="filterBorrowings('overdue')" class="filter-btn px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="overdue">
                Terlambat
            </button>
        </div>

        <div class="divide-y divide-stone-100">
            <?php foreach ($borrowings as $borrowing): ?>
                <?php 
                $isOverdue = $borrowing['status'] === 'overdue' || ($borrowing['status'] === 'borrowed' && strtotime($borrowing['due_date']) < time());
                $status = $isOverdue ? 'overdue' : $borrowing['status'];
                $daysLeft = ceil((strtotime($borrowing['due_date']) - time()) / (60 * 60 * 24));
                ?>
                <div class="borrowing-item p-6 flex items-center gap-4 hover:bg-stone-50 transition-colors" data-status="<?= $status ?>">
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
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-xs">
                            <span class="text-slate-400">
                                <i data-lucide="calendar" class="w-3 h-3 inline mr-1"></i>
                                Dipinjam: <?= date('d M Y', strtotime($borrowing['borrow_date'])) ?>
                            </span>
                            <span class="text-slate-400">
                                <i data-lucide="calendar-clock" class="w-3 h-3 inline mr-1"></i>
                                Jatuh tempo: <?= date('d M Y', strtotime($borrowing['due_date'])) ?>
                            </span>
                            <?php if ($borrowing['status'] === 'returned' && !empty($borrowing['return_date'])): ?>
                                <span class="text-emerald-600">
                                    <i data-lucide="calendar-check" class="w-3 h-3 inline mr-1"></i>
                                    Dikembalikan: <?= date('d M Y', strtotime($borrowing['return_date'])) ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="text-right flex-shrink-0">
                        <?php if ($borrowing['status'] === 'returned'): ?>
                            <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                Dikembalikan
                            </span>
                        <?php elseif ($isOverdue): ?>
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
                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                <i data-lucide="book-open" class="w-3 h-3"></i>
                                Dipinjam
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .filter-btn {
        background-color: #f5f5f4;
        color: #64748b;
    }
    .filter-btn:hover {
        background-color: #e7e5e4;
    }
    .filter-btn.active {
        background-color: #059669;
        color: white;
    }
</style>

<script>
    function filterBorrowings(status) {
        // Update active button
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active');
            if (btn.dataset.filter === status) {
                btn.classList.add('active');
            }
        });

        // Filter items
        document.querySelectorAll('.borrowing-item').forEach(item => {
            if (status === 'all') {
                item.style.display = 'flex';
            } else {
                item.style.display = item.dataset.status === status ? 'flex' : 'none';
            }
        });
    }
</script>

<?= $this->endSection() ?>
