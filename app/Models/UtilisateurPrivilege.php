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

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function privilege()
    {
        return $this->belongsTo(Privilege::class, 'id_privilege');
    }
    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service');
    }

    public function cellule()
    {
        return $this->belongsTo(Cellule::class, 'id_cellule');
    }
}
