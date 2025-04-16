<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBouquetTemplateItemsTable extends Migration
{
    public function up()
    {
        Schema::create('bouquet_template_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('flower_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('template_id')->references('id')->on('bouquet_templates')->onDelete('cascade');
            $table->foreign('flower_id')->references('id')->on('flowers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bouquet_template_items');
    }
}