<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeConge extends Model
{
    //
    protected $fillable = [
        'libelle',
        'description',
    ];

    // Relations
    public function demandeconges(){
        return $this->hasMany(DemandeConge::class,'id_typeconge');
    }
}
