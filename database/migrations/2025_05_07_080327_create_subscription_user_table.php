<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionUserTable extends Migration
{
    public function up()
    {
         Schema::create('subscription_user', function (Blueprint $table) {
             $table->id();
             $table->foreignId('user_id')->constrained()->onDelete('cascade');
             $table->foreignId('subscription_id')->constrained()->onDelete('cascade');
             $table->timestamps();
         });
    }

    public function down()
    {
         Schema::dropIfExists('subscription_user');
    }
}