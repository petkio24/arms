<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('workstations', function (Blueprint $table) {
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            // Убираем старое поле location, т.к. теперь есть связь с таблицей locations
            $table->dropColumn('location');
        });
    }

    public function down()
    {
        Schema::table('workstations', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');
            $table->string('location')->nullable();
        });
    }
};
