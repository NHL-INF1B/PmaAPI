<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $project_id = htmlentities($arr['projectId']);
    $result = array();

    $sql = "SELECT * FROM warning WHERE project_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $project_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    mysqli_stmt_bind_result($stmt, $id, $reason, $userId, $projectId);

    while (mysqli_stmt_fetch($stmt)) {
        $userValues = array();
        $userValues['id'] = $id;
        $userValues['reason'] = $reason;
        $userValues['userId'] = $userId;
        $userValues['projectId'] = $projectId;
        $userValues['warningId'] = $id;
        array_push($result, $userValues);
    }

    //Send back response (JSON)
    if(!empty($result)){
        echo json_encode($result);
    } else {
        echo json_encode("NO_DATA");
    }

} else {
    echo json_encode('No data send');
}
