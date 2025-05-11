<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeConge extends Model
{
    //

    protected $fillable = [
        'date_debut',
        'nombre_jour',
        'motif',
        'fichier',
        'id_user',
        'id_typeconge',
    ];
}
