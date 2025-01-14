<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductAdditional;

class ProductAdditionalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Loop dos produtos
        for($p = 1; $p <= 2; $p++){

            // Loop dos ingredientes
            for($i = 1; $i <= 7; $i++){
                $item = new ProductAdditional();
                $item->product_id = $p;
                $item->ingredient_id = $i;
                $item->save();
            }

        }
    }
}
