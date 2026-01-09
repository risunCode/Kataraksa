<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Glass Header with Breadcrumb -->
<section class="relative py-8 bg-gradient-to-br from-stone-100 to-stone-200 dark:from-gray-900 dark:to-gray-800 overflow-hidden transition-colors duration-300">
    <!-- Background Blobs -->
    <div class="absolute inset-0">
        <div class="absolute top-0 right-1/4 w-64 h-64 bg-emerald-500 rounded-full blur-3xl opacity-10"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center gap-2 text-sm">
            <a href="<?= base_url('/') ?>" class="text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors">Beranda</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
            <a href="<?= base_url('/catalog') ?>" class="text-gray-500 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors">Katalog</a>
            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
            <span class="text-gray-900 dark:text-white font-medium line-clamp-1"><?= esc($book['title']) ?></span>
        </nav>
    </div>
</section>

<!-- Book Detail with Glass Card -->
<section class="relative py-12 bg-gradient-to-br from-stone-100 to-stone-200 dark:from-gray-900 dark:to-gray-800 overflow-hidden transition-colors duration-300">
    <!-- Background Blobs -->
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-emerald-500 rounded-full blur-3xl opacity-15 dark:opacity-20"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-emerald-500 rounded-full blur-3xl opacity-10 dark:opacity-15"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="backdrop-blur-xl bg-white/70 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-3xl shadow-xl overflow-hidden">
            <div class="grid lg:grid-cols-3 gap-8 p-6 lg:p-10">
                <!-- Book Cover -->
                <div class="lg:col-span-1">
                    <div class="aspect-[3/4] bg-gray-100 dark:bg-gray-800 rounded-2xl overflow-hidden shadow-lg sticky top-24">
                        <?php if (!empty($book['cover'])): ?>
                            <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" alt="<?= esc($book['title']) ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-emerald-600">
                                <i data-lucide="book" class="w-24 h-24 text-white/50"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Book Info -->
                <div class="lg:col-span-2">
                    <!-- Status Badge -->
                    <div class="mb-4">
                        <?php if ($book['available'] > 0): ?>
                            <span class="inline-flex items-center gap-1 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-4 py-2 rounded-full text-sm font-medium">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Tersedia
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 px-4 py-2 rounded-full text-sm font-medium">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                Sedang Dipinjam
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-4"><?= esc($book['title']) ?></h1>

                    <!-- Author -->
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                        oleh <span class="font-semibold text-emerald-600 dark:text-emerald-500"><?= esc($book['author']) ?></span>
                    </p>

                    <!-- Book Details Grid with Glass Cards -->
                    <div class="grid sm:grid-cols-2 gap-4 mb-8">
                        <!-- Category -->
                        <div class="flex items-center gap-3 p-4 backdrop-blur-sm bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl">
                            <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="folder" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Kategori</p>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= esc($book['category_name'] ?? 'Tidak ada kategori') ?></p>
                            </div>
                        </div>

                        <!-- ISBN -->
                        <?php if (!empty($book['isbn'])): ?>
                        <div class="flex items-center gap-3 p-4 backdrop-blur-sm bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl">
                            <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="hash" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">ISBN</p>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= esc($book['isbn']) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Stock -->
                        <div class="flex items-center gap-3 p-4 backdrop-blur-sm bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl">
                            <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total Stok</p>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= $book['stock'] ?> eksemplar</p>
                            </div>
                        </div>

                        <!-- Available -->
                        <div class="flex items-center gap-3 p-4 backdrop-blur-sm bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl">
                            <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center">
                                <i data-lucide="book-check" class="w-6 h-6 text-white"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Tersedia</p>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= $book['available'] ?> eksemplar</p>
                            </div>
                        </div>
                    </div>

                    <!-- Synopsis -->
                    <?php if (!empty($book['synopsis'])): ?>
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">Sinopsis</h2>
                        <div class="backdrop-blur-sm bg-white/50 dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-xl p-5">
                            <p class="text-gray-600 dark:text-gray-300 leading-relaxed"><?= nl2br(esc($book['synopsis'])) ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="<?= base_url('/catalog') ?>" class="inline-flex items-center gap-2 bg-gray-100 dark:bg-white/10 backdrop-blur text-gray-700 dark:text-white px-6 py-3 rounded-xl font-medium border border-gray-200 dark:border-white/20 hover:bg-gray-200 dark:hover:bg-white/20 transition-all">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            Kembali ke Katalog
                        </a>
                        <?php if ($book['available'] > 0): ?>
                            <?php if (session()->get('isLoggedIn')): ?>
                                <?php if (session()->get('role') === 'member'): ?>
                                    <!-- Member: langsung pinjam -->
                                    <button onclick="confirmMemberBorrow('<?= base_url('/member/borrow/' . $book['id']) ?>', '<?= esc($book['title'], 'js') ?>')" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-emerald-700 transition-all shadow-lg">
                                        <i data-lucide="book-plus" class="w-5 h-5"></i>
                                        Pinjam Buku
                                    </button>
                                <?php else: ?>
                                    <!-- Admin/Petugas: redirect ke halaman peminjaman admin -->
                                    <a href="<?= base_url('/admin/borrowings?book_id=' . $book['id']) ?>" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-emerald-700 transition-all shadow-lg">
                                        <i data-lucide="book-plus" class="w-5 h-5"></i>
                                        Pinjam Buku
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- User belum login, tampilkan SweetAlert -->
                                <button onclick="requireLogin('<?= esc($book['title'], 'js') ?>')" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium hover:bg-emerald-700 transition-all shadow-lg">
                                    <i data-lucide="book-plus" class="w-5 h-5"></i>
                                    Pinjam Buku
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Books -->
<?php if (!empty($relatedBooks)): ?>
<section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Buku Terkait</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($relatedBooks as $relatedBook): ?>
                <a href="<?= book_url($relatedBook) ?>" class="group">
                    <div class="bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden aspect-[3/4] mb-3 shadow-md">
                        <?php if (!empty($relatedBook['cover'])): ?>
                            <img src="<?= base_url('uploads/covers/' . $relatedBook['cover']) ?>" alt="<?= esc($relatedBook['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-emerald-600">
                                <i data-lucide="book" class="w-12 h-12 text-white/50"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-500 transition-colors line-clamp-2 text-sm"><?= esc($relatedBook['title']) ?></h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= esc($relatedBook['author']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?= $this->endSection() ?>
