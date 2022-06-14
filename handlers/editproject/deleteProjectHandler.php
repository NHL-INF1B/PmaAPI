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

    $sql = "DELETE p, pm, w, i, s, t FROM project p 
            JOIN projectmember pm ON pm.project_id = p.id 
            JOIN warning w ON w.project_id = p.id
            JOIN invite i ON i.project_id = p.id
            JOIN schedule_line s ON s.project_id = p.id
            JOIN timesheet t ON t.project_id = p.id
            WHERE p.id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    echo json_encode('No data sent');
}



