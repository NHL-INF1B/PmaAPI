<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);

if (isset($array)) {
    $projectId = htmlentities($array['projectId']);
    
    $sql = "SELECT user.id, user.name, projectmember.reward_points FROM user INNER JOIN projectmember ON user.id = projectmember.user_id WHERE project_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $projectId);

    $result = mysqli_query($conn, $stmt);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $projectMembers = [];

    foreach ($data as $row){
        $projectMembers[$row["test"]][] = $row;
    }

    //Send back response (JSON)
    echo json_encode($projectMembers);
} else {
    echo json_encode('No data send');
}
