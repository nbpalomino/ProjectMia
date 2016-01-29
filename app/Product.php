<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $table = 'productos';

    /**
     * Los atributos agregados del formato JSON del Modelo
     *
     * @var array
     */
    protected $with = ['categoria', 'genero', 'tipo', 'marca'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['categoria_id', 'genero_id', 'tipo_id', 'marca_id', 'activo', 'created_at', 'updated_at', 'created_by', 'updated_by'];

    /**
     *
     */
    public function categoria()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     *
     */
    public function genero()
    {
        return $this->belongsTo('App\Genre');
    }

    /**
     *
     */
    public function tipo()
    {
        return $this->belongsTo('App\Type');
    }

    /**
     *
     */
    public function marca()
    {
        return $this->belongsTo('App\Brand');
    }
}