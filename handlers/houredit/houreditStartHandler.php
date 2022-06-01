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
    // $user_id = 1;
    // $project_id = 1;

    //Validate fields
    if ($error = validateFields($title, $description)) {
        echo json_encode($error);
    } else {
        // Set time_start to current time
        $time_start = date("Y-m-d H:i:s"); 

        $query = "INSERT INTO timesheet (title, description, date, time_start, time_end, user_id, project_id) VALUES (?,?,?,?,?,?,?)";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssii", $title, $description, $date, $time_start, $time_end, $user_id, $project_id);
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
    echo json_encode('No data send');
}

/**
 * Function to validate fields
 */
function validateFields ($title, $description) {
    $error = array();
    if (!isset($title) || !filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'title_incorrect';
    }
    if (!isset($description) || !filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'description_incorrect';
    }

    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}



//.json naar .text ofz