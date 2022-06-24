<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if(isset($arr)){
    //Bind data from the input fields to variables
    $userId = htmlentities($arr["userId"]);
    $projectValues = array();

    //select the id and the name of the projects of a user.
    $query = "SELECT pm.project_id, p.name FROM projectmember as pm INNER JOIN project as p ON pm.project_id = p.id WHERE user_id = ?";

    if(!$stmt = mysqli_prepare($conn, $query)){
        echo "DB error: " . mysqli_error($conn);
        die();
    }

    if(!mysqli_stmt_bind_param($stmt, "i", $userId) || !mysqli_stmt_execute($stmt)){
        echo "DB error: " . mysqli_error($conn);
    }
    
    //get all the results and put them in an array.
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    //check if result is empty
    if(!empty($result)){
        echo json_encode($result);
    } else {
        echo json_encode("NO_DATA");
    }
} else{
    echo json_encode("No data send");
}