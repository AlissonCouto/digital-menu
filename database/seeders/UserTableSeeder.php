<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User();
        $user->name = 'Alisson Couto';
        $user->type = 'admin';
        $user->email = 'alissonvieiracaires@gmail.com';
        $user->password = Hash::make('acaet@9pt');
        $user->save();
    }
}
