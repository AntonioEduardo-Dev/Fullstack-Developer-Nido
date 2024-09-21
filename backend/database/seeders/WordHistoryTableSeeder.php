<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WordHistory;

class WordHistoryTableSeeder extends Seeder
{
    public function run()
    {
        // Limpa os registros existentes na tabela antes de inserir os novos
        WordHistory::truncate();
        WordHistory::create([
            'user_id' => 1,
            'word_id' => 1
        ]);
    }
}