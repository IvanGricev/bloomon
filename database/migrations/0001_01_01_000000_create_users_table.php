<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // Первичный ключ
            $table->id();

            // Аутентификационные данные
            $table->string('username')->unique();      // Имя пользователя
            $table->string('email')->unique();         // Email
            $table->string('password');                // Пароль

            // Метаданные пользователя
            $table->string('name');                    // Отображаемое имя
            $table->boolean('is_active')->default(true); // Статус активности

            // Временные метки
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            
            // Автоматические временные метки
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
