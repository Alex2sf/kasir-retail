<x-app-layout title="Kategori">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>Kategori</x-slot:header>
    <x-slot:subtitle>Kelola kategori produk</x-slot:subtitle>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Category Form --}}
        <div class="glass-card p-6 h-fit" x-data="{ editing: null }">
            <h3 class="text-lg font-bold mb-4">➕ Tambah Kategori</h3>
            <form method="POST" action="{{ route('owner.categories.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-2">Nama Kategori</label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: Makanan" id="input-category-name">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Warna</label>
                    <input type="color" name="color" value="#6366f1" class="w-full h-10 rounded-lg border border-slate-200 dark:border-slate-700 cursor-pointer" id="input-category-color">
                </div>
                <button type="submit" class="btn-primary w-full">Simpan</button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="lg:col-span-2 glass-card overflow-hidden">
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <h3 class="text-lg font-bold">📂 Daftar Kategori</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($categories as $category)
                <div class="flex items-center justify-between p-4 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors" x-data="{ editMode: false }">
                    <div class="flex items-center gap-3" x-show="!editMode">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-sm" style="background: {{ $category->color ?? '#6366f1' }}">
                            {{ strtoupper(substr($category->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold">{{ $category->name }}</p>
                            <p class="text-xs text-slate-400">{{ $category->products_count }} produk</p>
                        </div>
                    </div>

                    {{-- Edit form inline --}}
                    <form x-show="editMode" method="POST" action="{{ route('owner.categories.update', $category) }}" class="flex items-center gap-2 flex-1">
                        @csrf @method('PUT')
                        <input type="text" name="name" value="{{ $category->name }}" class="form-input text-sm flex-1">
                        <input type="color" name="color" value="{{ $category->color ?? '#6366f1' }}" class="w-8 h-8 rounded cursor-pointer border-0">
                        <button type="submit" class="btn-primary text-xs px-3 py-1.5">Simpan</button>
                        <button type="button" @click="editMode = false" class="btn-secondary text-xs px-3 py-1.5">Batal</button>
                    </form>

                    <div class="flex gap-1" x-show="!editMode">
                        <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-danger' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        <button @click="editMode = true" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-600 transition-colors">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('owner.categories.destroy', $category) }}" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf @method('DELETE')
                            <button class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="empty-state py-12">
                    <svg class="w-16 h-16 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <p class="text-slate-400 font-medium">Belum ada kategori</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
