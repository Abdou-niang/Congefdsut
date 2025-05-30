<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeConge extends Model
{
    //

    protected $fillable = [
        'date_debut',
        'date_fin',
        'nombre_jour',
        'motif',
        'fichier',
        'id_user',
        'id_typeconge',
    ];

    // Relations
    public function user(){
        return $this->belongsTo(User::class,'id_user');
    }

    public function typeconge(){
        return $this->belongsTo(TypeConge::class,'id_typeconge');
    }
    public function historiquedemandeconges(){
        return $this->hasMany(HistoriqueDemandeConge::class,'id_demandeconge');
    }
}
