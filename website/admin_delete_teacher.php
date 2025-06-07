<?php
include("auth_admin.php");
include("db_connection.php");

if (isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];

    // Check if the teacher has any schedules assigned before deletion
    $result = $conn->query("SELECT COUNT(*) AS count FROM course_schedule WHERE teacher_id = $teacher_id");
    $count = $result->fetch_assoc()['count'];

    if ($count > 0) {
        // Prevent deletion if the teacher is assigned to any schedules
        echo "<script>alert('This teacher is assigned to one or more schedules and cannot be deleted.'); window.location.href = 'admin_manage_teachers.php';</script>";
        exit;
    }

    // Delete teacher from the database
    $stmt = $conn->prepare("DELETE FROM teacher WHERE teacher_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();

    echo "<script>alert('Teacher deleted successfully.'); window.location.href = 'admin_manage_teachers.php';</script>";
} else {
    echo "<script>alert('No teacher selected for deletion.'); window.location.href = 'admin_manage_teachers.php';</script>";
}
?>
