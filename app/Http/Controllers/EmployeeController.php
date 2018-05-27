<?php
// codigo de freddy
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Bank;
use App\Customer;
use App\CustomerUser;
use App\Http\Assets\ResourceFunctions;
use Illuminate\Support\MessageBag;
use Log;
use Excel;
use DB;
use Auth;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('employee.index');
    }

    public function terms()
    {
        return view('terms.index');
    }

    public function list()
    { 
        Log::info("ingreso al controlador EmployeeController@index: ".Auth::user()->name.'  '.request()->customer_search);
        
        $type = '';
        $customer_search = request()->customer_search;

        $customer_id = CustomerUser::where('customer_id', Auth::id())->pluck('customer_id');
        //todas las empresas para cargas el combo de carga masiva
        $customers = Customer::all();
        // busca el id, de las empresas donde id_admin sea igual al usuario logueado
        $customer_user = CustomerUser::whereIn('customer_id', $customer_id)->pluck('user_id');// devuelve todos los usuarios que pertenecen a esas empresas del usuario loqueado
        
        $users = User::with('customerUser') // devuelve los datos de la relación  with customer_user
         ->with('banks') // devuelve los datos de la relación  with banks
         ->search( '' , $customer_search ) // llamado al scope employeesearch dentro modelo user
         ->where('deleted', false) // deleted false
         // ->whereIn('id', $customer_user) // comparando id table user con todo los id de las tabla customer user
         ->orderBy('id', 'desc')
         ->paginate();

         if (Auth::user()->role == 'empresa') {
             $type = 'empresa';
         }

        $banks = Bank::all();

        return [
          'users' =>  $users,
          'banks' =>  $banks,
          'customers' =>  $customers,
          'type'  =>  $type
        ]; 
    }

    public function store()
    {
        try {
            Log::info("ingreso al controlador EmployeeController@store: ".Auth::user()->name);
             // DB::beginTransaction();

            $v = \Validator::make(request()->all(), [            
                'rfc' => 'required',
                'name' => 'required',
                'nacionality' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'bank_id' => 'required|integer|exists:banks,id',
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
                'password' => bcrypt(request()->password),
                'role' => "empleado",
                'status' => false,
            ]);

            $customer = Customer::where('admin_id', auth()->user()->id)->first();

            $customeruser = CustomerUser::create([
                'customer_id' => $customer->id,
                'user_id' => $user->id,
                'bank_id' => request()->bank_id,
                'phone' => request()->phone,
                'acconunt_number' => request()->acconunt_number,
                'acconunt_clabe' => request()->acconunt_clabe,
                'biweekly_salary' => request()->biweekly_salary,
                'status' => 'pending'

            ]);

            $customeruser->save();

            return ['message' => 'El empleado se ha creado con exito', 'status'=>1];
         }catch(\Exception $e){
            // DB::rollBack();
            Log::error("Ha ocurrido un error al intentar guardar el registro BankController@store [$e]");
            return response()->json(['status', 'Ha ocurrido un error al intentar guardar el registro'], 500);
        }
    }

    public function update($id)
    {
        try {
            Log::info("ingreso al controlador EmployeeController@update: ".Auth::user()->name);

            $v = \Validator::make(request()->all(), [            
                'rfc' => 'required',
                'name' => 'required',
                'nacionality' => 'required',
                'email' => 'required|email',
                'bank_id' => 'required|integer|exists:banks,id',
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
                'bank_id' => request()->bank_id,
                'phone' => request()->phone,
                'acconunt_number' => request()->acconunt_number,
                'acconunt_clabe' => request()->acconunt_clabe,
                'biweekly_salary' => request()->biweekly_salary,     
            ]);

            $customer_user -> save();
            
            return ['message' => 'El empleado se ha actualizada con exito','status'=> 1];
        }catch(\Exception $e){
            Log::error("Ha ocurrido un error al intentar actualizar el registro EmployeeController@update [$e]");
            return response()->json(['status', 'Ha ocurrido un error al intentar actualizar el registro'], 500);
        }
    }

    public function show()
    {
        Log::info("ingreso al controlador EmployeeController@show: ".Auth::user()->name);

        return view('admin.user.show') -> with(['id' => request() -> id]);
    }

    public function getData()
    {
       return User::FindOrFail(request()->id);
    }
     
    public function delete()
    {
        Log::info("ingreso al controlador EmployeeController@delete: ".Auth::user()->name);
        $user = User::find(request()->id); 
        $user -> deleted = true; 
        $user -> save();

        return ['message' => 'El empleado se ha eliminado con exito'];
    }

   /**
    * [validateTerms description: method that allows to validate the terms and conditions]
    * @param  [type] $id [description: user identifier that validates the terms and conditions]
    * @return [type]     [description: message that denotes what the result was]
    */
   protected function validateTerms($id)
   {
        Log::info("EmployeeController actualizar estatus terminos y condiciones updateTerms: ");
        try {

            $user = User::FindOrFail($id)
            ->update([
                'status'=> true
            ]);
            
            if ($user==1) {

            return['message'=> 'El proceso se relizo con exíto',
                    'status' => 1
            ];
            }else{
                return[
                    'message'=> 'El proceso no se realizo, usuario no encontrado',
                    'status' => 0
                ];
            }
            
            }catch(\Exception $e){
                Log::error("Ha ocurrido un error al intentar actualizar el estatus de aceptacion de terminos [$e]");
                return response()->json(['status', 'Error al actulizar el estatus de aceptacion de terminos'], 500);
            }
   }

    // importar archivos por lotes
     public function fileImport(Request $request)
    {
        Log::debug('ingreso al controlador employeeController@fileImport');
        
        if ( ( request()->type =='' )) {
            $v = \Validator::make(request()->all(), [            
                'file' => 'required',
                'customer_id' => 'required|integer|exists:customers,id',
            ]);            
        }else{
            $v = \Validator::make(request()->all(), [            
                'file' => 'required',
            ]);
        }

        $errors = $v->errors();
        $message=[];

        foreach ($errors->all() as  $mess) {
            $message[]=$mess.'  ';
        }

        if ($v->fails())
        {
            return ['message' => $message , 'status' => 0];
        }
        $exploded = explode(',', request()->file);
        
        $decoded = base64_decode($exploded[1]);


        $fileName = "usuarios.xlsx";

        $folder = public_path() . "/storage/users/" ;
           
        if( is_dir($folder) == false )
        {
                   
            mkdir($folder, 0777, true);
        }
               
        $path = $folder . $fileName;

        //Storage::delete($fileName);
        file_put_contents($path, $decoded);
         
        $data=Excel::load($path)->get();
        /*
        *Indices that validate the file header
        */
        $keys=[
            0=> "rfc", 
            1=> "name", 
            2=> "nacionality",
            3=> "email",
            4=> "bank",
            5=> "phone",
            6=> "biweekly_salary",
            7=> "acconunt_number",
            8=> "acconunt_clabe",

        ];
        
            if ((empty($data->toArray()) ) || (count($data->toArray())==0)) {
                 return [
                        'message' => 'El archivo que cargo no posee datos',
                        'error' => true,
                    ];
   
            }else if ( ResourceFunctions::validateHeadboard($keys, ResourceFunctions::headFile($data->toArray()) )==false ) {
                /*
                *Validate that the file header is correct
                */
               Log::debug('ingreso a la validacion de validateHeabboard');
               return [
                        'message' => 'La estructura del archivo no es la correcta',
                        'error' => true,
                    ];
                
            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'rfc') )==false) {
                /*
                *Validate that the rfc column is not void
                */
                return [
                        'message' => 'La columna RFC no debe tener campos vacios',
                        'error' => true,
                ];

            }else if ( ResourceFunctions::validateDuplicateValue( array_column($data->toArray(), 'rfc') )  ) {
                /*
                *Validate that there are no duplicate values in the column
                */  
                return [ 
                    'message' => 'En el archivo existen RFC duplicados',
                    'error' => true,
                 ];


                
            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'phone') )==false) {
                /*
                *Validate that the PHONE column is not void
                */ 
                 return[ 
                    'message' => 'La columna PHONE no debe tener campos vacios',
                    'error' => true,
                ];
                
            }else if ( ResourceFunctions::validateDuplicateValue( array_column($data->toArray(), 'phone')  )  ) {
                /*
                *Validate that there are no duplicate values in the column
                */   
                return[ 
                    'message' =>'En el archivo existen Phone duplicados',
                    'error' => true,
                ];
                
            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'acconunt_number') )==false) {

                /*
                *Validate that the number column is not void
                */
                return[ 

                    'message' =>'La columna account_number no debe tener campos vacios',
                    'error' => true,
                ];

            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'biweekly_salary') )==false) {
                /*
                *Validate that the biweekly_salary column is not void
                */
                return[ 
                    'message' =>'La columna salario quincenal no debe tener campos vacios',
                    'error' => true,
                ];

            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'bank') )==false) {
                /*
                *Validate that the biweekly_salary column is not void
                */
                 return[ 
                    'message' =>'La columna banco no debe tener campos vacios',
                    'error' => true,
                ];
                
            }else if (ResourceFunctions::validateColumnNumeric( array_column($data->toArray(), 'biweekly_salary') ) ) {
                /*
                *Validate that the biweekly_salary column is just users
                */
                return[ 
                    'message' =>'La columna salario quincenal solo debe poseer valores numericos',
                    'error' => true,
                ];

            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'acconunt_clabe') )==false) {
                /*
                *Validate that the biweekly_salary column is not void
                */
                ResourceFunctions::messageError('UserController',"biweekly_salary"," La columna account_clabe no debe tener campos vacios");

                return[ 
                    'message' =>' La columna cuenta clabe no debe tener campos vacios',
                    'error' => true,
            ];

            }else if (ResourceFunctions::validateColumnNumeric( array_column($data->toArray(), 'acconunt_clabe') ) ) {

                return[ 
                    'message' =>'La columna account_clabe no es númerica',
                    'error' => true,
                ];

            }else if (ResourceFunctions::validateColumnVoid( array_column($data->toArray(), 'acconunt_clabe') )==false) {
                return[ 
                    'message' =>'La columna cuenta clabe solo debe poseer campos vacios',
                    'error' => true,
                ];
                                
            }else if (ResourceFunctions::validateAccount( array_column($data->toArray(), 'acconunt_number'), 5, 15 ) ) {
                /*
                *Validate that the biweekly_salary column is just users
                */
                return[ 
                    'message' =>' Los registros de la columna numero de cuenta debe tener una cifra entre 5 y 15 dígitos',
                    'error' => true,
                ];             

            }else{

                $contenedor_users=[];
                /*
                *query the users 
                */
                $users = User::get()->toArray();
               
                /*
                *Consultation of venues the members related to the club
                */
                $customer = CustomerUser::select('customer_id')
                                        ->where('user_id', Auth::user()->id)
                                        ->first();

                $customer_users = CustomerUser::with(['user'])
                    ->where('customer_users.customer_id', $customer->customer_id)
                    ->get()->toArray();
                
                foreach ($customer_users as $value) {
                        $customer_users [] = $value['user'];                                                                          
                }
                /*
                *querry of phones
                */
                $phones = CustomerUser::select('phone')->get()->toArray();
                /*
                *querry of banks
                */
                $banks = Bank::select('transfer_key')->get()->toArray();
                /*
                *Initialize the arrangements in case the query does not bring any value
                */
                if (empty($users)) {
                    $users =[];
                    
                }

                if (empty($customer_users)) {
                    $customer_users=[];                    
                }
                $iteration = 0;
                $container=0;
                Log::info("Antes del primer Foreach");
                foreach ($data as $key => $valueData) 
                {
                    //Allows to validate if the user is registered in the database
                    if ( in_array($valueData->rfc, array_column($users, 'rfc') ) ) 
                    {
                        
                     Log::debug('esta en el primer if');
                        if ( in_array($valueData->rfc, array_column($customer_users, 'rfc') ) ) 
                        {
  
                            //Saves users who are already related to the customer exist in the db
                          
                            $contenedor_users[] = [
                                'rfc'               => $valueData->rfc,
                                'name'              => $valueData->name,
                                'nacionality'       => $valueData->nacionality,
                                'email'             => $valueData->email,
                                'bank'              => $valueData->bank,
                                'phone'             => $valueData->phone,
                                'biweekly_salary'   => $valueData->biweekly_salary,
                                'acconunt_number'    => $valueData->acconunt_number,
                                'acconunt_clabe'     => $valueData->acconunt_clabe,
                                'line'              => $iteration,
                                'status'            => 'Usuario ya existe'
                            ];
                            $container=1;
                            
                           
                        }else if( !(in_array($valueData->bank, array_column($banks, 'transfer_key') )) )
                        {
                            /*
                            *Saves users who have an unsigned site code in the DB
                            */
                            $contenedor_users[] = [
                                'rfc'               => $valueData->rfc,
                                'name'              => $valueData->name,
                                'nacionality'       => $valueData->nacionality,
                                'email'             => $valueData->email,
                                'bank'              => $valueData->bank,
                                'phone'             => $valueData->phone,
                                'biweekly_salary'   => $valueData->biweekly_salary,
                                'acconunt_number'    => $valueData->acconunt_number,
                                'acconunt_clabe'     => $valueData->acconunt_clabe,
                                'line'              => $iteration,
                                'status'            => 'La llave de transferencia no esta relaionada a ningun banco'
                            ];
                            $container=1;
                           
                        }else if ( in_array($valueData->phone, array_column($phones, 'phone') ) ){
                            /*
                            *Saves users who have an unsigned site code in the DB
                            */
                            $contenedor_users[] = [
                                'rfc'               => $valueData->rfc,
                                'name'              => $valueData->name,
                                'nacionality'       => $valueData->nacionality,
                                'email'             => $valueData->email,
                                'bank'              => $valueData->bank,
                                'phone'             => $valueData->phone,
                                'biweekly_salary'   => $valueData->biweekly_salary,
                                'acconunt_number'    => $valueData->acconunt_number,
                                'acconunt_clabe'     => $valueData->acconunt_clabe,
                                'line'              => $iteration,
                                'status'            => 'El telefono ya existe en la BD'
                            ];
                            $container=1;

                                        
                            
                        }else{

                            /*
                            *Saves users who have an unsigned site code in the DB
                            */
                           Log::debug('esta el el ultimo else');
                            $contenedor_users[] = [
                                'rfc'               => $valueData->rfc,
                                'name'              => $valueData->name,
                                'nacionality'       => $valueData->nacionality,
                                'email'             => $valueData->email,
                                'bank'              => $valueData->bank,
                                'phone'             => $valueData->phone,
                                'biweekly_salary'   => $valueData->biweekly_salary,
                                'acconunt_number'    => $valueData->acconunt_number,
                                'acconunt_clabe'     => $valueData->acconunt_clabe,
                                'line'              => $iteration,
                                'status'            => 'Listo para relacionar a la empresa'
                            ];
                            
                        } 
                    
                    }else if ( in_array($valueData->phone, array_column($phones, 'phone') ) )
                    {
                        /*
                        *Saves users who have an unsigned site code in the DB
                        */
                        $contenedor_users[] = [
                            'rfc'               => $valueData->rfc,
                            'name'              => $valueData->name,
                            'nacionality'       => $valueData->nacionality,
                            'email'             => $valueData->email,
                            'bank'              => $valueData->bank,
                            'phone'             => $valueData->phone,
                            'biweekly_salary'   => $valueData->biweekly_salary,
                            'acconunt_number'    => $valueData->acconunt_number,
                            'acconunt_clabe'     => $valueData->acconunt_clabe,
                            'line'              => $iteration,
                            'status'            => 'El telefono ya existe en la base de datos'
                        ];
                        $container=1;

                    }else if ( !in_array($valueData->bank, array_column($banks, 'transfer_key') ) )
                    {
                        /*
                        *Saves users who have an unsigned site code in the DB
                        */
                        $contenedor_users[] = [
                            'rfc'               => $valueData->rfc,
                            'name'              => $valueData->name,
                            'nacionality'       => $valueData->nacionality,
                            'email'             => $valueData->email,
                            'bank'              => $valueData->bank,
                            'phone'             => $valueData->phone,
                            'biweekly_salary'   => $valueData->biweekly_salary,
                            'acconunt_number'    => $valueData->acconunt_number,
                            'acconunt_clabe'     => $valueData->acconunt_clabe,
                            'line'              => $iteration,
                            'status'            => 'La llave de transferencia no esta relaionada a ningun banco'
                        ];
                        //return ['message' => 'El empleado se ha creado con exito'];
                        $container=1;
                         
                    }else{

                        /*
                        *Saves users who have an unsigned site code in the DB
                        */
                        $contenedor_users[] = [
                            'rfc'               => $valueData->rfc,
                            'name'              => $valueData->name,
                            'nacionality'       => $valueData->nacionality,
                            'email'             => $valueData->email,
                            'bank'              => $valueData->bank,
                            'phone'             => $valueData->phone,
                            'biweekly_salary'   => $valueData->biweekly_salary,
                            'acconunt_number'    => $valueData->acconunt_number,
                            'acconunt_clabe'     => $valueData->acconunt_clabe,
                            'line'              => $iteration,
                            'status'            => 'Usuario listo para registrar'
                        ];

                    }

                    $iteration++;
                }//close foreach

                if ($container==1) {
                    return [  
                        'message' => 'La operacion no se realizo',
                        'customer_users' => $contenedor_users,
                    ];                
                }
            
                if (Auth::user()->role == 'empresa') {
                    $c = Customer::where('admin_id',Auth::user()->id)->first();
                    $customer = $c->id;
                }else{
                    $customer= request()->customer_id;
                }
            
                foreach ($contenedor_users as $value) {

                    if ($value['status'] == 'Usuario listo para registrar') {
                        
                        try {
                        
                            DB::beginTransaction();
                            $pass = substr( md5(microtime()), 1, 8);
                            $bank = Bank::where('transfer_key',$value['bank'])->first();
                            /*
                            *querry  the id table user
                            */
                            $user = User::create([
                                'rfc'           => $value['rfc'],
                                'name'          => $value['name'],
                                'nacionality'   => $value['nacionality'],
                                'email'         => $value['email'],
                                'password'      => $pass,
                                'role'          => 'empleado',
                                'status'        => false,

                            ]);
                            
                            CustomerUser::create([
                                'customer_id'       => $customer,
                                'user_id'           => $user->id,
                                'bank_id'           => $bank->id,
                                'phone'             => $value['phone'],
                                'biweekly_salary'   => $value['biweekly_salary'],
                                'acconunt_number'   => $value['acconunt_number'],
                                'acconunt_clabe'    => $value['acconunt_clabe'],
                                'status'            => 'pending',
                            ]);

                            DB::commit();   
                        } catch (Exception $e) {
                            DB::rollBack();
                        }
                        
                    }else if($value['status'] == 'Listo para relacionar a la empresa'){
                        
                        try {
                            DB::beginTransaction();
                            $user = User::where('rfc',$value['rfc'])->first();
                            $bank = Bank::where('transfer_key',$value['bank'])->first();
                
                            CustomerUser::create([
                                'customer_id'       => $customer,
                                'user_id'           => $user->id,
                                'bank_id'           => $bank->id,
                                'phone'             => $value['phone'],
                                'biweekly_salary'   => $value['biweekly_salary'],
                                'acconunt_number'    => $value['acconunt_number'],
                                'acconunt_clabe'    => $value['acconunt_clabe'],
                            ]);  

                        DB::commit();   
                        } catch (Exception $e) {
                            DB::rollBack();
                        }
                    }
                    
                }//close foreach

                return [  
                    'message' => 'La operacion de registro se realizo',
                    'customer_users' => $contenedor_users,
                    'status'=> 1
                ];
        }//close else
    
       return true;        
    }//close methods import

}
