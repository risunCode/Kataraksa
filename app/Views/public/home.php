<?= $this->extend('layouts/public') ?>

<?= $this->section('content') ?>

<!-- Split Hero Section -->
<section class="min-h-[80vh] flex flex-col lg:flex-row">
    <!-- Left Side - Content -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-16 bg-stone-50 dark:bg-gray-900 transition-colors duration-300">
        <div class="max-w-lg">
            <!-- Logo -->
            <div class="flex items-center gap-3 mb-8">
                <div class="w-14 h-14 bg-emerald-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="book-open" class="w-8 h-8 text-white"></i>
                </div>
                <span class="text-3xl font-bold text-gray-900 dark:text-white">Kata<span class="text-emerald-600">raksa</span></span>
            </div>
            
            <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                Satu Halaman<br>Membuka Dunia
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
                Sistem perpustakaan digital modern yang memudahkan Anda dalam mencari, meminjam, dan mengelola koleksi buku.
            </p>
            
            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= base_url('/catalog') ?>" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg">
                    <i data-lucide="book-open" class="w-5 h-5"></i>
                    Jelajahi Katalog
                </a>
                <a href="<?= base_url('/login') ?>" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                    Masuk
                </a>
            </div>
        </div>
    </div>
    
    <!-- Right Side - Branding -->
    <div class="w-full lg:w-1/2 bg-emerald-700 flex items-center justify-center p-8 lg:p-16 relative overflow-hidden min-h-[50vh] lg:min-h-0">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 w-32 h-32 border-4 border-white rounded-full"></div>
            <div class="absolute top-1/4 right-10 w-24 h-24 border-4 border-white rounded-full"></div>
            <div class="absolute bottom-20 left-1/4 w-40 h-40 border-4 border-white rounded-full"></div>
            <div class="absolute bottom-10 right-20 w-20 h-20 border-4 border-white rounded-full"></div>
        </div>
        
        <div class="relative z-10 text-center text-white max-w-md">
            <p class="text-emerald-100 text-lg mb-8 leading-relaxed">
                "Satu Halaman Membuka Dunia,<br>Satu Sistem Menjaga Semuanya"
            </p>
            
            <!-- Features -->
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <i data-lucide="book" class="w-8 h-8 mx-auto mb-2"></i>
                    <p>Koleksi Lengkap</p>
                </div>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <i data-lucide="search" class="w-8 h-8 mx-auto mb-2"></i>
                    <p>Pencarian Cepat</p>
                </div>
                <div class="bg-white/10 rounded-xl p-4 backdrop-blur-sm">
                    <i data-lucide="shield-check" class="w-8 h-8 mx-auto mb-2"></i>
                    <p>Aman & Terpercaya</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section with Glass Cards -->
<section class="relative py-20 bg-gradient-to-br from-stone-100 to-stone-200 dark:from-gray-900 dark:to-gray-800 overflow-hidden transition-colors duration-300">
    <!-- Background Blobs -->
    <div class="absolute inset-0">
        <div class="absolute top-1/3 right-1/4 w-72 h-72 bg-emerald-500 rounded-full blur-3xl opacity-10"></div>
        <div class="absolute bottom-1/3 left-1/4 w-72 h-72 bg-emerald-500 rounded-full blur-3xl opacity-10"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Fitur Unggulan</h2>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Nikmati kemudahan dalam mengelola dan mengakses perpustakaan digital dengan fitur-fitur modern kami.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature Cards with Glass Effect -->
            <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="search" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pencarian Cepat</h3>
                <p class="text-gray-600 dark:text-gray-300">Temukan buku yang Anda cari dengan mudah menggunakan fitur pencarian yang cepat dan akurat.</p>
            </div>

            <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="filter" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Filter Kategori</h3>
                <p class="text-gray-600 dark:text-gray-300">Jelajahi koleksi buku berdasarkan kategori untuk menemukan bacaan sesuai minat Anda.</p>
            </div>

            <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="clock" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Peminjaman Mudah</h3>
                <p class="text-gray-600 dark:text-gray-300">Proses peminjaman buku yang cepat dan mudah dengan sistem yang terintegrasi.</p>
            </div>

            <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="bell" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Notifikasi</h3>
                <p class="text-gray-600 dark:text-gray-300">Dapatkan pengingat otomatis untuk batas waktu pengembalian buku.</p>
            </div>

            <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="history" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Riwayat Lengkap</h3>
                <p class="text-gray-600 dark:text-gray-300">Lacak semua riwayat peminjaman dan pengembalian buku Anda dengan mudah.</p>
            </div>

            <div class="backdrop-blur-xl bg-white/60 dark:bg-white/10 border border-gray-200 dark:border-white/20 rounded-2xl p-6 hover:shadow-lg transition-shadow">
                <div class="w-12 h-12 bg-emerald-600 rounded-xl flex items-center justify-center mb-4">
                    <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Keamanan Data</h3>
                <p class="text-gray-600 dark:text-gray-300">Data Anda aman dengan sistem keamanan yang terjamin dan terenkripsi.</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Books Section -->
<?php if (!empty($featuredBooks)): ?>
<section class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Buku Terbaru</h2>
                <p class="text-gray-500 dark:text-gray-400">Koleksi buku terbaru yang tersedia di perpustakaan kami.</p>
            </div>
            <a href="<?= base_url('/catalog') ?>" class="hidden sm:inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-400 font-medium">
                Lihat Semua
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredBooks as $book): ?>
            <a href="<?= book_url($book) ?>" class="group">
                <div class="bg-gray-100 dark:bg-gray-800 rounded-xl overflow-hidden aspect-[3/4] mb-3">
                    <?php if (!empty($book['cover'])): ?>
                        <img src="<?= base_url('uploads/covers/' . $book['cover']) ?>" alt="<?= esc($book['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-emerald-600">
                            <i data-lucide="book" class="w-16 h-16 text-white/50"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-500 transition-colors line-clamp-2"><?= esc($book['title']) ?></h3>
                <p class="text-sm text-gray-500 dark:text-gray-400"><?= esc($book['author']) ?></p>
                <?php if ($book['available'] > 0): ?>
                    <span class="inline-block mt-2 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-400 px-2 py-1 rounded-full text-xs">Tersedia</span>
                <?php else: ?>
                    <span class="inline-block mt-2 bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-400 px-2 py-1 rounded-full text-xs">Dipinjam</span>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-8 sm:hidden">
            <a href="<?= base_url('/catalog') ?>" class="inline-flex items-center gap-2 text-emerald-600 dark:text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-400 font-medium">
                Lihat Semua Buku
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section with Glass -->
<section class="relative py-20 bg-gradient-to-br from-emerald-600 to-emerald-700 overflow-hidden">
    <!-- Background Blobs -->
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-white rounded-full blur-3xl opacity-10"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-white rounded-full blur-3xl opacity-10"></div>
    </div>
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="backdrop-blur-xl bg-white/10 border border-white/20 rounded-3xl p-12 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Menjelajahi Dunia Buku?</h2>
            <p class="text-emerald-100 mb-8 text-lg max-w-xl mx-auto">Bergabunglah dengan ribuan pembaca lainnya dan temukan buku favorit Anda di Kataraksa.</p>
            <a href="<?= base_url('/catalog') ?>" class="inline-flex items-center gap-2 bg-white text-emerald-700 px-8 py-4 rounded-xl font-semibold hover:bg-emerald-50 transition-all shadow-lg">
                <i data-lucide="book-open" class="w-5 h-5"></i>
                Jelajahi Katalog
            </a>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-20 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Tentang Perpustakaan Kami</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-4">
                    Kataraksa adalah sistem perpustakaan digital yang dirancang untuk memudahkan pengelolaan dan akses koleksi buku. Dengan antarmuka yang modern dan intuitif, kami berkomitmen untuk memberikan pengalaman terbaik bagi para pembaca.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Perpustakaan kami menyediakan berbagai koleksi buku dari berbagai kategori, mulai dari fiksi, non-fiksi, pendidikan, hingga referensi ilmiah.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="map-pin" class="w-5 h-5 text-emerald-600 dark:text-emerald-500"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Alamat</h4>
                            <p class="text-gray-500 dark:text-gray-400">Jl. Perpustakaan No. 123, Pontianak</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="clock" class="w-5 h-5 text-emerald-600 dark:text-emerald-500"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Jam Operasional</h4>
                            <p class="text-gray-500 dark:text-gray-400">Senin - Jumat: 08:00 - 17:00</p>
                            <p class="text-gray-500 dark:text-gray-400">Sabtu: 09:00 - 14:00</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i data-lucide="phone" class="w-5 h-5 text-emerald-600 dark:text-emerald-500"></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">Kontak</h4>
                            <p class="text-gray-500 dark:text-gray-400">(021) 123-4567</p>
                            <p class="text-gray-500 dark:text-gray-400">info@kataraksa.id</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Glass Card for Library Info -->
            <div class="relative">
                <div class="absolute inset-0 bg-emerald-500 rounded-3xl blur-3xl opacity-10"></div>
                <div class="relative backdrop-blur-xl bg-gradient-to-br from-stone-100 to-stone-200 dark:from-gray-800 dark:to-gray-700 border border-gray-200 dark:border-gray-600 rounded-3xl p-8 text-center">
                    <div class="w-24 h-24 bg-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i data-lucide="building-2" class="w-12 h-12 text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Kataraksa Library</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-6">Perpustakaan Digital Modern</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">"Satu Halaman Membuka Dunia, Satu Sistem Menjaga Semuanya"</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
