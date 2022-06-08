<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

//get the data from the react native
$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);
$error = array();

//check if there is data send
if (isset($array)) {
    //put the info into variables
    $userId = htmlentities($array['userId']);
    
    $result = array();

    //getting the points the user has before new points
    $sql = "SELECT reward_points FROM projectmember WHERE user_id=?;";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $pointsBefore);
    mysqli_stmt_store_result($stmt);

    while (mysqli_stmt_fetch($stmt)) {}

    //if there is 1 result put the data into variables
    if (mysqli_stmt_num_rows($stmt) == 1) {
        // $result[] = $pointsBefore;
        // echo json_encode($result);

        $pointsAfter = $pointsBefore + 1;
        mysqli_stmt_close($stmt);

        // $result[] = $pointsAfter;
        // echo json_encode($result);

        //update the points in the database.
        $sql = "UPDATE projectmember SET reward_points = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $pointsAfter, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //send a maessage that the password has changed.
        $result[] = "points_updated";
        echo json_encode($result);
    }
}else{
    echo json_encode("No data send");
}