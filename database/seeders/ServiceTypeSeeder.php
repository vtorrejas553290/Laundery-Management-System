<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    public function run()
    {
        $services = [
            ['id' => 'ST1', 'name' => 'Regular Wash & Dry', 'category_id' => 'SC1', 'price_per_load' => 120],
            ['id' => 'ST2', 'name' => 'Premium Wash & Dry', 'category_id' => 'SC1', 'price_per_load' => 180],
            ['id' => 'ST3', 'name' => 'Express Dry Clean', 'category_id' => 'SC4', 'price_per_load' => 250],
            ['id' => 'ST4', 'name' => 'Standard Iron', 'category_id' => 'SC5', 'price_per_load' => 80],
            ['id' => 'ST5', 'name' => 'Delicate Wash', 'category_id' => 'SC2', 'price_per_load' => 150],
            ['id' => 'ST6', 'name' => 'Quick Dry', 'category_id' => 'SC3', 'price_per_load' => 100],
        ];

        foreach ($services as $service) {
            ServiceType::create($service);
        }
    }
}