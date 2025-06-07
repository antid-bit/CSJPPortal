<?php
// Database configuration
$host = "localhost";      // Your database host
$username = "root";       // Your database username (e.g., 'root' for local setup)
$password = "";           // Your database password
$db_name = "attendance_system";  // Your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    // Log the error to a file for debugging purposes
    error_log("Connection failed: " . $conn->connect_error, 3, "errors.log");
    die("Connection failed. Please try again later.");
}

// Set the character set to UTF-8
$conn->set_charset("utf8");

?>
