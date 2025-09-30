<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatUsuarios extends Model
{
    use HasFactory;

    protected $fillable=[
        'id_chat',
        'id_usuario'
    ];
}
