<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Buku</h1>
        <p class="text-slate-500">Kelola koleksi buku perpustakaan</p>
    </div>
    <a href="<?= base_url('/admin/books/create') ?>" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Tambah Buku
    </a>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="<?= base_url('/admin/books') ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
            </div>
            <input 
                type="text" 
                name="search" 
                value="<?= esc($search ?? '') ?>"
                placeholder="Cari judul, penulis, atau ISBN..."
                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
            >
        </div>
        <select name="category" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
            <option value="">Semua Kategori</option>
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= ($selectedCategory ?? '') == $category['id'] ? 'selected' : '' ?>>
                        <?= esc($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <button type="submit" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
            Filter
        </button>
    </form>
</div>

<!-- Books Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Buku</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Kategori</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">ISBN</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Stok</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Tersedia</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-16 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0">
                                        <?php if (!empty($book['cover'])): ?>
                                            <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" alt="<?= esc($book['title']) ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center bg-emerald-700">
                                                <i data-lucide="book" class="w-6 h-6 text-white/50"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800 line-clamp-1"><?= esc($book['title']) ?></p>
                                        <p class="text-sm text-slate-500"><?= esc($book['author']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-600"><?= esc($book['category_name'] ?? '-') ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-600 font-mono text-sm"><?= esc($book['isbn'] ?? '-') ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-medium text-slate-800"><?= $book['stock'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($book['available'] > 0): ?>
                                    <span class="inline-block bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-sm font-medium">
                                        <?= $book['available'] ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-sm font-medium">
                                        0
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('/admin/books/edit/' . $book['id']) ?>" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick="confirmDelete('<?= base_url('/admin/books/delete/' . $book['id']) ?>', 'Buku')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="book-x" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 mb-4">Belum ada data buku</p>
                                <a href="<?= base_url('/admin/books/create') ?>" class="inline-flex items-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Tambah Buku Pertama
                                </a>
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

<?= $this->endSection() ?>


