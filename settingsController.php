<?php 

  session_start();

  include 'connection.php';
  // check if user is logged in
  if(!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])){
    header('location:login.php');
  }


  function api_response($data){
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
  }

  function filterInput($name){
    if( is_array($_POST[$name]) ){
      $data = [];
      foreach($_POST[$name] as $key => $value){
        $data[$key] = htmlspecialchars(stripslashes(trim($value)));
      }
      return $data;
    }
    return htmlspecialchars(stripslashes(trim(isset($_POST[$name]) ? $_POST[$name] : '' )));
  }

  $action = isset($_POST['action']) ? $_POST['action'] : '';
  
  // check if invalid action
  if(!in_array($action,['loadEvStatus','saveSettings'])){
    api_response(['error' => 'Invalid action']);
  }

  
  $sql = "SELECT * FROM settings";
  $result = $pdo->prepare($sql);
  $result->execute();
  $set = $result->fetchAll(PDO::FETCH_OBJ);
  $settings = [];
  foreach($set as $s){
    $settings[$s->setting] = $s->value;
  }
  

  function getEvStatus(){
    global $settings;

    $apiStatus = '';
    $instanceStatus = '';

    if( $settings['ev_url'] == '' || $settings['ev_api_key'] == '' || $settings['ev_instance'] == '' ){
      $apiStatus = '<strong class="text-danger">API Info Missing</strong>';
      $instanceStatus = '<strong class="text-danger">API Info Missing</strong>';
      return ['apiStatus'=>$apiStatus,'instanceStatus'=>$instanceStatus];
    }

    // check api status
    $data = file_get_contents($settings['ev_url']);
    $data = json_decode($data,true);

    if( isset($data['status']) && $data['status'] == 200 ){
      $apiStatus = '<strong class="text-success">Connected</strong> (<a href="'.$data['manager'].'" target="_blank">Open Manager</a>)';
    }else{
      $apiStatus = '<strong class="text-danger">Not Connected</strong>';
    }
    $connectionState = sendEvAPIRequest('instance/connectionState/'.$settings['ev_instance']);
    if( $connectionState['status'] == 200 && isset($connectionState['data']['instance']['state']) && $connectionState['data']['instance']['state'] == 'open' ){
      $instanceStatus = '<strong class="text-success">Instance Connected</strong>';
    }else{
      $instanceStatus = '<strong class="text-danger">Instance Not Connected</strong>';
    }

    return [
      'apiStatus' => $apiStatus,
      'instanceStatus' => $instanceStatus
    ];
  }


  // loadEVStatus
  if( $action == 'loadEvStatus' ){

    $data = getEvStatus();
    $output = array('status'=>'success','message'=>'ok','data'=>$data);
    api_response($output);
  }

  if( $action == 'saveSettings' ){
    $settings = filterInput('settings');
    if( is_array($settings) && count($settings) ){
      foreach($settings as $key => $value){
        $sql = "UPDATE settings SET value = :value WHERE setting = :setting";
        $result = $pdo->prepare($sql);
        $result->execute(['value'=>$value,'setting'=>$key]);
      }
    }
    api_response(['status'=>'success','message'=>'Settings saved successfully']);
  }
