<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(["message" => "Fullstack Challenge 🏅 - Dictionary"], 200);
    }

    public function history()
    {
        try {
            return response()->json([
                'results' => [
                    [ 'word' => 'fire', 'added' => '2022-05-05T19:30:23.928Z' ],
                    [ 'word' => 'firefly', 'added' => '2022-05-05T19:30:23.928Z' ],
                    [ 'word' => 'fireplace', 'added' => '2022-05-05T19:30:23.928Z' ],
                    [ 'word' => 'fireman', 'added' => '2022-05-05T19:30:23.928Z' ]
                ],
                'totalDocs' => 20,
                'page' => 2,
                'totalPages' => 5,
                'hasNext' => true,
                'hasPrev' => true
            ], 200);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("UserController history server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in UserController history: {$e->getMessage()}", [
                'exception' => $e,
            ]);

            return response()->json([
                'status' => 'An unexpected error occurred.',
                'message' => 'Ocorreu um erro inesperado, tente novamente mais tarde.',
                'type' => 'error'
            ], 500);
        }
    }

    public function favorites()
    {
        try {
            return response()->json([
                'results' => [
                    [ 'word' => 'fire', 'added' => '2022-05-05T19:30:23.928Z' ],
                    [ 'word' => 'firefly', 'added' => '2022-05-05T19:30:23.928Z' ],
                    [ 'word' => 'fireplace', 'added' => '2022-05-05T19:30:23.928Z' ],
                    [ 'word' => 'fireman', 'added' => '2022-05-05T19:30:23.928Z' ]
                ],
                'totalDocs' => 20,
                'page' => 2,
                'totalPages' => 5,
                'hasNext' => true,
                'hasPrev' => true
            ], 200);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("UserController favorites server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in UserController favorites: {$e->getMessage()}", [
                'exception' => $e,
            ]);

            return response()->json([
                'status' => 'An unexpected error occurred.',
                'message' => 'Ocorreu um erro inesperado, tente novamente mais tarde.',
                'type' => 'error'
            ], 500);
        }
    }
}
