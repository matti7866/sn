<?php 


if( !function_exists('sendEvAPIRequest') ){
  function sendEvAPIRequest($endpoint,$method='GET',$data=[]){
    global $settings;
    $url = $settings['ev_url'].$endpoint;

    // send curl request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // api key for authentication apikey header
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'apikey: '.$settings['ev_api_key'],
      'Content-Type: application/json'
    ));

    $output = json_decode(curl_exec($ch),true);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $output;
  }
}

if( !function_exists('sendWhatsappText') ){
  function sendWhatsappText($phone,$message){
    global $settings;
    if( $settings['ev_status'] == 1 ){
      // filter number for whatsapp
      $phone = str_replace([' ','-','(',')','+'],'',$phone);
      $phone = ltrim($phone,'0');

      if( strlen($phone) <= 9 ){
        if( substr($phone,0,1) == "5" ){
          $phone = "971".$phone;
        }else if( substr($phone,0,1) == '3' ){
          $phone = "92".$phone;
        }
      }

      if( strlen($phone) < 12 ){
        return ['status'=>400,'message'=>'Invalid Phone Number'];
      }

      $status = sendEvAPIRequest('message/sendText/'.$settings['ev_instance'],'POST',[
        'number'=>$phone,
        'textMessage' => [
          'text' => $message
        ]
      ]);
      return $status;
    }else{
      return ['status'=>400,'message'=>'EV API is not enabled'];
    }
  }
}