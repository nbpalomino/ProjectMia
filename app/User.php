<?php namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;

use App\Services\Auditorias;
use App\Services\ComprobadorRoles;
use App\Services\ModelQueries;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;
	use ComprobadorRoles;

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
	protected $table = 'users';

	/**
	 * Los atributos agregados del formato JSON del Modelo
	 *
	 * @var array
	 */
	protected $with = ['rol', 'grupo'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'email', 'password', 'apellidos', 'cargo', 'telefono', 'celular', 'rol_id'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token', 'rol_id', 'grupo_id', 'activo', 'created_at', 'updated_at',  'created_by', 'updated_by'];

	/**
	 * Usuario tiene un Rol
	 */
	public function rol()
	{
		return $this->belongsTo('App\Rol');
	}

	/**
	 * Usuario tiene un Grupo
	 */
	public function grupo()
	{
		return $this->belongsTo('App\Grupo');
	}
}
