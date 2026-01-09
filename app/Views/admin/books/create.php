<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="<?= base_url('/admin/books') ?>" class="hover:text-emerald-700 transition-colors">Buku</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800">Tambah Buku</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-800">Tambah Buku Baru</h1>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <form action="<?= base_url('/admin/books/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Left Column - Cover Upload -->
            <div class="lg:col-span-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">Cover Buku</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:border-emerald-500 transition-colors">
                    <div id="cover-preview" class="hidden mb-4">
                        <img id="preview-image" src="" alt="Preview" class="w-full aspect-[3/4] object-cover rounded-lg mx-auto">
                    </div>
                    <div id="upload-placeholder">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="image" class="w-8 h-8 text-slate-400"></i>
                        </div>
                        <p class="text-slate-600 mb-2">Upload cover buku</p>
                        <p class="text-sm text-slate-400">PNG, JPG hingga 2MB</p>
                    </div>
                    <input 
                        type="file" 
                        name="cover" 
                        id="cover-input"
                        accept="image/*"
                        class="hidden"
                        onchange="previewCover(this)"
                    >
                    <button type="button" onclick="document.getElementById('cover-input').click()" class="mt-4 bg-slate-100 text-slate-700 px-4 py-2 rounded-lg hover:bg-slate-200 transition-all text-sm font-medium">
                        Pilih Gambar
                    </button>
                </div>
                <?php if (isset($validation) && $validation->hasError('cover')): ?>
                    <p class="mt-2 text-sm text-red-600"><?= $validation->getError('cover') ?></p>
                <?php endif; ?>
            </div>

            <!-- Right Column - Book Details -->
            <div class="lg:col-span-2 space-y-5">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Judul Buku <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        value="<?= old('title') ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        placeholder="Masukkan judul buku"
                        required
                    >
                    <?php if (isset($validation) && $validation->hasError('title')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('title') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-slate-700 mb-2">Penulis <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="author" 
                        name="author" 
                        value="<?= old('author') ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        placeholder="Masukkan nama penulis"
                        required
                    >
                    <?php if (isset($validation) && $validation->hasError('author')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('author') ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <!-- Category -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select 
                            id="category_id" 
                            name="category_id" 
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            required
                        >
                            <option value="">Pilih Kategori</option>
                            <?php if (!empty($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
                                        <?= esc($category['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('category_id')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('category_id') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- ISBN -->
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-slate-700 mb-2">ISBN</label>
                        <input 
                            type="text" 
                            id="isbn" 
                            name="isbn" 
                            value="<?= old('isbn') ?>"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="Masukkan ISBN"
                        >
                        <?php if (isset($validation) && $validation->hasError('isbn')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('isbn') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Stock -->
                <div>
                    <label for="stock" class="block text-sm font-medium text-slate-700 mb-2">Jumlah Stok <span class="text-red-500">*</span></label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        value="<?= old('stock', 1) ?>"
                        min="0"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        placeholder="Masukkan jumlah stok"
                        required
                    >
                    <?php if (isset($validation) && $validation->hasError('stock')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('stock') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Synopsis -->
                <div>
                    <label for="synopsis" class="block text-sm font-medium text-slate-700 mb-2">Sinopsis</label>
                    <textarea 
                        id="synopsis" 
                        name="synopsis" 
                        rows="5"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-none"
                        placeholder="Masukkan sinopsis buku"
                    ><?= old('synopsis') ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('synopsis')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('synopsis') ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
            <a href="<?= base_url('/admin/books') ?>" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-medium">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-all font-medium">
                Simpan Buku
            </button>
        </div>
    </form>
</div>

<script>
function previewCover(input) {
    const preview = document.getElementById('cover-preview');
    const placeholder = document.getElementById('upload-placeholder');
    const previewImage = document.getElementById('preview-image');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?= $this->endSection() ?>


