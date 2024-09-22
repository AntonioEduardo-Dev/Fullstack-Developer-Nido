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

    /**
     * @OA\Get(
     *     path="/user/me",
     *     summary="Obtém informações do usuário autenticado",
     *     operationId="getUserInfo",
     *     tags={"/user/me"},
     *     @OA\Response(
     *         response=200,
     *         description="Informações do usuário obtidas com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="João"),
     *             @OA\Property(property="email", type="string", example="joao@example.com"),
     *             @OA\Property(property="token", type="string", example="jwt-token-here"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T00:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T00:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuário não autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário não autenticado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro inesperado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro inesperado, tente novamente mais tarde.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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


    /**
     * @OA\Get(
     *     path="/user/me/history",
     *     summary="Obtém o histórico de palavras do usuário autenticado",
     *     operationId="getUserHistory",
     *     tags={"/user/me"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="cursor",
     *         in="query",
     *         description="Cursor para paginação",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Histórico de palavras obtido com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="results", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="totalDocs", type="integer", example=100),
     *             @OA\Property(property="previous", type="string", example="cursor-previous"),
     *             @OA\Property(property="next", type="string", example="cursor-next"),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuário não autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário não autenticado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro inesperado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro inesperado, tente novamente mais tarde.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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


    /**
     * @OA\Get(
     *     path="/user/me/favorites",
     *     summary="Obtém as palavras favoritas do usuário autenticado",
     *     operationId="getUserFavorites",
     *     tags={"/user/me"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de itens por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="cursor",
     *         in="query",
     *         description="Cursor para paginação",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Palavras favoritas obtidas com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="results", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="totalDocs", type="integer", example=50),
     *             @OA\Property(property="previous", type="string", example="cursor-previous"),
     *             @OA\Property(property="next", type="string", example="cursor-next"),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Usuário não autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário não autenticado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro inesperado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Ocorreu um erro inesperado, tente novamente mais tarde.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
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
