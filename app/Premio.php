<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ModelQueries;
use App\Services\Auditorias;
use App\Repository\PremioRepository;

class Premio extends Model {

	/**
	 * Consultas encapsuladas
	 *
	 * Actualiza los campos de Auditoria
	 */
	use ModelQueries, Auditorias, PremioRepository;

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Premio pertenece a muchos Taller
	 */
	public function taller()
	{
		return $this->belongsToMany('App\Taller', 'premio_taller');
	}

}
