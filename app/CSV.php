<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CSV extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'csv';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public $timestamps = false;

}