<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$query = "DELETE FROM warning WHERE id = ?";

//Sending data to the database
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

//All value's that will be send back to the application
$WarningDeleteValues['id'] = $id;
$WarningDeleteValues['reason'] = $reason;
$WarningDeleteValues['user_id'] = $user_id;
$WarningDeleteValues['project_id'] = $project_id;

//Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

//Send back response (JSON)
echo json_encode($WarningDeleteValues);   


  
