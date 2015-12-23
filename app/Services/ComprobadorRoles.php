<?php namespace App\Services;

trait ComprobadorRoles {

	/**
	 * Indica si el usuario puede actualizar registros
	 *
	 * @return boolean
	 */
	public function puedeGuardar()
	{
		return ! $this->esCliente();
	}

	/**
	 * Comprueba si el Rol es de SuperAdmin
	 *
	 * @return boolean
	 */
	public function esAdmin()
	{
		return $this->rol->nombre === 'Administrador';
	}

	/**
	 * Comprueba si el Rol es de Ejecutivo
	 *
	 * @return boolean
	 */
	public function esEjecutivo()
	{
		return $this->rol->nombre === 'Ejecutivo';
	}

	/**
	 * Comprueba si el Rol es de Cliente
	 *
	 * @return boolean
	 */
	public function esCliente()
	{
		return $this->rol->nombre === 'Cliente';
	}
}
