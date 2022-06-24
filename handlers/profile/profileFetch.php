<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $userId = htmlentities($arr['userId']);

    //Validate fields
    if ($error = false) {
        echo json_encode($error);
    } else {
        $error = array();
        $userValues = array();

        //Recieving data from the database
        $query = "SELECT `name`, `email`, `dateOfBirth`, `phoneNumber`, `discord` FROM user WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name, $email, $dateOfBirth, $phoneNumber, $discord);
        while (mysqli_stmt_fetch($stmt)) {
        }

        //Checking user exists
        if (mysqli_stmt_num_rows($stmt) > 0) {
            //All value's that will be send back to the application
            $userValues['id'] = $userId;
            $userValues['name'] = $name;
            $userValues['email'] = $email;
            $userValues['dateOfBirth'] = $dateOfBirth;
            $userValues['phoneNumber'] = $phoneNumber;
            $userValues['discord'] = $discord;

            //Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            //Send back response (JSON)
            echo json_encode($userValues);
        } else {
            $error[] = 'User does not exists';
            echo json_encode($error);
        }
    }
} else {
    echo json_encode('No data send');
}
