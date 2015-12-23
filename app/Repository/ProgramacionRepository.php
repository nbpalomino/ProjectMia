<?php namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Guard;

use DB;

trait ProgramacionRepository {

	/**
	 * Consulta todos los Model activos
	 *
	 * @return boolean
	 */
	public function scopeVisitaTalleres(Builder $query)
	{
		return DB::table('programacion_taller AS pt')
					->join('talleres AS t', 'pt.taller_id', '=', 't.id')
					->join('distritos AS d', 't.distrito_id', '=', 'd.id')
					->join('zonas AS z', 't.zona_id', '=', 'z.id')
					->where('t.activo', 1);
	}

}