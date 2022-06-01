<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);

if (isset($array)) {
    $projectId = htmlentities($array['projectId']);
    $qrcode = rand();

    $projectValues = array();
    $error = array();

    //updating the qrcode info
    $sql = "UPDATE project
            SET qrcode=?
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'si', $qrcode, $projectId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    //getting the new qrcode info
    $sql = "SELECT *
            FROM project
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $projectId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $qrcode, $teamcode);
    mysqli_stmt_store_result($stmt);
    
    while (mysqli_stmt_fetch($stmt)) {}

    //if there is 1 result put the data into variables
    if (mysqli_stmt_num_rows($stmt) == 1) {
        $projectValues[0]['id'] = $id;
        $projectValues[0]['name'] = $name;
        $projectValues[0]['newQrcode'] = $qrcode;

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo json_encode($projectValues);

    } else {
        echo json_encode("There is no data.");
    }
}