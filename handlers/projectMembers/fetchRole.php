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
    $userId = htmlentities($arr['userId']);

    //Validate fields
    if ($error = false) {
        echo json_encode($error);
    } else {
        //Recieving data from the database
        $query = "  SELECT name
                    FROM role
                    INNER JOIN projectmember ON projectmember.user_id = ?
                    WHERE role.id = projectmember.role_id";
                    
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $rolename);

        while (mysqli_stmt_fetch($stmt)) {}

        //Close the statement and connection
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        //Send back response (JSON)
        echo json_encode($rolename);
    }
} else {
    echo json_encode('No data send');
}