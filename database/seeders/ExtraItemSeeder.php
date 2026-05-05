<?php

namespace Database\Seeders;

use App\Models\ExtraItem;
use Illuminate\Database\Seeder;

class ExtraItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['id' => 'EI1', 'item_name' => 'Fabric Softener', 'price' => 25],
            ['id' => 'EI2', 'item_name' => 'Detergent (Premium)', 'price' => 35],
            ['id' => 'EI3', 'item_name' => 'Stain Remover', 'price' => 50],
            ['id' => 'EI4', 'item_name' => 'Clothes Hanger', 'price' => 15],
        ];

        foreach ($items as $item) {
            ExtraItem::create($item);
        }
    }
}