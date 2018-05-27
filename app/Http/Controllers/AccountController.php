<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use App\Account;
use App\Bank;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.account.index'); 
    }

    public function list()
    {
        Log::info("controlller account list: ");
        return [
        	'accounts' 	=> Account::with('bank')->orderBy('id', 'DESC')->paginate(),
        	'banks' 	=> Bank::all()
        ];
    }

    public function store()
    {
        try {
            Log::info('ingreso al meetodo AccountController@store'.request()->number);
            
            $v = \Validator::make(request()->all(), [            
                'number' => 'required|numeric',
                'daily_amount' => 'required|numeric',
                'status'    => 'required',
                'bank_id' => 'required|integer|exists:banks,id',
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
            
            $account = Account::create(request()->all());
            $account->save();

            return ['message' => 'El registro se realizo con exito', 'status' => 1];
            
        }catch(\Exception $e){
            Log::error("Ha ocurrido un error al intentar generar el archivo [$e]");
            return response()->json(['status', 'Ocurrio un error al generar el archivo de transferencia a otros bancos'], 500);
        }
    }

    public function update($id)
    {
        try {
            Log::info('ingreso al meetodo AccountController@update '.request()->number);
                // return request()->number;
            $v = \Validator::make(request()->all(), [            
                'number' => 'required|numeric',
                'daily_amount' => 'required|numeric',
                'status'    => 'required',
                'bank_id' => 'required|integer|exists:banks,id',
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
            
            $account = Account::find($id);

            $account->fill(request()->all());

            $account->save();
        
            return ['message' => 'La actualizacion se realizo con exito', 'status'=>1];
        
        }catch(\Exception $e){
            Log::error("Ha ocurrido un error al intentar generar el archivo [$e]");
            return response()->json(['status', 'Ocurrio un error al generar el archivo de transferencia a otros bancos'], 500);
        }
    }

    public function show()
    {
        return view('admin.account.show') -> with(['id' => request() -> id]);
    }

    public function getData()
    {
       return Account::FindOrFail(request()->id);
    }

    public function delete()
    {
        Account::destroy(request()->id);

        return ['message' => 'Se ha eliminado con exito'];
    }
}
