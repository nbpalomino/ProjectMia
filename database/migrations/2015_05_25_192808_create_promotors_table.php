<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promotor', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('nombres');
			$table->string('apellidos');
			$table->string('email', 60);
			$table->string('foto')->nullable();
			$table->string('celular')->nullable();
			$table->boolean('motorizado')->default(0);
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
		Schema::drop('promotor');
	}

}
