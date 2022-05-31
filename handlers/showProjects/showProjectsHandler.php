<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if(isset($arr)){
    // $userId = htmlentities($arr["userId"]);
    $userId = 1;
}

$sql = "SELECT project_id FROM projectmember WHERE user_id = 1[";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
mysqli_stmt_bind_result($stmt, $projectId);

echo "\n";
$query = mysqli_query($conn, $sql);
$result = array();

while ($res = mysqli_fetch_assoc($query)){
    $result[] = $res;
}

echo json_encode($result);