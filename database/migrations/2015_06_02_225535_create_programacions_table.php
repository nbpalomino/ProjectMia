<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramacionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('programaciones', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('fecha_inicio');
			$table->date('fecha_fin');

			$table->timestamps();
			$table->boolean('activo')->default(1);
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
		});

		// Programaciones es reemplazo de Visitas
		Schema::drop('visitas');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('programaciones');
	}

}
