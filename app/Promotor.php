<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Services\ModelQueries;
use App\Services\Auditorias;

class Promotor extends Model {

	/**
	 * Consultas encapsuladas
	 *
	 * Actualiza los campos de Auditoria
	 */
	use ModelQueries, Auditorias;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'promotor';

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = ['grupo'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombres', 'apellidos', 'email', 'celular', 'foto', 'motorizado', 'grupo_id', 'dni', 'calificacion'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['grupo_id', 'activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 *  Promotor pertenece a un Grupo
	 */
	public function grupo()
	{
		return $this->belongsTo('App\Grupo');
	}
}
