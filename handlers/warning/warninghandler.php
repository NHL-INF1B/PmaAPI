<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $sql = "SELECT * FROM warning WHERE project_id = 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $reason, $user_id, $project_id);

    $query = mysqli_query($conn, $sql);
    $result = array();

    while ($res = mysqli_fetch_assoc($query)) {
        $result[] = $res;
    }

    //Send back response (JSON)
    echo json_encode($result);
} else {
    echo json_encode('No data send');
}