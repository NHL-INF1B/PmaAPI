<?php
require_once("../../functions/database/dbconnect.php");
require_once("../../functions/anti-cors/anticors.php");

$json = file_get_contents("php://input");
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    //Bind data from the input fields to variables
    $projectId = htmlentities($arr["projectId"]);

    $query = "SELECT u.id, u.name FROM projectmember as pm INNER JOIN user as u ON pm.user_id = u.id WHERE pm.project_id = ?";

    if (!$stmt = mysqli_prepare($conn, $query)) {
        echo "DB error: " . mysqli_error($conn);
        die();
    }

    if (!mysqli_stmt_bind_param($stmt, "i", $projectId) || !mysqli_stmt_execute($stmt)) {
        echo "DB error: " . mysqli_error($conn);
    }

    //get the results and place them in an array.
    $res = mysqli_stmt_get_result($stmt);
    $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    echo json_encode($result);
} else {
    echo json_encode("No data send");
}
