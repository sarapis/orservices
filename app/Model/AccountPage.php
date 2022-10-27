<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AccountPage extends Model
{
    protected $fillable = [
        'top_content', 'sidebar_widget', 'appear_for'
    ];
}
