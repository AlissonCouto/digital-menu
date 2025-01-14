<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PastaOption;
use Illuminate\Support\Str;

class PastaOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = [
            [
                'name' => 'Tradicional',
            ],
            [
                'name' => 'Fina',
            ],
            [
                'name' => 'Grossa',
            ],
            [
                'name' => 'Integral',
                'price' => 5.00
            ]
        ];

        foreach($entities as $row){
            $item = new PastaOption();
            $item->name = $row['name'];
            $item->slug = Str::slug($row['name']);
            
            if(isset($row['price'])){
                $item->price = $row['price'];
            }

            $item->company_id = 1;

            $item->save();
        }
    }
}
