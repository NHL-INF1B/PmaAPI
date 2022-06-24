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
    $id = htmlentities($arr['id']);
    $title = htmlentities($arr['title']);
    $description = htmlentities($arr['description']);
    $date = htmlentities($arr['date']);
    $time_start = htmlentities($arr['time_start']);
    $time_end = htmlentities($arr['time_end']);
    $userId = htmlentities($arr['userId']);
    $projectId = htmlentities($arr['projectId']);

    //Validate fields
    if ($error = validateFields($title, $description, $date, $time_start, $time_end)) {
        echo json_encode($error);
    } else {
        $query = "UPDATE timesheet SET title = ?, description = ?, date = ?, time_start = ?, time_end = ? WHERE id = ?";

        //Sending data to the database
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $title, $description, $date, $time_start, $time_end, $id);
        mysqli_stmt_execute($stmt);

        //Send back response (JSON)
        echo json_encode("data_updated");   
        
        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }
} else {
    echo json_encode('No data sent');
}

/**
 * Function to validate fields
 */
function validateFields ($title, $description, $date, $time_start, $time_end) {

    if (!isset($title) || !filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error = 'title_incorrect';
    }
    if (!isset($description) || !filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error = 'description_incorrect';
    }
    if (!isset($date) || !filter_var($date, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error = 'date_incorrect';
    }
    if (!isset($time_start) || !filter_var($time_start, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error = 'time_start_incorrect';
    }
    if (!isset($time_end) || !filter_var($time_end, FILTER_SANITIZE_SPECIAL_CHARS)) {
        $error = 'time_end_incorrect';
    }
    if ($time_start > $time_end) {
        $error = 'times_invalid';
    }
    if ($time_start == $time_end) {
        $error = 'times_equal';
    }
    if (empty($error)) {
        return false;
    } else {
        return $error;
    }
}
?>



