<?php
// DO NOT call session_start() here if it's already called in the parent file
session_start();
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}
