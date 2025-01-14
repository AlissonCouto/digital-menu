<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BorderOption;
use Illuminate\Support\Str;

class BorderOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'name' => 'Catupiry',
                'price' => 5.00
            ],
            [
                'name' => 'Cheddar',
                'price' => 6.00
            ],
            [
                'name' => 'Presunto',
                'price' => 3.00
            ],
            [
                'name' => 'Mussarela',
                'price' => 4.00
            ]
        ];

        foreach($entities as $row){
            $item = new BorderOption();
            $item->name = $row['name'];
            $item->slug = Str::slug($row['name']);
            $item->price = $row['price'];
            $item->company_id = 1;
            $item->save();
        }
    }
}
