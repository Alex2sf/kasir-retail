<x-base-layout title="Kasir POS">
<div x-data="posApp()" x-init="init()" class="flex h-screen bg-slate-100 dark:bg-slate-900 overflow-hidden">
    {{-- Left Panel: Product Grid --}}
    <div class="flex-1 flex flex-col min-w-0">
        {{-- Top Bar --}}
        <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-4 py-3 flex items-center gap-3">
            <a href="{{ route('owner.dashboard') }}" class="p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors" title="Kembali">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="flex-1 relative">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="searchQuery" @input.debounce.200ms="filterProducts()" id="pos-search"
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all"
                       placeholder="Cari produk atau scan barcode... (F2)">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-400 hidden sm:block">F2: Cari · F9: Bayar</span>
            </div>
        </div>

        {{-- Category Tabs --}}
        <div class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-4 py-2 flex gap-2 overflow-x-auto">
            <button @click="selectedCategory = null; filterProducts()"
                    :class="selectedCategory === null ? 'gradient-primary text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all">
                Semua
            </button>
            @foreach($categories as $cat)
            <button @click="selectedCategory = {{ $cat->id }}; filterProducts()"
                    :class="selectedCategory === {{ $cat->id }} ? 'gradient-primary text-white shadow-lg shadow-indigo-500/30' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap transition-all">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        {{-- Product Grid --}}
        <div class="flex-1 overflow-y-auto p-4">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3">
                <template x-for="product in filteredProducts" :key="product.id">
                    <button @click="addToCart(product)"
                            class="pos-product-card flex flex-col items-center bg-white dark:bg-slate-800 relative">
                        <div class="w-full aspect-square rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-2 overflow-hidden">
                            <template x-if="product.image">
                                <img :src="'/storage/' + product.image" class="w-full h-full object-cover" alt="">
                            </template>
                            <template x-if="!product.image">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4"/></svg>
                            </template>
                        </div>
                        <p class="text-xs font-semibold text-center leading-tight line-clamp-2 mb-1" x-text="product.name"></p>
                        <p class="text-xs font-bold text-indigo-500" x-text="formatRupiah(product.selling_price)"></p>
                        <template x-if="product.track_stock">
                            <span class="absolute top-1 right-1 text-[10px] font-semibold px-1.5 py-0.5 rounded-md"
                                  :class="product.stock <= product.min_stock ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600'"
                                  x-text="product.stock + ' ' + product.unit"></span>
                        </template>
                    </button>
                </template>
            </div>
            <div x-show="filteredProducts.length === 0" class="empty-state py-16">
                <svg class="w-16 h-16 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <p class="text-slate-400 font-medium">Produk tidak ditemukan</p>
            </div>
        </div>
    </div>

    {{-- Right Panel: Cart --}}
    <div class="w-full max-w-md bg-white dark:bg-slate-800 border-l border-slate-200 dark:border-slate-700 flex flex-col">
        {{-- Cart Header --}}
        <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">🛒 Keranjang</h2>
                <p class="text-xs text-slate-400" x-text="cart.length + ' item'"></p>
            </div>
            <button @click="clearCart()" x-show="cart.length > 0" class="text-xs text-red-400 hover:text-red-500 font-semibold transition-colors">Kosongkan</button>
        </div>

        {{-- Cart Items --}}
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            <template x-for="(item, index) in cart" :key="item.product_id">
                <div class="flex gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-700/50 transition-all" style="animation: slideInUp 0.2s ease">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold truncate" x-text="item.name"></p>
                        <p class="text-xs text-indigo-500 font-bold" x-text="formatRupiah(item.price)"></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button @click="decrementItem(index)" class="w-7 h-7 rounded-lg bg-slate-200 dark:bg-slate-600 flex items-center justify-center hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors text-sm font-bold">−</button>
                        <span class="w-8 text-center text-sm font-bold" x-text="item.quantity"></span>
                        <button @click="incrementItem(index)" class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center hover:bg-indigo-200 transition-colors text-sm font-bold text-indigo-600">+</button>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold" x-text="formatRupiah(item.price * item.quantity)"></p>
                        <button @click="removeItem(index)" class="text-xs text-red-400 hover:text-red-500 transition-colors">Hapus</button>
                    </div>
                </div>
            </template>
            <div x-show="cart.length === 0" class="empty-state py-12">
                <svg class="w-16 h-16 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                <p class="text-slate-400 text-sm">Keranjang masih kosong</p>
                <p class="text-slate-300 text-xs">Klik produk untuk menambahkan</p>
            </div>
        </div>

        {{-- Cart Summary --}}
        <div class="border-t border-slate-200 dark:border-slate-700 p-4 space-y-3">
            {{-- Customer Select --}}
            <select x-model="customerId" class="form-input text-sm">
                <option value="">Pelanggan Umum</option>
                @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->name }}</option>
                @endforeach
            </select>

            {{-- Payment Method --}}
            <div class="flex gap-2">
                <template x-for="m in ['cash','qris','transfer','debit']" :key="m">
                    <button @click="paymentMethod = m"
                            :class="paymentMethod === m ? 'gradient-primary text-white shadow-md' : 'bg-slate-100 dark:bg-slate-700 text-slate-500'"
                            class="flex-1 py-2 rounded-lg text-xs font-semibold capitalize transition-all" x-text="m.toUpperCase()">
                    </button>
                </template>
            </div>

            {{-- Totals --}}
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-400">Subtotal</span><span class="font-semibold" x-text="formatRupiah(subtotal)"></span></div>
                <div class="flex justify-between text-lg font-extrabold border-t border-slate-200 dark:border-slate-700 pt-2">
                    <span>Total</span><span class="text-indigo-500" x-text="formatRupiah(subtotal)"></span>
                </div>
            </div>

            {{-- Pay Button --}}
            <button @click="openPaymentModal()" :disabled="cart.length === 0" id="btn-pay"
                    class="w-full py-4 btn-primary text-lg font-extrabold rounded-xl disabled:opacity-40 disabled:cursor-not-allowed disabled:transform-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                BAYAR (F9)
            </button>
        </div>
    </div>

    {{-- Payment Modal --}}
    <template x-if="showPaymentModal">
        <div class="modal-overlay" @click.self="showPaymentModal = false" @keydown.escape.window="showPaymentModal = false">
            <div class="modal-content p-6 max-w-lg">
                <h3 class="text-xl font-bold mb-6 text-center">💰 Pembayaran</h3>
                <div class="space-y-4">
                    <div class="text-center p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20">
                        <p class="text-sm text-slate-400">Total Bayar</p>
                        <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400" x-text="formatRupiah(subtotal)"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Jumlah Bayar</label>
                        <input type="number" x-model.number="paidAmount" @input="calculateChange()"
                               class="form-input text-center text-xl font-bold" autofocus id="input-paid-amount">
                    </div>
                    <div class="flex gap-2">
                        <button @click="paidAmount = subtotal; calculateChange()" class="flex-1 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors">Uang Pas</button>
                        <template x-for="nom in quickAmounts" :key="nom">
                            <button @click="paidAmount = nom; calculateChange()" class="flex-1 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-sm font-semibold hover:bg-slate-200 transition-colors" x-text="formatRupiah(nom)"></button>
                        </template>
                    </div>
                    <div x-show="changeAmount >= 0" class="text-center p-4 rounded-xl bg-green-50 dark:bg-green-900/20">
                        <p class="text-sm text-slate-400">Kembalian</p>
                        <p class="text-2xl font-extrabold text-green-600 dark:text-green-400" x-text="formatRupiah(changeAmount)"></p>
                    </div>
                    <div x-show="changeAmount < 0" class="text-center p-3 rounded-xl bg-red-50 dark:bg-red-900/20">
                        <p class="text-sm text-red-500 font-semibold">Uang kurang!</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button @click="submitTransaction()" :disabled="processing || changeAmount < 0"
                                class="flex-1 btn-success py-3 text-base font-bold disabled:opacity-40">
                            <span x-show="!processing">✅ Proses Pembayaran</span>
                            <span x-show="processing" class="flex items-center justify-center gap-2"><div class="spinner w-5 h-5"></div> Memproses...</span>
                        </button>
                        <button @click="showPaymentModal = false" class="btn-secondary py-3">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- Success Modal --}}
    <template x-if="showSuccessModal">
        <div class="modal-overlay">
            <div class="modal-content p-8 text-center max-w-md">
                <div class="w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-2xl font-extrabold mb-2">Transaksi Berhasil!</h3>
                <p class="text-slate-400 mb-1" x-text="lastTransaction?.invoice_number"></p>
                <p class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400 mb-2" x-text="formatRupiah(lastTransaction?.total)"></p>
                <p class="text-slate-400 mb-6">Kembalian: <span class="font-bold text-green-500" x-text="formatRupiah(lastTransaction?.change_amount)"></span></p>
                <div class="flex gap-3">
                    <a :href="'/owner/pos/receipt/' + lastTransaction?.id" target="_blank" class="flex-1 btn-primary py-3">🖨️ Cetak Struk</a>
                    <button @click="resetAfterSuccess()" class="flex-1 btn-secondary py-3">Transaksi Baru</button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function posApp() {
    return {
        allProducts: @json($products),
        filteredProducts: [],
        searchQuery: '',
        selectedCategory: null,
        cart: [],
        customerId: '',
        paymentMethod: 'cash',
        showPaymentModal: false,
        showSuccessModal: false,
        paidAmount: 0,
        changeAmount: 0,
        processing: false,
        lastTransaction: null,

        get subtotal() {
            return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        },

        get quickAmounts() {
            const total = this.subtotal;
            const amounts = [];
            const roundups = [1000, 5000, 10000, 20000, 50000, 100000];
            for (const r of roundups) {
                const rounded = Math.ceil(total / r) * r;
                if (rounded >= total && !amounts.includes(rounded) && rounded !== total) {
                    amounts.push(rounded);
                }
                if (amounts.length >= 3) break;
            }
            return amounts;
        },

        init() {
            this.filteredProducts = [...this.allProducts];
        },

        filterProducts() {
            let products = [...this.allProducts];
            if (this.selectedCategory) {
                products = products.filter(p => p.category_id === this.selectedCategory);
            }
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                products = products.filter(p =>
                    p.name.toLowerCase().includes(q) ||
                    (p.barcode && p.barcode.toLowerCase().includes(q)) ||
                    (p.sku && p.sku.toLowerCase().includes(q))
                );
                // Auto-add if barcode exact match
                if (products.length === 1 && products[0].barcode &&
                    products[0].barcode.toLowerCase() === q) {
                    this.addToCart(products[0]);
                    this.searchQuery = '';
                    this.filterProducts();
                    return;
                }
            }
            this.filteredProducts = products;
        },

        addToCart(product) {
            const existing = this.cart.find(i => i.product_id === product.id);
            if (existing) {
                if (product.track_stock && existing.quantity >= product.stock) {
                    showToast('Stok tidak mencukupi!', 'error');
                    return;
                }
                existing.quantity++;
            } else {
                if (product.track_stock && product.stock <= 0) {
                    showToast('Stok habis!', 'error');
                    return;
                }
                this.cart.push({
                    product_id: product.id,
                    name: product.name,
                    price: parseFloat(product.selling_price),
                    quantity: 1,
                    discount: 0,
                });
            }
        },

        incrementItem(index) { this.cart[index].quantity++; },
        decrementItem(index) {
            if (this.cart[index].quantity > 1) this.cart[index].quantity--;
            else this.removeItem(index);
        },
        removeItem(index) { this.cart.splice(index, 1); },
        clearCart() { if (confirm('Kosongkan keranjang?')) this.cart = []; },

        openPaymentModal() {
            if (this.cart.length === 0) return;
            this.paidAmount = this.subtotal;
            this.changeAmount = 0;
            this.showPaymentModal = true;
        },

        calculateChange() {
            this.changeAmount = this.paidAmount - this.subtotal;
        },

        async submitTransaction() {
            if (this.processing || this.changeAmount < 0) return;
            this.processing = true;
            try {
                const res = await fetch('{{ route("owner.pos.transaction") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        items: this.cart.map(i => ({
                            product_id: i.product_id,
                            price: i.price,
                            quantity: i.quantity,
                            discount: i.discount,
                        })),
                        paid_amount: this.paidAmount,
                        payment_method: this.paymentMethod,
                        customer_id: this.customerId || null,
                        discount_amount: 0,
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    this.lastTransaction = data.transaction;
                    this.showPaymentModal = false;
                    this.showSuccessModal = true;
                } else {
                    showToast(data.error || 'Terjadi kesalahan', 'error');
                }
            } catch (err) {
                showToast('Gagal memproses transaksi', 'error');
            } finally {
                this.processing = false;
            }
        },

        resetAfterSuccess() {
            this.cart = [];
            this.customerId = '';
            this.paymentMethod = 'cash';
            this.showSuccessModal = false;
            this.lastTransaction = null;
            // Reload product stock
            location.reload();
        },

        formatRupiah(n) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n || 0);
        },
    };
}
</script>
</x-base-layout>
