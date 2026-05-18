<x-app-layout title="Tambah Produk">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>Tambah Produk</x-slot:header>
    <x-slot:subtitle>Tambah produk baru ke toko Anda</x-slot:subtitle>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('owner.products.store') }}" enctype="multipart/form-data" class="glass-card p-6 space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="Nama produk" id="input-product-name">
                    @error('name') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Kategori</label>
                    <select name="category_id" class="form-input" id="input-product-category">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Barcode</label>
                    <input type="text" name="barcode" value="{{ old('barcode') }}" class="form-input" placeholder="Scan atau ketik barcode" id="input-product-barcode">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" class="form-input" placeholder="Kode SKU" id="input-product-sku">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Satuan</label>
                    <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" class="form-input" id="input-product-unit">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Harga Modal <span class="text-red-400">*</span></label>
                    <input type="number" name="cost_price" value="{{ old('cost_price', 0) }}" class="form-input" required min="0" id="input-cost-price">
                    @error('cost_price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Harga Jual <span class="text-red-400">*</span></label>
                    <input type="number" name="selling_price" value="{{ old('selling_price') }}" class="form-input" required min="0" id="input-selling-price">
                    @error('selling_price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stok <span class="text-red-400">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" class="form-input" required min="0" id="input-stock">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stok Minimum</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" class="form-input" min="0" id="input-min-stock">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*" class="form-input" id="input-product-image">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="Deskripsi produk (opsional)" id="input-description">{{ old('description') }}</textarea>
                </div>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-indigo-500 focus:ring-indigo-500/20">
                        <span class="text-sm">Produk Aktif</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="track_stock" value="1" {{ old('track_stock', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-indigo-500 focus:ring-indigo-500/20">
                        <span class="text-sm">Lacak Stok</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="btn-primary" id="btn-save-product">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Produk
                </button>
                <a href="{{ route('owner.products.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
