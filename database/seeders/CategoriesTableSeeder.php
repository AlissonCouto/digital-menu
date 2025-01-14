<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'name' => 'Pizzas',
                'icon' => 'pizza'
            ],
            [
                'name' => 'Lanches',
                'icon' => 'hamburguer'
            ],
            [
                'name' => 'Porções',
                'icon' => 'food'
            ],
            [
                'name' => 'Pastéis',
                'icon' => 'food-croissant'
            ],
            [
                'name' => 'Esfihas',
                'icon' => 'baguette'
            ],
            [
                'name' => 'Refrigerantes',
                'icon' => 'bottle-soda'
            ],
            [
                'name' => 'Espetos',
                'icon' => 'grill'
            ]
        ];

        foreach($entities as $row){
            $item = new Category();
            $item->name = $row['name'];
            $item->slug = Str::slug($row['name']);
            $item->company_id = 1;
            $item->icon = $row['icon'];

            $item->save();
        }
    }
}
