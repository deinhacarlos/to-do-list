<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    use HasFactory;

    protected $table = 'tarefas';

    protected $fillable = [
        'usuario_id',
        'titulo',
        'descricao',
        'concluida',
        'concluida_em',
    ];

    protected $casts = [
        'concluida' => 'boolean',
        'concluida_em' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function marcarComoConcluida()
    {
        $this->update([
            'concluida' => true,
            'concluida_em' => now(),
        ]);
    }

    public function marcarComoNaoConcluida()
    {
        $this->update([
            'concluida' => false,
            'concluida_em' => null,
        ]);
    }
}