<?php
include("auth_admin.php");
include("db_connection.php");

if (isset($_GET['subject_id'])) {
    $subject_id = $_GET['subject_id'];

    // Check if the subject is assigned to any schedules before deletion
    $result = $conn->query("SELECT COUNT(*) AS count FROM course_schedule WHERE subject_id = $subject_id");
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        // Prevent deletion if the subject is assigned to any schedules
        echo "<script>alert('This subject is assigned to one or more schedules and cannot be deleted.'); window.location.href = 'admin_manage_subjects.php';</script>";
        exit;
    }

    // Delete subject from the database
    $stmt = $conn->prepare("DELETE FROM subjects WHERE subject_id = ?");
    $stmt->bind_param("i", $subject_id);
    $stmt->execute();

    echo "<script>alert('Subject deleted successfully.'); window.location.href = 'admin_manage_subjects.php';</script>";
} else {
    echo "<script>alert('No subject selected for deletion.'); window.location.href = 'admin_manage_subjects.php';</script>";
}
?>
