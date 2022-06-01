<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $reason = htmlentities($arr['reason']);

    //Validate fields
    if ($error = validateFields($reason)) {
        echo json_encode($error);
    } else {
        $query = "UPDATE warning SET reason = ?, user_id = ? WHERE id = ?";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sii", $reason, $user_id, $id);
        mysqli_stmt_execute($stmt);

        //All value's that will be send back to the application
        $WarningUpdateValues['id'] = $id;
        $WarningUpdateValues['reason'] = $reason;

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($WarningUpdateValues);        
    }
} else {
    echo json_encode('No data sent');
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

  
