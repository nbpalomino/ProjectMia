<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddManyFieldsTalleres extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('talleres', function(Blueprint $table)
		{
			$table->dropForeign('grupo_id');
			$table->renameColumn('grupo_id', 'zona_id');
			$table->foreign('zona_id')->references('id')->on('zonas');

			$table->boolean('arregla_motos')->nullable();
			$table->boolean('rectificado')->nullable();
			$table->text('direccion')->nullable();
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
