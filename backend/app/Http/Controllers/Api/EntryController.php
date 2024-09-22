<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\Api\{
    DicionaryApiService,
    UserService,
    WordFavoriteService,
    WordHistoryService,
    WordService
};
use Illuminate\Support\Facades\Log;

class EntryController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected WordService $wordService,
        protected WordFavoriteService $wordFavoriteService,
        protected WordHistoryService $wordHistoryService,
        protected DicionaryApiService $dicionaryApiService
    )
    {}

    public function index(SearchRequest $request)
    {
        try {
            // Define o número de itens por página
            $perPage = $request['limit'] ?? 10;
            $cursor = $request['cursor'] ?? null;
            $search = $request['search'] ?? null;
    
            $response = $this->wordService->listData($perPage, $cursor, $search);
            
            return response()->json($response);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("EntryController index server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in EntryController index: {$e->getMessage()}", [
                'exception' => $e,
            ]);

            return response()->json([
                'status' => 'An unexpected error occurred.',
                'message' => 'Ocorreu um erro inesperado, tente novamente mais tarde.',
                'type' => 'error'
            ], 500);
        }
    }

    public function word(string $word)
    {
        try {
            $student = $this->userService->getAuthenticatedUser();
            $response = $this->wordHistoryService->makeUserHistory((int) $student['id'], (string) $word);
            $responseApi = null;
    
            if(!!$response){
                $responseApi = $this->dicionaryApiService->getAllItems($word);
            }
    
            return response()->json([
                'status' => !!$responseApi,
                'response' => $responseApi ?? []
            ]);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("EntryController word server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in EntryController word: {$e->getMessage()}", [
                'exception' => $e,
            ]);

            return response()->json([
                'status' => 'An unexpected error occurred.',
                'message' => 'Ocorreu um erro inesperado, tente novamente mais tarde.',
                'type' => 'error'
            ], 500);
        }
    }

    public function favorite(string $word)
    {
        try {
            $student = $this->userService->getAuthenticatedUser();
            $this->wordFavoriteService->makeUserFavorite((int) $student['id'], (string) $word);
            return response()->json([], 204);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("EntryController favorite server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in EntryController favorite: {$e->getMessage()}", [
                'exception' => $e,
            ]);

            return response()->json([
                'status' => 'An unexpected error occurred.',
                'message' => 'Ocorreu um erro inesperado, tente novamente mais tarde.',
                'type' => 'error'
            ], 500);
        }
    }

    public function unFavorite(string $word)
    {
        try {
            $student = $this->userService->getAuthenticatedUser();
            $this->wordFavoriteService->unMakeUserFavorite((int) $student['id'], (string) $word);
    
            return response()->json([], 204);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("EntryController unFavorite server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in EntryController unFavorite: {$e->getMessage()}", [
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
