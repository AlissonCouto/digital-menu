<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        /*
            CREATE DATABASE lanxi
                DEFAULT CHARACTER SET utf8mb4
                DEFAULT COLLATE utf8mb4_general_ci;

                USE lanxi;
        */

        $this->call([
            UserTableSeeder::class,
            CompanyTableSeeder::class,
            PastaOptionsTableSeeder::class,
            BorderOptionsTableSeeder::class,
            IngredientsTableSeeder::class,
            PizzaSizesTableSeeder::class,
            CategoriesTableSeeder::class,
            ProductsTableSeeder::class,
            ProductIngredientsTableSeeder::class,
            ProductAdditionalsTableSeeder::class,
        ]);

        DB::insert('INSERT INTO price_pizza_sizes (price, product_id, pizza_size_id) VALUES (71.5, 1, 1), (78.9, 2, 1)');
        DB::insert("INSERT INTO coupons (name, slug, validity_type, discount_type, value, usage_limit, company_id) VALUES ('PERCENTUAL', 'percentual', 'usage_limit', 'percent', 10, 5, 1)");
        DB::insert("INSERT INTO delivery_charges (name, slug, value, company_id) VALUES ('GERAL', 'geral', 7.00, 1)");
        DB::insert("INSERT INTO addresses (description, street, number, neighborhood, reference, main, company_id, city_id) VALUES ('Endereço da empresa', 'AV. Dourados', 1000, 'Centro', 'Prédio Espelhado', 1, 1, 1)");

    }
}
