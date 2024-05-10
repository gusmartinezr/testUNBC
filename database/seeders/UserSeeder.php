<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario con datos predefinidos
        User::create([
            'name' => 'Usuario1',
            'last_name' => 'Perez',
            'email' => 'correo@example.com',
            'nro_phone' => '123456789',
            'password' => Hash::make('password'),
        ]);
    }
}
