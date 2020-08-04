<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Suggest extends Model
{
    protected $fillable = [
        'suggest_recordid', 'suggest_organization', 'suggest_content', 'suggest_username', 'suggest_user_email', 'suggest_user_phone'
    ];

    public function organization()
    {
        return $this->belongsTo('App\Model\Organization', 'suggest_organization', 'organization_recordid');
    }
}
