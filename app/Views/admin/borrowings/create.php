<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="<?= base_url('/admin/borrowings') ?>" class="hover:text-emerald-700 transition-colors">Peminjaman</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800">Peminjaman Baru</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-800">Buat Peminjaman Baru</h1>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="<?= base_url('/admin/borrowings/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-5">
                <!-- Member -->
                <div>
                    <label for="member_id" class="block text-sm font-medium text-slate-700 mb-2">Anggota <span class="text-red-500">*</span></label>
                    <select 
                        id="member_id" 
                        name="member_id" 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        required
                    >
                        <option value="">Pilih Anggota</option>
                        <?php if (!empty($members)): ?>
                            <?php foreach ($members as $member): ?>
                                <option value="<?= $member['id'] ?>" <?= old('member_id') == $member['id'] ? 'selected' : '' ?>>
                                    <?= esc($member['name']) ?> - <?= esc($member['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('member_id')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('member_id') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Book -->
                <div>
                    <label for="book_id" class="block text-sm font-medium text-slate-700 mb-2">Buku <span class="text-red-500">*</span></label>
                    <select 
                        id="book_id" 
                        name="book_id" 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        required
                    >
                        <option value="">Pilih Buku</option>
                        <?php if (!empty($books)): ?>
                            <?php foreach ($books as $book): ?>
                                <?php if ($book['available'] > 0): ?>
                                    <option value="<?= $book['id'] ?>" <?= old('book_id') == $book['id'] ? 'selected' : '' ?>>
                                        <?= esc($book['title']) ?> - <?= esc($book['author']) ?> (Tersedia: <?= $book['available'] ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('book_id')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('book_id') ?></p>
                    <?php endif; ?>
                    <p class="mt-1 text-xs text-slate-400">Hanya buku yang tersedia yang ditampilkan</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <!-- Borrow Date -->
                    <div>
                        <label for="borrow_date" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            id="borrow_date" 
                            name="borrow_date" 
                            value="<?= old('borrow_date', date('Y-m-d')) ?>"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            required
                            onchange="calculateDueDate()"
                        >
                        <?php if (isset($validation) && $validation->hasError('borrow_date')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('borrow_date') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-medium text-slate-700 mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                        <input 
                            type="date" 
                            id="due_date" 
                            name="due_date" 
                            value="<?= old('due_date', date('Y-m-d', strtotime('+7 days'))) ?>"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            required
                        >
                        <?php if (isset($validation) && $validation->hasError('due_date')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('due_date') ?></p>
                        <?php endif; ?>
                        <p class="mt-1 text-xs text-slate-400">Default: 7 hari dari tanggal pinjam</p>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Catatan</label>
                    <textarea 
                        id="notes" 
                        name="notes" 
                        rows="3"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-none"
                        placeholder="Catatan tambahan (opsional)"
                    ><?= old('notes') ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('notes')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('notes') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-emerald-700 mt-0.5"></i>
                    <div class="text-sm text-emerald-700">
                        <p class="font-medium mb-1">Informasi Peminjaman:</p>
                        <ul class="list-disc list-inside space-y-1 text-emerald-700">
                            <li>Stok buku akan otomatis berkurang setelah peminjaman</li>
                            <li>Durasi peminjaman default adalah 7 hari</li>
                            <li>Peminjaman yang melewati jatuh tempo akan ditandai terlambat</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="<?= base_url('/admin/borrowings') ?>" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-all font-medium">
                    Simpan Peminjaman
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateDueDate() {
    const borrowDate = document.getElementById('borrow_date').value;
    if (borrowDate) {
        const date = new Date(borrowDate);
        date.setDate(date.getDate() + 7);
        const dueDate = date.toISOString().split('T')[0];
        document.getElementById('due_date').value = dueDate;
    }
}
</script>

<?= $this->endSection() ?>


