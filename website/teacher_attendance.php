<?php
include("auth_teacher.php");
include("db_connection.php");

$teacher_id = $_SESSION['teacher_id'];
$message = "";

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['attendance']) && !empty($_POST['subject_id']) && !empty($_POST['date'])) {
    $subject_id = $_POST['subject_id'];
    $date = $_POST['date'];

    // Validate date (ensure it's not in the future)
    $current_date = date("Y-m-d");
    if ($date > $current_date) {
        $message = "⚠️ Please select a valid date.";
    } else {
        foreach ($_POST['attendance'] as $student_no => $status) {
            // Check if attendance already exists
            $check_stmt = $conn->prepare("SELECT * FROM attendance WHERE student_no = ? AND subject_id = ? AND date = ?");
            $check_stmt->bind_param("iis", $student_no, $subject_id, $date);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows == 0) {
                // Insert attendance
                $stmt = $conn->prepare("INSERT INTO attendance (student_no, subject_id, date, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("iiss", $student_no, $subject_id, $date, $status);
                $stmt->execute();
            }
        }

        $message = "✅ Attendance recorded successfully.";
    }
}

// Fetch subjects taught by this teacher
$subject_stmt = $conn->prepare("SELECT subject_id, subject_name FROM subjects WHERE teacher_id = ?");
$subject_stmt->bind_param("i", $teacher_id);
$subject_stmt->execute();
$subjects = $subject_stmt->get_result();

// Fetch students for selected subject
$students = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject_id'])) {
    $subject_id = $_POST['subject_id'];
    $student_stmt = $conn->prepare("
        SELECT u.student_no, u.firstname, u.lastname
        FROM user u
        JOIN course_schedule cs ON u.course_id = cs.course_id
        JOIN subjects s ON cs.subject_id = s.subject_id
        WHERE cs.subject_id = ? AND cs.year_level_id = u.year_level_id
        GROUP BY u.student_no
    ");
    $student_stmt->bind_param("i", $subject_id);
    $student_stmt->execute();
    $students = $student_stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Take Attendance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ✅ Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <?php include("teacher_sidebar.php"); ?>

    <div class="p-6 w-full">
        <h1 class="text-2xl font-bold mb-4">Take Attendance</h1>

        <?php if ($message): ?>
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded shadow"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <!-- Subject Select -->
            <div class="mb-4">
                <label for="subject_id" class="block font-medium text-gray-700 mb-1">Subject</label>
                <select name="subject_id" id="subject_id" required class="w-full border border-gray-300 rounded p-2">
                    <option value="">Select a subject</option>
                    <?php while ($subject = $subjects->fetch_assoc()): ?>
                        <option value="<?php echo $subject['subject_id']; ?>" <?php if (isset($_POST['subject_id']) && $_POST['subject_id'] == $subject['subject_id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Date -->
            <div class="mb-6">
                <label for="date" class="block font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>" required class="w-full border border-gray-300 rounded p-2">
            </div>

            <!-- Attendance Table -->
            <?php if ($students && $students->num_rows > 0): ?>
                <div class="overflow-x-auto mb-6">
                    <table class="min-w-full bg-white shadow-md rounded">
                        <thead class="bg-blue-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left">Student Name</th>
                                <th class="px-6 py-3 text-left">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = $students->fetch_assoc()): ?>
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="px-6 py-3">
                                        <?php echo htmlspecialchars($student['firstname'] . ' ' . $student['lastname']); ?>
                                    </td>
                                    <td class="px-6 py-3">
                                        <select name="attendance[<?php echo $student['student_no']; ?>]" class="w-full border-gray-300 rounded">
                                            <option value="present">Present</option>
                                            <option value="absent">Absent</option>
                                            <option value="late">Late</option>
                                            <option value="early">Early</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (isset($_POST['subject_id'])): ?>
                <p class="text-gray-500">No students available for the selected subject.</p>
            <?php endif; ?>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700 transition">
                Submit Attendance
            </button>
        </form>
    </div>
</body>
</html>
