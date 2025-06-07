<?php
session_start();
include("db_connection.php"); // Make sure this file connects to your MySQL database

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Check user role
$admin_id = $_SESSION['admin_id'];

$query = "SELECT role FROM admin WHERE admin_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$stmt->bind_result($role);
$stmt->fetch();
$stmt->close();

// Redirect if not an admin
if ($role !== 'admin') {
    header("Location: index.php");
    exit();
}
?>
