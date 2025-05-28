<?php 
include 'db_connection.php';

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$message = "";

// Handle Save
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    $subject_name = trim($_POST['subject_name']);
    $day_of_week = $_POST['day_of_week'];
    $time_slot = $_POST['time_slot'];
    $teacher_id = $_POST['teacher_id'];
    $year_level_id = $_POST['year_level_id'];
    $student_id = $_POST['student_id'];  // Collected but not saved here
    $course_id = 2;  // BSAIS course_id

    // Check if subject exists for this course
    $subject_check_sql = "SELECT subject_id FROM subjects WHERE subject_name = ? AND course_id = ?";
    $stmt_check = $conn->prepare($subject_check_sql);
    $stmt_check->bind_param("si", $subject_name, $course_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $subject_id = $row['subject_id'];
    } else {
        // Insert new subject
        $insert_subject_sql = "INSERT INTO subjects (subject_name, course_id) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($insert_subject_sql);
        $stmt_insert->bind_param("si", $subject_name, $course_id);
        if ($stmt_insert->execute()) {
            $subject_id = $conn->insert_id;
        } else {
            $message = "❌ Failed to add new subject: " . $conn->error;
        }
    }

    if (empty($message)) {
        // Check for duplicate schedule on the same course, day, and subject
        $check_sql = "SELECT * FROM course_schedule WHERE subject_id = ? AND day_of_week = ? AND course_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("isi", $subject_id, $day_of_week, $course_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "✅ Schedule already saved! Redirecting to schedule page...";
            echo "<script>
                    setTimeout(function() {
                        window.location.href = '/CSJPPortal/TeacherBSAISSchedule.php';
                    }, 2000);
                  </script>";
        } else {
            // Insert new schedule record
            $insert_sql = "INSERT INTO course_schedule (course_id, subject_id, day_of_week, time_slot, teacher_id, year_level_id) 
                           VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("iisssi", $course_id, $subject_id, $day_of_week, $time_slot, $teacher_id, $year_level_id);

            if ($stmt->execute()) {
                $message = "✅ Schedule saved! Redirecting to schedule page...";
                echo "<script>
                        setTimeout(function() {
                            window.location.href = '/CSJPPortal/TeacherBSAISSchedule.php';
                        }, 2000);
                      </script>";
            } else {
                $message = "❌ Failed to add schedule: " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Schedule - BSAIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <h1 class="text-3xl font-bold mb-6">Add New Schedule Entry (BSAIS)</h1>

    <?php if (!empty($message)): ?>
        <div class="<?= strpos($message, '✅') !== false ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-700' ?> p-4 mb-4 rounded">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="max-w-lg bg-white p-6 rounded shadow-md">
        <label class="block mb-2 font-semibold">Subject:</label>
        <input type="text" name="subject_name" required class="w-full border rounded px-3 py-2 mb-4" placeholder="Enter subject name">

        <label class="block mb-2 font-semibold">Day of Week:</label>
        <select name="day_of_week" required class="w-full border rounded px-3 py-2 mb-4">
            <option value="">Select Day</option>
            <?php foreach ($days as $day): ?>
                <option value="<?= htmlspecialchars($day) ?>"><?= htmlspecialchars($day) ?></option>
            <?php endforeach; ?>
        </select>

        <label class="block mb-2 font-semibold">Time Slot:</label>
        <input type="text" name="time_slot" required class="w-full border rounded px-3 py-2 mb-4" placeholder="e.g. 9:00 AM - 10:30 AM">

        <label class="block mb-2 font-semibold">Teacher ID:</label>
        <input type="text" name="teacher_id" required class="w-full border rounded px-3 py-2 mb-4">

        <label class="block mb-2 font-semibold">Year Level:</label>
        <input type="number" name="year_level_id" min="1" max="5" required class="w-full border rounded px-3 py-2 mb-4">

        <label class="block mb-2 font-semibold">Student ID:</label>
        <input type="number" name="student_id" min="1" max="50" required class="w-full border rounded px-3 py-2 mb-4">

        <div class="flex justify-between">
            <a href="/CSJPPortal/TeacherBSAISSchedule.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" name="save" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Save Schedule</button>
        </div>
    </form>
</body>
</html>
