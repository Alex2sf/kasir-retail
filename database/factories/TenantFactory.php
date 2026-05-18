<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = fake('id_ID')->company();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'address' => fake('id_ID')->address(),
            'phone' => fake('id_ID')->phoneNumber(),
            'email' => fake()->companyEmail(),
            'description' => fake('id_ID')->sentence(10),
            'status' => 'active',
            'plan' => fake()->randomElement(['free', 'basic', 'premium']),
        ];
    }
}
