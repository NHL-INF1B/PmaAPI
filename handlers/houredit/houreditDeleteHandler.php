<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/** 
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $id = htmlentities($arr['id']);

    $sql = "DELETE FROM timesheet WHERE id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $sql) or die ("prepare");
    mysqli_stmt_bind_param($stmt, "i", $id)or die ("bind");
    mysqli_stmt_execute($stmt)or die ("exec");

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode('No data sent');
}



