<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Distrito extends Model {

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = ['zona'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['zona_id', 'activo', 'created_by', 'updated_by', 'created_at', 'updated_at'];

	/**
	 * Distrito pertenece a Zona
	 */
	public function zona()
	{
		return $this->belongsTo('App\Zona');
	}
}
