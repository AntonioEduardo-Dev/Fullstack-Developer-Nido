<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class EntryController extends Controller
{
    public function index()
    {
        return response()->json(["message" => "Fullstack Challenge 🏅 - Dictionary"], 200);
    }
}
