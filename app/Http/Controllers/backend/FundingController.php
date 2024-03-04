<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\FundingService;
use Illuminate\Http\Request;

class FundingController extends Controller
{
    protected $fundingService;

    public function __construct(FundingService $fundingService)
    {
        $this->fundingService = $fundingService;
    }

    public function airtable_v3($access_token, $base_url)
    {
        $this->fundingService->import_airtable_v3($access_token, $base_url);
    }
}
