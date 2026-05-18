<x-app-layout title="Pelanggan">
    <x-slot:sidebar>@include('owner.partials.sidebar')</x-slot:sidebar>
    <x-slot:header>Pelanggan</x-slot:header>
    <x-slot:subtitle>Kelola data pelanggan</x-slot:subtitle>

    <x-slot:headerActions>
        <button @click="$dispatch('open-add-customer')" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Pelanggan
        </button>
    </x-slot:headerActions>

    <div x-data="{ showAddModal: false }" @open-add-customer.window="showAddModal = true">
        {{-- Search --}}
        <div class="glass-card p-4 mb-6">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, telepon, email..." class="form-input flex-1">
                <button type="submit" class="btn-primary">Cari</button>
            </form>
        </div>

        {{-- Table --}}
        <div class="glass-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="modern-table">
                    <thead><tr><th>Pelanggan</th><th>Telepon</th><th>Total Belanja</th><th>Transaksi</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($customers as $customer)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full gradient-secondary flex items-center justify-center text-white font-bold text-sm">{{ strtoupper(substr($customer->name,0,2)) }}</div>
                                    <div>
                                        <p class="font-semibold">{{ $customer->name }}</p>
                                        <p class="text-xs text-slate-400">{{ $customer->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $customer->phone ?? '-' }}</td>
                            <td class="font-semibold">Rp {{ number_format($customer->total_spent,0,',','.') }}</td>
                            <td>{{ $customer->total_transactions }}x</td>
                            <td>
                                <form method="POST" action="{{ route('owner.customers.destroy', $customer) }}" onsubmit="return confirm('Hapus pelanggan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5"><div class="empty-state py-8"><p class="text-slate-400">Belum ada pelanggan</p></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($customers->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">{{ $customers->links() }}</div>
            @endif
        </div>

        {{-- Add Customer Modal --}}
        <template x-if="showAddModal">
            <div class="modal-overlay" @click.self="showAddModal = false">
                <div class="modal-content p-6">
                    <h3 class="text-lg font-bold mb-4">Tambah Pelanggan</h3>
                    <form method="POST" action="{{ route('owner.customers.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama</label>
                            <input type="text" name="name" class="form-input" required placeholder="Nama pelanggan">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Telepon</label>
                            <input type="text" name="phone" class="form-input" placeholder="08xxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" name="email" class="form-input" placeholder="email@contoh.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Alamat</label>
                            <textarea name="address" class="form-input" rows="2"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="btn-primary flex-1">Simpan</button>
                            <button type="button" @click="showAddModal = false" class="btn-secondary flex-1">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>
