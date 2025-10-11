<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tarefa;
use Illuminate\Http\Request;

class TarefaController extends Controller
{
    public function listar(Request $request)
    {
        $tarefas = $request->user()->tarefas()->latest()->get();

        return response()->json([
            'sucesso' => true,
            'dados' => $tarefas,
        ]);
    }

    public function criar(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $tarefa = $request->user()->tarefas()->create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'concluida' => false,
        ]);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Tarefa criada com sucesso',
            'dados' => $tarefa,
        ], 201);
    }

    public function visualizar(Request $request, $id)
    {
        $tarefa = $request->user()->tarefas()->findOrFail($id);

        return response()->json([
            'sucesso' => true,
            'dados' => $tarefa,
        ]);
    }

    public function atualizar(Request $request, $id)
    {
        $tarefa = $request->user()->tarefas()->findOrFail($id);

        $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $tarefa->update($request->only(['titulo', 'descricao']));

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Tarefa atualizada com sucesso',
            'dados' => $tarefa,
        ]);
    }

    public function excluir(Request $request, $id)
    {
        $tarefa = $request->user()->tarefas()->findOrFail($id);
        $tarefa->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Tarefa excluída com sucesso',
        ]);
    }

    public function alternarConclusao(Request $request, $id)
    {
        $tarefa = $request->user()->tarefas()->findOrFail($id);

        if ($tarefa->concluida) {
            $tarefa->marcarComoNaoConcluida();
        } else {
            $tarefa->marcarComoConcluida();
        }

        return response()->json([
            'sucesso' => true,
            'mensagem' => $tarefa->concluida ? 'Tarefa marcada como concluída' : 'Tarefa marcada como não concluída',
            'dados' => $tarefa->fresh(),
        ]);
    }
}