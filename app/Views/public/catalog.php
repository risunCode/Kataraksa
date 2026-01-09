<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Page Header with Glass -->
<section class="relative py-12 bg-gradient-to-br from-stone-100 to-stone-200 dark:from-gray-900 dark:to-gray-800 overflow-hidden transition-colors duration-300">
    <!-- Background Blobs -->
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500 rounded-full blur-3xl opacity-20"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-emerald-500 rounded-full blur-3xl opacity-15"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-3xl p-8 text-center shadow-xl">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Katalog Buku</h1>
            <p class="text-gray-600 dark:text-gray-300 max-w-xl mx-auto">Temukan buku favorit Anda dari koleksi perpustakaan kami.</p>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-16 z-20 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <form action="<?= base_url('/catalog') ?>" method="GET" class="flex flex-col md:flex-row gap-4">
            <!-- Search Input -->
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    name="search" 
                    value="<?= esc($search ?? '') ?>"
                    placeholder="Cari judul, penulis, atau ISBN..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all text-gray-900 dark:text-white placeholder-gray-500"
                >
            </div>

            <!-- Category Filter -->
            <div class="md:w-64">
                <select 
                    name="category" 
                    class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all appearance-none text-gray-900 dark:text-white"
                >
                    <option value="">Semua Kategori</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= ($selectedCategory == $category['id']) ? 'selected' : '' ?>>
                                <?= esc($category['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Search Button -->
            <button type="submit" class="bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-emerald-700 transition-all flex items-center justify-center gap-2">
                <i data-lucide="search" class="w-5 h-5"></i>
                Cari
            </button>

            <!-- Reset Button -->
            <?php if (!empty($search) || !empty($selectedCategory)): ?>
                <a href="<?= base_url('/catalog') ?>" class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 dark:hover:bg-gray-700 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" class="w-5 h-5"></i>
                    Reset
                </a>
            <?php endif; ?>
        </form>
    </div>
</section>

<!-- Books Grid -->
<section class="py-12 bg-stone-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Info -->
        <?php if (!empty($search) || !empty($selectedCategory)): ?>
            <div class="mb-6 flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <i data-lucide="filter" class="w-5 h-5"></i>
                <span>
                    Menampilkan hasil 
                    <?php if (!empty($search)): ?>
                        untuk "<strong class="text-gray-900 dark:text-white"><?= esc($search) ?></strong>"
                    <?php endif; ?>
                    <?php if (!empty($selectedCategory)): ?>
                        <?php 
                        $categoryName = '';
                        foreach ($categories as $cat) {
                            if ($cat['id'] == $selectedCategory) {
                                $categoryName = $cat['name'];
                                break;
                            }
                        }
                        ?>
                        dalam kategori "<strong class="text-gray-900 dark:text-white"><?= esc($categoryName) ?></strong>"
                    <?php endif; ?>
                </span>
            </div>
        <?php endif; ?>

        <?php if (!empty($books)): ?>
            <!-- Books Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                <?php foreach ($books as $book): ?>
                    <a href="<?= book_url($book) ?>" class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow border border-gray-200 dark:border-gray-700">
                        <!-- Book Cover -->
                        <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-700 overflow-hidden">
                            <?php if (!empty($book['cover'])): ?>
                                <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" alt="<?= esc($book['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-emerald-600">
                                    <i data-lucide="book" class="w-12 h-12 text-white/50"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Book Info -->
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-500 transition-colors line-clamp-2 text-sm mb-1">
                                <?= esc($book['title']) ?>
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 line-clamp-1"><?= esc($book['author']) ?></p>
                            
                            <?php if (!empty($book['category_name'])): ?>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mb-2">
                                    <i data-lucide="folder" class="w-3 h-3 inline"></i>
                                    <?= esc($book['category_name']) ?>
                                </p>
                            <?php endif; ?>

                            <!-- Status Badge -->
                            <?php if ($book['available'] > 0): ?>
                                <span class="inline-block bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-2 py-1 rounded-full text-xs font-medium">
                                    Tersedia (<?= $book['available'] ?>)
                                </span>
                            <?php else: ?>
                                <span class="inline-block bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 px-2 py-1 rounded-full text-xs font-medium">
                                    Dipinjam
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="mt-8 flex justify-center">
                    <?= $pager->links('default', 'default_full') ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="book-x" class="w-12 h-12 text-gray-400"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Buku Tidak Ditemukan</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">
                    <?php if (!empty($search) || !empty($selectedCategory)): ?>
                        Tidak ada buku yang sesuai dengan pencarian Anda.
                    <?php else: ?>
                        Belum ada buku yang tersedia di perpustakaan.
                    <?php endif; ?>
                </p>
                <?php if (!empty($search) || !empty($selectedCategory)): ?>
                    <a href="<?= base_url('/catalog') ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-emerald-700 transition-all">
                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                        Lihat Semua Buku
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Custom Pagination Styles -->
<style>
    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination li a,
    .pagination li span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.5rem;
        height: 2.5rem;
        padding: 0 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .pagination li a {
        background: white;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .dark .pagination li a {
        background: #1f2937;
        color: #9ca3af;
        border: 1px solid #374151;
    }
    .pagination li a:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .dark .pagination li a:hover {
        background: #374151;
        border-color: #4b5563;
    }
    .pagination li.active span {
        background: #059669;
        color: white;
        border: none;
    }
    .pagination li.disabled span {
        background: #f1f5f9;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
        cursor: not-allowed;
    }
    .dark .pagination li.disabled span {
        background: #1f2937;
        color: #4b5563;
        border: 1px solid #374151;
    }
</style>

<?= $this->endSection() ?>
