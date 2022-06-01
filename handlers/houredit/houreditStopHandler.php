<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $title = htmlentities($arr['title']);
    $description = htmlentities($arr['description']);
    $date = htmlentities($arr['date']);
    //Set time_end to current time
    $time_end = date("Y-m-d H:i:s");
    // $user_id = 1;
    // $project_id = 1;
    // $id = 1;

    $query = "UPDATE timesheet SET time_end = ? WHERE id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $time_end, $id);
    mysqli_stmt_execute($stmt);

    //All value's that will be send back to the application
    $HourEditStopValues['id'] = $id;
    $HourEditStopValues['title'] = $title;
    $HourEditStopValues['description'] = $description;
    $HourEditStopValues['date'] = $date;
    $HourEditStopValues['time_end'] = $time_end;

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($HourEditStopValues);   
} else {
    echo json_encode('No data send');
}
  
