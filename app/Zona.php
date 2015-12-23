<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\Auditorias;
use App\Services\ModelQueries;

class Zona extends Model {

	/**
	 * Consultas encapsuladas
	 *
	 * Actualiza los campos de Auditoria
	 */
	use ModelQueries, Auditorias;

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = ['grupo'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['activo', 'grupo_id', 'created_by', 'updated_by', 'created_at', 'updated_at'];

	/**
	 * Zona tiene muchos Distrito
	 */
	public function distritos()
	{
		return $this->hasMany('App\Distrito');
	}

	/**
	 * Zona pertenece a un Grupo
	 */
	public function grupo()
	{
		return $this->belongsTo('App\Grupo');
	}
}
