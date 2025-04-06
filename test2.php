<?php 

  require 'connection.php';

  $status = sendWhatsappText('0501652906','Hello World');
  
  echo '<pre>'.print_r($status,true).'</pre>';