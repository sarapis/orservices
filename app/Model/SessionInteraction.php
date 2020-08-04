<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SessionInteraction extends Model
{
    protected $fillable = [
        'interaction_recordid', 'interaction_session', 'interaction_method', 'interaction_disposition', 'interaction_notes', 'interaction_records_edited', 'interaction_timestamp'
    ];
}
