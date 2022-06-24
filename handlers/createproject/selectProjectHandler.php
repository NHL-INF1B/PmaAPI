<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    //Bind data from the input fields to variables
    $id = htmlentities($arr['id']);

    $sql = "SELECT * FROM project WHERE id = ?";

    //Sending data to the database
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $qrcode, $teamcode);
    mysqli_stmt_store_result($stmt);

    $result = array();

    while (mysqli_stmt_fetch($stmt)) {}

    //if there are more than 0 results
    if (mysqli_stmt_num_rows($stmt) > 0) {
        //place the info from the database into an array.
        $result['id'] = $id;
        $result['name'] = $name;
        $result['qrcode'] = $qrcode;
        $result['teamcode'] = $teamcode;
    } else {
        echo json_encode("No results");
    }

    //Close the statement and the connection
     mysqli_stmt_close($stmt);
     mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($result);
} else {
    echo json_encode('No data sent');
}




