<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpaqueTallerePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empaque_taller', function(Blueprint $table) 
        {
            $table->integer('total_puntos')->unsigned();
            $table->integer('total_empaques')->unsigned();

            $table->integer('empaque_id')->unsigned()->index();
            $table->foreign('empaque_id')->references('id')->on('empaques')->onDelete('cascade');
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
        Schema::drop('empaque_taller');
    }
}
