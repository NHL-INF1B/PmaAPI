<?php
require_once('../../functions/database/dbconnect.php');
require_once('../../functions/anti-cors/anticors.php');

$json = file_get_contents('php://input');
$array = json_decode($json, TRUE);
$error = array();

if (isset($array)) {
    $userId = htmlentities($array['id']);
    $oldPassword = htmlentities($array['oldPassword']);
    $newPassword = htmlentities($array['newPassword']);
    $confirmPassword = htmlentities($array['confirmPassword']);

    $result = array();

    if(correctOldPassword($conn, $userId, $oldPassword) == true){
        if($error = validateFields($newPassword, $confirmPassword)){
            echo json_encode($error);
        }else{
            $password = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE user SET password =? WHERE id=?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, 'si', $password, $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $result[] = "password_changed";
            echo json_encode($result);
        }

    }else{
        $error[] = "wrong_old_password";
        echo json_encode($error);
    }
}else{
    echo json_encode("no data send");
}

/**
 * Function to validate fields
 */
function validateFields ($newPassword, $confirmPassword) {
    $error = array();
    if (!isset($newPassword) || !filter_var($newPassword, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,254}+$/', $newPassword)) {
        $error[] = 'password_incorrect';
    }
    if (!isset($confirmPassword) || !filter_var($confirmPassword, FILTER_SANITIZE_SPECIAL_CHARS) || !preg_match('/^(?=.*[!@#$%^&*])[A-Za-z0-9!@#$%^&*]{6,254}+$/', $confirmPassword)) {
        $error[] = 'confirmPassword_incorrect';
    }
    if ($newPassword != $confirmPassword) {
        $error[] = 'samePassword_incorrect';
    }

    if (!empty($error)) {
        return $error;
    } else {
        return false;
    }
}


function correctOldPassword($conn, $userId, $oldPassword){
    $sql = "SELECT password FROM user WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $hashPassword);
    mysqli_stmt_store_result($stmt);

    while (mysqli_stmt_fetch($stmt)) {}

    
        if(password_verify($oldPassword, $hashPassword)){
            return true;
            mysqli_stmt_close($stmt);
        }else{
            return false;
        }
  
}