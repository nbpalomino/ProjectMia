<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePremiosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('premios', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombre');
			$table->integer('valor')->unsigned();
			$table->integer('stock')->unsigned();
			$table->string('imagen')->nullable();


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
		Schema::drop('premios');
	}

}
