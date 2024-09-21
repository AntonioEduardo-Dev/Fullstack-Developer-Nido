<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Limpa os registros existentes na tabela antes de inserir os novos
        User::truncate();
        // Cria um usuário com senha 'qwe123'
        User::create([
            'name' => 'Antonio Eduardo',
            'email' => 'antonio.eduardo.dev@gmail.com',
            'password' => Hash::make('qwe123'),
        ]);
    }
}