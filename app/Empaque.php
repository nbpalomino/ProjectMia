<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ModelQueries;
use App\Services\Auditorias;
use App\Repository\EmpaqueRepository;

class Empaque extends Model {

	/**
	 * Consultas encapsuladas
	 *
	 * Actualiza los campos de Auditoria
	 */
	use ModelQueries, Auditorias, EmpaqueRepository;

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = [];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Empaque pertenece a muchos Taller
	 */
	public function taller()
	{
		return $this->belongsToMany('App\Taller', 'empaque_taller');
	}

}
