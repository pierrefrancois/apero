<?php

function sendsms ($m, $to, $n) {
  global $smsfrom, $smsapikey;

  echo "About to send an sms to $to [$n]\n";
    
  $options = array(
    'http' => array(
      'method'  => 'POST',
      'content' => json_encode( [
        'content' => $m,
        'from'    => $smsfrom,
        'to'      => $to
      ]),
      'header'=>  "Content-Type: application/json\r\n" .
                "Accept: application/json\r\n" .
                "x-api-key: $smsapikey\r\n"
    )
  );

  $context  = stream_context_create( $options );
  $result = file_get_contents( "https://api.httpsms.com/v1/messages/send", false, $context );

  // echo $result . "\n"; 
}


