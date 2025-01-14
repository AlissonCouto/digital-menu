<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PizzaSize;
use Illuminate\Support\Str;

class PizzaSizesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = ['Grande', 'Média', 'Broto'];

        foreach($entities as $row){
            $item = new PizzaSize();
            $item->name = $row;
            $item->slug = Str::slug($row);
            $item->company_id = 1;
            $item->save();
        }
    }
}
