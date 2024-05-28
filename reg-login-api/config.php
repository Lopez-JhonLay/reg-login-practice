<?php
// Database configuration
define('DB_SERVER', 'localhost'); // Database server
define('DB_USERNAME', 'root');    // Database username
define('DB_PASSWORD', ''); // Database password
define('DB_NAME', 'register_login');      // Database name

// Connect to the database
function db_connect()
{
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // else {
    //     echo "Connection successful!";
    // }
    return $conn;
}

// Close the database connection
function db_close($conn)
{
    $conn->close();
}

//db_connect();
