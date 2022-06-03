<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if(isset($arr)){
    $projectId = htmlentities($arr["projectId"]);

    $query = "SELECT u.id, u.name FROM projectmember as pm INNER JOIN user as u ON pm.user_id = u.id WHERE pm.project_id = ?";

    if(!$stmt = mysqli_prepare($conn, $query)){
        echo "DB error: " . mysqli_error($conn);
        die();
    }

    if(!mysqli_stmt_bind_param($stmt, "i", $projectId) || !mysqli_stmt_execute($stmt)){
        echo "DB error: " . mysqli_error($conn);
    }

    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

    echo json_encode($result);
}
