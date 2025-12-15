<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workstations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('inventory_number')->unique();
            $table->string('location')->nullable();
            $table->string('status')->default('active'); // active, maintenance, decommissioned
            $table->text('initial_config')->nullable(); // JSON с первоначальной конфигурацией
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workstations');
    }
};
