<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\IdentifierService;
use Illuminate\Http\Request;

class IdentifierController extends Controller
{
    protected $identifierService;

    public function __construct(IdentifierService $identifierService)
    {
        $this->identifierService = $identifierService;
    }

    public function airtable_v3($access_token, $base_url)
    {
        $this->identifierService->import_airtable_v3($access_token, $base_url);
    }
}
