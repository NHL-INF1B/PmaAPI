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
    $name = htmlentities($arr['name']);
    $email = htmlentities($arr['email']);
    $dateOfBirth = htmlentities($arr['dateOfBirth']);
    $password = htmlentities($arr['password']);
    $confirmPassword = htmlentities($arr['confirmPassword']);

    //Validate fields
    if ($error = validateFields($name, $email, $dateOfBirth, $password, $confirmPassword)) {
        echo json_encode($error);
    } else {
        //Verify email does not exist
        if ($error = checkEmailInDataBase($conn, $email)) {
            echo json_encode($error);
        } else {
            $userValues = array();
            $password = password_hash($password, PASSWORD_DEFAULT);
            $phoneNumber = "";
            $discord = "";

            //Sending data to the database
            $query = "INSERT INTO user (name, email, password, dateOfBirth, phoneNumber, discord) VALUES (?,?,?,?,?,?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dateOfBirth, $phoneNumber, $discord);
            mysqli_stmt_execute($stmt);

            //All value's that will be send back to the application
            $userValues[0]['id'] = mysqli_insert_id($conn);
            $userValues[0]['name'] = $name;
            $userValues[0]['email'] = $email;
            $userValues[0]['dateOfBirth'] = $dateOfBirth;
            // $userValues['password'] = $password; //Probaly won't send...

            //Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            //Send back response (JSON)
            echo json_encode($userValues);
        }
    }
} else {
    echo json_encode('No data send');
}

/**
 * Function to validate fields
 */
function validateFields($name, $email, $dateOfBirth, $password, $confirmPassword)
{
    $error = array();

    if (!isset($name) || !filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^[A-Za-z][A-Za-z0-9]{0,49}$/', $name)) {
        $error[] = 'name_incorrect';
    }
    if (!isset($email) || !filter_var($email, FILTER_SANITIZE_EMAIL)) {
        $error[] = 'email_incorrect';
    }
    //Hier regex voor datum <----------------------------------!-!------------------------------------->
    if (!isset($dateOfBirth) || !filter_var($dateOfBirth, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/', $dateOfBirth)) {
        $error[] = 'dateOfBirth_incorrect';
    }
    if (!isset($password) || !filter_var($password, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,254}+$/', $password)) {
        $error[] = 'password_incorrect';
    }
    if (!isset($confirmPassword) || !filter_var($confirmPassword, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,254}+$/', $confirmPassword)) {
        $error[] = 'confirmPassword_incorrect';
    }
    if ($password != $confirmPassword) {
        $error[] = 'samePassword_incorrect';
    }

    if (!empty($error)) {
        return $error;
    } else {
        return false;
    }
}

/**
 * Function to check if user already exists in database
 */
function checkEmailInDataBase($conn, $email)
{
    $error = array();

    $query = "SELECT * FROM user WHERE email = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $ID, $name, $email, $password, $dateOfBirth, $phoneNumber, $discord);
    mysqli_stmt_store_result($stmt);

    //Check if a result has been found with number of rows
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        $error[] = 'email_in_use';
        return $error;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}
