<?php namespace App\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Guard;

use DB;

trait PremioRepository {

	/**
	 * Consulta todos los Model activos
	 *
	 * @return boolean
	 */
	public function scopeCanjeTalleres(Builder $query)
	{
		return DB::table('premio_taller AS pt')->join('talleres AS t', 'pt.taller_id', '=', 't.id')->where('t.activo', 1);
	}

}