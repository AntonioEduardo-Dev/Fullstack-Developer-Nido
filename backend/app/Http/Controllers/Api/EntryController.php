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
        // Define o número de itens por página
        $perPage = $request['limit'] ?? 10;
        $cursor = $request['cursor'] ?? null;
        $search = $request['search'] ?? null;

        $response = $this->wordService->listData($perPage, $cursor, $search);
        
        return response()->json($response);
    }

    public function word(string $word)
    {
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
    }

    public function favorite(string $word)
    {
        $student = $this->userService->getAuthenticatedUser();
        $response = $this->wordFavoriteService->makeUserFavorite((int) $student['id'], (string) $word);

        return response()->json(['status' => !!$response]);
    }

    public function unFavorite(string $word)
    {
        $student = $this->userService->getAuthenticatedUser();
        $response = $this->wordFavoriteService->unMakeUserFavorite((int) $student['id'], (string) $word);

        return response()->json(['status' => !!$response]);
    }
}
