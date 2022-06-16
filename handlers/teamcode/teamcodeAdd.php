<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

/**
 * Getting posted data from the app
 */
$json = file_get_contents('php://input');
$arr = json_decode($json, TRUE);

if (isset($arr)) {
    $base64 = htmlentities($arr['base64']);
    $projectid = htmlentities($arr['projectid']);

    if ($error = false) {
        echo json_encode($error);
    } else {
        //Delete a part of the path that is unneeded
        $base64 = str_replace("data:application/pdf;base64,", "", $base64);
        //base64 decoderen
        $pdf_decoded = base64_decode($base64);
        //Create a new pdf & give it 'write' rights
        $pdf = fopen('../../uploads/teamcodes/teamcode' . $projectid . '.pdf', 'w');
        //Write to pdf with decoded base64
        fwrite($pdf, $pdf_decoded);
        //Close and save the file
        fclose($pdf);

        echo json_encode('Success');
    }
} else {
    echo json_encode('No data sent');
}
