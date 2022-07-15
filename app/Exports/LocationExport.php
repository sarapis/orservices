<?php

namespace App\Exports;

use App\Model\Location;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class LocationExport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $locations = Location::get();
        return view('exports.locations', [
            'locations' => $locations,
        ]);;
    }
}
