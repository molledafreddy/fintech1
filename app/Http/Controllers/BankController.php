<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use App\Bank;

class BankController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.bank.index'); 
    }

    public function list()
    {
        Log::info("listo los bancos: ");
        return Bank::search(request()->search)->orderBy('id', 'DESC')->paginate();
    }

    public function store()
    {
        

        try {
           
            $v = \Validator::make(request()->all(), [            
                'name' => 'required',
                'number' => 'required|numeric',
                'transfer_key'    => 'required',
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

            $bank = Bank::create(request()->all());
            $bank->save();

            return ['message' => 'El registro se realizo con exito', 'status' => 1];
        }catch(\Exception $e){
            Log::error("Ha ocurrido un error al intentar guardar el registro BankController@store [$e]");
            return response()->json(['status', 'Ha ocurrido un error al intentar guardar el registro'], 500);
        }
    }

    public function update($id)
    {
                
        try {
            Log::info('ingreso al controlador BankController@update');
            
            $v = \Validator::make(request()->all(), [            
                'name' => 'required',
                'number' => 'required|numeric',
                'transfer_key'    => 'required',
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
            $bank = Bank::find($id);

            $bank->fill(request()->all());

            $bank->save();
         
            return ['message' => 'La actualizacion se realizo con exito', 'status' => 1];
        
        }catch(\Exception $e){
            Log::error("Ha ocurrido un error al intentar actualizar el registro del banco [$e]");
            return response()->json(['status', 'Ha ocurrido un error al intentar actualizar el registro del banco'], 500);
        }
        
    }

    public function show()
    {
        return view('admin.bank.show') -> with(['id' => request() -> id]);
    }

    public function getData()
    {
       return Bank::FindOrFail(request()->id);
    }

    public function delete()
    {
        Bank::destroy(request()->id);

        return ['message' => 'Se ha eliminado con exito'];
    }

}
