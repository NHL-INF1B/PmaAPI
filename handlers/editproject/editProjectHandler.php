<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/** 
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    //Bind data from the input fields to variablesF
    $id = htmlentities($arr['id']);
    $name = htmlentities($arr['name']);

    //Validate fields
    if ($error = validateFields($name)) {
        echo json_encode($error);
    } else {
        $query = "UPDATE project SET name = ? WHERE id = ?";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "si", $name, $id);
        mysqli_stmt_execute($stmt);

        //All values that will be send back to the application
        //Send back response (JSON)
        echo json_encode("data_updated");

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} else {
    echo json_encode('No data sent');
}

/**
 * Function to validate fields
 */
function validateFields($name)
{
    $error = array();

    if (!isset($name) || !filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'title_incorrect';
    }

    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}
