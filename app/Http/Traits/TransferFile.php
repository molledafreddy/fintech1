<?php

namespace App\Http\Traits; 

use Illuminate\Http\Request;
use App\Http\Assets\ResourceFunctions;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Credit;
use App\Account;
use App\CustomerUser;
use App\HistoricalImport;
use Auth;
use Excel;
use Carbon\Carbon;

trait TransferFile
{
    public function hola(){
    	return 'hola';
    }
    public function TransferOther()
	{
		
		Log::info("paso por el proceso de generar archivo de transferencia TransferOther ");

    	$container_name_file=[];
    	$contenedor=0;
    	$i=0;
		
    	try {

    		$bank_accounts = Account::with('bank')->where('status','available')
				    		->whereHas('bank', function ($query) {
								    			$query->where('transfer_key','BANME');
										})->get();
	       	
	       	foreach ($bank_accounts as $key => $value) {
	        	
	        	$bank = ResourceFunctions::selectBank($value);

	        	$consult_credits =Credit::where('status','pending_processing')
	    		->with(['order.customer_users.bank','order.customer_users.user'])
	    		->whereHas('order.customer_users.bank', function ($query) {
				 			$query->where('transfer_key','!=','BANME');
				})->get();

	    		$credits=[];

	    		foreach ($consult_credits as $key => $value) {

	    			if ($value->order->customer_users->bank_id != $bank['bank_id']) {
	    				$credits[]=[
		    				'id' => $value->id,
		    				'amount' => $value->order->amount,
		    				'credit_bank_id' => $value->order->customer_users->bank_id,
		    				'payment_account' => $value->order->customer_users->acconunt_clabe,
		    				'receiving_bank'=>$value->order->customer_users->bank->transfer_key,
		    				'baneficiary'=>$value->order->customer_users->user->name,
		    			];
	    			}
	    			
	    		}

	    		if ( ( count($credits)>0 ) || ( $bank['bank_id'] != -1 ) ) {
		    		Log::info('paso validador: '.count($credits));
					$result =ResourceFunctions::generateFileTransfer($credits, $bank);
					
					$contenedor = $result['contador'];
					if ($result['status']==1) {
						$container_name_file[$i]=['name'=> $result['fileNameOtherBank']];
					}

					if ($result['contador']<=0) {
						break;
					}

			    }else{

			    	break;
			    }
			    $i++;
	        }//close foreach
	        
	        return ['container_name_file'=>$container_name_file, 'contenedor'=>$contenedor];

    	} catch (Exception $e) {
    		Log::error("Ha ocurrido un error al intentar generar el archivo [$e]");
            return response()->json(['status', 'Ocurrio un error al generar el archivo de transferencia a otros bancos'], 500);
    	}
 
	}


	public function TransferSantander()
	{
		
		Log::info("paso por el proceso de generar archivo de transferencia TransferSantanderBank: ");

		$container_name_file=[];
    	$contenedor=0;
    	$i=0;
    	try {
	    		$bank_accounts = Account::with('bank')->where('status','available')
	    		->whereHas('bank', function ($query) {
					    			$query->where('transfer_key','BANME');
							})->get();

	       	foreach ($bank_accounts as $key => $value) {
	        	
	        	$bank = ResourceFunctions::selectBank($value);

	        	$consult_credits =Credit::where('status','pending_processing')
	    		->with(['order.customer_users.bank','order.customer_users.user'])
	    		->whereHas('order.customer_users.bank', function ($query) {
				 			$query->where('transfer_key','BANME');
				})->get();

	    		$credits=[];
	    		foreach ($consult_credits as $key => $value) {

	    			if ($value->order->customer_users->bank_id == $bank['bank_id']) {
	    				$credits[]=[
		    				'id' => $value->id,
		    				'amount' => $value->order->amount,
		    				'credit_bank_id' => $value->order->customer_users->bank_id,
		    				'payment_account' => $value->order->customer_users->acconunt_number,
		    				'receiving_bank'=>$value->order->customer_users->bank->transfer_key,
		    				'baneficiary'=>$value->order->customer_users->user->name,
		    			];
	    			}
	    			
	    		}

	    		if ( ( count($credits)>0 ) || ( $bank['bank_id'] != -1 ) ) {
		    		Log::info('paso validador: '.count($credits));
					$result =ResourceFunctions::generateFileTransferSantander($credits, $bank);
					
					$contenedor = $result['contador'];
					if ($result['status']==1) {
						$container_name_file[$i]=['name'=> $result['fileNameOtherBank']];
					}

					if ($result['contador']<=0) {
						break;
					}

			    }else{

			    	break;
			    }
			    $i++;
	        }//close foreach

	        return ['container_name_file'=>$container_name_file, 'contenedor'=>$contenedor];
    		
    	} catch (Exception $e) {
    		Log::error("Ha ocurrido un error al intentar generar el archivo [$e]");
            return response()->json(['status', 'Ocurrio un error al generar el archivo de transferencia a otros bancos'], 500);
    	}
 
	}

}