<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comments_recordid', 'comments_content', 'comments_user', 'comments_organization', 'comments_contact', 'comments_location', 'comments_user_firstname', 'comments_user_lastname', 'comments_datetime', 'comments_service'
    ];
}
