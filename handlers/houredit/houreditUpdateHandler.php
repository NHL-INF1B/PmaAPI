<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $title = htmlentities($arr['title']);
    $description = htmlentities($arr['description']);
    $date = htmlentities($arr['date']);
    $time_start = date("Y-m-d H:i:s");
    $time_end = htmlentities($arr['time_end']);
    $user_id = 1;
    $project_id = 1;

    $time_end = date("Y-m-d H:i:s");

    $query = "UPDATE timesheet SET time_end = ? WHERE id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $time_end);
    mysqli_stmt_execute($stmt);

    //All value's that will be send back to the application
    $HourEditUpdateValues[0]['id'] = mysqli_insert_id($conn);
    $HourEditUpdateValues[0]['title'] = $title;
    $HourEditUpdateValues[0]['description'] = $description;
    $HourEditUpdateValues[0]['date'] = $date;
    $HourEditUpdateValues[0]['time_start'] = $time_start;
    $HourEditUpdateValues[0]['time_end'] = $time_end;
    $HourEditUpdateValues[0]['user_id'] = $user_id;
    $HourEditUpdateValues[0]['project_id'] = $project_id;

    echo $time_start -
    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($HourEditUpdateValues);   
}

  
