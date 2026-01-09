<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Manajemen Kategori</h1>
        <p class="text-slate-500">Kelola kategori buku perpustakaan</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center justify-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
        <i data-lucide="plus" class="w-5 h-5"></i>
        Tambah Kategori
    </button>
</div>

<!-- Categories Table -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-stone-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 text-sm font-semibold text-slate-700">Nama Kategori</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700">Jumlah Buku</th>
                    <th class="text-center px-6 py-4 text-sm font-semibold text-slate-700 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                        <tr class="hover:bg-stone-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="folder" class="w-5 h-5 text-emerald-700"></i>
                                    </div>
                                    <span class="font-medium text-slate-800"><?= esc($category['name']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <?= $category['book_count'] ?? 0 ?> buku
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick="openModal(<?= $category['id'] ?>, '<?= esc($category['name'], 'js') ?>')" class="p-2 text-sky-600 hover:bg-sky-50 rounded-lg transition-colors" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button onclick="confirmDelete('<?= base_url('/admin/categories/delete/' . $category['id']) ?>', 'Kategori')" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                    <i data-lucide="folder-x" class="w-8 h-8 text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 mb-4">Belum ada data kategori</p>
                                <button onclick="openModal()" class="inline-flex items-center gap-2 bg-emerald-700 text-white px-4 py-2 rounded-lg hover:bg-emerald-800 transition-all font-medium">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Tambah Kategori Pertama
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
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg relative">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200">
                <h3 id="modal-title" class="text-xl font-bold text-slate-800">Tambah Kategori</h3>
                <button onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            
            <!-- Body -->
            <form id="category-form" method="POST">
                <?= csrf_field() ?>
                <div class="p-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="Masukkan nama kategori"
                            required
                        >
                    </div>
                    
                    <!-- Info -->
                    <div class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <div class="flex gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5"></i>
                            <p class="text-sm text-amber-700">Jumlah buku akan otomatis bertambah ketika ada buku yang menggunakan kategori ini.</p>
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
function openModal(id = null, name = '') {
    const modal = document.getElementById('modal');
    const form = document.getElementById('category-form');
    const title = document.getElementById('modal-title');
    const nameInput = document.getElementById('name');
    
    if (id) {
        title.textContent = 'Edit Kategori';
        form.action = '<?= base_url('/admin/categories/update/') ?>' + id;
        nameInput.value = name;
    } else {
        title.textContent = 'Tambah Kategori';
        form.action = '<?= base_url('/admin/categories/store') ?>';
        nameInput.value = '';
    }
    
    modal.classList.remove('hidden');
    nameInput.focus();
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
