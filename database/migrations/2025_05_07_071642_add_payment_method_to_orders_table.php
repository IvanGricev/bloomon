<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentMethodToOrdersTable extends Migration
{
    /**
     * Выполнение миграции: добавление поля payment_method.
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Добавляем поле после delivery_date
            $table->string('payment_method')->default('cash')->after('delivery_date');
        });
    }

    /**
     * Откат миграции: удаление поля payment_method.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_method');
        });
    }
}