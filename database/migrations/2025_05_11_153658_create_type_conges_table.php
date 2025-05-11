<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('type_conges', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
$table->string('description');

            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('type_conges');
        if ('type_conges' === "Users"||'type_conges' === "User" ||'type_conges' === "user"||'type_conges' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};