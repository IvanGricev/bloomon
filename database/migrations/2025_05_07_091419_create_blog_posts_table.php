<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Запуск миграции: создание таблицы blog_posts.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');      // Заголовок записи блога
            $table->text('body');         // Основной текст записи
            $table->string('image')->nullable(); // Имя файла (или путь) изображения
            $table->timestamps();         // Поля created_at и updated_at
        });
    }

    /**
     * Откат миграции: удаление таблицы blog_posts.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}