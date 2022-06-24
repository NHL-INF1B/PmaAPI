<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/** 
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    //Bind data from the input fields to variables
    $id = htmlentities($arr['id']);

    //delete the houredit.
    $sql = "DELETE FROM timesheet WHERE id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    echo json_encode("data_deleted");
} else {
    echo json_encode('No data sent');
}



