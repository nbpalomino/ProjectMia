<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ModelQueries;
use App\Services\Auditorias;

class Grupo extends Model {

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
	protected $with = [];

	/**
	 * Los atributos disponibles para ser llenados en grupo
	 *
	 * @var array
	 */
	protected $fillable = ['nombre'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Taller tiene muchas Zonas
	 */
	public function zonas()
	{
		return $this->hasMany('App\Zona');
	}
}
