<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

//get the data from the react native
$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);
$error = array();

//check if there is data send
if (isset($array)) {
    //Bind data from the input fields to variables
    $projectId = $array['projectId'];

    //the array for the results
    $result = array();

    $query = "SELECT name FROM project WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $projectId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $name);
        mysqli_stmt_store_result($stmt);

        while (mysqli_stmt_fetch($stmt)) {}

        if(mysqli_stmt_num_rows($stmt) > 0){
            $result[0]['projectName'] = $name;

            //Close the statement and connection
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            //Send back response (JSON)
            echo json_encode($result);
        }
        else{
            $error[] = 'Project does not exist';
            echo json_encode($error);
        }

        
}else {
    echo json_encode('No data send');
}