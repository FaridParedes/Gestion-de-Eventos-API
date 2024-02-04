<?php

namespace App\Models;

use App\Models\User;
use App\Models\Imagen;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    protected $fillable = [
        'title',
        'description',
        'allDay',
        'start',
        'end',
        'session',
        'ubication',
        'enlace',
        'organizadorId',
        'categoriaId',
        'estadoId',
    ];
}
