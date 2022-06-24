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
    $value = htmlentities($arr['value']);

    if ($error = validateFields($value)) {
        echo json_encode($error);
    } else {
        $userValues = array();

        $queryuery = "UPDATE `projectmember`
                        SET role_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $roleId);
        mysqli_stmt_execute($stmt);

        //All value's that will be send back to the application
        $userValues[0]['user_id'] = $userId;
        $userValues[0]['project_id'] = $projectId;
        $userValues[0]['role_id'] = $roleId;

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($userValues);
    }
} else {
    echo json_encode('No data send');
}
