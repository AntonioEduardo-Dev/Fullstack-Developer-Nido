<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\Api\{
    UserService,
    WordFavoriteService,
    WordService
};

class EntryController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected WordService $wordService,
        protected WordFavoriteService $wordFavoriteService
    )
    {}

    public function index(SearchRequest $request)
    {
        // Define o número de itens por página
        $perPage = $request['limit'] ?? 10;
        $cursor = $request['cursor'] ?? null;
        $search = $request['search'] ?? null;

        $response = $this->wordService->listData($perPage, $cursor, $search);
        
        return response()->json($response);
    }

    public function word(SearchRequest $request)
    {
        // Define o número de itens por página
        $perPage = $request['limit'] ?? 10;
        $cursor = $request['cursor'] ?? null;
        $search = $request['search'] ?? null;

        $response = $this->wordService->listData($perPage, $cursor, $search);
        
        return response()->json($response);
    }

    public function favorite(string $word)
    {
        $student = $this->userService->getAuthenticatedUser();
        $response = $this->wordFavoriteService->makeUserFavorite((int) $student['id'], (string) $word);

        return response()->json(['status' => !!$response]);
    }

    public function unFavorite(string $word)
    {
    }
}
