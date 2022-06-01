<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$query = "DELETE FROM timesheet WHERE id = ?";

//Sending data to the database
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

//All value's that will be send back to the application
$HourDeleteValues[0]['id'] = $id;
$HourDeleteValues[0]['title'] = $title;
$HourDeleteValues[0]['description'] = $description;
$HourDeleteValues[0]['time_start'] = $time_start;
$HourDeleteValues[0]['time_end'] = $time_end;
$HourDeleteValues[0]['user_id'] = $user_id;
$HourDeleteValues[0]['project_id'] = $project_id;

//Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

//Send back response (JSON)
echo json_encode($HourDeleteValues);   



