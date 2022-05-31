<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

    $sql = "SELECT * FROM timesheet WHERE project_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $title, $description, $time_start, $time_end, $user_id, $project_id);

    echo "\n";
    $query = mysqli_query($conn, $sql);
    $result = array();

    while ($res = mysqli_fetch_assoc($query)) {
        $result[] = $res;
    }

    //Send back response (JSON)
    echo json_encode($result);
?>


