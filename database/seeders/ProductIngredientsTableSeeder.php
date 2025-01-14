<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductIngredient;

class ProductIngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $entities = [
            [
                'product_id' => 1,
                'ingredient_id' => 1
            ],
            [
                'product_id' => 1,
                'ingredient_id' => 2
            ],
            [
                'product_id' => 1,
                'ingredient_id' => 3
            ],
            [
                'product_id' => 1,
                'ingredient_id' => 4
            ],
            [
                'product_id' => 1,
                'ingredient_id' => 5
            ],
            [
                'product_id' => 2,
                'ingredient_id' => 1
            ],
            [
                'product_id' => 2,
                'ingredient_id' => 3
            ],
            [
                'product_id' => 2,
                'ingredient_id' => 6
            ],
            [
                'product_id' => 2,
                'ingredient_id' => 7
            ],
        ];
        
        foreach($entities as $row){
            $item = new ProductIngredient();
            $item->product_id = $row['product_id'];
            $item->ingredient_id = $row['ingredient_id'];
            $item->save();
        }

    }
}

// INSERT INTO product_ingredients (product_id, ingredient_id) VALUES (3, 1), (3,2), (3,3), (4, 1), (4,2), (4,3), (4,4);