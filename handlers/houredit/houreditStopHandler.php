<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    //Bind data from the input fields to variables and set time_end
    $id = htmlentities($arr['id']);
    $title = htmlentities($arr['title']);
    $description = htmlentities($arr['description']);
    $date = htmlentities($arr['date']);
    $time_start = htmlentities($arr['time_start']);
    $time_end = date("H:i");
    $user_id = htmlentities($arr['user_id']);
    $project_id = htmlentities($arr['project_id']);

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
    $HourEditStopValues['time_start'] = $time_start;
    $HourEditStopValues['time_end'] = $time_end;

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($HourEditStopValues);   
} else {
    echo json_encode('No data sent');
}
  
