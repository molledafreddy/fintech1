<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bank;
use App\CustomerUser;
use App\Customer;
use App\Order;
use App\Credit;
use App\Account;
use App\File;   
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Http\Assets\ResourceFunctions;
use App\Mail\NewUserWelcome;
use flash;  
use Excel;
use Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\NewNofiticacionError;


class FileController extends Controller
{

    public function indexFileValidateAccount()
    {

        return view('admin.account-file.index'); 
    }

    public function listUser()
    { 
        $target = request()->search;
        $file = request()->file;
        $customer_id = request()->customer_id;
        Log::info('Dentro del controlador FileController:'.'target'. $target . $customer_id, request()->all());
        
        $user = CustomerUser::with('user')
         ->with('bank')
         ->with('customer')
         ->search( $target, $customer_id )
         ->file($file)
         ->orderBy('customer_users.user_id', 'desc')
         ->paginate();

         $banks = Bank::all();
         $accounts = Account::with('bank')->get();
         $files = File::where('status','no_processed')->orderBy('created_at', 'desc')->get();

         Log::info('Dentro del controlador FileController:'.'target'. $target . $customer_id, request()->all());

         $customers = Customer::where('status','verificado')->get();     
         
         //dd($user)   ;

        return [
          'users' =>  $user,
          'banks' =>  $banks,
          'customers' => $customers,
          'accounts' => $accounts,
          'files' => $files,

        ]; 
    }



    public function update($id)
    {
        Log::info("Actualizacion de estatus FileController update: ".request()->status);
        try {
            
            CustomerUser::FindOrFail($id)
                ->update([
                    'status'=> request()->status
                ]);
                
                if (request()->status == 'error') {
                    
                    $customerUser = CustomerUser::with(['customer','user'])->where('id',$id)->get();

                    Mail::to($customerUser[0]->customer->cp_email)
                        ->send(new \App\Mail\NewNotificacionError($customerUser));
                }

             return ['message' =>'El estatus se ha actualizado con exito'];
        } catch (\Exception $e) {
            Log::error("Ocurrio un error: [$e]");// esto sirve para mostrar los errores en el archivo de logs
        }
    }

/**
 * [fileValidateAccount description: method that allows you to generate csv file, to register the accounts]
 * @return [type] [description: file with cvs extension]
 */
    public function fileValidateAccount()
    {
        
        Log::info('Ingreso exitoso a FileController@fileValidteAccount,'.request()->type );

        if ( empty(request()->account_id) && empty(request()->bank_id) )  {
            return [
                    'message' => 'debe seleccionar una cuenta de origen y un banco de destino',
                    'status' => 0,
                    'fileName' => null
                ];
        }else if (request()->account_id != 1) {
            return [
                    'message' => 'Solo esta activa la cuenta del banco santander como cuenta de origen',
                    'status' => 0,
                    'fileName' => null
                ];
        }else if (request()->bank_id != 'all') {
            return [
                    'message' => 'Solo esta activa la opción de archivo para transferencias a todos los bancos',
                    'status' => 0,
                    'fileName' => null
                ];
        }
            
        //$bank_emisor = Account::where(['bank_id'=>request()->account,'status'=>'principal'])->first();
        
        $consult = CustomerUser::with('user')->with('bank')->where(['status' => 'pending'])->get();        
        
        if (count($consult)!=0) {
          
            try {
                
                DB::beginTransaction();

                $file_name = 'File_'.'_'.Auth::user()->name.time().'_'.mt_rand().'.txt';
                
                $file = fopen($file_name, "w");
                $folder = public_path() . "/storage/users/" ;
                
                if( is_dir($folder) == false )
                {                   
                    mkdir($folder, 0777, true);
                }

                $path = $folder . $file_name;
                
                $data_file = File::create([
                        'name'      => $file_name,
                        'status'    => 'no_processed',
                        'user_id'   => 1
                ]);
                $data_file->save();

                foreach ($consult as $key => $value) {

                    if ($value->bank_id == 1) {
                        file_put_contents(
                            $path,'SANTAN'.
                            ResourceFunctions::addSpaces(20, $value->acconunt_number, ' ','last').
                            ResourceFunctions::addSpaces(40, $value->user->name, ' ','last').
                            "\n", FILE_APPEND | LOCK_EX);
                    }else{
                        file_put_contents(
                            $path,'EXTRNA'.
                            ResourceFunctions::addSpaces(20, $value->acconunt_clabe, ' ','last').
                            ResourceFunctions::addSpaces(40, $value->user->name, ' ','last'). 
                            $value->bank->transfer_key.
                            '010010000140'.
                            "\n", FILE_APPEND | LOCK_EX);
                    }
                    
                    if (count($data_file) > 0) {
                        
                        $customer=CustomerUser::FindOrFail($value->id)
                        ->update([
                            'status'=> 'verifying',
                            'file_id' => $data_file->id                        
                        ]);                    
                    }                
                    
                }

                fclose($file);

                DB::commit();
                
                return [
                    'message' => 'El archivo se genero con exito',
                    'status' => 1,
                    'fileName' => $file_name
                ];
                
            } catch (Exception $e) {
                DB::rollBack();
            }
        
        }else{
            return[
                'message'=> 'NO hay cuentas pendientes para dar de alta',
                'status' => 0,
                'fileName' => null
            ];
            
        }
    }

/**
 * [imporFile description]
 * @return [type] [description: method that allows you to upload the files that allow you to register accounts ]
 */
public function imporFile()
{
        
        Log::info("cargar archivo para cambiar estatus a cuentas dadas de alta: ".Auth::user()->name );

        $customer_user = CustomerUser::with('user')->with('file')->with('customer')->where([
                                    'file_id' => request() -> loadFileId,
                                    'status'    => 'verifying'
                                    ])->get();
            Log::error("despues de la consulta customer_user ".count($customer_user));

        if ( count($customer_user) <= 0 ) {
            return [
                'message' => 'El archivo no existe en la base de datos',
                'status' => 0
            ];
        }else if ( $customer_user[0]->file->status == 'processed' ) {
            return [
                'message' => 'El archivo con dicho nombre ya fue procesado',
                'status' => 0
            ];
        }else if ( count($customer_user) <= 0 ) {
            return [
                'message' => 'No existen registros relacionados a ese nombre de archivo, o sus cuentas fueron dadas de alta',
                'status' => 0
            ];
        }else{

            
            try {

                foreach ($customer_user as $key => $value) {

                    $customer=CustomerUser::FindOrFail($value->id)
                    ->update([
                        'status'=> 'ready'
                    ]);
                    Log::error("dentro del forech");


                    if ($customer) { 
                        $url = url('terms/'.$value->user->id);
                        
                        Mail::to($value->user->email)
                            ->send(new NewUserWelcome($value->user->name, $value->customer->name, $url)
                        );

                         Log::info('mensaje con url para validar cuenta'.$value->phone);

                         ResourceFunctions::sendMessage($value->phone, "recibirá una dirección web para validar su cuenta de préstamos Fintech");
                         ResourceFunctions::sendMessage($value->phone, $url);  

                                            
                      }
                }//close foreach  

                File::FindOrFail(request() -> loadFileId)
                ->update([
                    'status'=> 'processed'
                ]);

                return [
                    'message' => 'Se actualizaron los estatus con exito',
                    'status' => 1
                ];             
            }catch(\Exception $e){
                Log::error("Ha ocurrido un error al intentar actualizar el estatus de customerUser [$e]");
                return response()->json(['status', 'Error al actulizar el estatus de los usuarios'], 500);
            }
            
        }

        
}


}
