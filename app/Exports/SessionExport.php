<?php

namespace App\Exports;

use App\Model\Organization;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Model\SessionData;

class SessionExport implements FromView
{
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        if (isset($this->request->id) && $this->request->id == 'all') {
            $sessions = SessionData::select('*');
            if ($this->request->organization && $this->request->organization != null) {
                $organization = explode('|', $this->request->organization);
                $sessions = $sessions->whereIn('session_organization', $organization);
            }
            if ($this->request->organization_tag && $this->request->organization_tag != null) {
                $session_organization = explode("|", $this->request->organization_tag);
                $oraganization_recordids = Organization::select('organization_recordid')
                    ->Where(function ($query) use ($session_organization) {
                        for ($i = 0; $i < count($session_organization); $i++) {
                            $query->orwhere('organization_tag', 'like',  '%' . $session_organization[$i] . '%');
                        }
                    })->get();
                $oraganization_recordids_array = [];
                foreach ($oraganization_recordids as $key => $value) {
                    $oraganization_recordids_array[] = $value->organization_recordid;
                }
                // $organization_tag = explode('|', $this->request->organization_tag);
                $sessions = $sessions->whereIn('session_organization', $oraganization_recordids_array);
            }
            if ($this->request->start_date && $this->request->end_date && $this->request->end_date != null && $this->request->start_date != null) {

                $start_date = $this->request->start_date;
                $end_date = $this->request->end_date;
                // dd($start_date, $end_date);

                $sessions = $sessions->whereDate('created_at', '>=', date('Y-m-d', strtotime($start_date)))->whereDate('created_at', '<=', date('Y-m-d', strtotime($end_date)));
            }
            $sessions = $sessions->get();
        } else {
            $sessions = SessionData::where('session_organization', $this->request)->get();
        }

        return view('exports.sessions', [
            'sessions' => $sessions,
        ]);
    }
}
