<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Repository\ProgramacionRepository;
use App\Services\ModelQueries;
use App\Services\Auditorias;

class Programacion extends Model {

	/**
	 * Consultas encapsuladas
	 *
	 * Actualiza los campos de Auditoria
	 */
	use ModelQueries, Auditorias, ProgramacionRepository;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'programaciones';

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = ['zona'];

	/**
	 * Los atributos disponibles para ser llenados en grupo
	 *
	 * @var array
	 */
	protected $fillable = ['fecha_inicio', 'fecha_fin', 'nombre', 'zona_id'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['zona_id', 'activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Programacion tiene muchas Visitas
	 */
	public function visitas()
	{
		return $this->belongsToMany('App\Taller', 'programacion_taller')->withPivot(['fecha_visita', 'motivo_no_visita', 'visitado', 'orden']);
	}

	/**
	 * Programacion tiene una Zona
	 */
	public function zona()
	{
		return $this->belongsTo('App\Zona');
	}

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	public function fecha($fecha)
	{
		return new \DateTime($fecha);
	}
}
