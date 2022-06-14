<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if(isset($arr)){

    $userId = htmlentities($arr["userId"]);
    $projectId = htmlentities($arr["projectId"]);

    $query = "SELECT * FROM `timesheet` WHERE user_id = ? AND project_id = ? ORDER BY time_start";

    if(!$stmt = mysqli_prepare($conn, $query)){
        echo "DB error: " . mysqli_error($conn);
        die();
    }

    if(!mysqli_stmt_bind_param($stmt, "ii", $userId, $projectId) || !mysqli_stmt_execute($stmt)){
        echo "DB error: " . mysqli_error($conn);
        die();
    }
    
    
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    
    echo json_encode($result);

} else{
    echo json_encode("No data send");
}