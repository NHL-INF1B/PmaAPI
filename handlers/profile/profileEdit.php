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
    $name = htmlentities($arr['name']);
    $email = htmlentities($arr['email']);
    $dateOfBirth = htmlentities($arr['dateOfBirth']);
    $phoneNumber = htmlentities($arr['phoneNumber']);
    $discord = htmlentities($arr['discord']);

    //Validate fields
    if ($error = false) {
        echo json_encode($error);
    } else {
        //Sending data to the database
        $query = "UPDATE user SET name = ?, email = ?, dateOfBirth = ?, phoneNumber = ?, discord = ? WHERE id = ?;";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $name, $email, $dateOfBirth, $phoneNumber, $discord, $id);
        mysqli_stmt_execute($stmt);

        //All value's that will be send back to the application
        $respond = "data_updated";

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($respond);
    }
} else {
    echo json_encode('No data sent');
}
