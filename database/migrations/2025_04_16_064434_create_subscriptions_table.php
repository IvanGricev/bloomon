<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Название плана подписки
            $table->string('subscription_type'); // Тип (например, "Романтические", "Свадебные" и т.д.)
            $table->string('frequency'); // Частота доставки (daily, weekly, и т.п.)
            $table->string('period');    // Период подписки (month, year)
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}