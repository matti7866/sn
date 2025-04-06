<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('location:../../../login.php');
}
function upload_Image($folder){
        $new_image_name = '';
        if(!empty($_FILES['uploaderFile']['name'])){
            if($_FILES['uploaderFile']['size']<=2097152){
                $extension = explode(".", $_FILES['uploaderFile']['name']);
                $f_name = '';
                $f_ext = '';
                if(count($extension) > 2){
                    for($i = 0; $i< count($extension); $i++){
                        if(count($extension) == $extension[$i]){
                            $f_name  = $f_name . $extension[$i];
                        }else{
                            $f_ext = $extension[$i];
                        }
                    }
                }else{
                    $f_name =  $extension[0];
                    $f_ext = $extension[1];
                }
                $ext = array("txt", "pdf", "doc", "docx","xls","xlsx","jpg","jpeg","png","ppt");
                if (in_array(strtolower($extension[1]), $ext))
                {
                    $new_image_name = $f_name . "." . date("Y/m/d h:i:s") . $f_ext;
                    $new_image_name = md5($new_image_name);
                    $new_image_name = '../../../' . $folder .'/'. $new_image_name. '.' .$f_ext;
                    $destination = $new_image_name;
                    move_uploaded_file($_FILES['uploaderFile']['tmp_name'],$destination);
                }else{
                    $new_image_name = '';
                }
                
            }else{
                $new_image_name = ''; 
            }
        }else{
            $new_image_name = '';
        }
        return $new_image_name;
    }
?>