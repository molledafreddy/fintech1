<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\User;
use App\Bank;
use App\File;
use App\Customer;
use App\CustomerUser;
use App\Account;
use DB;
use Mail;


class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('admin.user.index'); 
	}

	public function list()
	{ 
		$target = request()->search;
	    $customer_id = request()->customer_id;
	    Log::info('Dentro del controlador: ' . $customer_id, request()->all());
        
	    $user = User::with('customerUser')
	     ->with('banks')
	     ->with('customers')
	     ->search( $target, $customer_id )
	     ->orderBy('users.id', 'desc')
	     ->paginate();

	     $banks = Bank::all();
         $accounts = Account::with('bank')->get();
         // $customers = Customer::all();
         $files = File::where('status','no_processed')->orderBy('created_at', 'desc')->get();

	     $customers = Customer::where('status','verificado')->get();

		return [
		  'users' =>  $user,
		  'banks' =>  $banks,
		  'customers' => $customers,
          'files' => $files,
          'accounts' => $accounts,

		]; 
	}

	public function store()
	{
		try { // EVALUA LA TRANSACCION, si ninguna de los no son los correctos no hace la transaccion
			Log::info('ingreso al controlador UserController@store');
			// DB::beginTransaction(); // comienza una transaccion desde aqui

			$v = \Validator::make(request()->all(), [            
                'rfc' => 'required',
                'name' => 'required',
                'nacionality' => 'required',
                'email' => 'required|email',
                'status'    => 'required',
                'role'    => 'required',
                'bank_id' => 'required|integer|exists:banks,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'phone'    => 'required',
                'acconunt_number'    => 'required|numeric',
                'acconunt_clabe'    => 'required|numeric',
                'biweekly_salary'    => 'required|numeric',
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

			$user = User::create([
				'rfc' => request()->rfc,
				'name' => request()->name,
				'nacionality' => request()->nacionality,
				'email' => request()->email,
				'password' => bcrypt('admin1234'),
				'role' => request()->role,
			]);

			$user->save();

			$customeruser = CustomerUser::create([
				'customer_id' => request()->customer_id,
				'user_id' => $user->id,
				'bank_id' => request()->bank_id,
				'phone' => request()->phone,
				'acconunt_number' => request()->acconunt_number,
				'acconunt_clabe' => request()->acconunt_clabe,
	            'biweekly_salary' => request()->biweekly_salary,       
			]);

			$customeruser->save();

	        $customer = Customer::FindOrFail(request()->customer_id);

	        $customer->update([
	            'admin_id' => $user->id
	        ]);

			
			$data = [
	               'name' => $user->name,
	               'customer'=> $customer -> name,
	           ];
			
	        $token = app('auth.password.broker')->createToken($user); 

			Mail::to($user->email)->send(new \App\Mail\NewCustomerAdmin($data, $token));
			// envia a este correo, el cual se le envia al usuario para que pueda acceder
			// la funcion to(), le indica al modelo Mail, a que correo debe enviar el mail

			return ['message' => 'El usuario se ha creado con exito', 'status'=>1];

			// DB::commit(); // si todo funciono correctamente guardame en base de datos
		} catch (\Exception $e) {
			Log::error("Ocurrio un error: [$e]");// esto sirve para mostrar los errores en el archivo de logs
			// DB::rollback();// si detecta un error, no guarde en base de datos, el rollback echa para atras el proceso	
		}

		return ['message' => 'Hubo un error al registrar usuario'];

		
	}

	public function update($id)
	{
        try {
        	Log::info('ingreso al controlador UserController@update');
        	$v = \Validator::make(request()->all(), [            
                'rfc' => 'required',
                'name' => 'required',
                'nacionality' => 'required',
                'email' => 'required|email',
                'status'    => 'required',
                'role'    => 'required',
                'bank_id' => 'required|integer|exists:banks,id',
                'customer_id' => 'required|integer|exists:customers,id',
                'phone'    => 'required',
                'acconunt_number'    => 'required|numeric',
                'acconunt_clabe'    => 'required|numeric',
                'biweekly_salary'    => 'required|numeric',
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
            
    		$user = User::find($id);

    		$user->fill(request()->all());

    		$user->save();

    		$customer_user = CustomerUser::where('user_id', $id)->first();

    		$customer_user->update([
    		   'customer_id' => request()->customer_id,
    			'user_id' => $id,
    			'bank_id' => request()->bank_id,
    			'phone' => request()->phone,
    			'acconunt_number' => request()->acconunt_number,
    			'acconunt_clabe' => request()->acconunt_clabe,
                'biweekly_salary' => request()->biweekly_salary,     
    		]);

    		$customer_user -> save();

            $customer = Customer::FindOrFail(request()->customer_id);

            $customer->update([
                'admin_id' => $id
            ]);
    		
    		return ['message' => 'El usuario se ha actualizada con exito', 'status'=>1];
        
        } catch (\Exception $e) {
            Log::error("Ocurrio un error: [$e]");// esto sirve para mostrar los errores en el archivo de logs
            DB::rollback();// si detecta un error, no guarde en base de datos, el rollback echa para atras el proceso   
        }
	}

	
	public function getData()
	{
	   return User::FindOrFail(request()->id);
	}
	 
	public function delete()
	{
		$user = User::find(request()->id); 
		$user -> deleted = true; 
		$user -> save();

		return ['message' => 'El usuario se ha eliminado con exito'];
	}
}

