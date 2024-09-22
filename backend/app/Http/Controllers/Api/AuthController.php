<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Services\Api\AuthService;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
    )
    {}

    /**
     * @OA\Post(
     *     path="/auth/signup",
     *     summary="Registro de novo usuário",
     *     description="Registra um novo usuário no sistema e retorna um token JWT.",
     *     operationId="registerUser",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados do usuário para registro",
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="token", type="string", example="Bearer {jwt_token}")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="O campo e-mail já está sendo utilizado.")
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
    public function signUp(AuthRegisterRequest $request)
    {
        try {
            return $this->authService->signUp($request->validated());
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("AuthController signUp server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in AuthController signUp: {$e->getMessage()}", [
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
     *     path="/auth/signin",
     *     summary="Login de usuário",
     *     description="Autentica o usuário e retorna um token JWT.",
     *     operationId="loginUser",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados de login do usuário",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="João Silva"),
     *             @OA\Property(property="token", type="string", example="Bearer {jwt_token}")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de cliente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas.")
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
    public function signIn(AuthLoginRequest $request)
    {
        try {
            return $this->authService->signIn($request->validated());
        } catch (\App\Exceptions\ClientException $e) {
            // Trata exceções relacionadas a erros do cliente
            return $e->render();
        } catch (\App\Exceptions\ServerException $e) {
            // Registra o erro de servidor ocorrido durante a execução do método
            Log::error("AuthController signIn server error: {$e->getMessage()}", [
                'exception' => $e->getMessage(),
            ]);

            return $e->render();
        } catch (\Exception $e) {
            // Captura todas as outras exceções não tratadas especificamente
            Log::critical("Unexpected error in AuthController signIn: {$e->getMessage()}", [
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
