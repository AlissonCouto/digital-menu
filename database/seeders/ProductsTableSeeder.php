<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'name' => 'Filé Mignon',
                'img' => 'file-mignon.webp',
                'price' => 71.50,
                'category_id' => 1
            ],
            [
                'name' => 'Strogonoff de Carne',
                'img' => 'strogonoff-carne.webp',
                'price' => 55.00,
                'category_id' => 1,
            ]
        ];

        foreach ($entities as $row) {
            $item = new Product();
            $item->name = $row['name'];
            $item->slug = Str::slug($row['name']);
            $item->img = $row['img'];
            $item->price = $row['price'];
            $item->category_id = $row['category_id'];
            $item->company_id = 1;
            $item->save();
        }
    }
}

/*
    INSERT INTO products (name, slug, img, price, category_id, company_id) VALUES ('X-Salada', 'x-salada', 'file-mignon.webp', 22.90, 2, 1);
    INSERT INTO products (name, slug, img, price, category_id, company_id) VALUES ('X-Salada 2', 'x-salada-2', 'file-mignon.webp', 32.90, 2, 1);
*/