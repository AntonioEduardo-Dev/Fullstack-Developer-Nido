<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="NidoAPI", version="0.1")
 * @OA\Tag(
 *     name="/",
 *     description="Endpoint relacionado a pagina inicial"
 * )
 * @OA\Tag(
 *     name="/auth",
 *     description="Endpoints relacionados a autenticação"
 * )
 * @OA\Tag(
 *     name="/entries/en",
 *     description="Endpoints relacionados a pesquisa e listagem de palavras"
 * )
 * @OA\Tag(
 *     name="/user/me",
 *     description="Endpoints relacionados a usuários"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
