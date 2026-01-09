<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen User</h1>
        <p class="text-slate-500">Kelola user sistem (admin & petugas)</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Tambah User
    </button>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700 w-16">No</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">User</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Role</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Dibuat</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (!empty($users)): ?>
                    <?php $no = 1; foreach ($users as $user): ?>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-700 rounded-full flex items-center justify-center text-white font-semibold">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800"><?= esc($user['name']) ?></p>
                                        <p class="text-sm text-slate-500"><?= esc($user['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                $roleClass = match($user['role']) {
                                    'admin' => 'bg-purple-100 text-purple-700',
                                    'petugas' => 'bg-sky-100 text-sky-700',
                                    default => 'bg-slate-100 text-slate-700'
                                };
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium capitalize <?= $roleClass ?>">
                                    <?= esc($user['role']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-500 text-sm">
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openModal(<?= json_encode($user) ?>)' class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <?php if ($user['id'] != session()->get('user_id')): ?>
                                        <button onclick="confirmDelete('<?= base_url('/admin/users/delete/' . $user['id']) ?>', 'User')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="p-2 text-slate-300 cursor-not-allowed" title="Tidak dapat menghapus diri sendiri">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="user-x" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 mb-4">Belum ada data user</p>
                                <button onclick="openModal()" class="inline-flex items-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Tambah User Pertama
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200 sticky top-0 bg-white">
                <h3 id="modal-title" class="text-xl font-bold text-slate-800">Tambah User</h3>
                <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Body -->
            <form id="user-form" method="POST">
                <?= csrf_field() ?>
                <div class="p-6">
                    <div class="grid md:grid-cols-2 gap-5">
                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                placeholder="Masukkan nama lengkap"
                                required
                            >
                        </div>
                        
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                placeholder="nama@email.com"
                                required
                            >
                        </div>
                        
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">
                                Password <span id="password-required" class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                placeholder="Masukkan password"
                            >
                            <p id="password-hint" class="mt-1 text-xs text-slate-500 hidden">Kosongkan jika tidak ingin mengubah password</p>
                        </div>
                        
                        <!-- Role -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700 mb-2">Role <span class="text-red-500">*</span></label>
                            <select 
                                id="role" 
                                name="role" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                required
                            >
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="petugas">Petugas</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-200">
                    <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 transition-all font-medium">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 transition-all font-medium">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(user = null) {
    const modal = document.getElementById('modal');
    const form = document.getElementById('user-form');
    const title = document.getElementById('modal-title');
    const passwordInput = document.getElementById('password');
    const passwordRequired = document.getElementById('password-required');
    const passwordHint = document.getElementById('password-hint');
    
    if (user) {
        title.textContent = 'Edit User';
        form.action = '<?= base_url('/admin/users/update/') ?>' + user.id;
        document.getElementById('name').value = user.name || '';
        document.getElementById('email').value = user.email || '';
        document.getElementById('role').value = user.role || '';
        passwordInput.value = '';
        passwordInput.required = false;
        passwordRequired.classList.add('hidden');
        passwordHint.classList.remove('hidden');
    } else {
        title.textContent = 'Tambah User';
        form.action = '<?= base_url('/admin/users/store') ?>';
        form.reset();
        passwordInput.required = true;
        passwordRequired.classList.remove('hidden');
        passwordHint.classList.add('hidden');
    }
    
    modal.classList.remove('hidden');
    document.getElementById('name').focus();
    lucide.createIcons();
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>

<?= $this->endSection() ?>
