<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="<?= base_url('/admin/users') ?>" class="hover:text-emerald-700 transition-colors">User</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800">Edit User</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-800">Edit User</h1>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="<?= base_url('/admin/users/update/' . $user['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name', $user['name']) ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                    <?php if (isset($validation) && $validation->hasError('name')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('name') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= old('email', $user['email']) ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        placeholder="contoh@email.com"
                        required
                    >
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('email') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700 mb-2">Role <span class="text-red-500">*</span></label>
                    <select 
                        id="role" 
                        name="role" 
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        required
                        <?= $user['id'] == session()->get('user_id') ? 'disabled' : '' ?>
                    >
                        <option value="">Pilih Role</option>
                        <option value="admin" <?= old('role', $user['role']) == 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="petugas" <?= old('role', $user['role']) == 'petugas' ? 'selected' : '' ?>>Petugas</option>
                    </select>
                    <?php if ($user['id'] == session()->get('user_id')): ?>
                        <input type="hidden" name="role" value="<?= $user['role'] ?>">
                        <p class="mt-1 text-xs text-amber-600">Anda tidak dapat mengubah role diri sendiri</p>
                    <?php endif; ?>
                    <?php if (isset($validation) && $validation->hasError('role')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('role') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password Section -->
                <div class="pt-4 border-t border-slate-200">
                    <h3 class="text-sm font-medium text-slate-700 mb-4">Ubah Password (Opsional)</h3>
                    <p class="text-xs text-slate-500 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                    
                    <div class="grid sm:grid-cols-2 gap-5">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password" 
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all pr-10"
                                    placeholder="Masukkan password baru"
                                >
                                <button type="button" onclick="togglePassword('password', 'eye1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <i data-lucide="eye" id="eye1" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $validation->getError('password') ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirm" class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirm" 
                                    name="password_confirm" 
                                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all pr-10"
                                    placeholder="Ulangi password baru"
                                >
                                <button type="button" onclick="togglePassword('password_confirm', 'eye2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <i data-lucide="eye" id="eye2" class="w-5 h-5"></i>
                                </button>
                            </div>
                            <?php if (isset($validation) && $validation->hasError('password_confirm')): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $validation->getError('password_confirm') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 p-4 bg-stone-50 rounded-lg">
                <p class="text-sm text-slate-500">
                    <i data-lucide="info" class="w-4 h-4 inline mr-1"></i>
                    Dibuat pada: <?= date('d M Y H:i', strtotime($user['created_at'])) ?>
                </p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="<?= base_url('/admin/users') ?>" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-all font-medium">
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
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
</script>

<?= $this->endSection() ?>


