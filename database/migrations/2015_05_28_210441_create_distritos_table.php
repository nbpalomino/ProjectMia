<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistritosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distritos', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre');

			$table->integer('zona_id')->unsigned()->nullable();
			$table->foreign('zona_id')->references('id')->on('zonas');

			$table->boolean('activo')->default(1);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('distritos');
	}

}
