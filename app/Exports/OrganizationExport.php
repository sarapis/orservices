<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Model\Organization;
use Illuminate\Http\Request;

class OrganizationExport implements FromView
{

    public function __construct($organizations)
    {
        $this->organizations = $organizations;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        // $organizations = Organization::select('*');
        // $chip_organization = $this->request->input('find');
        // $organization_tags = $this->request->get('organization_tags');
        // $sort = $this->request->input('sort');
        // if ($chip_organization) {
        //     $organizations = Organization::where('organization_name', 'like', '%' . $chip_organization . '%');
        // }
        // if ($organization_tags) {
        //     $organization_tags = $organization_tags != null ?  json_decode($organization_tags) : [];
        //     $organizations = $organizations->whereIn('organization_tag', $organization_tags);
        // }
        // if (strpos(url()->previous(), '/organizations/') !== false) {
        //     $url = url()->previous();
        //     $recordedId = explode('organizations/', $url);
        //     if (count($recordedId) > 1) {
        //         $organizations = $organizations->where('organization_recordid', $recordedId[1]);
        //     }
        // }
        // if ($sort == 'Most Recently Updated') {
        //     $organizations = $organizations->orderBy('updated_at', 'desc')->get();
        // } else if ($sort == 'Least Recently Updated') {
        //     $organizations = $organizations->orderBy('updated_at')->get();
        // } else {
        //     $organizations = $organizations->orderBy('updated_at', 'desc')->get();
        // }
        return view('exports.organizations', [
            'organizations' => $this->organizations->get(),
        ]);
    }
}
