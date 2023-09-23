<?php

namespace App\Exports\zip;

use App\Model\Address;
use App\Model\LocationAddress;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class AddressZipExport implements FromView, WithCustomCsvSettings
{
    public $location_recordids_temp, $enclosure;
    public function __construct($location_recordids_temp, $enclosure)
    {
        $this->location_recordids_temp = $location_recordids_temp;
        $this->enclosure = $enclosure;
    }


    /**
     * @return View
     */
    public function view(): View
    {
        if (isset($this->location_recordids_temp) && count($this->location_recordids_temp) > 0) {
            $chunk = $this->location_recordids_temp;
            $chunk = array_chunk($chunk, 500);

            $table_address = collect();
            for ($i = 0; $i < count($chunk); $i++) {
                $address_record_ids = LocationAddress::whereIn('location_recordid', $chunk[$i])->pluck('address_recordid')->toArray();
                $query = Address::whereIn('address_recordid', $address_record_ids)->get();
                $addresses = $table_address->merge($query);
            }
        } else {
            $addresses = Address::get();
        }
        return view('exports.zip.addresses', [
            'addresses' => $addresses,
        ]);
    }
    public function getCsvSettings(): array
    {
        if ($this->enclosure) {
            return [
                'delimiter' => ',',
                'enclosure' => '',
            ];
        }
        return [];
    }
}
