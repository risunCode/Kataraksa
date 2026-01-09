<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Kataraksa - Sistem Perpustakaan Digital' ?></title>
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
        
        // Check localStorage for theme preference, default to dark
        if (localStorage.getItem('theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-stone-50 dark:bg-gray-900 min-h-screen flex flex-col transition-colors duration-300">
    <!-- Header/Navbar -->
    <header class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800 sticky top-0 z-50 transition-colors duration-300">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <i data-lucide="book-open" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-gray-900 dark:text-white">Kata<span class="text-emerald-600 dark:text-emerald-500">raksa</span></span>
                </a>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center gap-6">
                    <a href="<?= base_url('/') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium">Beranda</a>
                    <a href="<?= base_url('/catalog') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium">Katalog</a>
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-3">
                    <!-- Dark Mode Toggle -->
                    <button id="theme-toggle" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors" title="Toggle Dark Mode">
                        <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
                        <i data-lucide="moon" class="w-5 h-5 block dark:hidden"></i>
                    </button>
                    
                    <?php if (session()->get('isLoggedIn')): ?>
                        <!-- User Dropdown -->
                        <div class="relative" id="user-dropdown-container">
                            <button id="user-dropdown-btn" class="hidden sm:flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-all font-medium">
                                <i data-lucide="user" class="w-4 h-4"></i>
                                <span class="max-w-[100px] truncate"><?= esc(session()->get('user_name')) ?></span>
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>
                            <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50">
                                <?php if (session()->get('role') === 'member'): ?>
                                    <a href="<?= base_url('/member/dashboard') ?>" class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        Dashboard
                                    </a>
                                    <a href="<?= base_url('/member/borrowings') ?>" class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i data-lucide="book-copy" class="w-4 h-4"></i>
                                        Peminjaman Saya
                                    </a>
                                <?php else: ?>
                                    <a href="<?= base_url('/admin/dashboard') ?>" class="flex items-center gap-2 px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                        Admin Panel
                                    </a>
                                <?php endif; ?>
                                <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>
                                <a href="<?= base_url('/logout') ?>" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    Logout
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Login Button -->
                        <a href="<?= base_url('/login') ?>" class="hidden sm:inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition-all font-medium">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            Login
                        </a>
                    <?php endif; ?>
                    
                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-btn" class="md:hidden p-2 text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="hidden md:hidden pb-4">
                <div class="flex flex-col gap-2">
                    <a href="<?= base_url('/') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium py-2">Beranda</a>
                    <a href="<?= base_url('/catalog') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium py-2">Katalog</a>
                    <?php if (session()->get('isLoggedIn')): ?>
                        <?php if (session()->get('role') === 'member'): ?>
                            <a href="<?= base_url('/member/dashboard') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium py-2">Dashboard</a>
                            <a href="<?= base_url('/member/borrowings') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium py-2">Peminjaman Saya</a>
                        <?php else: ?>
                            <a href="<?= base_url('/admin/dashboard') ?>" class="text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium py-2">Admin Panel</a>
                        <?php endif; ?>
                        <a href="<?= base_url('/logout') ?>" class="text-red-600 hover:text-red-700 transition-colors font-medium py-2">Logout</a>
                    <?php else: ?>
                        <a href="<?= base_url('/login') ?>" class="sm:hidden text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors font-medium py-2">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 text-gray-900 dark:text-white mt-auto transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="book-open" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-xl font-bold">Kata<span class="text-emerald-600 dark:text-emerald-500">raksa</span></span>
                    </div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        Satu Halaman Membuka Dunia, Satu Sistem Menjaga Semuanya.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="font-semibold mb-4">Menu</h3>
                    <ul class="space-y-2 text-gray-500 dark:text-gray-400 text-sm">
                        <li><a href="<?= base_url('/') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors">Beranda</a></li>
                        <li><a href="<?= base_url('/catalog') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors">Katalog Buku</a></li>
                        <li><a href="<?= base_url('/login') ?>" class="hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors">Login</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="font-semibold mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-500 dark:text-gray-400 text-sm">
                        <li class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4"></i>
                            Jl. Perpustakaan No. 123, Pontianak
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                            (021) 123-4567
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            info@kataraksa.id
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-800 mt-8 pt-8 text-center text-gray-500 text-sm">
                <p>&copy; <?= date('Y') ?> Kataraksa. Dibuat oleh Dimas - Universitas Bina Sarana Informatika</p>
            </div>
        </div>
    </footer>

    <!-- Initialize Lucide Icons & Theme Toggle -->
    <script>
        lucide.createIcons();

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
        
        // User dropdown toggle
        const userDropdownBtn = document.getElementById('user-dropdown-btn');
        const userDropdownMenu = document.getElementById('user-dropdown-menu');
        
        if (userDropdownBtn && userDropdownMenu) {
            userDropdownBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!userDropdownMenu.classList.contains('hidden')) {
                    userDropdownMenu.classList.add('hidden');
                }
            });
        }
        
        // Theme toggle
        document.getElementById('theme-toggle').addEventListener('click', function() {
            const html = document.documentElement;
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
            
            // Re-render icons after theme change
            lucide.createIcons();
        });
        
        // Function to show login required alert
        function requireLogin(bookTitle = 'buku ini') {
            Swal.fire({
                icon: 'info',
                title: 'Login Diperlukan',
                html: '<p>Anda harus login terlebih dahulu untuk meminjam <strong>' + bookTitle + '</strong>.</p>',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="mr-2">🔐</i> Login Sekarang',
                cancelButtonText: 'Nanti Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('/login') ?>';
                }
            });
        }

        // Function for member to confirm borrow
        function confirmMemberBorrow(url, bookTitle) {
            Swal.fire({
                title: 'Pinjam Buku?',
                html: `<p>Anda akan meminjam buku:</p><p class="font-semibold mt-2">"${bookTitle}"</p><p class="text-sm text-gray-500 mt-2">Jatuh tempo: 7 hari dari sekarang</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Pinjam!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>
</html>
