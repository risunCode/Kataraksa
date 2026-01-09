<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Anggota</h1>
        <p class="text-slate-500">Kelola data anggota perpustakaan</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Tambah Anggota
    </button>
</div>

<!-- Search -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4 mb-6">
    <form action="<?= base_url('/admin/members') ?>" method="GET" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
            </div>
            <input 
                type="text" 
                name="search" 
                value="<?= esc($search ?? '') ?>"
                placeholder="Cari nama, email, atau no. telepon..."
                class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
            >
        </div>
        <button type="submit" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all font-medium">
            Cari
        </button>
    </form>
</div>

<!-- Members Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Anggota</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Kontak</th>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Alamat</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Terdaftar</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (!empty($members)): ?>
                    <?php foreach ($members as $member): ?>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-700 rounded-full flex items-center justify-center text-white font-semibold">
                                        <?= strtoupper(substr($member['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800"><?= esc($member['name']) ?></p>
                                        <p class="text-sm text-slate-500"><?= esc($member['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                                    <?= esc($member['phone'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-slate-600 line-clamp-1 max-w-xs"><?= esc($member['address'] ?? '-') ?></p>
                            </td>
                            <td class="px-6 py-4 text-center text-slate-500 text-sm">
                                <?= date('d M Y', strtotime($member['registered_at'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='openModal(<?= json_encode($member) ?>)' class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="confirmDelete('<?= base_url('/admin/members/delete/' . $member['id']) ?>', 'Anggota')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="users" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 mb-4">Belum ada data anggota</p>
                                <button onclick="openModal()" class="inline-flex items-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Tambah Anggota Pertama
                                </button>
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

<!-- Modal -->
<div id="modal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/50" onclick="closeModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl relative max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200 sticky top-0 bg-white">
                <h3 id="modal-title" class="text-xl font-bold text-slate-800">Tambah Anggota</h3>
                <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Body -->
            <form id="member-form" method="POST">
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
                        
                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">No. Telepon</label>
                            <input 
                                type="text" 
                                id="phone" 
                                name="phone" 
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                                placeholder="08xxxxxxxxxx"
                            >
                        </div>
                        
                        <!-- Registered At -->
                        <div>
                            <label for="registered_at" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Daftar</label>
                            <input 
                                type="date" 
                                id="registered_at" 
                                name="registered_at" 
                                value="<?= date('Y-m-d') ?>"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            >
                        </div>
                        
                        <!-- Address - Full Width -->
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-slate-700 mb-2">Alamat</label>
                            <textarea 
                                id="address" 
                                name="address" 
                                rows="3"
                                class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-none"
                                placeholder="Masukkan alamat lengkap"
                            ></textarea>
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
function openModal(member = null) {
    const modal = document.getElementById('modal');
    const form = document.getElementById('member-form');
    const title = document.getElementById('modal-title');
    
    if (member) {
        title.textContent = 'Edit Anggota';
        form.action = '<?= base_url('/admin/members/update/') ?>' + member.id;
        document.getElementById('name').value = member.name || '';
        document.getElementById('email').value = member.email || '';
        document.getElementById('phone').value = member.phone || '';
        document.getElementById('address').value = member.address || '';
        document.getElementById('registered_at').value = member.registered_at ? member.registered_at.split(' ')[0] : '<?= date('Y-m-d') ?>';
    } else {
        title.textContent = 'Tambah Anggota';
        form.action = '<?= base_url('/admin/members/store') ?>';
        form.reset();
        document.getElementById('registered_at').value = '<?= date('Y-m-d') ?>';
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
