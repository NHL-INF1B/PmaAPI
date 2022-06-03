<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */

$json = file_get_contents('php://input');
var_dump($json);
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.

if (isset($arr)) {
    $projectId = htmlentities($arr['project_id']);

    if ($error = validateFields($value)) {
        echo json_encode($error);
    } else {
        $userValues = array();
        $error = array();

        //Requesting data from the database
        $query = "SELECT `projectmember`.user_id, `projectmember`.role_id, `user`.name
                    FROM `projectmember` 
                    JOIN `user`
                    ON `user`.id = `projectmember`.user_id
                    WHERE project_id = ?
                    ORDER BY user_id DESC";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $projectId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $userId, $roleId, $userName);
        

        if (mysqli_stmt_num_rows($stmt) > 0) {
            while (mysqli_stmt_fetch($stmt)) {
                //All value's that will be send back to the application
                $userValues['user_id'] = $userId;
                $userValues['role_id'] = $roleId;
                $userValues['name'] = $userName;
            }
        
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

function validateFields($value) {
    $error = array();

    if (!isset($value)) {
        $error[] = 'No_value_set';
    }

    if(!empty($error)) {
        return $error;
    } else {
        return false;
    }
}