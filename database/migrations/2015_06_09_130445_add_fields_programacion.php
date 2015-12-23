<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsProgramacion extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('programaciones', function(Blueprint $table)
		{
			$table->string('nombre');

			$table->integer('zona_id')->unsigned();
			$table->foreign('zona_id')->references('id')->on('zonas');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
