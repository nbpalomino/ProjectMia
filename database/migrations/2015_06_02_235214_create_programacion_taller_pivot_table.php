<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramacionTallerPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programacion_taller', function(Blueprint $table) {
            $table->integer('programacion_id')->unsigned()->index();
            $table->foreign('programacion_id')->references('id')->on('programaciones')->onDelete('cascade');
            $table->integer('taller_id')->unsigned()->index();
            $table->foreign('taller_id')->references('id')->on('talleres')->onDelete('cascade');

            $table->date('fecha_visita');
            $table->text('motivo_no_visita', 500)->nullable();
            $table->boolean('visitado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('programacion_taller');
    }
}
