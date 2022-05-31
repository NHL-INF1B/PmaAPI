<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

    $sql = "UPDATE timesheet SET title, description, time_start, time_end VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $time_start, $time_end);
    mysqli_stmt_execute($stmt);

    echo "\n";
    $query = mysqli_query($conn, $sql);
    $result = array();

    while ($res = mysqli_fetch_assoc($query)) {
        $result[] = $res;
    }

    //Send back response (JSON)
    echo json_encode($result);
?>



