<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function index(string $boardId, string $columnId)
    {
        //
    }
    
    public function store(Request $request, string $boardId, string $columnId)
    {
        //
    }

    public function show(string $boardId, string $columnId, string $cardId)
    {
        //
    }

    public function update(Request $request, string $boardId, string $columnId, string $cardId)
    {
        //
    }

    public function destroy(string $boardId, string $columnId, string $cardId)
    {
        //
    }
    
    public function move(Request $request, string $boardId, string $columnId, string $cardId) {
        //
    }
}
