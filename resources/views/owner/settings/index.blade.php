<x-app-layout title="Pengaturan Toko" header="Pengaturan Toko" subtitle="Kelola informasi toko Anda">
    <x-slot name="sidebar">
        @include('owner.partials.sidebar')
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400 rounded-2xl border border-green-200 dark:border-green-800 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="p-6 md:p-8 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 dark:text-white">Profil Toko</h2>
                        <p class="text-slate-500 text-sm mt-1">Informasi ini akan ditampilkan pada struk dan portal pelanggan.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('owner.settings.update') }}" method="POST" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nama Toko <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
                               class="w-full px-4 py-3 rounded-xl border text-sm font-medium outline-none transition-all
                                      bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700
                                      text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Email Toko</label>
                        <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
                               class="w-full px-4 py-3 rounded-xl border text-sm font-medium outline-none transition-all
                                      bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700
                                      text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}"
                               class="w-full px-4 py-3 rounded-xl border text-sm font-medium outline-none transition-all
                                      bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700
                                      text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3"
                                  class="w-full px-4 py-3 rounded-xl border text-sm font-medium outline-none transition-all
                                         bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700
                                         text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">{{ old('address', $tenant->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Deskripsi / Slogan</label>
                        <input type="text" name="description" value="{{ old('description', $tenant->description) }}"
                               class="w-full px-4 py-3 rounded-xl border text-sm font-medium outline-none transition-all
                                      bg-slate-50 dark:bg-slate-800/50 border-slate-200 dark:border-slate-700
                                      text-slate-800 dark:text-slate-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10">
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-colors">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
