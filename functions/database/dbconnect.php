<?php
/**
 * Function connectDB
 * Function to connect to Database
 * @return object $conn Database connection
 */
function connectDB() {
    //Require ENV
    require_once('env.php');

    // Connect to server (localhost server)
    $conn = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE);

    // Test the connection
    if (!$conn) {
        echo "database_connect_error";
    }

    return $conn;
}