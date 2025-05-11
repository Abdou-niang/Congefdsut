<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('demande_conges', function (Blueprint $table) {
            $table->id();
            $table->string('date_debut');
            $table->string('nombre_jour');
            $table->string('motif');
            $table->string('fichier');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_typeconge');
            $table->foreign('id_typeconge')->references('id')->on('typeconges')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demande_conges');
        if ('demande_conges' === "Users" || 'demande_conges' === "User" || 'demande_conges' === "user" || 'demande_conges' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};
