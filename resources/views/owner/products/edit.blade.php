<x-app-layout title="Edit Produk">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>Edit Produk</x-slot:header>
    <x-slot:subtitle>{{ $product->name }}</x-slot:subtitle>

    <div class="max-w-3xl">
        <form method="POST" action="{{ route('owner.products.update', $product) }}" enctype="multipart/form-data" class="glass-card p-6 space-y-6">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Nama Produk <span class="text-red-400">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Kategori</label>
                    <select name="category_id" class="form-input">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id',$product->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Barcode</label>
                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Satuan</label>
                    <input type="text" name="unit" value="{{ old('unit', $product->unit) }}" class="form-input">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Harga Modal <span class="text-red-400">*</span></label>
                    <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" class="form-input" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Harga Jual <span class="text-red-400">*</span></label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" class="form-input" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stok <span class="text-red-400">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-input" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Stok Minimum</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" class="form-input" min="0">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Gambar Produk</label>
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" class="w-20 h-20 rounded-lg object-cover mb-2" alt="">
                    @endif
                    <input type="file" name="image" accept="image/*" class="form-input">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="form-input">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active)?'checked':'' }} class="w-4 h-4 rounded border-slate-300 text-indigo-500">
                        <span class="text-sm">Produk Aktif</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="track_stock" value="1" {{ old('track_stock', $product->track_stock)?'checked':'' }} class="w-4 h-4 rounded border-slate-300 text-indigo-500">
                        <span class="text-sm">Lacak Stok</span>
                    </label>
                </div>
            </div>
            <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-slate-700">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('owner.products.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
