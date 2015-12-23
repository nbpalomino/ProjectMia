<?php namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\Guard;
use Auth;

trait Auditorias {

	/**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function($model) {
            $model->created_by = Auth::user()->id;
        });

        self::saving(function($model) {
            $model->updated_by = Auth::user()->id;
        });
    }
}