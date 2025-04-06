<?php

  session_start();

  include 'connection.php';
  // check if user is logged in
  if(!isset($_SESSION['user_id']) || !isset($_SESSION['role_id'])){
    header('location:login.php');
  }
  // load the permission for the user
  $rolId = $_SESSION['role_id'];
  $result = $pdo->prepare("SELECT * FROM `permission` WHERE role_id = :role_id AND page_name = 'Accounts' ");
  $result->bindParam(':role_id', $rolId);
  $result->execute();
  $permission = $result->fetch(\PDO::FETCH_ASSOC);


  $action = isset($_POST['action']) ? $_POST['action'] : '';

  $output = array();

  /// check if action not set or invalid action 
  if( $action == '' || !in_array($action,['searchTransactions','addTransaction','updateTransaction','deleteTransaction']) ){
    

  }

  /* ADD TRANSACTIONS  */
  if( $action == "addTransaction" ){  
    // secure input post
    $dateAdd = isset($_POST['dateAdd']) ? htmlspecialchars(strip_tags($_POST['dateAdd'])) : '';
    $fromAccountAdd = isset($_POST['fromAccountAdd']) ? htmlspecialchars(strip_tags($_POST['fromAccountAdd'])) : '';
    $toAccountAdd = isset($_POST['toAccountAdd']) ? htmlspecialchars(strip_tags($_POST['toAccountAdd'])) : '';
    $remarksAdd = isset($_POST['remarksAdd']) ? htmlspecialchars(strip_tags($_POST['remarksAdd'])) : '';
    $trxNumberAdd = isset($_POST['trxNumberAdd']) ? htmlspecialchars(strip_tags($_POST['trxNumberAdd'])) : '';
    $amountAdd = isset($_POST['amountAdd']) ? htmlspecialchars(strip_tags($_POST['amountAdd'])) : '';
    $amountConfirmAdd = isset($_POST['amountConfirmAdd']) ? htmlspecialchars(strip_tags($_POST['amountConfirmAdd'])) : '';
    $chargesAdd = isset($_POST['chargesAdd']) ? htmlspecialchars(strip_tags($_POST['chargesAdd'])) : '';
    $exchangeRateAdd = isset($_POST['exchangeRateAdd']) ? htmlspecialchars(strip_tags($_POST['exchangeRateAdd'])) : '';
    $filenameAdd = isset($_FILES['filenameAdd']) ? $_FILES['filenameAdd'] : ['name' => ''];


    $errors = [];
    if( $dateAdd == '' ){
      $errors['dateAdd'] = 'Date is required';
    }elseif( $dateAdd > date('Y-m-d') ){
      $errors['dateAdd'] = 'Date must be less than or equal to today';
    }



    if( $fromAccountAdd == '' ){
      $errors['fromAccountAdd'] = 'From Account is required';
    }
    if( $toAccountAdd == '' ){
      $errors['toAccountAdd'] = 'To Account is required';
    }
    // check if both accounts are the same
    if( $fromAccountAdd == $toAccountAdd ){
      $errors['toAccountAdd'] = 'To Account must be different from From Account';
    }

    $amountAdd = str_replace(',','',$amountAdd);
    if( $amountAdd == '' ){
      $errors['amountAdd'] = 'Amount is required';
    }elseif( !is_numeric($amountAdd) ){
      $errors['amountAdd'] = 'Amount must be a number';
    }elseif( $amountAdd <= 0 ){
      $errors['amountAdd'] = 'Amount must be greater than 0';
    }

    if( $amountConfirmAdd == '' ){
      $errors['amountConfirmAdd'] = 'Confirm Amount is required';
    }elseif( $amountAdd != $amountConfirmAdd ){
      $errors['amountConfirmAdd'] = 'Amount and Confirm Amount must be the same';
    }

    if( $filenameAdd['name'] == '' ){
      $errors['filenameAdd'] = 'Receipt file is required';
    }


    if( count($errors) ){
      $output = array('status'=>'error','message'=>'form_errors','errors'=>$errors);
    }else{
      // chaneg the filenaem random 
      $filename = str_replace(" ","_",time().'_'.$filenameAdd['name']);
      $fileUploadStatus = move_uploaded_file($filenameAdd['tmp_name'],'attachment/transfers/'.$filename);

      if( $fileUploadStatus ){

        $transferCheck = true;
        if( $trxNumberAdd != '' ){
          $result = $pdo->prepare("SELECT * FROM `transfers` WHERE trx = :trx_number");
          $result->bindParam(':trx_number', $trxNumberAdd);
          $result->execute();

          if( $result->rowCount() ){
            $transferCheck = false;
          }
        }

        if( !$transferCheck ){
          $output = array('status'=>'error','message'=>'form_errors','errors'=>['trxNumberAdd'=>'Transaction Number already exists']);
        }else{
          $datetime = date('Y-m-d H:i:s',strtotime( date($dateAdd . ' ' . date('H:i:s')) ));
          $result = $pdo->prepare("INSERT INTO `transfers` (`datetime`,from_account,to_account,remarks,trx,amount,charges,exchange_rate,`filename`,added_by) VALUES (:datetime,:from_account,:to_account,:remarks,:trx,:amount,:charges,:exchange_rate,:filename,:added_by)");
          $result->bindParam(':datetime', $datetime);
          $result->bindParam(':from_account', $fromAccountAdd);
          $result->bindParam(':to_account', $toAccountAdd);
          $result->bindParam(':remarks', $remarksAdd);
          $result->bindParam(':trx', $trxNumberAdd);
          $result->bindParam(':amount', $amountAdd);
          $result->bindParam(':charges', $chargesAdd);
          $result->bindParam(':exchange_rate', $exchangeRateAdd);
          $result->bindParam(':filename', $filename);
          $result->bindParam(':added_by', $_SESSION['user_id']);
          $result->execute();

          $output = array('status'=>'success','message'=>'Transaction added successfully');

        }

      }else{
        $output = array('status'=>'error','message'=>'file_upload_error','errors'=>['filenameAdd'=>'Error uploading file']);
      }

    }
  }




  /* SEARCH TRANSACTIONS */
  if( $action == "searchTransactions" ){


    $period = isset($_POST['period']) ? htmlspecialchars(strip_tags($_POST['period'])) : '';
    $search = isset($_POST['search']) ? htmlspecialchars(strip_tags($_POST['search'])) : '';
    $startDate = isset($_POST['startDate']) ? htmlspecialchars(strip_tags($_POST['startDate'])) : '';
    $endDate = isset($_POST['endDate']) ? htmlspecialchars(strip_tags($_POST['endDate'])) : '';
    $fromAccount = isset($_POST['fromAccount']) ? htmlspecialchars(strip_tags($_POST['fromAccount'])) : '';
    $toAccount = isset($_POST['toAccount']) ? htmlspecialchars(strip_tags($_POST['toAccount'])) : '';

    $where = "";

    if( $search != '' ){
      $where .= " AND ( trx LIKE '%$search%' OR remarks LIKE '%$search%' ) ";
    }else{
      if( $period == 'dates' ){
        $where .= " AND datetime BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ";
      }
      if( $fromAccount != '' ){
        $where .= " AND `from_account` = '$fromAccount' ";
      }
      if( $toAccount != '' ){
        $where  .= " AND  `to_account` = '$toAccount' ";
      }
    }

    $result = $pdo->prepare("SELECT 
    transfers.* , a1.account_Name as fromAccountName, a2.account_Name as toAccountName
    FROM `transfers` 
    LEFT JOIN `accounts` a1 ON a1.account_ID = transfers.from_account
    LEFT JOIN `accounts` a2 ON a2.account_ID = transfers.to_account
    WHERE 1=1 $where ORDER BY datetime DESC");
    $result->execute();
    $transactions = $result->fetchAll(\PDO::FETCH_ASSOC);


    $html = '';
    // check if there are no transactions
    if( count($transactions) ){
      foreach($transactions as $trx){
        $html .= '<tr>
          <td>'.$trx['id'].'</td>
          <td>'.$trx['datetime'].'</td>
          <td>
            <strong>From: </strong>'.$trx['fromAccountName'].'<br />
            <strong>To: </strong>'.$trx['toAccountName'].'
          </td>
          <td>'.nl2br($trx['remarks']).'</td>
          <td>'.$trx['trx'].'</td>
          <td>'.number_format($trx['amount']).'</td>
          <td>'.number_format($trx['charges']).'</td>
          <td>'.number_format($trx['exchange_rate']).'</td>
          <td>
            <a target="_blank" class="btn btn-sm btn-success" href="/attachment/transfers/'.$trx['filename'].'"><i class="fa fa-print"></i> Reciept</a>
            <button class="btn btn-sm btn-warning btn-edit" data-id="'.$trx['id'].'" ><i class="fa fa-edit"></i></button>
            <button class="btn btn-sm btn-danger btn-delete" data-id="'.$trx['id'].'" ><i class="fa fa-trash"></i></button>
          </td>
        </tr>';
      }
      $output = array('status'=>'success','message' => 'OK','html'=>$html);
    }else{
      $html = '<tr><td colspan="9" class="text-center text-danger"><strong>No transactions found</strong></td></tr>';
      $output = array('status'=>'success','message' => 'No transactions found','html'=>$html);
    }    
  }


  // delete transaction
  if( $action == "deleteTransaction" ){
    $id = isset($_POST['id']) ? htmlspecialchars(strip_tags($_POST['id'])) : '';
    if( $id == '' ){
      $output = array('status'=>'error','message'=>'Invalid transaction');
    }else{

      // load transaction and delete the file from attachment/transfers using field filename
      $result = $pdo->prepare("SELECT * FROM `transfers` WHERE id = :id");
      $result->bindParam(':id',$id);
      $result->execute();
      $transaction = $result->fetch(\PDO::FETCH_ASSOC);

      if( $transaction['filename'] != '' ){
        @unlink('attachment/transfers/'.$transaction['filename']);
      }

      // delete transaction
      $result = $pdo->prepare("DELETE FROM `transfers` WHERE id = :id");
      $result->bindParam(':id',$id);
      $result->execute();
      $output = array('status'=>'success','message'=>'Transaction deleted successfully');
    }
  }


  // get transaction details
  if( $action == "getTransaction" ){
    $id = isset($_POST['id']) ? htmlspecialchars(strip_tags($_POST['id'])) : '';
    if( $id == '' ){
      $output = array('status'=>'error','message'=>'Invalid transaction');
    }else{
      $result = $pdo->prepare("SELECT * FROM `transfers` WHERE id = :id");
      $result->bindParam(':id',$id);
      $result->execute();
      $transaction = $result->fetch(\PDO::FETCH_ASSOC);

      $transaction['datetime'] = date('Y-m-d',strtotime($transaction['datetime']));

      $output = array('status'=>'success','message'=>'Transaction details','trx'=>$transaction);
    }
  }

  if( $action == "updateTransaction" ){

    $id = isset($_POST['idEdit']) ? htmlspecialchars(strip_tags($_POST['idEdit'])) : '';
    $dateEdit = isset($_POST['dateEdit']) ? htmlspecialchars(strip_tags($_POST['dateEdit'])) : '';
    $fromAccountEdit = isset($_POST['fromAccountEdit']) ? htmlspecialchars(strip_tags($_POST['fromAccountEdit'])) : '';
    $toAccountEdit = isset($_POST['toAccountEdit']) ? htmlspecialchars(strip_tags($_POST['toAccountEdit'])) : '';
    $remarksEdit = isset($_POST['remarksEdit']) ? htmlspecialchars(strip_tags($_POST['remarksEdit'])) : '';
    $trxNumberEdit = isset($_POST['trxNumberEdit']) ? htmlspecialchars(strip_tags($_POST['trxNumberEdit'])) : '';
    $amountEdit = isset($_POST['amountEdit']) ? htmlspecialchars(strip_tags($_POST['amountEdit'])) : '';
    $amountConfirmEdit = isset($_POST['amountConfirmEdit']) ? htmlspecialchars(strip_tags($_POST['amountConfirmEdit'])) : '';
    $chargesEdit = isset($_POST['chargesEdit']) ? htmlspecialchars(strip_tags($_POST['chargesEdit'])) : '';
    $exchangeRateEdit = isset($_POST['exchangeRateEdit']) ? htmlspecialchars(strip_tags($_POST['exchangeRateEdit'])) : '';
    $filenameEdit = isset($_FILES['filenameEdit']) ? $_FILES['filenameEdit'] : ['name' => ''];

    $errors = [];
    if( $dateEdit == '' ){
      $errors['dateEdit'] = 'Date is required';
    }elseif( $dateEdit > date('Y-m-d') ){
      $errors['dateEdit'] = 'Date must be less than or equal to today';
    }

    if( $fromAccountEdit == '' ){
      $errors['fromAccountEdit'] = 'From Account is required';
    }
    if( $toAccountEdit == '' ){
      $errors['toAccountEdit'] = 'To Account is required';
    }
    // check if both accounts are the same
    if( $fromAccountEdit == $toAccountEdit ){
      $errors['toAccountEdit'] = 'To Account must be different from From Account';
    }

    $amountEdit = str_replace(',','',$amountEdit);
    if( $amountEdit == '' ){
      $errors['amountEdit'] = 'Amount is required';
    }elseif( !is_numeric($amountEdit) ){
      $errors['amountEdit'] = 'Amount must be a number';
    }elseif( $amountEdit <= 0 ){
      $errors['amountEdit'] = 'Amount must be greater than 0';
    }

    if( $amountConfirmEdit == '' ){
      $errors['amountConfirmEdit'] = 'Confirm Amount is required';
    }elseif( $amountEdit != $amountConfirmEdit ){
      $errors['amountConfirmEdit'] = 'Amount and Confirm Amount must be the same';
    }

    if( count($errors) ){
      $output = array('status'=>'error','message'=>'form_errors','errors'=>$errors);
    }else{

      // load transaction
      $result = $pdo->prepare("SELECT * FROM `transfers` WHERE id = :id");
      $result->bindParam(':id',$id);
      $result->execute();
      $transaction = $result->fetch(\PDO::FETCH_ASSOC);

      if( !$transaction ){
        $output = array('status'=>'error','message'=>'Invalid transaction');
      }else{

        $filename = $transaction['filename'];
        if( $filenameEdit['name'] != '' ){
          @unlink('attachment/transfers/'.$transaction['filename']);
          $filename = str_replace(" ","_",time().'_'.$filenameEdit['name']);
          $fileUploadStatus = move_uploaded_file($filenameEdit['tmp_name'],'attachment/transfers/'.$filename);
          if( !$fileUploadStatus ){
            $output = array('status'=>'error','message'=>'file_upload_error','errors'=>['filenameEdit'=>'Error uploading file']);
          }
        }

        if( $dateEdit != date('Y-m-d',strtotime($transaction['datetime'])) ){
          $datetime = date('Y-m-d H:i:s',strtotime( date($dateEdit . ' ' . date('H:i:s')) ));
        }else{
          $datetime = $transaction['datetime'];
        }

        $result = $pdo->prepare("UPDATE `transfers` SET `datetime` = :datetime, `from_account` = :from_account, `to_account` = :to_account, `remarks` = :remarks, `trx` = :trx, `amount` = :amount, `charges` = :charges, `exchange_rate` = :exchange_rate, `filename` = :filename WHERE id = :id");
        $result->bindParam(':datetime', $datetime);
        $result->bindParam(':from_account', $fromAccountEdit);
        $result->bindParam(':to_account', $toAccountEdit);
        $result->bindParam(':remarks', $remarksEdit);
        $result->bindParam(':trx', $trxNumberEdit);
        $result->bindParam(':amount', $amountEdit);
        $result->bindParam(':charges', $chargesEdit);
        $result->bindParam(':exchange_rate', $exchangeRateEdit);
        $result->bindParam(':filename', $filename);
        $result->bindParam(':id', $id);
        $result->execute();

        $output = array('status'=>'success','message'=>'Transaction updated successfully');

      }
    }
  }


  header("Content-Type: application/json");
  echo json_encode($output);