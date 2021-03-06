<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    //Bind data from the input fields to variables
    $userId = htmlentities($arr["userId"]);
    $projectId = htmlentities($arr["projectId"]);

    $query = "SELECT role_id FROM projectmember WHERE user_id = ? AND project_id = ?";

    if (!$stmt = mysqli_prepare($conn, $query)) {
        echo "DB error: " . mysqli_error($conn);
        die();
    }

    if (!mysqli_stmt_bind_param($stmt, "ii", $userId, $projectId) || !mysqli_stmt_execute($stmt)) {
        echo "DB error: " . mysqli_error($conn);
        die();
    }


    //get the data and place them in an array.
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_array($res, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($result);
} else {
    json_decode("No data send");
}
