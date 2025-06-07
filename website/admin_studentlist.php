<?php
include("auth_admin.php");
include("db_connection.php");

// Check if filters are applied (by course and year level)
$course_filter = isset($_GET['course_id']) ? $_GET['course_id'] : '';
$year_level_filter = isset($_GET['year_level_id']) ? $_GET['year_level_id'] : '';

// Query to fetch students, join with courses and year_levels tables
$students_query = "
    SELECT u.student_no, u.firstname, u.lastname, c.course_name, yl.year_level 
    FROM user u
    INNER JOIN courses c ON u.course_id = c.course_id
    INNER JOIN year_levels yl ON u.year_level_id = yl.year_level_id
    WHERE 1=1
";

// Add filters if selected
if ($course_filter) {
    $students_query .= " AND u.course_id = " . intval($course_filter);
}
if ($year_level_filter) {
    $students_query .= " AND u.year_level_id = " . intval($year_level_filter);
}

$students_query .= " ORDER BY c.course_name, yl.year_level";

$result = $conn->query($students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <?php include("admin_sidebar.php"); ?>

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Student List</h1>

        <!-- Filters -->
        <div class="flex space-x-4 mb-6">
            <form method="GET" action="">
                <div class="flex items-center space-x-2">
                    <label for="course_id" class="text-sm font-medium text-gray-700">Course</label>
                    <select name="course_id" id="course_id" class="p-2 border rounded-md">
                        <option value="">Select Course</option>
                        <?php
                        // Fetch courses for the filter dropdown
                        $courses_query = "SELECT * FROM courses";
                        $courses_result = $conn->query($courses_query);
                        while ($course = $courses_result->fetch_assoc()) {
                            echo "<option value='" . $course['course_id'] . "' " . ($course['course_id'] == $course_filter ? 'selected' : '') . ">" . $course['course_name'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>
            <form method="GET" action="">
                <div class="flex items-center space-x-2">
                    <label for="year_level_id" class="text-sm font-medium text-gray-700">Year Level</label>
                    <select name="year_level_id" id="year_level_id" class="p-2 border rounded-md">
                        <option value="">Select Year Level</option>
                        <?php
                        // Fetch year levels for the filter dropdown
                        $year_levels_query = "SELECT * FROM year_levels";
                        $year_levels_result = $conn->query($year_levels_query);
                        while ($year_level = $year_levels_result->fetch_assoc()) {
                            echo "<option value='" . $year_level['year_level_id'] . "' " . ($year_level['year_level_id'] == $year_level_filter ? 'selected' : '') . ">" . $year_level['year_level'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded shadow-md">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-3 border">Student No.</th>
                            <th class="px-4 py-3 border">First Name</th>
                            <th class="px-4 py-3 border">Last Name</th>
                            <th class="px-4 py-3 border">Course</th>
                            <th class="px-4 py-3 border">Year Level</th>
                            <th class="px-4 py-3 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['student_no']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['firstname']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['lastname']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['course_name']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['year_level']); ?></td>
                                    <td class="px-4 py-2 border space-x-2">
                                        <a href="edit_student.php?student_no=<?php echo urlencode($row['student_no']); ?>" class="text-blue-600 hover:underline">Edit</a>
                                        <a href="delete_student.php?student_no=<?php echo urlencode($row['student_no']); ?>" class="text-red-600 hover:underline" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
