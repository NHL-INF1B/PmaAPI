<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/** 
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); 

if (isset($arr)) {
    $title = htmlentities($arr['title']);
    $description = htmlentities($arr['description']);
    $date = htmlentities($arr['date']);
    $time_start = htmlentities($arr['time_start']);
    $time_end = htmlentities($arr['time_end']);
    $user_id = 1;
    $project_id = 1;

    $query = "INSERT INTO timesheet (title, description, date, time_start, time_end, user_id, project_id) VALUES (?,?,?,?,?,?,?)";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssii", $title, $description, $date, $time_start, $time_end, $user_id, $project_id);
    mysqli_stmt_execute($stmt);

    //All value's that will be send back to the application
    $HourEditValues[0]['id'] = mysqli_insert_id($conn);
    $HourEditValues[0]['title'] = $title;
    $HourEditValues[0]['description'] = $description;
    $HourEditValues[0]['date'] = $date;
    $HourEditValues[0]['time_start'] = $time_start;
    $HourEditValues[0]['time_end'] = $time_end;
    $HourEditValues[0]['user_id'] = $user_id;
    $HourEditValues[0]['project_id'] = $project_id;

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($HourEditValues);   
}

/**
 * Function to validate fields
 */
function validateFields ($title, $description, $date, $time_start, $time_end) {
    $error = array();
    if (!isset($title) || !filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'title_incorrect';
    }
    if (!isset($description) || !filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'description_incorrect';
    }
    if (!isset($date) || !filter_var($date, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'date_incorrect';
    }
    if (!isset($time_start) || !filter_var($time_start, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'time_start_incorrect';
    }
    if (!isset($time_end) || !filter_var($time_end, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'time_end_incorrect';
    }
    if (!isset($user_id) || !filter_var($user_id, FILTER_SANITIZE_NUMBER_INT)) {
        $error[] = 'user_id_incorrect';
    }
    if (!isset($project_id) || !filter_var($project_id, FILTER_SANITIZE_NUMBER_INT)) {
        $error[] = 'project_id_incorrect';
    }

    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}
