<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToPromotionsTable extends Migration
{
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
}