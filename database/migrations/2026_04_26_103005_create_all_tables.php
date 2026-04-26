<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Таблица пользователей
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role')->default('user');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // Таблица сессий
        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }

        // Таблица кэша
        if (!Schema::hasTable('cache')) {
            Schema::create('cache', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->mediumText('value');
                $table->integer('expiration');
            });
        }

        if (!Schema::hasTable('cache_locks')) {
            Schema::create('cache_locks', function (Blueprint $table) {
                $table->string('key')->primary();
                $table->string('owner');
                $table->integer('expiration');
            });
        }

        // Таблица password_reset_tokens
        if (!Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email')->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        // Таблица failed_jobs
        if (!Schema::hasTable('failed_jobs')) {
            Schema::create('failed_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('uuid')->unique();
                $table->text('connection');
                $table->text('queue');
                $table->longText('payload');
                $table->longText('exception');
                $table->timestamp('failed_at')->useCurrent();
            });
        }

        // Таблица помещений
        if (!Schema::hasTable('locations')) {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('building')->nullable();
                $table->string('floor')->nullable();
                $table->string('room')->nullable();
                $table->text('description')->nullable();
                $table->string('responsible_person')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        // Таблица компонентов
        if (!Schema::hasTable('components')) {
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
                $table->string('status')->default('in_stock');
                $table->string('socket')->nullable();
                $table->string('ram_type')->nullable();
                $table->string('form_factor')->nullable();
                $table->integer('power')->nullable();
                $table->timestamps();
            });
        }

        // Таблица рабочих станций
        if (!Schema::hasTable('workstations')) {
            Schema::create('workstations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('inventory_number')->unique();
                $table->foreignId('location_id')->nullable()->constrained('locations')->onDelete('set null');
                $table->string('status')->default('active');
                $table->text('initial_config')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Таблица связи станций и компонентов
        if (!Schema::hasTable('workstation_components')) {
            Schema::create('workstation_components', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workstation_id')->constrained('workstations')->onDelete('cascade');
                $table->foreignId('component_id')->constrained('components')->onDelete('cascade');
                $table->date('installed_at');
                $table->date('removed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // Таблица истории конфигураций
        if (!Schema::hasTable('config_history')) {
            Schema::create('config_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('workstation_id')->constrained('workstations')->onDelete('cascade');
                $table->text('change_description');
                $table->json('components_before')->nullable();
                $table->json('components_after')->nullable();
                $table->string('change_type');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('config_history');
        Schema::dropIfExists('workstation_components');
        Schema::dropIfExists('workstations');
        Schema::dropIfExists('components');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('users');
    }
};
