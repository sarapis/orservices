<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    protected $fillable = [
         'contact_recordid','phone_recordid'
    ];
}
