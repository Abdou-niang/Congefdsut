<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('privileges', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
$table->string('descripiton');

            $table->timestamps();
        });    }

    public function down()
    {
        Schema::dropIfExists('privileges');
        if ('privileges' === "Users"||'privileges' === "User" ||'privileges' === "user"||'privileges' === "users") {
            Schema::dropIfExists('password_reset_tokens');
            Schema::dropIfExists('sessions');
        }
    }
};