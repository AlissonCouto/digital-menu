<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;
use Illuminate\Support\Str;

class IngredientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entities = ['Molho', 'Filé Mignon', 'Mussarela', 'Tomate Seco', 'Rúcula', 'Strogonnoff de carne', 'Batata Palha'];

        foreach($entities as $row){
            $item = new Ingredient();
            $item->name = $row;
            $item->slug = Str::slug($row);
            $item->company_id = 1;
            $item->save();
        }
    }
}
