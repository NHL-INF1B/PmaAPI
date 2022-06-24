<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);

if (isset($array)) {
    //Bind data from the input fields to variables
    $projectId = htmlentities($array['projectId']);

    $projectValues = array();
    $error = array();

    $sql = "SELECT *
            FROM project
            WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $projectId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $qrcode, $teamcode);
    mysqli_stmt_store_result($stmt);
    // echo mysqli_stmt_num_rows($stmt);
    while (mysqli_stmt_fetch($stmt)) {
    }

    //if there is 1 result
    if (mysqli_stmt_num_rows($stmt)  == 1) {
        //add the data to the array.
        $projectValues[0]['id'] = $id;
        $projectValues[0]['name'] = $name;
        $projectValues[0]['newQrcode'] = $qrcode;

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo json_encode($projectValues);
    } else {
        $error[] = 'project_not_exists';
        echo json_encode($error);
    }
} else {
    echo json_encode("No data send.");
}
