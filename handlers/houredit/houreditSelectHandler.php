<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $id = htmlentities($arr['id']);

    $sql = "SELECT * FROM timesheet WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $title, $description, $date, $time_start, $time_end, $user_id, $project_id);
    mysqli_stmt_store_result($stmt);

    $result = array();

    while (mysqli_stmt_fetch($stmt)) { }

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $result['id'] = $id;
        $result['title'] = $title;
        $result['description'] = $description;
        $result['date'] = $date;
        $result['time_start'] = $time_start;
        $result['time_end'] = $time_end;
    } else {
        echo json_encode("kapot");
    }

    //Close the statement and the connection
     mysqli_stmt_close($stmt);
     mysqli_close($conn);
     
    //Send back response (JSON)
    echo json_encode($result);
} else {
    echo json_encode('No data sent');
}




