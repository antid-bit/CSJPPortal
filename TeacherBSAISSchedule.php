<?php
include 'db_connection.php';

$today = date('l');

// Fetch BSAIS (`course_id = 2`) schedule
$sql = "SELECT cs.schedule_id, s.subject_id, s.subject_name, cs.day_of_week, cs.time_slot 
        FROM course_schedule cs
        JOIN subjects s ON cs.subject_id = s.subject_id
        WHERE cs.course_id = 2
        ORDER BY FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";
$result = $conn->query($sql);
if (!$result) {
    die("SQL Error: " . $conn->error);
}

// Success message
if (isset($_GET['message']) && $_GET['message'] === "updated") {
    echo "<p class='text-green-600'>Schedule updated successfully!</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BSAIS Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<div class="flex">
    <!-- Sidebar -->
    <div class="w-64 bg-blue-900 h-screen relative">
        <div class="p-4">
            <div class="text-white text-3xl font-bold mb-4 tracking-widest">CSJP</div>
        </div>
        <nav class="text-white">
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSAISHome.php">
                <i class="fas fa-home mr-2"></i>Home</a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSAISSchedule.php">
                <i class="fas fa-user-graduate mr-2"></i>Schedule
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSAISAddStudent.php">
                <i class="fas fa-user-plus mr-2"></i>Add Student</a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSAISMasterlist.php">
                <i class="fas fa-chart-bar mr-2"></i>Masterlist
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSAISReports.php">
                <i class="fas fa-chart-bar mr-2"></i>Reports
            </a>
        </nav>
        <div class="absolute bottom-0 w-full p-4">
            <a class="block py-2.5 px-4 text-yellow-500 hover:text-yellow-600" href="CSJPlogin.php">
                <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-8 overflow-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">📅 Weekly Class Schedule (BSAIS)</h1>
            <a href="/CSJPPortal/TeacherBSAISAdd.php" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                ➕ Add Schedule
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 text-center text-lg">
                <thead>
                <tr class="bg-blue-500 text-white text-xl">
                    <th class="py-4 px-6 border">Day</th>
                    <th class="py-4 px-6 border">Subject Name</th>
                    <th class="py-4 px-6 border">Time Slot</th>
                    <th class="py-4 px-6 border">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="<?= ($row['day_of_week'] === $today) ? 'bg-yellow-100 font-semibold' : 'hover:bg-gray-50' ?>">
                        <td class="py-3 px-4 border"><?= htmlspecialchars($row['day_of_week']) ?><?= ($row['day_of_week'] === $today) ? " (Today)" : "" ?></td>
                        <td class="py-3 px-4 border"><?= htmlspecialchars($row['subject_name']) ?></td>
                        <td class="py-3 px-4 border"><?= htmlspecialchars($row['time_slot']) ?></td>
                        <td class="py-3 px-4 border space-x-2">
                            <form method="POST" action="/CSJPPortal/TeacherBSAISDelete.php" onsubmit="return confirm('Are you sure you want to delete this schedule?')" class="inline">
                                <input type="hidden" name="schedule_id" value="<?= $row['schedule_id'] ?>">
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    🗑️ Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
