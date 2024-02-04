<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asistente extends Model
{
    use HasFactory;
    use Notifiable;

    protected $table = 'asistentes';

    protected $fillable = [
        'name',
        'lastName',
        'email',
        'registro',
        'userId',
        'eventoId',
    ];
}
