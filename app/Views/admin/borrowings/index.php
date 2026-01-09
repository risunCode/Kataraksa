<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Peminjaman</h1>
        <p class="text-slate-500">Kelola transaksi peminjaman buku</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Peminjaman Baru
    </button>
</div>

<!-- Filter & Search -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="<?= base_url('/admin/borrowings') ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
            </div>
            <input 
                type="text" 
                name="search" 
                value="<?= esc($search ?? '') ?>"
                placeholder="Cari anggota atau judul buku..."
                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
            >
        </div>
        <select name="status" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
            <option value="">Semua Status</option>
            <option value="borrowed" <?= ($selectedStatus ?? '') == 'borrowed' ? 'selected' : '' ?>>Dipinjam</option>
            <option value="returned" <?= ($selectedStatus ?? '') == 'returned' ? 'selected' : '' ?>>Dikembalikan</option>
            <option value="overdue" <?= ($selectedStatus ?? '') == 'overdue' ? 'selected' : '' ?>>Terlambat</option>
        </select>
        <button type="submit" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
            Filter
        </button>
    </form>
</div>

<!-- Borrowings Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Anggota</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Buku</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Tgl Pinjam</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Jatuh Tempo</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Status</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (!empty($borrowings)): ?>
                    <?php foreach ($borrowings as $borrowing): ?>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-700 rounded-full flex items-center justify-center text-white font-semibold">
                                        <?= strtoupper(substr($borrowing['member_name'] ?? 'A', 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800"><?= esc($borrowing['member_name'] ?? '-') ?></p>
                                        <p class="text-sm text-slate-500"><?= esc($borrowing['member_email'] ?? '-') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-slate-800 line-clamp-1"><?= esc($borrowing['book_title'] ?? '-') ?></p>
                                <p class="text-sm text-slate-500"><?= esc($borrowing['book_author'] ?? '-') ?></p>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-600 text-sm">
                                <?= date('d M Y', strtotime($borrowing['borrow_date'])) ?>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <?php 
                                $dueDate = strtotime($borrowing['due_date']);
                                $today = strtotime(date('Y-m-d'));
                                $isOverdue = $borrowing['status'] == 'borrowed' && $dueDate < $today;
                                ?>
                                <span class="<?= $isOverdue ? 'text-red-600 font-medium' : 'text-slate-600' ?>">
                                    <?= date('d M Y', $dueDate) ?>
                                </span>
                                <?php if ($isOverdue): ?>
                                    <p class="text-xs text-red-500">
                                        <?php 
                                        $daysLate = floor(($today - $dueDate) / (60 * 60 * 24));
                                        echo $daysLate . ' hari terlambat';
                                        ?>
                                    </p>
                                <?php endif; ?>
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
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('/admin/borrowings/show/' . $borrowing['id']) ?>" class="p-2 text-slate-600 hover:bg-stone-50 rounded-lg transition-colors" title="Detail">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <?php if ($borrowing['status'] == 'borrowed'): ?>
                                        <button onclick="confirmReturn('<?= base_url('/admin/borrowings/return/' . $borrowing['id']) ?>')" class="p-2 text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors" title="Kembalikan">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($borrowing['status'] !== 'borrowed'): ?>
                                    <button onclick="confirmDelete('<?= base_url('/admin/borrowings/delete/' . $borrowing['id']) ?>', 'Peminjaman')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="repeat" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 mb-4">Belum ada data peminjaman</p>
                                <button onclick="openModal()" class="inline-flex items-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Buat Peminjaman Pertama
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
        <div class="px-6 py-4 border-t border-slate-200">
            <?= $pager->links('default', 'default_full') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Peminjaman Baru -->
<div id="modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200 sticky top-0 bg-white">
                <h3 class="text-xl font-bold text-slate-800">Peminjaman Baru</h3>
                <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Body -->
            <form action="<?= base_url('/admin/borrowings/store') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Member -->
                        <div>
                            <label for="member_id" class="block text-sm font-medium text-slate-700 mb-2">Anggota <span class="text-red-500">*</span></label>
                            <select 
                                id="member_id" 
                                name="member_id" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                required
                            >
                                <option value="">Pilih Anggota</option>
                                <?php if (!empty($members)): ?>
                                    <?php foreach ($members as $member): ?>
                                        <option value="<?= $member['id'] ?>">
                                            <?= esc($member['name']) ?> - <?= esc($member['email']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Book -->
                        <div>
                            <label for="book_id" class="block text-sm font-medium text-slate-700 mb-2">Buku <span class="text-red-500">*</span></label>
                            <select 
                                id="book_id" 
                                name="book_id" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                required
                            >
                                <option value="">Pilih Buku</option>
                                <?php if (!empty($books)): ?>
                                    <?php foreach ($books as $book): ?>
                                        <?php if ($book['available'] > 0): ?>
                                            <option value="<?= $book['id'] ?>">
                                                <?= esc($book['title']) ?> (<?= $book['available'] ?> tersedia)
                                            </option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="mt-1 text-xs text-slate-400">Hanya buku yang tersedia</p>
                        </div>

                        <!-- Borrow Date -->
                        <div>
                            <label for="borrow_date" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Pinjam <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="borrow_date" 
                                name="borrow_date" 
                                value="<?= date('Y-m-d') ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                required
                                onchange="calculateDueDate()"
                            >
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700 mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                            <input 
                                type="date" 
                                id="due_date" 
                                name="due_date" 
                                value="<?= date('Y-m-d', strtotime('+7 days')) ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                required
                            >
                            <p class="mt-1 text-xs text-slate-400">Default: 7 hari dari tanggal pinjam</p>
                        </div>

                        <!-- Notes - Full Width -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-slate-700 mb-2">Catatan</label>
                            <textarea 
                                id="notes" 
                                name="notes" 
                                rows="2"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-none"
                                placeholder="Catatan tambahan (opsional)"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="flex gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-emerald-700 flex-shrink-0 mt-0.5"></i>
                            <div class="text-sm text-emerald-700">
                                <p>Stok buku akan otomatis berkurang setelah peminjaman disimpan.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all font-medium">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 transition-all font-medium">
                        Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
    lucide.createIcons();
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
    // Clear book_id from URL without reload
    const url = new URL(window.location);
    url.searchParams.delete('book_id');
    window.history.replaceState({}, '', url);
}

function calculateDueDate() {
    const borrowDate = document.getElementById('borrow_date').value;
    if (borrowDate) {
        const date = new Date(borrowDate);
        date.setDate(date.getDate() + 7);
        const dueDate = date.toISOString().split('T')[0];
        document.getElementById('due_date').value = dueDate;
    }
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});

// Auto open modal if book_id is provided
<?php if (!empty($openModal) && !empty($selectedBook)): ?>
document.addEventListener('DOMContentLoaded', function() {
    // Set the book in dropdown
    const bookSelect = document.getElementById('book_id');
    if (bookSelect) {
        bookSelect.value = '<?= $selectedBook['id'] ?>';
    }
    
    // Open modal
    openModal();
    
    // Show SweetAlert
    Swal.fire({
        icon: 'info',
        title: 'Pinjam Buku',
        html: '<p>Anda akan meminjam buku:</p><p class="font-semibold text-lg mt-2"><?= esc($selectedBook['title'], 'js') ?></p><p class="text-gray-500"><?= esc($selectedBook['author'], 'js') ?></p>',
        confirmButtonColor: '#059669',
        confirmButtonText: 'Lanjutkan'
    });
});
<?php endif; ?>
</script>

<?= $this->endSection() ?>
