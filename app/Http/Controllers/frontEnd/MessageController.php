<?php

namespace App\Http\Controllers\frontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function messagesSetting()
    {
        // $twillioSid = env('TWILIO_SID');
        // $twillioKey = env('TWILIO_TOKEN');
        // $twllioNumber = env('TWILIO_FROM');
        $sendgridKey = env('SENDGRID_API_KEY');
        $sendgridMailFromName = env('MAIL_FROM_NAME');
        $sendgridMailFromAddress = env('MAIL_FROM_ADDRESS');
        $share_this_api = env('SHARETHIS_API');
        $share_this_api_activate = env('SHARETHIS_ACTIVATE');
        return view('backEnd.messages.messageSetting', compact('sendgridKey', 'sendgridMailFromName', 'sendgridMailFromAddress', 'share_this_api', 'share_this_api_activate'));
    }


    public function saveMessageCredential(Request $request)
    {
        $this->validate($request, [
            // 'twillioSid' => 'required',
            // 'twillioKey' => 'required',
            // 'twillioNumber' => 'required',
            'sendgridApiKey' => 'required',
            'sendgridMailFromName' => 'required',
            'sendgridMailFromName' => 'required',
        ]);

        try {

            $envFile = app()->environmentFilePath();
            $str = file_get_contents($envFile);
            $values = [
                "TWILIO_SID" => $request->get('twillioSid'),
                "TWILIO_TOKEN" => $request->get('twillioKey'),
                "TWILIO_FROM" => $request->get('twillioNumber'),
                "SENDGRID_API_KEY" => $request->get('sendgridApiKey'),
                "MAIL_PASSWORD" => $request->get('sendgridApiKey'),
                "MAIL_FROM_ADDRESS" => $request->get('sendgridMailFromAddress'),
                "MAIL_FROM_NAME" => '"' . $request->get('sendgridMailFromName') . '"',
                "SHARETHIS_API" => '"' . $request->get('share_this_api') . '"',
                "SHARETHIS_ACTIVATE" => '"' . $request->get('share_this_api_activate') . '"',
            ];

            if (count($values) > 0) {
                foreach ($values as $envKey => $envValue) {

                    $str .= "\n"; // In case the searched variable is in the last line without \n
                    $keyPosition = strpos($str, "{$envKey}=");
                    $endOfLinePosition = strpos($str, "\n", $keyPosition);
                    $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                    // If key does not exist, add it
                    if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                        $str .= "{$envKey}={$envValue}\n";
                    } else {
                        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                    }
                }
            }

            $str = substr($str, 0, -1);
            if (!file_put_contents($envFile, $str)) {
                return false;
            }
            // $this->clearCache();
            Session::flash('message', 'Credential store successfully!');
            Session::flash('status', 'success');
            return redirect('messagesSetting');
        } catch (\Throwable $th) {
            Session::flash('message', $th->getMessage());
            Session::flash('status', 'error');
            return redirect('messagesSetting');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
