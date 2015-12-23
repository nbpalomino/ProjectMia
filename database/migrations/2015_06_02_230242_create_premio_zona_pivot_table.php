<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremioZonaPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premio_zona', function(Blueprint $table)
        {
            $table->integer('premio_id')->unsigned()->index();
            $table->foreign('premio_id')->references('id')->on('premios')->onDelete('cascade');
            $table->integer('zona_id')->unsigned()->index();
            $table->foreign('zona_id')->references('id')->on('zonas')->onDelete('cascade');

            $table->integer('stock')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('premio_zona');
    }
}
