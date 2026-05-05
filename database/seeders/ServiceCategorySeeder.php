<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 'SC1', 'category_name' => 'Wash & Dry', 'description' => 'Combined washing and drying service for your convenience'],
            ['id' => 'SC2', 'category_name' => 'Wash Only', 'description' => 'Professional washing service without drying'],
            ['id' => 'SC3', 'category_name' => 'Dry Only', 'description' => 'Quick and efficient drying service'],
            ['id' => 'SC4', 'category_name' => 'Dry Clean', 'description' => 'Professional dry cleaning for delicate fabrics'],
            ['id' => 'SC5', 'category_name' => 'Iron Only', 'description' => 'Expert ironing service for wrinkle-free clothes'],
        ];

        foreach ($categories as $category) {
            ServiceCategory::create($category);
        }
    }
}