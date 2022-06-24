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
    $name = htmlentities($arr['name']);
    $userid = htmlentities($arr['userid']);
    $qrcode = rand();

    //Requesting data from the database
    $query = "INSERT INTO project (name, qrcode) VALUES (?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $name, $qrcode);
    mysqli_stmt_execute($stmt);
    $projectid = mysqli_insert_id($conn);
    //Close the statement
    mysqli_stmt_close($stmt);

    $roleid = getVoorzitterRole($conn);

    //Requesting data from the database
    $query = "INSERT INTO projectmember (user_id, project_id, role_id) VALUES (?,?,?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $userid, $projectid, $roleid);
    mysqli_stmt_execute($stmt);

    //Close the statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

/**
 * Function to get the role id of the voorzitter.
 */
function getVoorzitterRole($conn)
{
    $role = 'voorzitter';
    $sql = "SELECT id FROM role WHERE name = ?";
    $stmt = mysqli_prepare($conn, $sql) or die("prepare error");
    mysqli_stmt_bind_param($stmt, 's', $role) or die("bind param error");
    mysqli_stmt_execute($stmt) or die("execute error");
    mysqli_stmt_bind_result($stmt, $id);
    mysqli_stmt_store_result($stmt);
    while (mysqli_stmt_fetch($stmt)) {
    }
    $returnid = $id;
    mysqli_stmt_close($stmt);

    return $returnid;
}
