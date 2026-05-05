<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ServiceCategorySeeder::class,
            ServiceTypeSeeder::class,
            StatusSeeder::class,
            ExtraItemSeeder::class,
        ]);
    }
}