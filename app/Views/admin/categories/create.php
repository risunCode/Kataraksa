<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="<?= base_url('/admin/categories') ?>" class="hover:text-emerald-700 transition-colors">Kategori</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800">Tambah Kategori</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-800">Tambah Kategori Baru</h1>
</div>

<!-- Form Card -->
<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="<?= base_url('/admin/categories/store') ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Category Name -->
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Kategori <span class="text-red-500">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?= old('name') ?>"
                    class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                    placeholder="Masukkan nama kategori"
                    required
                    autofocus
                >
                <?php if (isset($validation) && $validation->hasError('name')): ?>
                    <p class="mt-1 text-sm text-red-600"><?= $validation->getError('name') ?></p>
                <?php endif; ?>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="<?= base_url('/admin/categories') ?>" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-all font-medium">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


