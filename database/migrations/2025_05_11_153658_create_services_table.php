<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
$table->string('description');

            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('services');
        if ('services' === "Users"||'services' === "User" ||'services' === "user"||'services' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};