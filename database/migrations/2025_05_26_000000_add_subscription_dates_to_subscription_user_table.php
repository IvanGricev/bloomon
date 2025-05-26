<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscription_user', function (Blueprint $table) {
            $table->timestamp('last_payment_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            $table->string('status')->default('active'); // active, expired, cancelled
        });
    }

    public function down()
    {
        Schema::table('subscription_user', function (Blueprint $table) {
            $table->dropColumn(['last_payment_date', 'subscription_end_date', 'status']);
        });
    }
}; 