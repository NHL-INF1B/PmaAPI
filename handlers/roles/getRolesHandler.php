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
        $error = array();

        //Requesting data from the database
        $queryuery = "SELECT * 
                        FROM `projectmember` 
                        WHERE project_id = ?
                        ORDER BY user_id DESC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $projectId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userId, $projectId, $roleId);
        while (mysqli_stmt_fetch($stmt)) {
        }

        if (mysqli_stmt_num_rows($stmt) > 0) {
            //All value's that will be send back to the application
            $userValues[0]['user_id'] = $userId;
            $userValues[0]['project_id'] = $projectId;
            $userValues[0]['role_id'] = $roleId;

            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            //Send back response (JSON)
            echo json_encode($userValues);
        } else {
            $error[] = 'No_projectmembers';
            echo json_encode($error);
        }
    }
} else {
    echo json_encode('No data send');
}

function validateFields($value)
{
    $error = array();

    if (!isset($value)) {
        $error[] = 'No_value_set';
    }

    if (!empty($error)) {
        return $error;
    } else {
        return false;
    }
}
