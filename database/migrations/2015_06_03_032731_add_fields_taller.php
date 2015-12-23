<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsTaller extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('talleres', function(Blueprint $table)
		{
			$table->integer('grupo_id')->unsigned();
			$table->foreign('grupo_id')->references('id')->on('grupos');

			$table->dropColumn('zona_id');
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
