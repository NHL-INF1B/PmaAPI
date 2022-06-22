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

    $sql = "DELETE  p.*, pm.*, i.*, sl.*, ts.*, w.* 
        FROM project AS p 
        LEFT JOIN projectmember AS pm ON pm.project_id = p.id 
        LEFT JOIN invite AS i ON i.project_id = p.id 
        LEFT JOIN schedule_line AS sl ON sl.project_id = p.id 
        LEFT JOIN timesheet AS ts ON ts.project_id = p.id 
        LEFT JOIN warning AS w ON w.project_id = p.id 
        WHERE p.id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    echo json_encode("data_deleted");
} else {
    echo json_encode('No data sent');
}



