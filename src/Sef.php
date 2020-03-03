<?php

namespace TaffoVelikoff\LaravelSef;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Sef extends Model
{

	/**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [''];

    /**
     * Get the owning model.
     */
    public function model() {
        return $this->morphTo('model');
    }

    /*
     * Delete & clear from cache
     */
    protected static function boot() {
        parent::boot();

        static::deleting(function($sef) { 
            Cache::forget('sef_'.$sef->keyword);
        });
    }
}
