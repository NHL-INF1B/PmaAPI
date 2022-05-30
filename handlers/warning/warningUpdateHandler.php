<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $reason = htmlentities($arr['reason']);

    $query = "UPDATE warning SET reason = ? WHERE id = 1";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $reason);
    mysqli_stmt_execute($stmt);

    //All value's that will be send back to the application
    $WarningUpdateValues[0]['id'] = mysqli_insert_id($conn);
    $WarningUpdateValues[0]['reason'] = $reason;
    $WarningUpdateValues[0]['user_id'] = $user_id;
    $WarningUpdateValues[0]['project_id'] = $project_id;

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($WarningUpdateValues);   
}

  
