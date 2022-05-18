<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.

if (isset($arr)) {
    $email = htmlentities($arr['email']);
    $password = htmlentities($arr['password']);

    if ($error = validateFields($email, $password)) {
        echo json_encode($error);
    } else  {
        $userValues = array();
        $error = array();

        //Requesting data from the database
        $query = "SELECT * FROM user WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $email, $hashedPass, $dateOfBirth, $phoneNumber, $discord);
        while (mysqli_stmt_fetch($stmt)) {}

        //Checking user credentials
        if (mysqli_stmt_num_rows($stmt) > 0) {
            if (password_verify($password, $hashedPass)) {
                //All value's that will be send back to the application
                $userValues[0]['id'] = $id;
                $userValues[0]['name'] = $name;
                $userValues[0]['email'] = $email;
                $userValues[0]['dateOfBirth'] = $dateOfBirth;
                $userValues[0]['phoneNumber'] = $phoneNumber;
                $userValues[0]['discord'] = $discord;

                //Close the statement and connection
                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                //Send back response (JSON)
                echo json_encode($userValues);
            } else {
                $error[] = 'login_incorrect';
                echo json_encode($error);
            }
        } else {
            $error[] = 'user_not_exists';
            echo json_encode($error);
        }
    }
} else {
    echo json_encode('No data send');
}

function validateFields($email, $password) {
    $error = array();

    if (!isset($email) || !filter_var($email, FILTER_SANITIZE_EMAIL)) {
        $error[] = 'email_incorrect';
    }
    if (!isset($password) || !filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'password_incorrect';
    }

    if (!empty($error)) {
        return $error;
    } else {
        return false;
    }
}