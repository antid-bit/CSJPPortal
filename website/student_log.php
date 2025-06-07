<?php
include("auth_student.php");
include("db_connection.php");

$student_no = $_SESSION['student_no'];
$message = "";

// Get the current day and time
$current_day = date('l'); // e.g., Monday
$current_time = date('H:i'); // 'HH:MM' format
$current_timestamp = strtotime($current_time); // Convert to timestamp
$date = date('Y-m-d');

// Get student info including user_id
$get_id_stmt = $conn->prepare("SELECT student_no, course_id, year_level_id FROM user WHERE student_no = ?");
$get_id_stmt->bind_param("s", $student_no); // Use "s" for string since student_no is a string
$get_id_stmt->execute();
$get_id_result = $get_id_stmt->get_result();
$student_info = $get_id_result->fetch_assoc();
$get_id_stmt->close();

if (!$student_info) {
    die("Student record not found.");
}

$user_id = $student_info['student_no'];  // actual user_id (primary key)
$course_id = $student_info['course_id'];
$year_level_id = $student_info['year_level_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $status = 'absent'; // Default to absent

    // Fetch subject schedule
    $schedule_query = "SELECT day_of_week, time_slot FROM course_schedule 
                       WHERE subject_id = ? AND course_id = ? AND year_level_id = ?";
    $stmt = $conn->prepare($schedule_query);
    $stmt->bind_param("iii", $subject_id, $course_id, $year_level_id);
    $stmt->execute();
    $schedule_result = $stmt->get_result();
    $stmt->close();

    if ($schedule_result->num_rows > 0) {
        $schedule = $schedule_result->fetch_assoc();
        $scheduled_day = $schedule['day_of_week'];
        $scheduled_time = date('H:i', strtotime($schedule['time_slot']));
        $scheduled_timestamp = strtotime($scheduled_time); // Convert to timestamp

        // Compare current day and time
        if ($scheduled_day === $current_day) {
            if ($current_timestamp < $scheduled_timestamp) {
                $status = 'early'; // Student logs in before scheduled time
            } elseif ($current_timestamp <= ($scheduled_timestamp + 30 * 60)) {
                $status = 'present'; // Student logs in within 30 minutes of scheduled time
            } elseif ($current_timestamp > ($scheduled_timestamp + 60 * 60)) {
                $status = 'absent'; // Student logs in 1 hour after class starts
            } else {
                $status = 'late'; // Student logs in after scheduled time but within 1 hour
            }
        }
    }

    // Prevent duplicate attendance
    $check_stmt = $conn->prepare("SELECT * FROM attendance WHERE student_no = ? AND subject_id = ? AND date = ?");
    $check_stmt->bind_param("sss", $user_id, $subject_id, $date); // Use "s" for string as it's the date
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_stmt->close();

    if ($check_result->num_rows > 0) {
        $message = "⚠️ You have already logged attendance for this subject today.";
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO attendance (student_no, subject_id, date, status) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $user_id, $subject_id, $date, $status); // Adjust to "ssss" for all string values
        if ($insert_stmt->execute()) {
            $message = "✅ Attendance logged successfully as " . ucfirst($status) . ".";
        } else {
            $message = "❌ Error logging attendance: " . $conn->error;
        }
        $insert_stmt->close();
    }
}

// Fetch subjects the student is enrolled in
$subject_query = "SELECT DISTINCT s.subject_id, s.subject_name 
                  FROM subjects s
                  JOIN course_schedule cs ON s.subject_id = cs.subject_id
                  WHERE cs.course_id = ? AND cs.year_level_id = ?";
$stmt = $conn->prepare($subject_query);
$stmt->bind_param("ii", $course_id, $year_level_id);
$stmt->execute();
$subjects = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function confirmSubmission() {
            return confirm("Are you sure you want to log this attendance?");
        }
    </script>
</head>
<body class="bg-gray-100 flex">
    <?php include("student_sidebar.php"); ?>
    <div class="p-6 w-full max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Log Attendance</h2>

        <?php if (!empty($message)): ?>
            <div class="mb-4 p-4 rounded <?php echo str_starts_with($message, '✅') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return confirmSubmission();" class="bg-white p-6 rounded shadow space-y-4">
            <div>
                <label for="subject_id" class="block font-medium mb-1">Subject</label>
                <select name="subject_id" id="subject_id" required class="w-full border-gray-300 rounded p-2">
                    <?php while ($s = $subjects->fetch_assoc()): ?>
                        <option value="<?php echo $s['subject_id']; ?>">
                            <?php echo htmlspecialchars($s['subject_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Submit Attendance
            </button>
        </form>
    </div>
</body>
</html>
