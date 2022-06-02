<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE); // returns array("projectName" => "PMA") etc.

if (isset($arr)) {
    $name = htmlentities($arr['name']);

    //Requesting data from the database
    $query = "DELETE * FROM `project` WHERE `project`.`id` = 1;";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);

    $ProjectValues[0]['name'] = $name;

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    //Send back response (JSON)
    echo json_encode($ProjectValues);
}
