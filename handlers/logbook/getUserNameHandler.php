<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if(isset($arr)){
    $userId = htmlentities($arr["userId"]);

    $query = "SELECT `name` FROM user WHERE id = ?";

    if(!$stmt = mysqli_prepare($conn, $query)){
        echo "DB error: " . mysqli_error($conn);
        die();
    }
    
    if(!mysqli_stmt_bind_param($stmt, "i", $userId) || !mysqli_stmt_execute($stmt)){
        echo "DB error: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);
    
    echo json_encode($result);
} else{
    echo json_encode("No data send");
}
