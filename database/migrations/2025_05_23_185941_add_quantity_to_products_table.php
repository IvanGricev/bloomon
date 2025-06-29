<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'quantity')) {
                $table->integer('quantity')->default(0);
            }
        });
    }
 
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }
};
