<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('building')->nullable(); // Корпус/здание
            $table->string('floor')->nullable();    // Этаж
            $table->string('room')->nullable();     // Кабинет/комната
            $table->text('description')->nullable();
            $table->string('responsible_person')->nullable(); // Ответственное лицо
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('locations');
    }
};
