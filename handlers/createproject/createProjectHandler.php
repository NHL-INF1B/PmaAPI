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

    // if ($error = validateFields($name)) {
    //     echo json_encode($error);
    // } else {
    //     $userValues = array();
    //     $error = array();

    //Requesting data from the database
    $query = "INSERT INTO project (name) VALUES (?)";
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

            // else {
//                 $error[] = 'login_incorrect';
//                 echo json_encode($error);
//             }
//         } else {
//             $error[] = 'user_not_exists';
//             echo json_encode($error);
//         }
//     }
// } else {
//     echo json_encode('No data send');
// }

// function validateFields($name)
// {
//     $error = array();

//     if (!isset($name) || !filter_var($name, FILTER_SANITIZE_NAME)) {
//         $error[] = 'name_incorrect';
//     }

//     if (!empty($error)) {
//         return $error;
//     } else {
//         return false;
//     }
// }
