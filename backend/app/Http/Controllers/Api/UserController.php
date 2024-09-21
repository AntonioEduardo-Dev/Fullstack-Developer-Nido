<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\Api\{
    WordHistoryService,
    UserService,
    WordFavoriteService
};
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected WordHistoryService $wordHistoryService,
        protected WordFavoriteService $wordFavoriteService
    )
    {}

    public function index()
    {
        try {
            $user = $this->userService->getAuthenticatedUser();
            
            // Verifique se há um usuário autenticado antes de tentar mapear
            if (!$user) {
                return response()->json(['message' => 'Usuário não autenticado'], 401);
            }
        
            return [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $user->token,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
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

    public function history(SearchRequest $request)
    {
        try {
            // Define o número de itens por página
            $perPage = $request['limit'] ?? 10;
            $cursor = $request['cursor'] ?? null;

            $user = $this->userService->getAuthenticatedUser();
            return $this->wordHistoryService->getHistory($user['id'], $perPage, $cursor);
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

    public function favorites(SearchRequest $request)
    {
        try {
            // Define o número de itens por página
            $perPage = $request['limit'] ?? 10;
            $cursor = $request['cursor'] ?? null;

            $user = $this->userService->getAuthenticatedUser();
            return $this->wordFavoriteService->getFavorite($user['id'], $perPage, $cursor);
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
