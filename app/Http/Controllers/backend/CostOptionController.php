<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\CostOptionService;
use Illuminate\Http\Request;

class CostOptionController extends Controller
{
    protected $costOptionService;

    public function __construct(CostOptionService $costOptionService)
    {
        $this->costOptionService = $costOptionService;
    }

    public function airtable_v3($access_token, $base_url)
    {
        $this->costOptionService->import_airtable_v3($access_token, $base_url);
    }
}
