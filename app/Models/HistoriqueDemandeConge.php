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

    // Relations
    public function demandeconge()
    {
        return $this->belongsTo(DemandeConge::class, 'id_demandeconge');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
