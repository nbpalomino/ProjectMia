<?php namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Guard;

trait ModelQueries {

	/**
	 * Consulta todos los Model activos
	 *
	 * @return boolean
	 */
	public function scopeActivo(Builder $query)
	{
		return $query->where('activo', 1);
	}
}