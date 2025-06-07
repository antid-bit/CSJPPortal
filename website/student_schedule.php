<?php
include("auth_student.php");
include("db_connection.php");

$student_no = $_SESSION['student_no'];

// Step 1: Get course_id and year_level_id from users table
$user_query = $conn->prepare("SELECT course_id, year_level_id FROM user WHERE student_no = ?");
$user_query->bind_param("i", $student_no);
$user_query->execute();
$user_result = $user_query->get_result();

if ($user_result->num_rows === 0) {
    die("User not found.");
}

$user = $user_result->fetch_assoc();
$course_id = $user['course_id'];
$year_level_id = $user['year_level_id'];

// Step 2: Fetch the schedule for the user
$query = "SELECT cs.day_of_week, cs.time_slot, r.room_name, s.subject_name, t.firstname, t.lastname
          FROM course_schedule cs
          JOIN subjects s ON cs.subject_id = s.subject_id
          JOIN rooms r ON cs.room_id = r.room_id
          JOIN teacher t ON cs.teacher_id = t.teacher_id
          WHERE cs.course_id = ? AND cs.year_level_id = ?";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("ii", $course_id, $year_level_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <?php include("student_sidebar.php"); ?>

    <div class="p-6 w-full">
        <h2 class="text-xl font-bold mb-4">My Weekly Schedule</h2>

        <div class="overflow-x-auto">
            <table class="table-auto w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Day</th>
                        <th class="px-4 py-2">Time</th>
                        <th class="px-4 py-2">Subject</th>
                        <th class="px-4 py-2">Room</th>
                        <th class="px-4 py-2">Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-100">
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                                <td class="px-4 py-2"><?php echo date("g:i A", strtotime($row['time_slot'])); ?></td> <!-- Formatting time -->
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['room_name']); ?></td>
                                <td class="px-4 py-2"><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-4">No schedule found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
