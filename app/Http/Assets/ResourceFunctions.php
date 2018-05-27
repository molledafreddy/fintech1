<?php

namespace App\Http\Assets;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Credit;
use App\Account;
use Carbon\Carbon;
use Twilio;
use Services_Twilio;
use Twilio\Rest\Client;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;


class ResourceFunctions 
{

    public static function addSpaces($ceros,$data,$type, $pos)
    {
      $insertar_ceros =null;
      $order_diez = explode(".",$data);
      $dif_diez = $ceros - strlen($order_diez[0]);
      
      if ($type == 1) {
        $add = 0;
      }else{
        $add = " ";
      }
      for($m = 0 ; $m < $dif_diez;  $m++)
      {
              $insertar_ceros .=$add;
      }

      if ($pos==='first') {
        
      return $insertar_ceros.$data;
      
      }else{
      
        return $data.$insertar_ceros;
      }

    }
    /**
     * [selectBank description:select the source bank for transfers]
     * @param  [type] $bank_accounts [description:fix the accounts registered in the bd]
     * @return [type]                [description:account keys]
     */
    public static function selectBank($bank)
    {
      // Log::info('ingreso al metodo que selecciona al banco a transferir '.count($bank_accounts));
        $amount =0;
        $today = Carbon::today();
         $credits = Credit::with('order')
                              ->where([['status','!=','pending_processing'],['account_id', $bank->id ]])
                              ->whereDate('updated_at',$today)->get();
          foreach ($credits as $key => $value2) {
           $amount += $value2->order->amount;
          }


          if ( $amount < $bank->daily_amount ) {
                // Log::info('bank_id: '.$value->bank->id);
                return [
                  'bank_id'=>$bank->bank->id,
                  'bank_number'=>$bank->number,
                  'account_id'=> $bank->id,
                  'daily_amount'=> $bank->daily_amount,
                  'container' => $amount,
               ];
          }
      return -1;
    }
    /**
     * @param  [type] $credits      [description:method that allows to generate the file of transfers]
     * @param  [type] $bank_id      [description]
     * @param  [type] $total_amount [description]
     * @param  [type] $container    [description]
     * @return [type]               [description]
     */
    public static function generateFileTransfer($credits, $bank)
    {
      Log::debug('ingreso al metodo generateFile');
      
          $flag = false;
          $container_transference = [];
          $contador = count($credits);
          $sum_credit= $bank['container'];

              $file_name = 'OtherBank_'.time().'_'. mt_rand().'.txt';
              $file = fopen($file_name, "w");
              $folder = public_path() . "/storage/users/" ;
              
              if( is_dir($folder) == false )
              {                   
                  mkdir($folder, 0777, true);
              }
                     
              $path = $folder . $file_name;
              $i=0;
              foreach ($credits as $value) {
                
                if ( $bank['daily_amount'] >= ($sum_credit + $value['amount'])  ) {

                      $contador--;
                      $container_transference = [
                        'change_account' => $bank['bank_number'].'     ',
                        'payment_account' => $value['payment_account'].'  ',
                        'receiving_bank' => ResourceFunctions::addSpaces(5, $value['receiving_bank'], 0,'first'),
                        'baneficiary'    => ResourceFunctions::addSpaces(40, $value['baneficiary'], 0,null),
                        'sucursal' =>'0000',
                        'amount'  => ResourceFunctions::addSpaces(15, number_format($value['amount'], 2, '', '') , 1,'first'),
                        'plaza_banxico' =>'00000',
                        'concepto' => ResourceFunctions::addSpaces(40, 'PRESTAMO FINCTECH', 0,null),
                        'application_form' =>ResourceFunctions::addSpaces(98,'1', 0,'first')."\n",
                      ];

                      $flag = true;
                      file_put_contents($path, $container_transference, FILE_APPEND | LOCK_EX);

                      try {
                        Credit::where('id',$value['id'])
                        ->update(['status'=> 'processing', 'account_id'=>$bank['account_id']]);
                        
                        $sum_credit += $value['amount'];
                        Log::debug('contador container '.$bank['container']);
                      }catch(\Exception $e){
                        Log::error("Ha ocurrido un error al intentar generar el archivo [$e]");
                        return response()->json(['status', 'Ocurrio un error al generar el archivo de transferencia a otros bancos'], 500);
                      }                      

                }else{

                  break; 

                }//close else
                $i++;
                // break;
              }//close foreach
                     Log::debug('recorrido foreach: '.$i);
            
          if ($flag==true) {
            fclose($file);   
            
            return [
                'status' => 1,
                'fileNameOtherBank' => $file_name,
                'contador'=>$contador,
            ];
            
          }else{
            return [
                'status' => 0,
                'fileNameOtherBank' => null,
                'contador'=>$contador,
            ];
          }
          
    }

    public static function generateFileTransferSantander($credits, $bank)
    {
      Log::debug('ingreso al metodo generateFile');
      
          $flag = false;
          $date = Carbon::today();
          $today = $date->format('dmY');
          $container_transference = [];
          $contador = count($credits);
          $sum_credit= $bank['container'];

              $file_name = 'SantanderBank_'.time().'_'. mt_rand().'.txt';
              $file = fopen($file_name, "w");
              $folder = public_path() . "/storage/users/" ;
              
              if( is_dir($folder) == false )
              {                   
                  mkdir($folder, 0777, true);
              }
                     
              $path = $folder . $file_name;
              $i=0;
              foreach ($credits as $value) {
                if ( $bank['daily_amount'] >= ($sum_credit + $value['amount'])  ) {

                      $contador--;
                      $container_transference = [
                        'change_account' => ResourceFunctions::addSpaces(11, $bank['bank_number'], 0,null).'     ',
                        'payment_account' => ResourceFunctions::addSpaces(11, $value['payment_account'], 0,null).'     ',
                        'amount'  => ResourceFunctions::addSpaces(10, number_format($value['amount'], 2, '.', '') , 1,'first'),
                        'concepto' => ResourceFunctions::addSpaces(40, 'PRESTAMO FINCTECH', 0,null),
                        'date' => $today."\n",
                      ];

                      $flag = true;
                      file_put_contents($path, $container_transference, FILE_APPEND | LOCK_EX);

                      try {
                        Credit::where('id',$value['id'])
                        ->update(['status'=> 'processing', 'account_id'=>$bank['account_id']]);
                        
                        $sum_credit += $value['amount'];
                        Log::debug('contador container '.$sum_credit);
                      }catch(\Exception $e){
                        Log::error("Ha ocurrido un error al intentar generar el archivo [$e]");
                        return response()->json(['status', 'Ocurrio un error al generar el archivo de transferencia a otros bancos'], 500);
                      }                      

                }else{

                  break; 

                }//close else
                $i++;
                // break;
              }//close foreach
                     Log::debug('recorrido foreach: '.$i);
            
          if ($flag==true) {
            fclose($file);   
            
            return [
                'status' => 1,
                'fileNameOtherBank' => $file_name,
                'contador'=>$contador,
            ];
            
          }else{
            return [
                'status' => 0,
                'fileNameOtherBank' => null,
                'contador'=>$contador,
            ];
          }
          
    }
    /**
     * [sendMessage description:realiza el envio de mensajes]
     * @param  [type] $number  [description:numero de telefono al cual se enviara el mensaje]
     * @param  [type] $message [description: el cuerpo del mensaje]
     * @return [type]          [description: retorna duna respuesta del resultado delenvio del mensaje]
     */
    public static function sendMessage ($number, $message) 
    {

       /* 
        // Create an authenticated client for the Twilio API
        $client = new Services_Twilio($_ENV['TWILIO_ACCOUNT_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
     
            // Use the Twilio REST API client to send a text message
            $m = $client->account->messages->sendMessage(
            $_ENV['TWILIO_NUMBER'], // the text will be sent from your Twilio number
            $number, // the phone number the text will be sent to
            $message // the body of the text message
        );
 
        // Return the message object to the browser as JSON
        return $m;
        */
       
       // Initializing SendSingleTextualSms client with appropriate configuration
      $client = new SendSingleTextualSms(new BasicAuthConfiguration($_ENV['INFOBIP_USERNAME'], $_ENV['INFOBIP_PASSWORD'] ));
      // Creating request imap_body(imap_stream, msg_number)
      $requestBody = new SMSTextualRequest();
      $requestBody->setFrom($_ENV['INFOBIP_FROM']);
      $requestBody->setTo([$number]);
      $requestBody->setText($message);
      // Executing request
      try {
          $response = $client->execute($requestBody);
          $sentMessageInfo = $response->getMessages()[0];
         // echo "Message ID: " . $sentMessageInfo->getMessageId() . "\n";
          //echo "Receiver: " . $sentMessageInfo->getTo() . "\n";
          //echo "Message status: " . $sentMessageInfo->getStatus()->getName();
      } catch (Exception $exception) {
        Log::error("Ocurrio un error: [$exception->getCode()]");
        Log::error("Ocurrio un error: [$exception->getMessage()]"); 

      }
      return $sentMessageInfo->getStatus()->getName();        
    
    }

    /**
     * [sendMessage description:realiza el envio de mensajes]
     * @param  [type] $number  [description:numero de telefono al cual se enviara el mensaje]
     * @param  [type] $message [description: el cuerpo del mensaje]
     * @return [type]          [description: retorna duna respuesta del resultado delenvio del mensaje]
     */
    public static function Message ($number, $message, $type) 
    {
      if ($type == 'twilio') {
        // Create an authenticated client for the Twilio API
        $client = new Services_Twilio($_ENV['TWILIO_ACCOUNT_SID'], $_ENV['TWILIO_AUTH_TOKEN']);
     
            // Use the Twilio REST API client to send a text message
            $m = $client->account->messages->sendMessage(
            $_ENV['TWILIO_NUMBER'], // the text will be sent from your Twilio number
            $number, // the phone number the text will be sent to
            $message // the body of the text message
        );
 
        // Return the message object to the browser as JSON
        return $m;
        
      }else{
         // Initializing SendSingleTextualSms client with appropriate configuration
        $client = new SendSingleTextualSms(new BasicAuthConfiguration($_ENV['INFOBIP_USERNAME'], $_ENV['INFOBIP_PASSWORD'] ));
        // Creating request imap_body(imap_stream, msg_number)
        $requestBody = new SMSTextualRequest();
        $requestBody->setFrom($_ENV['INFOBIP_FROM']);
        $requestBody->setTo([$number]);
        $requestBody->setText($message);
        // Executing request
        try {
            $response = $client->execute($requestBody);
            $sentMessageInfo = $response->getMessages()[0];
        } catch (Exception $exception) {
          Log::error("Ocurrio un error: [$exception->getCode()]");
          Log::error("Ocurrio un error: [$exception->getMessage()]"); 

        }
        return $sentMessageInfo->getStatus()->getName();        
        
      }
    
    }

    /**
     * [searchCredit description: method that nusca the credits in process related to the account passed by parameter]
     * @return [type] [description]
     */
    public static function searchCredit($credit, $account_file, $amount_file)
    {
      
      $result_amount =preg_replace("/[^0-9.,]/", "", $amount_file);
      
      foreach ($credit as $key => $value) {
          Log::debug('account_file: '.$account_file.' account_consult: '.$value->order->customer_users->acconunt_number.' amount_file: '.$amount_file.' amount_consult: '.$value->order->amount);
        if ( ($account_file=== $value->order->customer_users->acconunt_number) 

              && ($result_amount ===  $value->order->amount) ) {
          
              return ['id'=>$value->id, 'phone'=>$value->order->customer_users->phone]; 
        }

      }
      return ['id'=> false, 'phone'=>null];
    }

    /**
     * [validateCredit description:method that allows you to validate if you have credit]
     * @param  [type] $customerUsers [description: arrangement with user information]
     * @param  [type] $credit        [description: the credits requested in a range of dates]
     * @param  [type] $credit_amount [description: the amount of credit requested]
     * @return [type]                [description: arrangement with the data indicating whether or not the loan proceeds]
     */
    public static function validateCredit ($customerUsers, $credit, $credit_amount)
    {
        $total_credit =0;
        $flag=false;
        $available = 0;
        $calculation_percentage =($customerUsers->biweekly_salary * 0.30);
        foreach ($customerUsers->orders as $value) {
            
          foreach ($credit as $value_credit) {
              if ($value_credit->credit->status=='pending_processing' && $value_credit->credit->order_id == $value->id ) {
                  $total_credit += $value->amount;
              }
            }            
              
        }
        /**
         * 
         */
        $available = (  $calculation_percentage - $total_credit) ;
        if ($available <= 0 ) {
            $available =0.0;
        }
        if (($total_credit >= $calculation_percentage ) || ( ( $total_credit + $credit_amount) > $calculation_percentage ) ) {
            return[
                'flag'      => true,
                'message'   => 'Solo tiene un monto disponible para solicitar credito es de: '.number_format($available, 2, ',', ''),
                  'available' => $available,
            ];
            
        }else{ 
            return[
                'flag' => false,
                'message' => 'El monto solicitado esta disponible',
                'available' => $available,
            ];
        }        
    }

    /**
     * [validateBodyMessage description:validate the body of the message]
     * @param  [type] $text [description:message body]
     * @return [type]       [description: array winth split data and flag winth false or true]
     */
    public static function validateBodyMessage ( $text, $keyWord)
      {
        Log::debug('ingreso al metodo validateBodyMessage '.$text.'  key '.$keyWord);
        $data= explode(" ", $text );
        
        if ( stristr($keyWord , 'Balance1') != false) {
            Log::debug('ingreso a la validacion del balance');
            if ( (empty($data[0])) || (empty($data[1])) ) {
              // Log::debug('retorno de false estructura del mensaje incorrecta validacion Balance1');
              return[
                'flag'=>false,
                'keyWord'=>$keyWord,
                'customer'=>'',
                'amount'=>0,
              ];  
              
            }else{
              // Log::debug('la estructura del mesaje es correcta');
              return[
                'flag'=>true,
                'keyWord'=>$keyWord,
                'customer'=>$data[1],
                'amount'=> 0,
              ];
            } 
        }else {
            
            if ( (empty($data[0])) || (empty($data[1])) || (empty($data[2])) ) {
              Log::debug('retorno de false estructura del mesaje incorrecta Presta1');
              return[
                'flag'=>false,
                'keyWord'=>$keyWord,
                'customer'=>'',
                'amount'=>0,
              ];  
              
            }else{
              Log::debug('la estructura del mesaje es correcta');
              return[
                'flag'=>true,
                'keyWord'=>$keyWord,
                'customer'=>$data[1],
                'amount'=>$data[2],
              ];
            }

          }
      }
    /**
     * [rangeDate description:generates a range of dates]
     * @return [type] [description:returns an array with the range of dates]
     */
    public static function rangeDate()
    {
        $date = Carbon::now();

        $date_d = $date->format('d');
        
        if ($date_d <= 15) {
           return array(
                $date->format('Y').'-'.$date->format('m').'-'.'01 00:00:00',
                $date->format('Y').'-'.$date->format('m').'-'.'15 23:59:59'
            );
        }else{
            return array(
                $date->format('Y').'-'.$date->format('m').'-'.'16 00:00:00',
                $date->format('Y').'-'.$date->format('m').'-'.'31 23:59:59'
            );
        }
    }

     public static function messageError($controller, $column, $message)
    {
        \Log::critical($message.':  || '.$controller . '- (), del usuario: ');
        return ['message' => 'La columna bank solo debe poseer valores numericos'];
        //flash('La columna '.$column.' '.$message.' ¡Error!')->error();
    }

    public static function messageErrorColumnNumeric($controller, $column)
    {
        \Log::critical('la columna debe poseer datos numericos: '. ' || '.$controller . '- (), del usuario: ');
        //flash('La Columna '.$column.' debe poseer solo valores numericos', '¡Error!')->error();
    }

    public static function messageStructureHeadboard($controller)
    {
        \Log::critical(' La estructura del archivo no es la correcta ' . ' -----> ['.$controller . ' del usuario: ');
        //flash('La estructura del archivo no es la correcta', '¡Alert!')->warning();
    }

    /**
     * [validateColumnNumeric
     *  Validates that a column has only numerical values ]
     * @param  [arrays] $dataColumn   [Arrangement with the data in column]
     * @param  [arrays] $key   [Column index]
     * @return [boolean] $validate     [Returns true if it has a non-numeric value]
     */
    public static function validateColumnNumeric($dataColumn){
        
        foreach ($dataColumn as $value) {
            if (is_numeric($value)==false ) {
                return true;
            }
        }

        return false;
    }

    /**
     * [validateColumnNumeric
     *  Validates that a column has only numerical values ]
     * @param  [arrays] $dataColumn   [Arrangement with the data in column]
     * @param  [arrays] $key   [Column index]
     * @return [boolean] $validate     [Returns true if it has a non-numeric value]
     */
    public static function validateAccount($dataColumn, $rango1,$rango2){
        foreach ($dataColumn as $value) {
            if ((strlen($value) < $rango1) || (strlen($value) > $rango2) ) {
                return true;
            }
        }

        return false;
    }
    /**
     * [validateAccountClabe description:]
     * @param  [type] $dataColumn [description]
     * @param  [type] $q          [description]
     * @return [type]             [description]
     */
    public static function validateAccountClabe($dataColumn, $q){
        foreach ($dataColumn as $value) {
            if ( strlen($value) != $q ) {
                if (count($value)!= $q ) {
                    return true;
                }
            }

        return false;
        }
    }


    /**
     * [validateDuplicateValue
     *  Validates that a column has no duplicate values ]
     * @param  [arrays] $array   [Arrangement with the data in column]
     * @return [boolean] $validate     [Returns true if it gets duplicate values]
     */
    public static function validateDuplicateValue($array){
        
        if( (count($array) )!= (count(array_unique($array) )) ){
            Log::error("tamaño: ".(count($array)) ." otro dato: ".(count(array_unique($array) )));
            return true;
        }

        return false;

    }

    /**
     * [validateColumnVoid
     *  Validate that a column has no empty values ]
     * @param  [arrays] $array   [Arrangement with the data in column]
     * @return [boolean] $validate     [Returns true if it gets duplicate values]
     */
    public static function  validateColumnVoid($dataColumn){
      
      $validate=true;
      foreach ($dataColumn as $value) {
            if ( (empty($value)==true) )  {
                $validate = false;

            }

        }
        Log::info('Dentro de validateColumnVoid '.$validate);
      return $validate;
    }


   public static function arraySimple($array)
   {
      $i = 0;
      foreach ($array as $key) {
          $valor[$i] = $key;
          $i++;
      }

      return $valor;
   }
   /**
    * [headFile description:]
    * @param  [type] $data [description]
    * @return [type]       [description]
    */
   public static function headFile($data)
   {
    $prueba=[];
        foreach ($data as $k => $value) {
            foreach ($value as $key => $recor) {
                $prueba[]= $key;
            }
            break;
        }
        return $prueba;
   }

    /**
     * [validateHead
     *  .
     *  Validates the header of the files to import ]
     * @param  [arrays] $keys   [Arrangement with defined keys]
     * @param  [arrays] $data   [Fix with the indexes that the file brings]
     * @return [integet] $i     [Returns in number of valid indexes]
     */
    public static function validateHeadboard($keys, $data){
      $j=0; $i=0;
      $validate=true;
      $data = array_filter($data);
      foreach ($data as $value) {
        Log::debug('value '.$value);
                foreach ($keys as $value3) {
                 if ($value === $value3) {
                        $j++;
                    }
                }
            $i++;
        }
        // Log::debug('pases i '.$i.' cantidad de pases j '.$j);
        if(  !((count($keys) == $j) &&  ( count($keys)==$i ))   ){
            $validate = false;

       }

      return $validate;

    }

    public static function validateHeadboardTransfer($keys, $data){
      $j=0; $i=0;
      $validate=true;
      
      foreach ($data as $value) {
        foreach ($value as $key => $value2) {
                  foreach ($keys as $value3) {
                   if ($value === $value3) {
                          $j++;
                      }
                  }
          
        }
            $i++;
      }
      if(  !((count($keys) == $j) &&  ( count($keys)==$i ))   ){
          $validate = false;
      }

      return $validate;

    }

 
}
