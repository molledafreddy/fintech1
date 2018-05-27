<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Assets\ResourceFunctions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\CustomerUser;
use App\Customer;
use App\Order;
use App\User;
use App\Credit;
use App\Message;
use App\Account;
use App\Bank;
use Response;
use Twilio\Rest\Client;
use Carbon\Carbon;
use Auth;	


class OrderController extends Controller
{
	 
    public function getMessage(Request $request)
    {
    	/*
		*generate array via the separator " " which is the one with the txt file
		*/
		$i =0;
		foreach (request()->results as $key => $value) {
			Log::info('numero de telefono de quien envio el mensaje OrderController@getMessage '.$value['from']);
			Log::info('cuerpo de mensaje '.$value['text']);
		
			/**
			 * [$data description:functions that allow validating the body of the message and the range of dates]
			 * @var [type]
			 */
			$data = ResourceFunctions::validateBodyMessage($value['text'] , $value['keyword']);
			Log::info('paso la funcionn orderController '.$value['text']);
	        $date=ResourceFunctions::rangeDate();
			/**
	      	* [$customerUsers description: query that looks for the telephone number of the request]
	      	* @var [type]
	      	*/
	      	$customerUser = CustomerUser::with(['user','bank','customer'])
	      				->where(['phone'=> $value['from']])->first();

		    Message::create([
	            'phone' 	=> $value['from'],
	            'body'  	=> $value['text']            
	        ]);

	    	if ( empty($customerUser)) {

	    		Log::info('se envio mensaje cuerpo:solicitud no procede');
	        	ResourceFunctions::sendMessage($value['from'], "solicitud no procede");

	    	}else if (  $customerUser->status != 'ready' ) {

	    		Log::info('su usuario no esta activo: debe validar la cuenta '.$customerUser->status);
	        	ResourceFunctions::sendMessage($value['from'], "su cuenta esta inactiva");

	    	}else if ( $customerUser->user->status != 1) {

	    		Log::info('usuario inactivo'.$customerUser->user->status);
	        	ResourceFunctions::sendMessage($value['from'], "su usuario no esta actívo debe validar su cuenta");

	    	}else if ( $data['flag']==false ) {

	    		Log::info('la estructura del mensaje el incorrecta:solicitud no procede '.$data['flag']);
	        	ResourceFunctions::sendMessage($value['from'], "La estructura del mensaje el incorrecta");

	    	}else if ( !is_numeric($data['amount'])) {
		        	
		        	Log::info('En el monto debe incluir solo numeros '.$data['amount']);
		        	ResourceFunctions::sendMessage($value['from'], "En el monto de prestamo debe incluir solo numeros");
		        
		    }else{

				Log::info("consiguio informacion relacionada al telefono: ".$value['from']);
				$credit = Order::with('credit')->where(
					['orders.customer_user_id'=> $customerUser->id])
						  ->whereBetween('orders.created_at', [ $date[0], $date[1]])
						  ->get();
						  
	      		$result= ResourceFunctions::validateCredit($customerUser, $credit, $data['amount']);
	      		
	      		if (( strcasecmp($value['keyword'] , 'Presta1') != 0 ) && ( strcasecmp($value['keyword'] , 'Balance1') != 0  ) ) {
	      			
	      			Log::info("La primera palabra del mensaje debe ser Presta1 o Balance1: ".$value['keyword']);
	      			ResourceFunctions::sendMessage($value['from'], "La primera palabra del mensaje debe ser Presta1 o Balance1" );
		        
		        }else if ( strcasecmp ( $customerUser->customer->name , $data['customer'] )  != 0 ) {
		        	
		        	Log::info('Indicaste un nombre de empresa incorrecto valor ingresado'.$data['customer'].' valor consulta '.$customerUser->customer->name);
		        	ResourceFunctions::sendMessage($value['from'], "Indicaste un nombre de empresa incorrecto" );
		        
		        }else if( (strcasecmp($data['keyWord'] , 'Balance1') == 0) ){
		        	
		        	Log::info('Tiene un saldo disponible de: '.$result['available']);
		        	ResourceFunctions::sendMessage($value['from'], "Tiene un saldo disponible de: ".number_format($result['available'], 2, ',', '') );	
		        
		        }else if ( $result['flag']	 ){

		        	ResourceFunctions::sendMessage($value['from'], $result['message'] );

		        }else if ( $data['amount'] <= 0	 ){
		        	Log::info('Ingrese un monto de credito valido:  monto '.$data['amount']);
		        	ResourceFunctions::sendMessage($value['from'], 'Ingrese un monto de credito valido' );

		        }else{

		        	try {

		        		DB::beginTransaction();
			        	
			        	$dt = Carbon::now();

			        	$order = Order::create([
			                'customer_user_id' => $customerUser->id,
			                'amount'           => $data['amount'],
			            ]);

			            $credit = Credit::create([
			                'order_id' 	=> $order->id,
			                'status'    => 'pending_processing',
			            ]);
			            /**
			             * valida si el monto disponible es igual a 0	
			             */
			            if ( ($result['available'] - $data['amount']) <= 0) {
			            	$available_balance= '0.00';
			            }else{
			            	$available_balance = ($result['available'] - $data['amount']);
			            }
			            /**
			             * [$customerUser->bank->transfer_key description:valida si la solicitud se realizo un fin de semana y es de un banco distinto al banco santander]
			             * @var [type]
			             */
			            if ( ($customerUser->bank->transfer_key != 'BANME') && ( ($dt->dayOfWeek==6) || ($dt->dayOfWeek == 0)  ) ) {
			            	$message = "Su solicitud esta en proceso, Saldo disponible: ".number_format($available_balance, 2, '.', ''). ". La transferencia se realizara el dia lunes";
			            }else{
			            	$message = "Su solicitud esta en proceso, Saldo disponible: ".number_format($available_balance, 2, '.', '');
			            }
			           	ResourceFunctions::sendMessage($value['from'], $message );
			           	Log::info('La solicitud en proceso'.$value['from']);
			           	
		        	DB::commit();   
	                } catch (Exception $e) {
	                    DB::rollBack();
	                }		        	
		        }
        	
        	}//close else

        	$i++;
        	break;
		}
		Log::debug('proceso terminado cantidad de recorridos: '.$i);
		
    }
}
