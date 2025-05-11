<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UtilisateurPrivilege extends Model
{
    //
    protected $fillable = [
        'date',
        'status',
        'id_user',
        'id_privilege',
        'id_service',
        'id_cellule',
    ];
}
