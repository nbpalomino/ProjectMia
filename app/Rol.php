<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'roles';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'descripcion'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Rol tiene muchos Usuarios
	 */
	public function users()
	{
		return $this->hasMany('App\User');
	}
}
