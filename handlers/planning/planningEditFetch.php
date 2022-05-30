<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.

if (isset($arr)) {
    $scheduleId = htmlentities($arr['scheduleId']);

    //Validate fields
    if ($error = false) { //Heeft nog validatie nodig! <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        echo json_encode($error);
    } else {
        $error = array();
        $scheduleValues = array();

        //Recieving data from the database
        $query = "SELECT `id`, `week`, `activiteit` FROM schedule_line WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $scheduleId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $week, $activity);
        while (mysqli_stmt_fetch($stmt)) {}

                //Checking schedule line
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    //All value's that will be send back to the application
                    $scheduleValues[0]['id'] = $id;
                    $scheduleValues[0]['week'] = $week;
                    $scheduleValues[0]['activity'] = $activity;

                    //Close the statement and connection
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    //Send back response (JSON)
                    echo json_encode($scheduleValues);
                } else {
                    $error[] = 'Schedule line does not exists';
                    echo json_encode($error);
                }
    }
} else {
    echo json_encode('No data send');
}

function checkIdInDB () {
    
}