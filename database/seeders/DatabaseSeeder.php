<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== SUPER ADMIN ==========
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@kasirretail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'tenant_id' => null,
            'is_active' => true,
        ]);

        // ========== DEMO TENANT 1: Toko Maju Jaya ==========
        $tenant1 = Tenant::create([
            'name' => 'Toko Maju Jaya',
            'slug' => 'toko-maju-jaya',
            'address' => 'Jl. Raya Utama No. 123, Jakarta Selatan',
            'phone' => '081234567890',
            'email' => 'info@tokomajujaya.com',
            'status' => 'active',
            'plan' => 'premium',
        ]);

        $owner1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@kasirretail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'tenant_id' => $tenant1->id,
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        $this->seedTenantData($tenant1, $owner1);

        // ========== DEMO TENANT 2: Sumber Rezeki ==========
        $tenant2 = Tenant::create([
            'name' => 'Sumber Rezeki',
            'slug' => 'sumber-rezeki',
            'address' => 'Jl. Merdeka No. 45, Bandung',
            'phone' => '082345678901',
            'email' => 'info@sumberrezeki.com',
            'status' => 'active',
            'plan' => 'basic',
        ]);

        $owner2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@kasirretail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'tenant_id' => $tenant2->id,
            'phone' => '082345678901',
            'is_active' => true,
        ]);

        $this->seedTenantData($tenant2, $owner2);

        // ========== DEMO TENANT 3: Warung Berkah ==========
        $tenant3 = Tenant::create([
            'name' => 'Warung Berkah',
            'slug' => 'warung-berkah',
            'address' => 'Jl. Pahlawan No. 78, Surabaya',
            'phone' => '083456789012',
            'status' => 'active',
            'plan' => 'free',
        ]);

        User::create([
            'name' => 'Ahmad Wijaya',
            'email' => 'ahmad@kasirretail.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'tenant_id' => $tenant3->id,
            'phone' => '083456789012',
            'is_active' => true,
        ]);
    }

    private function seedTenantData(Tenant $tenant, User $owner): void
    {
        // Categories
        $categories = [
            ['name' => 'Makanan', 'color' => '#ef4444'],
            ['name' => 'Minuman', 'color' => '#3b82f6'],
            ['name' => 'Snack', 'color' => '#f59e0b'],
            ['name' => 'Rokok', 'color' => '#6b7280'],
            ['name' => 'Kebutuhan Rumah', 'color' => '#10b981'],
            ['name' => 'ATK', 'color' => '#8b5cf6'],
        ];

        $catModels = [];
        foreach ($categories as $cat) {
            $catModels[] = Category::create([
                'tenant_id' => $tenant->id,
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']) . '-' . Str::random(3),
                'color' => $cat['color'],
                'is_active' => true,
            ]);
        }

        // Products
        $products = [
            ['name' => 'Indomie Goreng', 'cost_price' => 2500, 'selling_price' => 3500, 'stock' => 100, 'barcode' => '089686010947', 'cat' => 0],
            ['name' => 'Indomie Kuah Soto', 'cost_price' => 2500, 'selling_price' => 3500, 'stock' => 80, 'barcode' => '089686010107', 'cat' => 0],
            ['name' => 'Nasi Bungkus', 'cost_price' => 5000, 'selling_price' => 8000, 'stock' => 30, 'cat' => 0],
            ['name' => 'Telor Ayam (1 butir)', 'cost_price' => 2000, 'selling_price' => 2800, 'stock' => 200, 'cat' => 0],
            ['name' => 'Aqua 600ml', 'cost_price' => 2000, 'selling_price' => 3000, 'stock' => 150, 'barcode' => '8886008101053', 'cat' => 1],
            ['name' => 'Teh Botol Sosro 350ml', 'cost_price' => 3000, 'selling_price' => 4500, 'stock' => 80, 'cat' => 1],
            ['name' => 'Es Teh Manis', 'cost_price' => 1000, 'selling_price' => 3000, 'stock' => 50, 'cat' => 1],
            ['name' => 'Kopi Sachet Kapal Api', 'cost_price' => 1500, 'selling_price' => 2500, 'stock' => 120, 'cat' => 1],
            ['name' => 'Chitato 68g', 'cost_price' => 7000, 'selling_price' => 10000, 'stock' => 40, 'barcode' => '089686600100', 'cat' => 2],
            ['name' => 'Oreo 133g', 'cost_price' => 8000, 'selling_price' => 11000, 'stock' => 35, 'cat' => 2],
            ['name' => 'Roti Tawar Sari Roti', 'cost_price' => 13000, 'selling_price' => 16000, 'stock' => 20, 'cat' => 0],
            ['name' => 'Gudang Garam Filter 12', 'cost_price' => 22000, 'selling_price' => 26000, 'stock' => 50, 'barcode' => '8998989111126', 'cat' => 3],
            ['name' => 'Sampoerna Mild 16', 'cost_price' => 28000, 'selling_price' => 32000, 'stock' => 40, 'cat' => 3],
            ['name' => 'Sabun Lifebuoy 100g', 'cost_price' => 4000, 'selling_price' => 5500, 'stock' => 60, 'cat' => 4],
            ['name' => 'Detergen Rinso 800g', 'cost_price' => 15000, 'selling_price' => 19000, 'stock' => 25, 'cat' => 4],
            ['name' => 'Pulpen Pilot', 'cost_price' => 3000, 'selling_price' => 5000, 'stock' => 70, 'cat' => 5],
            ['name' => 'Buku Tulis 58 Lembar', 'cost_price' => 3500, 'selling_price' => 5000, 'stock' => 60, 'cat' => 5],
            ['name' => 'Minyak Goreng Bimoli 1L', 'cost_price' => 17000, 'selling_price' => 20000, 'stock' => 30, 'cat' => 4],
            ['name' => 'Gula Pasir 1kg', 'cost_price' => 14000, 'selling_price' => 16500, 'stock' => 3, 'min_stock' => 10, 'cat' => 4],
            ['name' => 'Beras 5kg', 'cost_price' => 60000, 'selling_price' => 70000, 'stock' => 15, 'cat' => 4],
        ];

        $productModels = [];
        foreach ($products as $p) {
            $productModels[] = Product::create([
                'tenant_id' => $tenant->id,
                'category_id' => $catModels[$p['cat']]->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']) . '-' . Str::random(3),
                'barcode' => $p['barcode'] ?? null,
                'cost_price' => $p['cost_price'],
                'selling_price' => $p['selling_price'],
                'stock' => $p['stock'],
                'min_stock' => $p['min_stock'] ?? 5,
                'unit' => 'pcs',
                'is_active' => true,
                'track_stock' => true,
            ]);
        }

        // Customers
        $customers = [];
        for ($i = 0; $i < 5; $i++) {
            $customers[] = Customer::create([
                'tenant_id' => $tenant->id,
                'name' => fake('id_ID')->name(),
                'phone' => fake('id_ID')->phoneNumber(),
            ]);
        }

        // Transactions (create realistic history)
        for ($day = 14; $day >= 0; $day--) {
            $numTrx = rand(2, 8);
            for ($t = 0; $t < $numTrx; $t++) {
                $items = collect($productModels)->random(rand(1, 5));
                $subtotal = 0;
                $trxItems = [];

                foreach ($items as $product) {
                    $qty = rand(1, 3);
                    $itemSub = $product->selling_price * $qty;
                    $subtotal += $itemSub;
                    $trxItems[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->selling_price,
                        'quantity' => $qty,
                        'discount' => 0,
                        'subtotal' => $itemSub,
                    ];
                }

                $trx = Transaction::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $owner->id,
                    'customer_id' => rand(0, 1) ? $customers[array_rand($customers)]->id : null,
                    'invoice_number' => 'INV-T' . $tenant->id . '-' . now()->subDays($day)->format('Ymd') . '-' . str_pad($t + 1, 4, '0', STR_PAD_LEFT),
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'tax_amount' => 0,
                    'total' => $subtotal,
                    'paid_amount' => $subtotal,
                    'change_amount' => 0,
                    'payment_method' => ['cash', 'qris', 'transfer', 'debit'][array_rand(['cash', 'qris', 'transfer', 'debit'])],
                    'status' => 'completed',
                    'created_at' => now()->subDays($day)->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);

                foreach ($trxItems as $item) {
                    TransactionItem::create(array_merge($item, ['transaction_id' => $trx->id]));
                }
            }
        }
    }
}
