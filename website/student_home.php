<?php
include("auth_student.php");
include("db_connection.php");

$student_no = $_SESSION['student_no'] ?? '';
if (empty($student_no)) {
    header("Location: login.php");
    exit;
}

// Get student info: name, course, year_level
$query = "
    SELECT u.firstname, u.lastname, c.course_name, yl.year_level, u.course_id, u.year_level_id
    FROM user u
    JOIN courses c ON u.course_id = c.course_id
    JOIN year_levels yl ON u.year_level_id = yl.year_level_id
    WHERE u.student_no = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_no);
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $course_name, $year_level_name, $course_id, $year_level_id);
$stmt->fetch();
$stmt->close();

if (empty($firstname)) {
    $firstname = "Student";
    $lastname = "";
    $course_name = "";
    $year_level_name = "";
}

// Get attendance
$attendance_query = "
    SELECT a.date, a.status, s.subject_name
    FROM attendance a
    JOIN subjects s ON a.subject_id = s.subject_id
    WHERE a.student_no = ?
    ORDER BY a.date DESC
";
$attendance_stmt = $conn->prepare($attendance_query);
$attendance_stmt->bind_param("s", $student_no);
$attendance_stmt->execute();
$attendance_result = $attendance_stmt->get_result();
$attendance_stmt->close();

// Get classmates with same course & year level (exclude self)
$classmates_query = "
    SELECT u.student_no, u.firstname, u.lastname
    FROM user u
    WHERE u.course_id = ? AND u.year_level_id = ? AND u.student_no != ?
";
$classmates_stmt = $conn->prepare($classmates_query);
$classmates_stmt->bind_param("iis", $course_id, $year_level_id, $student_no);
$classmates_stmt->execute();
$classmates_result = $classmates_stmt->get_result();
$classmates_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <?php include("student_sidebar.php"); ?>

    <div class="p-6 w-full">
        <h1 class="text-2xl font-bold mb-4">Welcome, <?= htmlspecialchars("$firstname $lastname") ?>!</h1>
        <p class="text-gray-700 mb-6">Your course: <strong><?= htmlspecialchars($course_name) ?></strong>, 
        Year level: <strong><?= htmlspecialchars($year_level_name) ?></strong></p>

        <!-- Attendance -->
        <h2 class="text-xl font-bold mb-2">Your Attendance</h2>
        <table class="w-full bg-white shadow rounded mb-6">
            <thead class="bg-gray-200 text-sm">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Subject</th>
                    <th class="px-4 py-2 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendance_result->num_rows > 0): ?>
                    <?php while ($row = $attendance_result->fetch_assoc()): ?>
                        <tr class="border-t hover:bg-gray-50 text-sm">
                            <td class="px-4 py-2"><?= date("F j, Y", strtotime($row['date'])) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['subject_name']) ?></td>
                            <td class="px-4 py-2 capitalize"><?= htmlspecialchars($row['status']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center text-gray-600 py-4">No attendance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Classmates -->
        <h2 class="text-xl font-bold mb-2">Your Classmates</h2>
        <table class="w-full bg-white shadow rounded">
            <thead class="bg-gray-200 text-sm">
                <tr>
                    <th class="px-4 py-2 text-left">Student No</th>
                    <th class="px-4 py-2 text-left">Name</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($classmates_result->num_rows > 0): ?>
                    <?php while ($row = $classmates_result->fetch_assoc()): ?>
                        <tr class="border-t hover:bg-gray-50 text-sm">
                            <td class="px-4 py-2"><?= htmlspecialchars($row['student_no']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="2" class="text-center text-gray-600 py-4">No classmates found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
		<div class="flex justify-center items-start min-h-screen bg-gray-100 p-4">
    <div class="bg-white p-6 rounded-md shadow-md w-[500px] h-[300px] flex flex-col justify-center items-center mt-8">
        <h2 class="text-lg font-bold mb-3 text-center">CSJP Post</h2>
        <div class="w-full h-full overflow-hidden">
            <iframe src="https://www.facebook.com/plugins/post.php?href=https://www.facebook.com/100064641764297/posts/1105082471656468"
                    class="w-full h-full"
                    style="border:none;overflow:hidden"
                    scrolling="no"
                    frameborder="0"
                    allowfullscreen="true"></iframe>
        </div>
    </div>
</div>

    </div>
</body>
</html>
