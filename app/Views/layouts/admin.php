<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin - Kataraksa' ?></title>
    
    <!-- TailwindCSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Styles -->
    <style>
        .sidebar-link.active {
            background-color: #ecfdf5;
            color: #047857;
            border-left: 3px solid #047857;
        }
    </style>
</head>
<body class="bg-stone-50 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-stone-200">
                <a href="<?= base_url('/admin/dashboard') ?>" class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-emerald-700 rounded-lg flex items-center justify-center">
                        <i data-lucide="book-open" class="w-6 h-6 text-white"></i>
                    </div>
                    <span class="text-xl font-bold text-emerald-700">Kataraksa</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <?php 
                $currentUrl = current_url();
                $menuItems = [
                    ['url' => '/admin/dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
                    ['url' => '/admin/books', 'icon' => 'book', 'label' => 'Buku'],
                    ['url' => '/admin/categories', 'icon' => 'folder', 'label' => 'Kategori'],
                    ['url' => '/admin/members', 'icon' => 'users', 'label' => 'Anggota'],
                    ['url' => '/admin/borrowings', 'icon' => 'repeat', 'label' => 'Peminjaman'],
                    ['url' => '/admin/users', 'icon' => 'user-cog', 'label' => 'User'],
                ];
                ?>
                
                <?php foreach ($menuItems as $item): ?>
                    <?php 
                    $isActive = strpos($currentUrl, $item['url']) !== false;
                    $activeClass = $isActive ? 'sidebar-link active' : 'text-slate-600 hover:bg-stone-100';
                    ?>
                    <a href="<?= base_url($item['url']) ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg transition-all <?= $activeClass ?>">
                        <i data-lucide="<?= $item['icon'] ?>" class="w-5 h-5"></i>
                        <span class="font-medium"><?= $item['label'] ?></span>
                    </a>
                <?php endforeach; ?>

                <!-- Divider -->
                <div class="border-t border-stone-200 my-4"></div>

                <!-- Link ke Halaman Publik -->
                <a href="<?= base_url('/') ?>" target="_blank" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-stone-100 rounded-lg transition-all">
                    <i data-lucide="globe" class="w-5 h-5"></i>
                    <span class="font-medium">Lihat Website</span>
                </a>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64">
            <!-- Top Navbar -->
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-4 lg:px-8 sticky top-0 z-30">
                <!-- Mobile Menu Button -->
                <button onclick="toggleSidebar()" class="lg:hidden p-2 text-slate-600 hover:text-emerald-700 rounded-lg hover:bg-stone-100">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>

                <!-- Page Title -->
                <h1 class="text-lg font-semibold text-slate-800 hidden lg:block"><?= $pageTitle ?? 'Dashboard' ?></h1>

                <!-- Right Side -->
                <div class="flex items-center gap-4">
                    <!-- User Dropdown -->
                    <div class="relative" id="user-dropdown-container">
                        <button id="user-dropdown-btn" class="flex items-center gap-3 hover:bg-stone-100 rounded-lg px-3 py-2 transition-all">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-medium text-slate-800"><?= session()->get('user_name') ?? 'Admin' ?></p>
                                <p class="text-xs text-slate-500 capitalize"><?= session()->get('role') ?? 'Administrator' ?></p>
                            </div>
                            <div class="w-10 h-10 bg-emerald-700 rounded-full flex items-center justify-center">
                                <i data-lucide="user" class="w-5 h-5 text-white"></i>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 hidden sm:block"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="user-dropdown-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-stone-200 py-2 z-50">
                            <div class="px-4 py-2 border-b border-stone-100 sm:hidden">
                                <p class="text-sm font-medium text-slate-800"><?= session()->get('user_name') ?? 'Admin' ?></p>
                                <p class="text-xs text-slate-500 capitalize"><?= session()->get('role') ?? 'Administrator' ?></p>
                            </div>
                            <a href="<?= base_url('/') ?>" target="_blank" class="flex items-center gap-2 px-4 py-2 text-slate-600 hover:bg-stone-100 transition-colors">
                                <i data-lucide="globe" class="w-4 h-4"></i>
                                Lihat Website
                            </a>
                            <div class="border-t border-stone-100 my-1"></div>
                            <a href="<?= base_url('/logout') ?>" class="flex items-center gap-2 px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="p-4 lg:p-8">
                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span><?= session()->getFlashdata('success') ?></span>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        <span><?= session()->getFlashdata('error') ?></span>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        lucide.createIcons();

        // Sidebar toggle for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

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

        // SweetAlert2 Delete Confirmation
        function confirmDelete(url, itemName = 'item') {
            Swal.fire({
                title: 'Hapus ' + itemName + '?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create and submit a form for POST request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // SweetAlert2 Return Book Confirmation
        function confirmReturn(url) {
            Swal.fire({
                title: 'Kembalikan Buku?',
                text: 'Konfirmasi pengembalian buku ini.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Kembalikan!',
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

        // Show success message from session
        <?php if (session()->getFlashdata('swal_success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('swal_success') ?>',
            timer: 2000,
            showConfirmButton: false
        });
        <?php endif; ?>
        
        // Show welcome message after login
        <?php if (session()->getFlashdata('swal_welcome')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Selamat Datang! 👋',
            html: '<p class="text-lg">Halo, <strong><?= esc(session()->getFlashdata('swal_welcome')) ?></strong></p><p class="text-gray-500">Senang melihat Anda kembali!</p>',
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true
        });
        <?php endif; ?>
    </script>
</body>
</html>


