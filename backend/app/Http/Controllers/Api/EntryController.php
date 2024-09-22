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

    /**
     * @OA\Get(
     *     path="/entries/en",
     *     summary="Lista dados com paginação usando cursores",
     *     description="Retorna uma lista de palavras com suporte à paginação via cursores e uma busca opcional por palavras.",
     *     operationId="getEntries",
     *     tags={"/entries/en"},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de itens por página (padrão: 10)",
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
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Palavra ou termo a ser buscado",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de palavras paginada",
     *         @OA\JsonContent(
     *             @OA\Property(property="results", type="array", @OA\Items(type="string", example="apple")),
     *             @OA\Property(property="totalDocs", type="integer", example=100),
     *             @OA\Property(property="previous", type="string", nullable=true, example="eyJpdiI6IjM0"),
     *             @OA\Property(property="next", type="string", nullable=true, example="eyJpdiI6IjU2"),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de cliente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro de servidor",
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

    /**
     * @OA\Get(
     *     path="/entries/en/{word}",
     *     summary="Busca informações sobre uma palavra",
     *     description="Permite que o usuário autenticado adicione uma palavra ao seu histórico e busque informações dessa palavra.",
     *     operationId="getWordInfo",
     *     tags={"/entries/en"},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         description="A palavra para buscar informações",
     *         required=true,
     *         @OA\Schema(type="string", example="apple")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informações da palavra obtidas com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="response", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de cliente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro de servidor",
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

    /**
     * @OA\Post(
     *     path="/entries/en/{word}/favorite",
     *     summary="Marca uma palavra como favorita para o usuário autenticado",
     *     description="Permite que o usuário autenticado marque uma palavra como favorita.",
     *     operationId="favoriteWord",
     *     tags={"/entries/en"},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         description="A palavra a ser marcada como favorita",
     *         required=true,
     *         @OA\Schema(type="string", example="apple")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Palavra marcada como favorita com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de cliente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro de servidor",
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

    /**
     * @OA\Delete(
     *     path="/entries/en/{word}/unfavorite",
     *     summary="Remove uma palavra dos favoritos do usuário autenticado",
     *     description="Permite que o usuário autenticado remova uma palavra dos seus favoritos.",
     *     operationId="unfavoriteWord",
     *     tags={"/entries/en"},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         description="A palavra a ser removida dos favoritos",
     *         required=true,
     *         @OA\Schema(type="string", example="apple")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Palavra removida dos favoritos com sucesso"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de cliente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro de servidor",
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
