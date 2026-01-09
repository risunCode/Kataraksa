<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kataraksa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-stone-50">
    <div class="min-h-screen flex">
        <!-- Left Side - Login/Register Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                
                <!-- LOGIN PANEL -->
                <div id="login-panel">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-12 h-12 bg-emerald-700 rounded-xl flex items-center justify-center">
                            <i data-lucide="book-open" class="w-7 h-7 text-white"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-800">Kataraksa</span>
                    </div>

                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Selamat Datang!</h1>
                    <p class="text-slate-500 mb-8">Silakan masuk untuk mengakses dashboard</p>

                    <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-4 rounded-r-lg flex items-start gap-3">
                        <i data-lucide="alert-triangle" class="w-6 h-6 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-semibold">Login Gagal!</p>
                            <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-4 rounded-r-lg flex items-start gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="font-semibold">Berhasil!</p>
                            <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/login') ?>" method="POST" class="space-y-5">
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <input type="email" id="email" name="email" value="<?= old('email') ?>" class="w-full pl-12 pr-4 py-3 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="nama@email.com" required>
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <input type="password" id="password" name="password" class="w-full pl-12 pr-12 py-3 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('password', 'eye-icon')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                                    <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-emerald-700 text-white py-3 rounded-xl font-semibold hover:bg-emerald-800 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="log-in" class="w-5 h-5"></i> Masuk
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-slate-500">Belum punya akun?</p>
                        <button onclick="showRegister()" class="text-emerald-700 hover:text-emerald-800 font-semibold mt-1 inline-flex items-center gap-1">
                            Daftar Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="mt-6 text-center">
                        <a href="<?= base_url('/') ?>" class="text-slate-500 hover:text-emerald-700 transition-colors inline-flex items-center gap-2">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>

                <!-- REGISTER PANEL -->
                <div id="register-panel" class="hidden">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-emerald-700 rounded-xl flex items-center justify-center">
                            <i data-lucide="book-open" class="w-7 h-7 text-white"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-800">Kataraksa</span>
                    </div>

                    <h1 class="text-3xl font-bold text-slate-800 mb-2">Daftar Member</h1>
                    <p class="text-slate-500 mb-6">Buat akun untuk mulai meminjam buku</p>

                    <form id="register-form" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="user" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <input type="text" id="reg_name" name="name" class="w-full pl-12 pr-4 py-3 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Nama lengkap Anda" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="mail" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <input type="email" id="reg_email" name="email" class="w-full pl-12 pr-4 py-3 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="nama@email.com" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">No. Telepon <span class="text-slate-400">(opsional)</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="phone" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <input type="tel" id="reg_phone" name="phone" class="w-full pl-12 pr-4 py-3 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-slate-400"></i>
                                </div>
                                <input type="password" id="reg_password" name="password" class="w-full pl-12 pr-12 py-3 bg-white border border-stone-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all" placeholder="Minimal 6 karakter" required>
                                <button type="button" onclick="togglePassword('reg_password', 'reg-eye-icon')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                                    <i data-lucide="eye" id="reg-eye-icon" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" id="register-btn" class="w-full bg-emerald-700 text-white py-3 rounded-xl font-semibold hover:bg-emerald-800 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="user-plus" class="w-5 h-5"></i> Daftar
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-slate-500">Sudah punya akun?</p>
                        <button onclick="showLogin()" class="text-emerald-700 hover:text-emerald-800 font-semibold mt-1 inline-flex items-center gap-1">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Masuk
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-emerald-700 items-center justify-center p-12 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 border-4 border-white rounded-full"></div>
                <div class="absolute top-1/4 right-20 w-24 h-24 border-4 border-white rounded-full"></div>
                <div class="absolute bottom-20 left-1/4 w-40 h-40 border-4 border-white rounded-full"></div>
                <div class="absolute bottom-10 right-10 w-20 h-20 border-4 border-white rounded-full"></div>
            </div>
            <div class="relative z-10 text-center text-white max-w-lg">
                <h2 class="text-3xl font-bold mb-4">Sistem Perpustakaan Digital</h2>
                <p class="text-emerald-100 text-lg mb-8 leading-relaxed">"Satu Halaman Membuka Dunia,<br>Satu Sistem Menjaga Semuanya"</p>
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
                <p class="mt-12 text-emerald-200 text-sm">&copy; <?= date('Y') ?> Kataraksa - Universitas Bina Sarana Informatika</p>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function showRegister() {
            document.getElementById('login-panel').classList.add('hidden');
            document.getElementById('register-panel').classList.remove('hidden');
            lucide.createIcons();
        }

        function showLogin() {
            document.getElementById('register-panel').classList.add('hidden');
            document.getElementById('login-panel').classList.remove('hidden');
            lucide.createIcons();
        }

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }

        document.getElementById('register-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('register-btn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...';
            btn.disabled = true;

            try {
                const response = await fetch('<?= base_url('/register') ?>', {
                    method: 'POST',
                    body: new FormData(this)
                });
                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pendaftaran Berhasil! 🎉',
                        html: '<p>Akun Anda telah dibuat.</p><p class="text-sm text-gray-500 mt-2">Silakan login dengan email dan password yang telah didaftarkan.</p>',
                        confirmButtonColor: '#059669'
                    }).then(() => {
                        showLogin();
                        document.getElementById('register-form').reset();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, confirmButtonColor: '#059669' });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Silakan coba lagi.', confirmButtonColor: '#059669' });
            }

            btn.innerHTML = originalText;
            btn.disabled = false;
            lucide.createIcons();
        });
    </script>
</body>
</html>
