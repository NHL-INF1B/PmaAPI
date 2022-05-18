<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $planning = htmlentities($arr['planning']);
    // $id = htmlentities($arr['id']);
    $week = htmlentities($arr['week']);
    $activiteit = htmlentities($arr['activiteit']);
    $project_id = htmlentities($arr['project_id']);

    //Validate fields
    if ($error = validateFields($planning, $week, $activiteit, $project_id)) {
        echo json_encode($error);
    // } else {
    //     //Verify act$activiteit does not exist
    //     if ($error = checkact$activiteitInDataBase($conn, $planning)) {
    //         echo json_encode($error);
    //     } else {
    //         $planning = "";
    //        // $id = "";
    //         $week = "";
    //         $activiteit = "";
    //         $project_id = "";
    
            $query = "INSERT INTO shedule_line (week, activiteit, project_id) VALUES (?,?,?)";
    
            //Sending data to the database
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "isi", $week, $activiteit, $project_id);
            mysqli_stmt_execute($stmt);
            
            //All value's that will be send back to the application
            $PlanningValues[0]['id'] = mysqli_insert_id($conn);
            $PlanningValues[0]['week'] = $week;
            $PlanningValues[0]['activiteit'] = $activiteit;
            $PlanningValues[0]['project_id'] = $project_id;
    
            //Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
    
            //Send back response (JSON)
            echo json_encode($PlanningValues);
        }
  //  }
} else {
    echo json_encode('No data send');
}

/**
 * Function to validate fields
 */
function validateFields ($week, $activiteit, $project_id) {
    $error = array();

    if (!isset($week) || !filter_var($week, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^[A-Za-z][A-Za-z0-9]{0,49}$/', $week)) {
        $error[] = 'week_incorrect';
    }
    if (!isset($activiteit) || !filter_var($activiteit, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'activiteit_incorrect';
    }
    
    if (!isset($project_id) || !filter_var($project_id, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/', $project_id)) {
        $error[] = 'project_id_incorrect';
    }
    
    if (!empty($error)) {
        return $error;
    } else {
        return false;
    }
}

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