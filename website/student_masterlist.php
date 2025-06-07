<?php
include("auth_student.php");  // Ensure the user is authenticated as a student
include("db_connection.php");  // Connect to the database

// Get the logged-in student's course and year level based on their student_no
$student_no = $_SESSION['student_no'];  // Assuming student_no is stored in session

// Use a prepared statement to fetch the student's course and year level
$student_query = "SELECT course_id, year_level_id FROM user WHERE student_no = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("s", $student_no);  // Bind student_no as a string parameter
$stmt->execute();
$student_result = $stmt->get_result();

// If the query returns a result, fetch the course and year level
if ($student_result->num_rows > 0) {
    $student_data = $student_result->fetch_assoc();
    $course_id = $student_data['course_id'];
    $year_level_id = $student_data['year_level_id'];
} else {
    // If no student is found (for some reason), redirect to login or show an error
    header("Location: login.php");
    exit;
}

// Set the number of records per page
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Use a prepared statement to fetch students in the same course and year level
$students_query = "
    SELECT u.student_no, u.firstname, u.lastname, c.course_name, yl.year_level
    FROM user u
    INNER JOIN courses c ON u.course_id = c.course_id
    INNER JOIN year_levels yl ON u.year_level_id = yl.year_level_id
    WHERE u.course_id = ? AND u.year_level_id = ? AND u.role = 'student'
    ORDER BY u.lastname
    LIMIT ? OFFSET ?
";
$stmt = $conn->prepare($students_query);
$stmt->bind_param("iiii", $course_id, $year_level_id, $records_per_page, $offset);
$stmt->execute();
$students_result = $stmt->get_result();

// Get total number of students for pagination
$total_students_query = "
    SELECT COUNT(*) AS total_students
    FROM user u
    WHERE u.course_id = ? AND u.year_level_id = ? AND u.role = 'student'
";
$total_stmt = $conn->prepare($total_students_query);
$total_stmt->bind_param("ii", $course_id, $year_level_id);
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_students = $total_result->fetch_assoc()['total_students'];
$total_pages = ceil($total_students / $records_per_page);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master List of Students</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">
    <!-- Sidebar -->
    <?php include("student_sidebar.php"); ?> <!-- Ensure you have a sidebar file for students -->

    <!-- Main Content -->
    <div class="flex-1 p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Master List of Classmates</h1>

        <!-- Student Table -->
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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if ($students_result->num_rows > 0): ?>
                            <?php while ($row = $students_result->fetch_assoc()): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['student_no']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['firstname']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['lastname']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['course_name']); ?></td>
                                    <td class="px-4 py-2 border"><?php echo htmlspecialchars($row['year_level']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">No classmates found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-4">
            <nav class="inline-flex rounded-md shadow-sm">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>" class="px-4 py-2 text-gray-700 bg-gray-200 border border-gray-300 rounded-l-md">Previous</a>
                <?php endif; ?>
                
                <span class="px-4 py-2 text-gray-700"><?php echo $page; ?> / <?php echo $total_pages; ?></span>

                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?>" class="px-4 py-2 text-gray-700 bg-gray-200 border border-gray-300 rounded-r-md">Next</a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</body>
</html>
