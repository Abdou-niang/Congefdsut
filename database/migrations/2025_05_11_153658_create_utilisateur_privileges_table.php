<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('utilisateur_privileges', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('status')->default(1);
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_privilege');
            $table->foreign('id_privilege')->references('id')->on('privileges')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_service');
            $table->foreign('id_service')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('id_cellule');
            $table->foreign('id_cellule')->references('id')->on('cellules')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('utilisateur_privileges');
        if ('utilisateur_privileges' === "Users" || 'utilisateur_privileges' === "User" || 'utilisateur_privileges' === "user" || 'utilisateur_privileges' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};
