<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('productos', function(Blueprint $table){
			$table->increments('id');
			$table->string('nombre');
			$table->decimal('precio');
			$table->string('modelo');
			$table->boolean('disponible')->default(1);

			$table->integer('genero_id')->unsigned();
			$table->foreign('genero_id')->references('id')->on('generos');

			$table->integer('tipo_id')->unsigned();
			$table->foreign('tipo_id')->references('id')->on('tipos');

			$table->integer('categoria_id')->unsigned();
			$table->foreign('categoria_id')->references('id')->on('categorias');

			$table->integer('marca_id')->unsigned();
			$table->foreign('marca_id')->references('id')->on('marcas');

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
		Schema::drop('productos');
	}

}
