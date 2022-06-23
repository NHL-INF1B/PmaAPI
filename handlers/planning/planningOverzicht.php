<?php 
require_once('../../functions/database/dbconnect.php'); 
require_once('../../functions/anti-cors/anticors.php'); 
 
/** 
 * Getting posted data from the app 
 */ 
$json = file_get_contents('php://input'); 
$arr = json_decode($json, TRUE); // returns array("username" => "stefan") etc. 
 
if (isset($arr)) { 
    $projectid = htmlentities($arr['projectid']); 
 
    //Validate fields 
    if ($error = false) { //Heeft nog validatie nodig! <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< 
        echo json_encode($error); 
    } else { 
        $respond = array(); 
 
        //Sending data to the database 
        $query = "SELECT id, week, activiteit FROM schedule_line WHERE project_id = ? ORDER BY week ASC"; 
        $stmt = mysqli_prepare($conn, $query); 
        mysqli_stmt_bind_param($stmt, "i", $projectid); 
        mysqli_stmt_execute($stmt); 
        mysqli_stmt_bind_result($stmt, $id, $week, $activiteit); 
        while (mysqli_stmt_fetch($stmt)) { 
            $multiArr = array(); 
            $multiArr['title'] = 'Week '.$week; 
            $multiArr['data'] = [$activiteit]; 
            $multiArr['id'] = $id; 
            array_push($respond, $multiArr); 
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
 
// require_once('../../functions/database/dbconnect.php'); 
// require_once('../../functions/anti-cors/anticors.php'); 
 
//     $sql = "SELECT id, week, activiteit FROM schedule_line WHERE project_id = 1;"; 
//     $stmt = mysqli_prepare($conn, $sql); 
//     mysqli_stmt_execute($stmt); 
//     mysqli_stmt_bind_result($stmt, $id, $week, $activiteit); 
//     mysqli_stmt_store_result($stmt); 
//     mysqli_stmt_fetch($stmt); 
 
//     $num = mysqli_stmt_num_rows($stmt); 
//     if($num > 0) { 
//         $results = array(); 
 
//         do { 
//             $row = array($id, $week, $activiteit); 
//             array_push($results, $row); 
//         } while (mysqli_stmt_fetch($stmt)); 
 
//         $json = "{ "arr": " . json_encode($results) . "}"; 
 
//         echo json_encode($results); 
//         // var_dump(json_encode($results)); 
//     } 
?>