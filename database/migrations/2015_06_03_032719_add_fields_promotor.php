<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPromotor extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('promotor', function(Blueprint $table)
		{
			$table->string('dni', 8);
			$table->smallInteger('calificacion')->unsigned()->nullable();

			$table->integer('grupo_id')->unsigned()->nullable();
			$table->foreign('grupo_id')->references('id')->on('grupos');
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
