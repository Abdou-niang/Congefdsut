<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('historique_demande_conges', function (Blueprint $table) {
            $table->id();
            $table->string('niveau_validation');
$table->string('decision');
$table->string('commentaire');
$table->string('date_validation');
$table->unsignedBigInteger('id_user');
$table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
$table->unsignedBigInteger('id_demandeconge');
$table->foreign('id_demandeconge')->references('id')->on('demandeconges')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('historique_demande_conges');
        if ('historique_demande_conges' === "Users"||'historique_demande_conges' === "User" ||'historique_demande_conges' === "user"||'historique_demande_conges' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};