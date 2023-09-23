<?php

namespace App\Exports\zip;

use App\Model\Contact;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ContactZipExport implements FromView, WithCustomCsvSettings
{
    public $organization_ids, $enclosure;

    public function __construct($organization_ids, $enclosure)
    {
        $this->organization_ids = $organization_ids;
        $this->enclosure = $enclosure;
    }
    /**
     * @return View
     */
    public function view(): View
    {
        $contacts = Contact::select('*');
        if ($this->organization_ids) {
            $contacts->where('contact_organizations', $this->organization_ids);
        }

        return view('exports.zip.contacts', [
            'contacts' => $contacts->cursor(),
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
