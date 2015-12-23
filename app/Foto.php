<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Services\Auditorias;

class Foto extends Model {

	/**
	 * Actualiza los campos de Auditoria
	 */
	use Auditorias;

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	// protected $with = ['user'];

	/**
	 * Los atributos disponibles para ser llenados en grupo
	 *
	 * @var array
	 */
	protected $fillable = ['ruta', 'tipo'];

	/**
	 * Los atributos excluidos del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $hidden = ['updated_at', 'updated_by'];

	/**
	 * Agregar el campo user
	 */
   	public function toArray()
    {
        $array = parent::toArray();
        $array['user'] = $this->user;
        return $array;
    }

	/**
	 * Foto pertenece a User
	 */
	public function getUserAttribute()
	{
		return User::find( $this->created_by );
	}
}
