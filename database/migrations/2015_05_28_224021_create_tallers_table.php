<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTallersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('talleres', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre');
			$table->string('ruc', 15);
			$table->string('contacto');
			$table->string('email');
			$table->string('telefono');
			$table->string('celular');
			$table->string('estado');
			$table->integer('puntos_disponible')->unsigned()->nullable();
			$table->text('motivo_eliminado', 500);

			$table->integer('distrito_id')->unsigned()->nullable();
			$table->foreign('distrito_id')->references('id')->on('distritos');

			$table->integer('zona_id')->unsigned()->nullable();
			$table->foreign('zona_id')->references('id')->on('zonas');

			$table->boolean('activo')->default(1);
			$table->timestamps();
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('updated_by')->unsigned()->nullable();
			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('updated_by')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('talleres');
	}

}
