<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/",
     *     summary="Retorna mensagem de boas-vindas",
     *     description="Retorna uma mensagem simples para indicar que a API está funcionando corretamente.",
     *     operationId="getWelcomeMessage",
     *     tags={"Home"},
     *     @OA\Response(
     *         response=200,
     *         description="Mensagem de boas-vindas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Fullstack Challenge 🏅 - Dictionary")
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
     *     )
     * )
     */
    public function index()
    {
        try {
            return response()->json(["message" => "Fullstack Challenge 🏅 - Dictionary"], 200);
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("HomeController index server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in HomeController index: {$e->getMessage()}", [
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
