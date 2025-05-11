<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueDemandeConge extends Model
{
    //
    protected $fillable = [
        'niveau_validation',
        'decision',
        'commentaire',
        'date_validation',
        'id_user',
        'id_demandeconge',
    ];
}
