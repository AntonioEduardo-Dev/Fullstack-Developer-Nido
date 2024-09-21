<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\Api\WordService;

class EntryController extends Controller
{
    public function __construct(protected WordService $wordService)
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
}
