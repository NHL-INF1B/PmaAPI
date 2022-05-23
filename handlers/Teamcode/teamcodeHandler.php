<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    // $name = htmlentities($arr['name']);
    // $qrcode = htmlentities($arr['qrcode']);
    $teamcode = htmlentities($arr['teamcode']);

    //$planning = "test";
    // $name = "Test";
    // $qrcode = "test";
    // $teamcode = 1;

    $query = "INSERT INTO project (teamcode) VALUES (?)";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $teamcode);
    mysqli_stmt_execute($stmt);
    
    //All value's that will be send back to the application
    $PlanningValues[0]['id'] = mysqli_insert_id($conn);
    // $PlanningValues[0]['name'] = $name;
    // $PlanningValues[0]['qrcode'] = $qrcode;
    $PlanningValues[0]['teamcode'] = $teamcode;

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($PlanningValues);

    //Validate fields
    // if ($error = validateFields($planning, $week, $activiteit, $project_id)) {
    //     echo json_encode($error);
    // } else {
    //     $week = 1;
    //     $project_id = 1;

    //     $query = "INSERT INTO shedule_line (week, activiteit, project_id) VALUES (?,?,?)";
    
    //     //Sending data to the database
    //     $stmt = mysqli_prepare($conn, $query);
    //     mysqli_stmt_bind_param($stmt, "isi", $week, $activiteit, $project_id);
    //     mysqli_stmt_execute($stmt);
        
    //     //All value's that will be send back to the application
    //     $PlanningValues[0]['id'] = mysqli_insert_id($conn);
    //     $PlanningValues[0]['week'] = $week;
    //     $PlanningValues[0]['activiteit'] = $activiteit;
    //     $PlanningValues[0]['project_id'] = $project_id;

    //     //Close the statement and connection
    //     mysqli_stmt_close($stmt);
    //     mysqli_close($conn);

    //     //Send back response (JSON)
    //     echo json_encode($PlanningValues);
    // }
} else {
    echo json_encode('No data send');
}

/**
 * Function to validate fields
 */
// function validateFields ($name, $qrcode, $teamcode) {
//     $error = array();

//     if (!isset($name) || !filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS)) {
//         $name[] = 'name_incorrect';
//     }
//     if (!isset($qrcode) || !filter_var($qrcode, FILTER_SANITIZE_SPECIAL_CHARS)) {
//         $qrcode[] = 'qrcode_incorrect';
//     }
    
//     if (!isset($teamcode) || !filter_var($teamcode, FILTER_SANITIZE_SPECIAL_CHARS)) {
//         $teamcode[] = 'teamcode_incorrect';
//     }
    
//     if (!empty($error)) {
//         return $error;
//     } else {
//         return false;
//     }
// }

/**
 * Function to check if Planning already exists in database
 */
// function checkact$activiteitInDataBase($conn, $activiteit) {
//     $error = array();

//     $query = "SELECT * FROM Planning WHERE act$activiteit = ?";

//     $stmt = mysqli_prepare($conn, $query);
//     mysqli_stmt_bind_param($stmt, "s", $activiteit);
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_bind_result($stmt, $ID, $week, $activiteit, $password, $project_id, $phoneNumber, $discord);
//     mysqli_stmt_store_result($stmt);

//     //Check if a result has been found with number of rows
//     if (mysqli_stmt_num_rows($stmt) > 0) {
//         mysqli_stmt_close($stmt);
//         mysqli_close($conn);
//         $error[] = 'act$activiteit_in_use';
//         return $error;
//     } else {
//         mysqli_stmt_close($stmt);
//         return false;
//     }
// }

  if(!empty($_FILES['file_attachment']['name']))
  {
    $target_dir = "uploads/";
    if (!file_exists($target_dir))
    {
      mkdir($target_dir, 0777);
    }
    $target_file =
      $target_dir . basename($_FILES["file_attachment"]["name"]);
    $imageFileType = 
      strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if file already exists
    if (file_exists($target_file)) {
      echo json_encode(
         array(
           "status" => 0,
           "data" => array()
           ,"msg" => "Sorry, file already exists."
         )
      );
      die();
    }
    // Check file size
    if ($_FILES["file_attachment"]["size"] > 50000000) {
      echo json_encode(
         array(
           "status" => 0,
           "data" => array(),
           "msg" => "Sorry, your file is too large."
         )
       );
      die();
    }
    if (
      move_uploaded_file(
        $_FILES["file_attachment"]["tmp_name"], $target_file
      )
    ) {
      echo json_encode(
        array(
          "status" => 1,
          "data" => array(),
          "msg" => "The file " . 
                   basename( $_FILES["file_attachment"]["name"]) .
                   " has been uploaded."));
    } else {
      echo json_encode(
        array(
          "status" => 0,
          "data" => array(),
          "msg" => "Sorry, there was an error uploading your file."
        )
      );
    }
  }

/*  Update Images*/
// class MyClass {


// public function uploadImage() {	
//     if(!empty($_FILES['file_attachment']['name'])) {
//       $res        = array();
//       $name       = 'file_attachment';
//       $imagePath 	= 'assets/upload/file_attachment';
//       $temp       = explode(".",$_FILES['file_attachment']['name']);
//       $extension 	= end($temp);
//       $filenew 	= str_replace(
//                       $_FILES['file_attachment']['name'],
//                       $name,
//                       $_FILES['file_attachment']['name']) . 
//                       '_' . time() . '' . "." . $extension;  		
//       $config['file_name']   = $filenew;
//       $config['upload_path'] = $imagePath;
//       $this->upload->initialize($config);
//       $this->upload->set_allowed_types('*');
//       $this->upload->set_filename($config['upload_path'],$filenew);
//       if(!$this->upload->do_upload('file_attachment')) {
//         $data = array('msg' => $this->upload->display_errors());
//       } else {
//         $data = $this->upload->data();	
//         if(!empty($data['file_name'])){
//           $res['image_url'] = 'assets/upload/file_attachment/' .
//                               $data['file_name']; 
//         }
//         if (!empty($res)) {
//       echo json_encode(
//             array(
//               "status" => 1,
//               "data" => array(),
//               "msg" => "upload successfully",
//              // "base_url" => base_url(),
//               "count" => "0"
//             )
//           );
//         }else{
//       echo json_encode(
//             array(
//               "status" => 1,
//               "data" => array(),
//               "msg" => "not found",
//             //  "base_url" => base_url(),
//               "count" => "0"
//             )
//           );
//         }
//       }
//     }
//   }
// }
?>