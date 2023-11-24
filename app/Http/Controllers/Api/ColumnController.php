<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    public function index(string $boardId)
    {
        //
    }
    
    public function store(Request $request, string $boardId)
    {
        //
    }

    public function show(string $boardId, string $columnId)
    {
        //
    }

    public function update(Request $request,  string $boardId, string $columnId)
    {
        //
    }

    public function destroy(string $boardId, string $columnId)
    {
        //
    }
    
    public function move(Request $request, string $boardId, string $columnId) {
        //
    }
}
