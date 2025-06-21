<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GmailToken extends Model
{
    protected $table = 'gmail_tokens';

    protected $fillable = [
        'email',
        'token',
    ];

    protected $casts = [
        'token' => 'array', // Permet de manipuler le token comme un tableau
    ];

    public $timestamps = true;
}
