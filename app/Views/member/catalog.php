<?= $this->extend('layouts/member') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-800">Katalog Buku</h2>
    <p class="text-slate-500 mt-1">Cari dan pinjam buku yang Anda inginkan.</p>
</div>

<!-- Search & Filter -->
<div class="bg-white rounded-xl shadow-sm border border-stone-200 p-6 mb-8">
    <form action="<?= base_url('/member/catalog') ?>" method="GET" class="flex flex-col md:flex-row gap-4">
        <!-- Search Input -->
        <div class="flex-1 relative">
            <i data-lucide="search" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" name="search" value="<?= esc($keyword ?? '') ?>" placeholder="Cari judul, penulis, atau ISBN..." class="w-full pl-10 pr-4 py-3 border border-stone-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
        </div>
        
        <!-- Category Filter -->
        <div class="md:w-64">
            <select name="category" class="w-full px-4 py-3 border border-stone-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= ($selectedCategory ?? '') == $category['id'] ? 'selected' : '' ?>>
                        <?= esc($category['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <!-- Search Button -->
        <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
            <i data-lucide="search" class="w-5 h-5"></i>
            Cari
        </button>
    </form>
</div>

<!-- Books Grid -->
<?php if (empty($books)): ?>
    <div class="bg-white rounded-xl shadow-sm border border-stone-200 p-12 text-center">
        <div class="w-20 h-20 bg-stone-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="search-x" class="w-10 h-10 text-stone-400"></i>
        </div>
        <h4 class="text-lg font-medium text-slate-600 mb-2">Buku Tidak Ditemukan</h4>
        <p class="text-slate-400">Coba kata kunci atau kategori lain.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        <?php foreach ($books as $book): ?>
            <div class="bg-white rounded-xl shadow-sm border border-stone-200 overflow-hidden group hover:shadow-lg transition-all">
                <!-- Book Cover -->
                <div class="aspect-[3/4] bg-stone-100 relative overflow-hidden">
                    <?php if (!empty($book['cover'])): ?>
                        <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" alt="<?= esc($book['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-emerald-600">
                            <i data-lucide="book" class="w-12 h-12 text-white/50"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Availability Badge -->
                    <div class="absolute top-2 right-2">
                        <?php if ($book['available'] > 0): ?>
                            <span class="bg-emerald-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                <?= $book['available'] ?> tersedia
                            </span>
                        <?php else: ?>
                            <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                                Habis
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Book Info -->
                <div class="p-4">
                    <h4 class="font-semibold text-slate-800 line-clamp-2 text-sm mb-1"><?= esc($book['title']) ?></h4>
                    <p class="text-xs text-slate-500 mb-2"><?= esc($book['author']) ?></p>
                    <p class="text-xs text-emerald-600 mb-3"><?= esc($book['category_name'] ?? 'Tanpa Kategori') ?></p>
                    
                    <!-- Action Button -->
                    <?php if ($book['available'] > 0): ?>
                        <button onclick="confirmBorrow('<?= base_url('/member/borrow/' . $book['id']) ?>', '<?= esc($book['title'], 'js') ?>')" class="w-full bg-emerald-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="book-plus" class="w-4 h-4"></i>
                            Pinjam
                        </button>
                    <?php else: ?>
                        <button disabled class="w-full bg-stone-200 text-stone-500 py-2 rounded-lg text-sm font-medium cursor-not-allowed flex items-center justify-center gap-2">
                            <i data-lucide="book-x" class="w-4 h-4"></i>
                            Tidak Tersedia
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
