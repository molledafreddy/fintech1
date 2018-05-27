<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Banxico;
use Auth;

use Illuminate\Http\Request;

class BanxicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.banxico.index'); 
    }

    public function list()
    {
    	Log::info("ingreso al controlador BanxicoController@list: ".Auth::user()->name);
        return Banxico::search(request()->search)->orderBy('id', 'DESC')->paginate();
    }

    public function store()
    {
        try {
            
            DB::beginTransaction();
        	Log::info("ingreso al controlador BanxicoController@store: ".Auth::user()->name);

             $v = \Validator::make(request()->all(), [            
                'number' => 'required|numeric',
                'city' => 'required',
                'status'    => 'required',
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

            $banxico = Banxico::create(request()->all());
            $banxico->save();
            
            DB::commit();
       } catch (\Exception $e) {
            DB::rollBack();           
            Log::error('Ah ocurrido un error en BanxicoController@store: ' . $e );
            return ['message'=> 'Ah ocurrido un error al realizar la operación de registro'];
        }

        return ['message' => 'El registro se realizo con exito', 'status'=>1];
    }

    public function show()
    {
        return view('admin.banxico.show') -> with(['id' => request() -> id]);
    }

    public function update($id)
    {
    	try {
            Log::info("update banxico: ".request()->status);

            $v = \Validator::make(request()->all(), [            
                'number' => 'required|numeric',
                'city' => 'required',
                'status'    => 'required',
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

            $banxico = Banxico::find($id);

            $banxico->fill(request()->all());

            $banxico->save();
            
            return ['message' => 'La actualizacion se realizo con exito', 'status'=>1];
            
       } catch (\Exception $e) {
            Log::error('Ah ocurrido un error en BnaxicoController@update: ' . $e );            
            return ['message'=> 'Ah ocurrido un error al realizar la operación de registro'];
        }
    }

    public function getData()
    {
       return Banxico::FindOrFail(request()->id);
    }

    public function delete()
    {
    	try {
    		DB::beginTransaction();
    		Log::info("delete banxico: ".request()->number);

        	Banxico::destroy(request()->id);
    		DB::commit();   
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Ah ocurrido un error en BnaxicoController@delete: ' . $e );            
            return ['message'=> 'Ah ocurrido un error al eliminar'];
        }

        return ['message' => 'Se ha eliminado con exito'];
    }
}
