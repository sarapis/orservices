<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AllLanguage extends Model
{
    use SoftDeletes;
    protected $fillable = ['language_name', 'note', 'flag', 'created_by', 'updated_by', 'deleted_by'];
}
