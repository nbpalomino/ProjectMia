<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ModelQueries;
use App\Services\Auditorias;

class Taller extends Model {

	/**
	 * Consultas encapsuladas
	 *
	 * Actualiza los campos de Auditoria
	 */
	use ModelQueries, Auditorias;

	/**
	 * El nombre real de la tabla
	 *
	 * @var string
	 */
	protected $table = 'talleres';

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = ['zona', 'distrito'];

	/**
	 * Los atributos disponibles para ser llenados en grupo
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'ruc', 'contacto', 'email', 'telefono', 'celular', 'estado', 'motivo_eliminado', 'distrito_id', 'zona_id'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['distrito_id', 'zona_id', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Taller tiene un Distrito
	 */
	public function distrito()
	{
		return $this->belongsTo('App\Distrito');
	}

	/**
	 * Taller tiene una Zona
	 */
	public function zona()
	{
		return $this->belongsTo('App\Zona');
	}

	/**
	 * Taller tiene muchos Empaque
	 */
	public function empaques()
	{
		return $this->belongsToMany('App\Empaque', 'empaque_taller')->withPivot(['total_puntos', 'total_empaques', 'fecha_visita']);
	}

	/**
	 * Taller tiene muchos Premio
	 */
	public function premios()
	{
		return $this->belongsToMany('App\Premio', 'premio_taller')->withPivot(['total_premios', 'fecha_visita']);
	}

	/**
	 * Programacion tiene muchas Visitas
	 */
	public function visitas()
	{
		return $this->belongsToMany('App\Programacion', 'programacion_taller')->withPivot(['fecha_visita', 'motivo_no_visita', 'visitado', 'orden']);
	}
}
