<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('model')->nullable();
            $table->string('serial_number')->unique();
            $table->string('inventory_number')->unique();
            $table->string('manufacturer')->nullable();
            $table->text('specifications')->nullable();
            $table->date('purchase_date');
            $table->string('status')->default('in_stock'); // in_stock, installed, defective, decommissioned
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('components');
    }
};
