<?php

namespace App\Console\Commands;

use App\Services\Api\WordService;
use Illuminate\Console\Command;

class ImportChunkWords extends Command
{
    protected $signature = 'importar:chunkpalavras {url}';
    protected $description = 'Baixa uma lista de palavras e importa para o banco de dados';

    public function __construct(private WordService $wordService)
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $url = $this->argument('url');
            $this->wordService->importChunkWords($url);
            $this->info('Palavras importadas com sucesso!');
        } catch (\Exception $e) {
            $this->error('Falha ao importar a lista de palavras.');
        }
    }
}
