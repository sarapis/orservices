<?php

namespace App\Exports;

use App\Model\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServiceExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $services = Service::where('id', 2)->get();

        return view('exports.services', [
            'services' => $services,
        ]);;
    }
}
