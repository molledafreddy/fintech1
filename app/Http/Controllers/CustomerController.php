<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\User;
use App\CustomerUser;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Mail\NewCustomer;
use Mail;
use Illuminate\Support\MessageBag;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.customer.index'); 
    }


    public function list()
    {   
        return Customer::search(request()->search, request() ->status )->paginate();
    }

    public function create()
    {
        return view('customer.index');
    }

    public function store()
    {
        try {
            Log::info('ingreso al controlador CustomerController@store ');
            
            $v = \Validator::make(request()->all(), [            
                'name' => 'required',
                'rfc' => 'required',
                'address'    => 'required',
                'cp_email' => 'required|email',
                'cp_first_name'=> 'required',
                'cp_last_name'=> 'required',
                'cp_phone'=> 'required',
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

            request() -> request -> add(['ative' => false]);
            $customer = Customer::create(request()->all());
            
            if (request()->register && $customer) {
                $data = [
                    'name' => $customer->name,
                    'rfc' => $customer->rfc,
                    'address' => $customer->address,
                    'cp_first_name' => $customer ->cp_first_name,
                    'cp_last_name' => $customer ->cp_last_name,
                    'cp_email' => $customer->cp_email,
                    'cp_phone' => $customer->cp_phone,
                ];

                Mail::to("admin@fintech.com")->send(new NewCustomer($customer));
                Mail::to($customer->cp_email)->send(new \App\Mail\CustomerNotification($customer));

                $data = nulL;
                return ['message' => 'La empresa se ha creado con exito', 'status'=>1];
                
            }
            return 'hola';
        return ['message' => 'La empresa se ha creado con exito', 'status'=>1];
            
        }catch(\Exception $e){
            Log::error("Ha ocurrido un error al intentar guardar el registro CustomerController@store [$e]");
            return response()->json(['status', 'Ha ocurrido un error al intentar guardar el registro'], 500);
        }


    }

     
    // carga el vector de usuarios que pueden ser adminsitradores
    public function getUsers()
    {
        try {
            $users=null;
            
            $customers_users = CustomerUser::where('customer_id', request()->id)->first();

            if ($customers_users->user_id != null) {
                $users = User::where('id', $customers_users->user_id)->first();
                
                return $users;
                
            }

            
        } catch (Exception $e) {
          return response()->json(['status', 'No esta relacionada a ningun usuario'], 500);  
        }
    }

    public function update($id)
    {
        try {
            
            Log::info('ingreso al controlador CustomerController@update');
            
            $v = \Validator::make(request()->all(), [
            
                'name' => 'required',
                'rfc' => 'required',
                'address'    => 'required',
                'cp_first_name'=> 'required',
                'cp_last_name'=> 'required',
                'cp_phone'=> 'required',
                'cp_email' => 'required|string|email|max:255|unique:customers,cp_email,'.$id,

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

            $customer = Customer::find($id);

            $customer->fill(request()->all());

            $customer->save();

            if( request()->status=='verificado'){

                $data = [
                    'name' => $customer->name,
                    'rfc' => $customer->rfc,
                    'address' => $customer->address,
                    'cp_first_name' => $customer ->cp_first_name,
                    'cp_last_name' => $customer ->cp_last_name,
                    'cp_email' => $customer->cp_email,
                    'cp_phone' => $customer->cp_phone,
                ];

                Mail::to($customer->cp_email)->send(new \App\Mail\CustomerVerificated($data));

                $data = nulL;

            }
            
            return ['message' => 'La empresa se ha actualizada con exito', 'status'=>1];
            
        } catch (Exception $e) {
            
        }
    }

    public function show()
    {
        return view('admin.customer.show') -> with(['id' => request() -> id]);
    }

    public function getData()
    {
       return Customer::FindOrFail(request()->id);
    }

    public function delete()
    {
        $customer = Customer::find(request()->id); 
        $customer -> deleted = true; 
        $customer -> save();

        return ['message' => 'La empresa se ha eliminado con exito'];
    }
}
