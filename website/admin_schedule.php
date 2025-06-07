<?php
include("auth_admin.php");
include("db_connection.php");

// Fetch data
$teachers = $conn->query("SELECT teacher_id, firstname, lastname FROM teacher");
$courses = $conn->query("SELECT course_id, course_name FROM courses");
$subjects = $conn->query("SELECT subject_id, subject_name FROM subjects");
$rooms = $conn->query("SELECT room_id, room_name FROM rooms");
$years = $conn->query("SELECT year_level_id, year_level FROM year_levels");

// Insert or update schedule
if (isset($_POST['save_schedule'])) {
    $course_id = $_POST['course_id'];
    $year_level_id = $_POST['year_level_id'];
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];
    $room_id = $_POST['room_id'];
    $day_of_week = $_POST['day_of_week'];
    $time_slot = $_POST['time_slot'];

    if (!empty($_POST['schedule_id'])) {
        // Update
        $schedule_id = $_POST['schedule_id'];
        $stmt = $conn->prepare("UPDATE course_schedule SET course_id=?, year_level_id=?, subject_id=?, teacher_id=?, room_id=?, day_of_week=?, time_slot=? WHERE schedule_id=?");
        $stmt->bind_param("iiiisssi", $course_id, $year_level_id, $subject_id, $teacher_id, $room_id, $day_of_week, $time_slot, $schedule_id);
    } else {
        // Insert
        $stmt = $conn->prepare("INSERT INTO course_schedule (course_id, year_level_id, subject_id, teacher_id, room_id, day_of_week, time_slot) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiiisss", $course_id, $year_level_id, $subject_id, $teacher_id, $room_id, $day_of_week, $time_slot);
    }
    $stmt->execute();
}

// Delete schedule
if (isset($_GET['delete_schedule'])) {
    $id = $_GET['delete_schedule'];
    $conn->query("DELETE FROM course_schedule WHERE schedule_id = $id");
}

// Fetch all schedules
$schedules = $conn->query("
    SELECT cs.schedule_id, c.course_name, y.year_level, s.subject_name,
           CONCAT(t.firstname, ' ', t.lastname) AS teacher_name,
           r.room_name, cs.day_of_week, cs.time_slot
    FROM course_schedule cs
    JOIN courses c ON cs.course_id = c.course_id
    JOIN year_levels y ON cs.year_level_id = y.year_level_id
    JOIN subjects s ON cs.subject_id = s.subject_id
    JOIN teacher t ON cs.teacher_id = t.teacher_id
    JOIN rooms r ON cs.room_id = r.room_id
    ORDER BY c.course_name, y.year_level, cs.day_of_week, cs.time_slot
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include("admin_sidebar.php"); ?>

<div class="flex-1 p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manage Class Schedules</h1>

    <!-- Schedule Form -->
    <div class="bg-white p-6 mb-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add or Edit Schedule</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="hidden" name="schedule_id" id="schedule_id">
            <div>
                <label>Course</label>
                <select name="course_id" class="w-full p-2 border rounded">
                    <?php while ($c = $courses->fetch_assoc()): ?>
                        <option value="<?= $c['course_id'] ?>"><?= $c['course_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Year Level</label>
                <select name="year_level_id" class="w-full p-2 border rounded">
                    <?php while ($y = $years->fetch_assoc()): ?>
                        <option value="<?= $y['year_level_id'] ?>">Year <?= $y['year_level'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Subject</label>
                <select name="subject_id" class="w-full p-2 border rounded">
                    <?php while ($s = $subjects->fetch_assoc()): ?>
                        <option value="<?= $s['subject_id'] ?>"><?= $s['subject_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Teacher</label>
                <select name="teacher_id" class="w-full p-2 border rounded">
                    <?php while ($t = $teachers->fetch_assoc()): ?>
                        <option value="<?= $t['teacher_id'] ?>"><?= $t['firstname'] . ' ' . $t['lastname'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Room</label>
                <select name="room_id" class="w-full p-2 border rounded">
                    <?php while ($r = $rooms->fetch_assoc()): ?>
                        <option value="<?= $r['room_id'] ?>"><?= $r['room_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label>Day of Week</label>
                <select name="day_of_week" class="w-full p-2 border rounded">
                    <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day): ?>
                        <option value="<?= $day ?>"><?= $day ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label>Time Slot</label>
                <input type="text" name="time_slot" class="w-full p-2 border rounded" placeholder="e.g. 08:00 AM - 10:00 AM">
            </div>
            <div class="col-span-3 text-right">
                <button type="submit" name="save_schedule" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Save Schedule
                </button>
            </div>
        </form>
    </div>

    <!-- Schedule Table -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Current Schedule</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left border">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border">Course</th>
                        <th class="px-4 py-2 border">Year</th>
                        <th class="px-4 py-2 border">Subject</th>
                        <th class="px-4 py-2 border">Teacher</th>
                        <th class="px-4 py-2 border">Room</th>
                        <th class="px-4 py-2 border">Day</th>
                        <th class="px-4 py-2 border">Time</th>
                        <th class="px-4 py-2 border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Group schedules by Course + Year Level
                    $groupedSchedules = [];
                    while ($row = $schedules->fetch_assoc()) {
                        $groupKey = $row['course_name'] . ' - Year ' . $row['year_level'];
                        $groupedSchedules[$groupKey][] = $row;
                    }

                    if (empty($groupedSchedules)): ?>
                        <tr><td colspan="8" class="text-center text-gray-500 py-4">No schedules available.</td></tr>
                    <?php else: ?>
                        <?php foreach ($groupedSchedules as $group => $rows): ?>
                            <tr class="bg-gray-100 font-semibold">
                                <td colspan="8" class="px-4 py-2 border"><?= $group ?></td>
                            </tr>
                            <?php foreach ($rows as $row): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border"><?= $row['course_name'] ?></td>
                                    <td class="px-4 py-2 border">Year <?= $row['year_level'] ?></td>
                                    <td class="px-4 py-2 border"><?= $row['subject_name'] ?></td>
                                    <td class="px-4 py-2 border"><?= $row['teacher_name'] ?></td>
                                    <td class="px-4 py-2 border"><?= $row['room_name'] ?></td>
                                    <td class="px-4 py-2 border"><?= $row['day_of_week'] ?></td>
                                    <td class="px-4 py-2 border"><?= $row['time_slot'] ?></td>
                                    <td class="px-4 py-2 border">
                                        <a href="?delete_schedule=<?= $row['schedule_id'] ?>" class="text-red-600 hover:underline"
                                           onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
