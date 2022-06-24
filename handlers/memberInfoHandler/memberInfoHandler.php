<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);

if (isset($array)) {
    //Bind data from the input fields to variables
    $memberId = $array["memberId"];

    $memberValues = array();
    $error = array();

    //]requesting data from the database
    $query = "SELECT * FROM user WHERE user.id = ?";
    $stmt = mysqli_prepare($conn, $query) or die("prepare error");
    mysqli_stmt_bind_param($stmt, "i", $memberId) or die("bind param error");
    mysqli_stmt_execute($stmt) or die("exucute error");
    mysqli_stmt_bind_result($stmt, $id, $name, $email, $pass, $dateOfBirth, $phoneNumber, $discord) or die("bind result error");
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_num_rows($stmt);
    while (mysqli_stmt_fetch($stmt)) {
    }

    //if there are more than 0 results.
    if (mysqli_stmt_num_rows($stmt) > 0) {
        //place the data into an array.
        $memberValues[0]['id'] = $id;
        $memberValues['name'] = $name;
        $memberValues['email'] = $email;
        $memberValues['dateOfBirth'] = $dateOfBirth;
        $memberValues['phoneNumber'] = $phoneNumber;
        $memberValues['discord'] = $discord;

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($memberValues);
    } else {
        $error[] = 'user_not_exists';
        echo json_encode($error);
    }
} else {
    echo json_encode("There is no data");
}
