<?php
include("auth_teacher.php");
include("db_connection.php");

$teacher_id = $_SESSION['teacher_id'];

// Get filters from GET
$course_filter = $_GET['course_id'] ?? '';
$year_level_filter = $_GET['year_level_id'] ?? '';

// Fetch dropdown options
$courses_result = $conn->query("SELECT * FROM courses ORDER BY course_name");
$year_levels_result = $conn->query("SELECT * FROM year_levels ORDER BY year_level");

// Base query
$students_query = "
    SELECT DISTINCT  u.student_no, u.firstname, u.lastname, c.course_name, yl.year_level
    FROM user u
    INNER JOIN courses c ON u.course_id = c.course_id
    INNER JOIN year_levels yl ON u.year_level_id = yl.year_level_id
    INNER JOIN course_schedule cs ON cs.course_id = u.course_id AND cs.year_level_id = u.year_level_id
    WHERE cs.teacher_id = ?
";

// Dynamic filters
$types = "s"; // teacher_id as string
$params = [$teacher_id];

if (!empty($course_filter)) {
    $students_query .= " AND u.course_id = ?";
    $types .= "i";
    $params[] = $course_filter;
}

if (!empty($year_level_filter)) {
    $students_query .= " AND u.year_level_id = ?";
    $types .= "i";
    $params[] = $year_level_filter;
}

$students_query .= " ORDER BY c.course_name, yl.year_level, u.lastname";

// Prepare & execute
$stmt = $conn->prepare($students_query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$students_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Master List of Students</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <?php include("teacher_sidebar.php"); ?>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Master List of Students</h1>

        <!-- Filters -->
        <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
            <div>
                <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                <select name="course_id" id="course_id" class="mt-1 block w-full p-2 border rounded-md">
                    <option value="">All Courses</option>
                    <?php while ($course = $courses_result->fetch_assoc()): ?>
                        <option value="<?= $course['course_id']; ?>" <?= ($course['course_id'] == $course_filter) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($course['course_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                <select name="year_level_id" id="year_level_id" class="mt-1 block w-full p-2 border rounded-md">
                    <option value="">All Year Levels</option>
                    <?php while ($yl = $year_levels_result->fetch_assoc()): ?>
                        <option value="<?= $yl['year_level_id']; ?>" <?= ($yl['year_level_id'] == $year_level_filter) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($yl['year_level']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Apply Filters
            </button>
        </form>

        <!-- Student Table -->
        <div class="bg-white shadow rounded-md overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 border">Student No.</th>
                        <th class="px-4 py-3 border">First Name</th>
                        <th class="px-4 py-3 border">Last Name</th>
                        <th class="px-4 py-3 border">Course</th>
                        <th class="px-4 py-3 border">Year Level</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if ($students_result->num_rows > 0): ?>
                        <?php while ($row = $students_result->fetch_assoc()): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['student_no']); ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['firstname']); ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['lastname']); ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['course_name']); ?></td>
                                <td class="px-4 py-2 border"><?= htmlspecialchars($row['year_level']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">No students found with the selected filters.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
