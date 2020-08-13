<?php

namespace App\Http\Controllers;

use App\Flower;
use App\Mail\MessageMail;
use App\Mesaj;
use Error;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class MessagesController extends Controller
{

    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    //this method sends messages
    public function store(Request $request)
    {

        Mesaj::create($this->validateRequest());
        return response()->json('Success in message sending', 200);
    }
    public function sendemail(Request $request){

        $adresadetrimis=User::where('id',$request->get('receverid'))->first()->email;
        $data=[
         'dela'=>User::where('id',$request->get('senderid'))->first()->email,
        'numesender'=>User::where('id',$request->get('senderid'))->first()->name,
            'subiect'=>$request->get('subiect'),
            'mesaj'=>$request->get('message'),
        ];

        try {
            \Illuminate\Support\Facades\Mail::to($adresadetrimis)->send(new \App\Mail\MessageMail($data));

        } catch (Error $error) {
            echo $error;
            return response()->json($error, 200);
        }
        return response()->json('Success in email sending', 200);
    }

    private function validateRequest()
    {
        return request()->validate([
            'senderid' => 'required',
            'receverid' => 'required',
            'subiect' => 'sometimes',
            'message' => 'sometimes',
        ]);
    }

    public function show(Mesaj $m)
    {

        try {
            $id = \request()->user()->id;
            $mesaje = Mesaj::where('receverid', $id . "")->get();
        } catch (RequestException $e) {
            $mesaje = [
                'senderid' => '',
                'subiect' => '',
                'receverid' => '',
                'message' => 'n-am mesaj'];
        }
        //echo dd($mesaje['senderid']);
        return response()->json($mesaje, 200);
    }

}
