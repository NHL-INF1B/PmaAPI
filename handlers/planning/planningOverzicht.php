<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

// $projectid = 7;

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.

if (isset($arr)) {
    $projectid = htmlentities($arr['projectid']);

    //Validate fields
    if ($error = false) { //Heeft nog validatie nodig! <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        echo json_encode($error);
    } else {
        $results = array();

        //Sending data to the database
        $query = "SELECT id, week, activiteit FROM schedule_line WHERE project_id = ? ORDER BY week ASC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $projectid);
        mysqli_stmt_execute($stmt);

        $res = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        if(!empty($result)){
            echo json_encode($result);
        } else {
            echo json_encode("NO_DATA");
        }
    }
} else {
    echo json_encode('No data send');
}
