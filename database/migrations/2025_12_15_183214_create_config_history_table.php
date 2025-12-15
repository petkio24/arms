<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('config_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workstation_id')->constrained()->onDelete('cascade');
            $table->text('change_description');
            $table->json('components_before')->nullable();
            $table->json('components_after')->nullable();
            $table->string('change_type'); // assembly, upgrade, repair, replacement
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('config_history');
    }
};
