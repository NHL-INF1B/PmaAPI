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
        //hier verwijdert hij dat voorstukje wat je hieronder ziet
        $base64 = str_replace("data:application/pdf;base64,", "", $base64);
        //base64 decoderen
        $pdf_decoded = base64_decode($base64);
        //Nieuw pdf bestand aanmaken en 'write' rechten geven
        $pdf = fopen('../../uploads/teamcodes/teamcode' . $projectid . '.pdf', 'w');
        //Schrijven naar het pdf bestand met de gedecodeerde base64
        fwrite($pdf, $pdf_decoded);
        //Bestand weer sluiten en dus opslaan
        fclose($pdf);

        echo json_encode('done');
    }
} else {
    echo json_encode('No data send');
}
