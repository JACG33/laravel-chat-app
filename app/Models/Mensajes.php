<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mensajes extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_chat',
        'id_usuario',
        'mensaje',
        'tipo_mensaje',
        'archivo_mensaje'
    ];

    /**
     * Relacion Mensajes Usuario
     */
    public function getUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id')->select('id', 'name');
    }
}
