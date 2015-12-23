<?php namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Guard;

use DB;

trait EmpaqueRepository {

	/**
	 * Consulta todos los Model activos
	 *
	 * @return boolean
	 */
	public function scopeCanjeTalleres(Builder $query)
	{
		return DB::table('empaque_taller AS et')
				->join('talleres AS t', 'et.taller_id', '=', 't.id')
				->join('distritos AS d', 't.distrito_id', '=', 'd.id')
				->join('zonas AS z', 't.zona_id', '=', 'z.id')
				->where('t.activo', 1);
	}

}