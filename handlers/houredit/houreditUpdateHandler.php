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

    //Validate fields
    if ($error = validateFields($title, $description, $date, $time_start, $time_end)) {
        echo json_encode($error);
    } else {
        $query = "UPDATE timesheet SET title = ?, description = ?, date = ?, time_start = ?, time_end = ? WHERE id = ?";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $date, $time_start, $time_end, $id);
        mysqli_stmt_execute($stmt);

        //All value's that will be send back to the application
        $HourUpdateValues['id'] = $id;
        $HourUpdateValues['title'] = $title;
        $HourUpdateValues['description'] = $description;
        $HourUpdateValues['date'] = $date;
        $HourUpdateValues['time_start'] = $time_start;
        $HourUpdateValues['time_end'] = $time_end;

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($HourUpdateValues);        
    }
} else {
    echo json_encode('No data sent');
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
    // $date = preg_replace("([^0-9/])", "", $_POST['date']);
    if (!isset($date) || !filter_var($date, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'date_incorrect';
    }
    if (!isset($time_start) || !filter_var($time_start, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'time_start_incorrect';
    }
    if (!isset($time_end) || !filter_var($time_end, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error[] = 'time_end_incorrect';
    }

    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}
?>


