<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */

if(!isset($_GET["projectId"])) {
    echo 'de get wordt niet meegegeven ';
} else {
    $projectId = $_GET['projectId'];
    echo $projectId;
}

$json = file_get_contents('php://input');

if($json === false){
    echo" teruggegeven waarde is: false ";
}elseif($json == false){
    echo" teruggegeven waarde is een nonboolean die wordt uitgelezen als: false ";
}else{
    echo" waarom de f doet hij het niet? ";
}
// php://input geeft empty string als waarde in plaats van wat ik mee zou willen geven
var_dump($json);
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc.
print_r($arr);

if (isset($arr)) {
    $projectId = htmlentities($arr['project_id']);

    if ($error = validateFields($projectId)) {
        echo json_encode($error);
    } else {
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

        $res = mysqli_stmt_get_result($stmt);
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        echo json_encode($result);
    }
} else {
    echo json_encode('No data send');
}

function validateFields($projectId) {
    $error = array();

    if (!isset($projectId)) {
        $error[] = 'No_value_set';
    }

    if(!empty($error)) {
        return $error;
    } else {
        return false;
    }
}