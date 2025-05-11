<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cellules', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
$table->string('description');
$table->unsignedBigInteger('id_service');
$table->foreign('id_service')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('cellules');
        if ('cellules' === "Users"||'cellules' === "User" ||'cellules' === "user"||'cellules' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};