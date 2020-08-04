<?php

namespace App\Exports;

use App\Model\SessionInteraction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InteractionExport implements FromView
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
        $ineteractions = SessionInteraction::where('interaction_session', $this->request->session_recordid)->get();

        return view('exports.SessionInteraction', [
            'ineteractions' => $ineteractions,
        ]);
    }
}
