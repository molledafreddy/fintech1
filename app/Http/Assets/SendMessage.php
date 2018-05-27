<?php

namespace App\Http\Assets;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Twilio;
use Services_Twilio;
use Twilio\Rest\Client;
use infobip\api\client\SendSingleTextualSms;
use infobip\api\configuration\BasicAuthConfiguration;
use infobip\api\model\sms\mt\send\textual\SMSTextualRequest;


class SendMessage 
{

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
    
}
