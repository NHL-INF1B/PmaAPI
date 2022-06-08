<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

    $sql = "SELECT id, week, activiteit FROM `schedule_line` WHERE project_id = 1;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $week, $activiteit);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_fetch($stmt);

    $num = mysqli_stmt_num_rows($stmt);
    if($num > 0) {
        $results = array();

        do {
            $row = array($id, $week, $activiteit);
            array_push($results, $row);
        } while (mysqli_stmt_fetch($stmt));
        
        $json = "{ \"arr\": " . json_encode($results) . "}";

        echo json_encode($results);
        // var_dump(json_encode($results));
    }
?>