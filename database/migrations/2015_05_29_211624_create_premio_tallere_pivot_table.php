<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremioTallerePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('premio_taller', function(Blueprint $table) 
        {
            $table->integer('total_premios')->unsigned();

            $table->integer('premio_id')->unsigned()->index();
            $table->foreign('premio_id')->references('id')->on('premios')->onDelete('cascade');
            $table->integer('taller_id')->unsigned()->index();
            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('premio_taller');
    }
}
