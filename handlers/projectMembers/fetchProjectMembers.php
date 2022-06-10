<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $userId = htmlentities($arr['userId']);
    $projectId = htmlentities($arr['projectId']);

    //Validate fields
    if ($error = false) { //Heeft nog validatie nodig! <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
        echo json_encode($error);
    } else {
        $respond = array();

        //Recieving data from the database
        $query = "  SELECT user.id, user.name, role.name AS rolename
                    FROM USER
                    INNER JOIN projectmember ON projectmember.project_id = ?
                    INNER JOIN role ON role.id = projectmember.role_id
                    WHERE user.id = projectmember.user_id
                    AND NOT user.id = ?";
                    
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $projectId, $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $rolename);
        while (mysqli_stmt_fetch($stmt)) {
            $userValues = array();
            $userValues['id'] = $id;
            $userValues['name'] = $name;
            $userValues['role'] = $rolename;
            array_push($respond, $userValues);
        }

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($respond);
    }
} else {
    echo json_encode('No data send');
}