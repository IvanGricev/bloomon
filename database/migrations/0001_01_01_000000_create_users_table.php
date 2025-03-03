<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Запустить миграции.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Аутентификационные данные
            $table->string('name');               // Отображаемое имя
            $table->string('email')->unique();    // Email

            $table->timestamp('email_verified_at')->nullable();

            $table->string('password');           // Пароль

            // Метаданные
            $table->boolean('is_active')->default(true); // Статус активности

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Откатить миграции.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
