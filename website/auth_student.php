<?php
session_start();
include("db_connection.php"); // Ensure this contains your database connection setup

// Check if user is logged in
if (!isset($_SESSION['student_no'])) {
    header("Location: index.php");
    exit();
}

// Check user role
$student_no = $_SESSION['student_no'];

$query = "SELECT role FROM user WHERE student_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_no); // Bind student_no, not $user_id
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Redirect if not a student
if ($role !== 'student') {
    header("Location: index.php");
    exit();
}
?>
