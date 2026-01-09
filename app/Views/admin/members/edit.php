<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
        <a href="<?= base_url('/admin/members') ?>" class="hover:text-emerald-700 transition-colors">Anggota</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-slate-800">Edit Anggota</span>
    </div>
    <h1 class="text-2xl font-bold text-slate-800">Edit Anggota</h1>
</div>

<!-- Form Card -->
<div class="max-w-2xl">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="<?= base_url('/admin/members/update/' . $member['id']) ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name', $member['name']) ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                        placeholder="Masukkan nama lengkap"
                        required
                    >
                    <?php if (isset($validation) && $validation->hasError('name')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('name') ?></p>
                    <?php endif; ?>
                </div>

                <div class="grid sm:grid-cols-2 gap-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="<?= old('email', $member['email']) ?>"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="contoh@email.com"
                            required
                        >
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('email') ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-2">No. Telepon</label>
                        <input 
                            type="text" 
                            id="phone" 
                            name="phone" 
                            value="<?= old('phone', $member['phone']) ?>"
                            class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                            placeholder="08xxxxxxxxxx"
                        >
                        <?php if (isset($validation) && $validation->hasError('phone')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= $validation->getError('phone') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-2">Alamat</label>
                    <textarea 
                        id="address" 
                        name="address" 
                        rows="3"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all resize-none"
                        placeholder="Masukkan alamat lengkap"
                    ><?= old('address', $member['address']) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('address')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('address') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Registered Date -->
                <div>
                    <label for="registered_at" class="block text-sm font-medium text-slate-700 mb-2">Tanggal Daftar</label>
                    <input 
                        type="date" 
                        id="registered_at" 
                        name="registered_at" 
                        value="<?= old('registered_at', $member['registered_at']) ?>"
                        class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition-all"
                    >
                    <?php if (isset($validation) && $validation->hasError('registered_at')): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $validation->getError('registered_at') ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="<?= base_url('/admin/members') ?>" class="px-6 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-all font-medium">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-700 text-white rounded-lg hover:bg-emerald-800 transition-all font-medium">
                    Update Anggota
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>


