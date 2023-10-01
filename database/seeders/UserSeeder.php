<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'name' => 'Nombre Apellido',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);
    }
}
