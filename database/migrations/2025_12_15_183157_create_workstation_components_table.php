<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('workstation_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workstation_id')->constrained()->onDelete('cascade');
            $table->foreignId('component_id')->constrained()->onDelete('cascade');
            $table->date('installed_at');
            $table->date('removed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('workstation_components');
    }
};
