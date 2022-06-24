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
    $scheduleId = htmlentities($arr['scheduleId']);

    $query = "SELECT week, activiteit FROM schedule_line WHERE id = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $scheduleId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $week, $activity);
    mysqli_stmt_store_result($stmt);

    $scheduleValues = array();

    while (mysqli_stmt_fetch($stmt)) {
    }

    //Checking if there are any schedule_lines with the scheduleId
    if (mysqli_stmt_num_rows($stmt) > 0) {
        //All value's that will be send back to the application
        $scheduleValues['week'] = $week;
        $scheduleValues['activity'] = $activity;
    } else {
        echo json_encode("No results");
    }

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($scheduleValues);
} else {
    echo json_encode('No data sent');
}
