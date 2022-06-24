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
    $roleId = htmlentities($arr['role']);
    $usrId = htmlentities($arr['userId']);

    //Validate fields
    if ($error = false) {
        echo json_encode($error);
    } else {
        //Recieving data from the database
        $query = "UPDATE `projectmember` SET `role_id` = ? WHERE `user_id` = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $roleId, $usrId);
        mysqli_stmt_execute($stmt);

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode('done');
    }
} else {
    echo json_encode('No data send');
}
