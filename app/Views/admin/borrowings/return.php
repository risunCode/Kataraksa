<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="<?= base_url('/admin/borrowings') ?>" class="hover:text-emerald-700 transition-colors">Peminjaman</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800">Pengembalian</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-800">Konfirmasi Pengembalian</h1>
</div>

<!-- Return Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <!-- Borrowing Info -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Detail Peminjaman</h2>
            
            <div class="grid sm:grid-cols-2 gap-4">
                <!-- Member Info -->
                <div class="p-4 bg-stone-50 rounded-lg">
                    <p class="text-xs text-slate-500 mb-1">Anggota</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-700 rounded-full flex items-center justify-center text-white font-semibold">
                            <?= strtoupper(substr($borrowing['member_name'] ?? 'A', 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800"><?= esc($borrowing['member_name'] ?? '-') ?></p>
                            <p class="text-sm text-slate-500"><?= esc($borrowing['member_email'] ?? '-') ?></p>
                        </div>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="p-4 bg-stone-50 rounded-lg">
                    <p class="text-xs text-slate-500 mb-1">Buku</p>
                    <p class="font-medium text-slate-800"><?= esc($borrowing['book_title'] ?? '-') ?></p>
                    <p class="text-sm text-slate-500"><?= esc($borrowing['book_author'] ?? '-') ?></p>
                </div>

                <!-- Borrow Date -->
                <div class="p-4 bg-stone-50 rounded-lg">
                    <p class="text-xs text-slate-500 mb-1">Tanggal Pinjam</p>
                    <p class="font-medium text-slate-800"><?= date('d M Y', strtotime($borrowing['borrow_date'])) ?></p>
                </div>

                <!-- Due Date -->
                <div class="p-4 bg-stone-50 rounded-lg">
                    <p class="text-xs text-slate-500 mb-1">Jatuh Tempo</p>
                    <?php 
                    $dueDate = strtotime($borrowing['due_date']);
                    $today = strtotime(date('Y-m-d'));
                    $isOverdue = $dueDate < $today;
                    ?>
                    <p class="font-medium <?= $isOverdue ? 'text-red-600' : 'text-slate-800' ?>">
                        <?= date('d M Y', $dueDate) ?>
                    </p>
                    <?php if ($isOverdue): ?>
                        <?php $daysLate = floor(($today - $dueDate) / (60 * 60 * 24)); ?>
                        <p class="text-sm text-red-500"><?= $daysLate ?> hari terlambat</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Status Warning -->
        <?php if ($isOverdue): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-red-600 mt-0.5"></i>
                    <div class="text-sm text-red-700">
                        <p class="font-medium mb-1">Peminjaman Terlambat!</p>
                        <p class="text-red-600">Buku ini sudah melewati batas waktu pengembalian selama <?= $daysLate ?> hari.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-700 mt-0.5"></i>
                    <div class="text-sm text-emerald-700">
                        <p class="font-medium mb-1">Pengembalian Tepat Waktu</p>
                        <p class="text-emerald-700">Buku dikembalikan sebelum jatuh tempo.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Return Form -->
        <form action="<?= base_url('/admin/borrowings/processReturn/' . $borrowing['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Return Date -->
            <div class="mb-5">
                <label for="return_date" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Pengembalian</label>
                <input 
                    type="date" 
                    id="return_date" 
                    name="return_date" 
                    value="<?= date('Y-m-d') ?>"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                    required
                >
            </div>

            <!-- Notes -->
            <div class="mb-5">
                <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Catatan Pengembalian</label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="3"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-none"
                    placeholder="Catatan kondisi buku atau keterangan lainnya (opsional)"
                ><?= esc($borrowing['notes'] ?? '') ?></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
                <a href="<?= base_url('/admin/borrowings') ?>" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-all font-medium inline-flex items-center gap-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    Konfirmasi Pengembalian
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


