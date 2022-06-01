<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/** 
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $reason = htmlentities($arr['reason']);

    //Validate fields
    if ($error = validateFields($reason)) {
        echo json_encode($error);
    } else {
        $query = "INSERT INTO warning (reason, user_id, project_id) VALUES (?,?,?)";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sii", $reason, $user_id, $project_id);
        mysqli_stmt_execute($stmt);

        //All value's that will be send back to the application
        $WarningInsertValues[0]['id'] = mysqli_insert_id($conn);
        $WarningInsertValues[0]['reason'] = $reason;
        $WarningInsertValues[0]['user_id'] = $user_id;
        $WarningInsertValues[0]['project_id'] = $project_id;

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($WarningInsertValues);   
    }
}

/**
 * Function to validate fields
 */
function validateFields ($reason) {
    $error = array();

    if (!isset($reason) || !filter_var($reason, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'reason_incorrect';
    }

    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}



