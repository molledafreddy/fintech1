<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Mail\ContactFintech;
use App\Mail\ContactCustomer;
use Auth;
use Mail;

class ContactController extends Controller
{
    public function index()
    {
        Log::info("ingreso al controlador ContactController@index: ".Auth::user()->name);
        return view('admin.contact.index'); 
    }

    public function store()
    {
        Log::info("ingreso al controlador ContactController@store: ".Auth::user()->name);
        
        try {
            $v = \Validator::make(request()->all(), [            
                'subject' => 'required',
                'content' => 'required',
            ]);

            $errors = $v->errors();
            $message=[];

            foreach ($errors->all() as  $mess) {
                $message[]=$mess.'  ';
            }

            if ($v->fails())
            {
                return ['message' => $message , 'status' => 0];
            }

            $user = User::with('customers')->where('id', Auth::id())->first();

            Mail::to("admin@fintech.com")->send(new ContactFintech($user, request()->subject, request()->content));
            Mail::to($user->email)->send(new ContactCustomer($user, request()->subject, request()->content));
            
        } catch (\Exception $e) {
            Log::error('Ah ocurrido un error en ContactController@store: ' . $e );
            return ['message'=> 'Ah ocurrido un error al realizar la operación de envio de correo', 'status'=> 0];
        }

        return ['message' => 'Su correo fue enviado de forma satisfactoria al personal de FINTECH', 'status'=> 1];

    }
}
