<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

//Get the data from the react native
$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);
$error = array();

//Check if there is data send
if (isset($array)) {
    //Bind data from the input fields to variables
    $userId = htmlentities($array['userId']);
    $projectId = htmlentities($array['projectId']);

    $result = array();

    //Getting the points the user has before new points
    $sql = "SELECT reward_points FROM projectmember WHERE user_id = ? AND project_id = ?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $projectId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $pointsBefore);
    mysqli_stmt_store_result($stmt);

    while (mysqli_stmt_fetch($stmt)) {
    }

    //If there is 1 result put the data into variables
    if (mysqli_stmt_num_rows($stmt) == 1) {
        $pointsAfter = $pointsBefore + 1;
        mysqli_stmt_close($stmt);

        //Update the points in the database.
        $sql = "UPDATE projectmember SET reward_points = ? WHERE user_id = ? AND project_id = ?;";
        $stmt = mysqli_prepare($conn, $sql) or die;
        mysqli_stmt_bind_param($stmt, 'iii', $pointsAfter, $userId, $projectId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send a message that the password has changed.
        $result[] = "points_updated";
        echo json_encode($result);
    }
} else {
    echo json_encode("No data send");
}
