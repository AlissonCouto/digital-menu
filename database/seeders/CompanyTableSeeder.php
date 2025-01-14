<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
            $company = new Company();
            $company->name = 'Alisson Couto';
            $company->slug = 'alisson-couto';
            $company->user_id = 1;
            $company->save();
    }
}
