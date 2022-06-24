<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/** 
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    //Bind data from the input fields to variables and set current date & time_start
    $title = htmlentities($arr['title']);
    $description = htmlentities($arr['description']);
    $date = date("Y-m-d");
    $time_start = date("H:i");
    $time_end = "00:00";
    $userId = htmlentities($arr['userId']);
    $projectId = htmlentities($arr['projectId']);

    //Validate fields
    if ($error = validateFields($title, $description)) {
        echo json_encode($error);
    } else {
        $query = "INSERT INTO timesheet (title, description, date, time_start, time_end, user_id, project_id) VALUES (?,?,?,?,?,?,?)";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssii", $title, $description, $date, $time_start, $time_end, $userId, $projectId);
        mysqli_stmt_execute($stmt);

        //All value's that will be send back to the application
        $HourEditStartValues['id'] = mysqli_insert_id($conn);
        $HourEditStartValues['title'] = $title;
        $HourEditStartValues['description'] = $description;
        $HourEditStartValues['date'] = $date;
        $HourEditStartValues['time_start'] = $time_start;
        $HourEditStartValues['time_end'] = $time_end;

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($HourEditStartValues);
    }
} else {
    echo json_encode('No data sent');
}

/**
 * Function to validate fields
 */
function validateFields($title, $description)
{
    $error = array();
    if (!isset($title) || !filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'title_timer_incorrect';
    }
    if (!isset($description) || !filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'description_timer_incorrect';
    }

    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}
