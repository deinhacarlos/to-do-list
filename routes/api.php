<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TarefaController;

// Rotas públicas (não precisam de autenticação)
Route::post('/cadastrar', [AuthController::class, 'cadastrar']);
Route::post('/entrar', [AuthController::class, 'entrar']);

// Rotas protegidas (precisam de autenticação)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticação
    Route::post('/sair', [AuthController::class, 'sair']);
    Route::get('/perfil', [AuthController::class, 'perfil']);
    
    // Tarefas
    Route::get('/tarefas', [TarefaController::class, 'listar']);
    Route::post('/tarefas', [TarefaController::class, 'criar']);
    Route::get('/tarefas/{id}', [TarefaController::class, 'visualizar']);
    Route::put('/tarefas/{id}', [TarefaController::class, 'atualizar']);
    Route::delete('/tarefas/{id}', [TarefaController::class, 'excluir']);
    Route::patch('/tarefas/{id}/alternar', [TarefaController::class, 'alternarConclusao']);
});