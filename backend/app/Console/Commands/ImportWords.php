<?php

namespace App\Console\Commands;

use App\Services\Api\WordService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportWords extends Command
{
    protected $signature = 'importar:palavras {url}';
    protected $description = 'Baixa uma lista de palavras e importa para o banco de dados';

    public function __construct(private WordService $wordService)
    {
        parent::__construct();
    }

    public function handle()
    {
        $url = $this->argument('url');
        $response = Http::get($url);

        if ($response->successful()) {
            $listWords = explode("\n", $response->body());
            $this->info('Aguarde...');
            $this->wordService->importWords($listWords);
            $this->info('Palavras importadas com sucesso!');
        } else {
            $this->error('Falha ao baixar a lista de palavras.');
        }
    }
}
