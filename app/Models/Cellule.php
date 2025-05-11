<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cellule extends Model
{
    //
      protected $fillable = [
        'libelle',
        'description',
        'id_service',
    ];
}
