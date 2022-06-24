<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $id = htmlentities($arr['id']);

    $sql = "SELECT w.reason, u.name
            FROM warning AS w
            JOIN user AS u ON u.id = w.user_id
            WHERE w.id = ?";

    //Selecting data from the database
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $reason, $name);
    mysqli_stmt_store_result($stmt);

    $result = array();

    while (mysqli_stmt_fetch($stmt)) { }
    
    if (mysqli_stmt_num_rows($stmt) > 0) {
        $result['reason'] = $reason;
        $result['name'] = $name;
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




