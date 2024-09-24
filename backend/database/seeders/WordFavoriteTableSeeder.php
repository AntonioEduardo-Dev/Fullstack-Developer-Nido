<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WordFavorite;

class WordFavoriteTableSeeder extends Seeder
{
    public function run()
    {
        return true;
        
        // Limpa os registros existentes na tabela antes de inserir os novos
        WordFavorite::truncate();
        WordFavorite::create([
            'user_id' => 1,
            'word_id' => 1
        ]);
    }
}