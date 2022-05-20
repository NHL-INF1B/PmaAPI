<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    // $planning = htmlentities($arr['planning']);
    $weekNummer = htmlentities($arr['week']);
    $activiteit = htmlentities($arr['activiteit']);
    // $project_id = htmlentities($arr['project_id']);

    // $planning = "test";
    // $activiteit = "test";
    // $week = 1;
    $project_id = 1;

    $query = "INSERT INTO schedule_line (week, activiteit, project_id) VALUES (?,?,?)";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isi", $weekNummer, $activiteit, $project_id);
    mysqli_stmt_execute($stmt);
    
    //All value's that will be send back to the application
    $PlanningValues[0]['id'] = mysqli_insert_id($conn);
    $PlanningValues[0]['week'] = $weekNummer;
    $PlanningValues[0]['activiteit'] = $activiteit;
    $PlanningValues[0]['project_id'] = $project_id;

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
function validateFields ($weekNummer, $activiteit, $project_id) {
    $error = array();

    if (!isset($week) || !filter_var($weekNummer, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'week_incorrect';
    }
    if (!isset($activiteit) || !filter_var($activiteit, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'activiteit_incorrect';
    }
    
    if (!isset($project_id) || !filter_var($project_id, FILTER_SANITIZE_SPECIAL_CHARS)) {
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