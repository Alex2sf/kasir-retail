<x-base-layout title="Daftar - Warunggalih">
    <div class="min-h-screen flex">

        {{-- LEFT PANEL: Branding --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-between p-16"
             style="background: linear-gradient(135deg, #3b82f6, #4f46e5, #7c3aed);">

            {{-- Animated blobs --}}
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-[-80px] right-[-80px] w-[400px] h-[400px] rounded-full opacity-20 blur-3xl"
                     style="background: radial-gradient(circle, #8b5cf6, #6366f1); animation: float 9s ease-in-out infinite;"></div>
                <div class="absolute bottom-[-100px] left-[-80px] w-[350px] h-[350px] rounded-full opacity-20 blur-3xl"
                     style="background: radial-gradient(circle, #06b6d4, #0891b2); animation: float 11s ease-in-out infinite reverse;"></div>
            </div>

            {{-- Top: Logo --}}
            <div class="flex items-center gap-3 relative z-10">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-lg overflow-hidden p-1.5">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <h2 class="text-xl font-bold text-white tracking-wide">
                    WarungGalih<span class="font-normal text-indigo-200">POS</span>
                </h2>
            </div>

            {{-- Center: Hero text & badges --}}
            <div class="relative z-10 my-auto py-12">
                <h1 class="text-5xl font-extrabold text-white leading-tight mb-6">
                    Kelola Toko Retail<br>Anda<br>dengan mudah.
                </h1>

                {{-- Badges --}}
                <div class="flex flex-wrap gap-3">
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 border border-white/10 backdrop-blur-md">
                        <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span class="text-white text-xs font-semibold">Super Cepat</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 border border-white/10 backdrop-blur-md">
                        <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="text-white text-xs font-semibold">Aman & Terenkripsi</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/10 border border-white/10 backdrop-blur-md">
                        <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <span class="text-white text-xs font-semibold">Laporan Real-time</span>
                    </div>
                </div>
            </div>

            {{-- Bottom: Footer --}}
            <div class="relative z-10 text-indigo-200/60 text-xs">
                &copy; 2026 WarungGalih POS. All rights reserved.
            </div>
        </div>

        {{-- RIGHT PANEL: Register Form --}}
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-6 sm:p-10 bg-white dark:bg-slate-900 relative overflow-y-auto"
             x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" :class="{ 'dark': darkMode }">

            {{-- Dark mode toggle --}}
            <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode)"
                    class="absolute top-6 right-6 p-2.5 rounded-xl text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>

            <div class="w-full max-w-md" style="animation: slideInUp 0.5s ease;">

                {{-- Mobile logo --}}
                <div class="lg:hidden text-center mb-8 flex items-center justify-center gap-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-8 h-8 object-contain">
                    <h1 class="text-3xl font-extrabold text-slate-800 dark:text-white">Warunggalih</h1>
                </div>

                <div class="mb-7">
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white mb-2">Buat Akun Toko</h2>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Store name --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Toko / UMKM <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                            </div>
                            <input type="text" name="store_name" value="{{ old('store_name') }}" required id="register-store"
                                   class="w-full pl-12 pr-4 py-3.5 rounded-xl border text-sm font-medium outline-none transition-all
                                          bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700
                                          text-slate-800 dark:text-slate-200 placeholder-slate-400
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
                                          @error('store_name') border-red-400 @enderror"
                                   placeholder="Contoh: Toko Maju Jaya">
                        </div>
                        @error('store_name')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    {{-- Name + Phone 2 cols --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required id="register-name"
                                   class="w-full px-4 py-3.5 rounded-xl border text-sm font-medium outline-none transition-all
                                          bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700
                                          text-slate-800 dark:text-slate-200 placeholder-slate-400
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
                                          @error('name') border-red-400 @enderror"
                                   placeholder="Nama Anda">
                            @error('name')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" id="register-phone"
                                   class="w-full px-4 py-3.5 rounded-xl border text-sm font-medium outline-none transition-all
                                          bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700
                                          text-slate-800 dark:text-slate-200 placeholder-slate-400
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                                   placeholder="08xxxxxxxxx">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Alamat Email <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required id="register-email"
                                   class="w-full pl-12 pr-4 py-3.5 rounded-xl border text-sm font-medium outline-none transition-all
                                          bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700
                                          text-slate-800 dark:text-slate-200 placeholder-slate-400
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
                                          @error('email') border-red-400 @enderror"
                                   placeholder="email@toko.com">
                        </div>
                        @error('email')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password 2 cols --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Password <span class="text-red-400">*</span></label>
                            <input type="password" name="password" required id="register-password"
                                   class="w-full px-4 py-3.5 rounded-xl border text-sm font-medium outline-none transition-all
                                          bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700
                                          text-slate-800 dark:text-slate-200 placeholder-slate-400
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10
                                          @error('password') border-red-400 @enderror"
                                   placeholder="Min. 8 karakter">
                            @error('password')<p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Konfirmasi <span class="text-red-400">*</span></label>
                            <input type="password" name="password_confirmation" required id="register-password-confirm"
                                   class="w-full px-4 py-3.5 rounded-xl border text-sm font-medium outline-none transition-all
                                          bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700
                                          text-slate-800 dark:text-slate-200 placeholder-slate-400
                                          focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10"
                                   placeholder="Ulangi password">
                        </div>
                    </div>

                    {{-- Terms --}}
                    <label class="flex items-start gap-2.5 cursor-pointer">
                        <input type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-slate-300 dark:border-slate-600 text-indigo-500 focus:ring-indigo-500/20 flex-shrink-0">
                        <span class="text-xs text-slate-500 dark:text-slate-400">
                            Saya menyetujui <span class="text-indigo-500 font-semibold">Syarat & Ketentuan</span> penggunaan Warunggalih
                        </span>
                    </label>

                    <button type="submit" id="register-submit"
                            class="w-full py-3.5 rounded-xl text-white text-sm font-bold tracking-wide transition-all duration-300"
                            style="background: linear-gradient(135deg, #06b6d4, #6366f1); box-shadow: 0 4px 20px rgba(6,182,212,0.4);"
                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 30px rgba(6,182,212,0.5)'"
                            onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 20px rgba(6,182,212,0.4)'">
                        <span class="flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                            Daftar Sekarang — Gratis!
                        </span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="my-6 flex items-center gap-4">
                    <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                    <span class="text-xs text-slate-400 font-medium">SUDAH PUNYA AKUN?</span>
                    <div class="flex-1 h-px bg-slate-200 dark:bg-slate-700"></div>
                </div>

                <a href="{{ route('login') }}"
                   class="flex items-center justify-center gap-2 w-full py-3.5 rounded-xl text-sm font-bold transition-all duration-300
                          border-2 border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300
                          hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    Masuk ke Dashboard
                </a>

                <p class="text-center text-slate-400 text-xs mt-6">
                    &copy; {{ date('Y') }} Warunggalih
                </p>
            </div>
        </div>
    </div>

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
        }
    </style>
</x-base-layout>
