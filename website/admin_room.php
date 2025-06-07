<?php
include("auth_admin.php"); // Admin authentication
include("db_connection.php"); // Database connection

// Fetch room schedule data with teacher and room information
$query = "
    SELECT cs.schedule_id, r.room_name, cs.day_of_week, cs.time_slot, 
           c.course_name, s.subject_name, CONCAT(t.firstname, ' ', t.lastname) AS teacher_name
    FROM course_schedule cs
    INNER JOIN courses c ON cs.course_id = c.course_id
    INNER JOIN subjects s ON cs.subject_id = s.subject_id
    INNER JOIN teacher t ON cs.teacher_id = t.teacher_id
    INNER JOIN rooms r ON cs.room_id = r.room_id
    ORDER BY r.room_name, cs.day_of_week, cs.time_slot
";
$result = $conn->query($query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Schedule</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <?php include("admin_sidebar.php"); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Room Schedule</h1>

        <div class="bg-white p-6 rounded shadow-md">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 border">Room</th>
                            <th class="px-4 py-3 border">Day</th>
                            <th class="px-4 py-3 border">Time Slot</th>
                            <th class="px-4 py-3 border">Course</th>
                            <th class="px-4 py-3 border">Subject</th>
                            <th class="px-4 py-3 border">Teacher</th>
                            <th class="px-4 py-3 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['room_name']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['day_of_week']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['time_slot']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['course_name']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                                    <td class="px-4 py-2 border space-x-2">
                                        <a href="edit_schedule.php?schedule_id=<?php echo urlencode($row['schedule_id']); ?>" class="text-blue-600 hover:underline">Edit</a>
                                        <a href="delete_schedule.php?schedule_id=<?php echo urlencode($row['schedule_id']); ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this schedule?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-gray-500 py-4">No schedule records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
