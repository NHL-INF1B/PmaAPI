<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.

if (isset($arr)) {
    $name = $arr['name'];
    $email = $arr['email'];
    $dateOfBirth = $arr['dateOfBirth'];
    $password = $arr['password'];

    echo "invalid_email";;
    // echo password_hash($password, PASSWORD_DEFAULT);

    /**
     * Debugging
     */
    // $file = fopen("debug.txt","a");
    // fwrite($file, implode("\n", $arr));
    // fclose($file);

    /**
     * Returning message to app
     */
    // echo json_encode('Data has been send');

    //Do something
} else {
    echo json_encode('No data send');
}

function validateField () {

}
