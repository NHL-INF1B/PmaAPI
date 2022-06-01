<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.

if (isset($arr)) {
    $id = htmlentities($arr['id']);

    //Validate fields
    if ($error = false) { // ======================================== Hier moet nog stricte validatie komen
        echo json_encode($error);
    } else {
        $error = array();

        //Recieving data from the database
        $query = "SELECT * FROM schedule_line WHERE project_id = 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name);
        while (mysqli_stmt_fetch($stmt)) {}

        //Checking user exists
        if (mysqli_stmt_num_rows($stmt) > 0) {
            //Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            //Send back response (JSON)
            echo json_encode($name);
        } else {
            $error[] = 'Planning does not exists';
            echo json_encode($error);
        }
    }
} else {
    echo json_encode('No data send');
}

function checkIdInDB () {
    
}