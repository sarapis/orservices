<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequiredDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'service_recordid', 'detail_id', 'document_type', 'document_link', 'document_title', 'created_by'
    ];
}
