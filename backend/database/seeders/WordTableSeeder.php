<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Seeder;

class WordTableSeeder extends Seeder
{
    public function run()
    {
        // Limpa os registros existentes na tabela antes de inserir os novos
        Word::truncate();
    }
}