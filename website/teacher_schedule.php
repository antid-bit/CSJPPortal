<?php
include("auth_teacher.php");
include("db_connection.php");

// Ensure teacher is authenticated
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id']; // e.g., "T01-0001"

// Query to fetch the teacher's schedule
$query = "
    SELECT cs.day_of_week, cs.time_slot, r.room_name, s.subject_name
    FROM course_schedule cs
    JOIN rooms r ON cs.room_id = r.room_id
    JOIN subjects s ON cs.subject_id = s.subject_id
    WHERE cs.teacher_id = ?
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("s", $teacher_id); // FIXED: "s" for string
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Schedule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <?php include("teacher_sidebar.php"); ?>

    <!-- Main Content -->
    <div class="p-6 w-full">
        <h1 class="text-2xl font-bold mb-6">My Schedule</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-md shadow">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Day</th>
                        <th class="py-3 px-4 text-left">Time Slot</th>
                        <th class="py-3 px-4 text-left">Room</th>
                        <th class="py-3 px-4 text-left">Subject</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="py-2 px-4"><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                                <td class="py-2 px-4"><?php echo format_time_slot($row['time_slot']); ?></td>
                                <td class="py-2 px-4"><?php echo htmlspecialchars($row['room_name']); ?></td>
                                <td class="py-2 px-4"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No schedule assigned. Please contact the admin.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>

<?php
// Function to format time slot
function format_time_slot($time_slot) {
    if (empty($time_slot)) {
        return 'Not specified';
    }

    // Handle ranges like "08:00-10:00"
    if (strpos($time_slot, '-') !== false) {
        list($start, $end) = explode('-', $time_slot);
        $start_formatted = date('g:i A', strtotime($start));
        $end_formatted = date('g:i A', strtotime($end));
        return "$start_formatted - $end_formatted";
    }

    // Otherwise, format single time
    $time = strtotime($time_slot);
    return $time ? date('g:i A', $time) : htmlspecialchars($time_slot);
}
?>
