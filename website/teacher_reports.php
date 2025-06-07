<?php
include("auth_teacher.php");
include("db_connection.php");

// Make sure the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit;
}

$teacher_id = $_SESSION['teacher_id']; // e.g., "T01-0001" → string

// Pagination setup
$limit = 10;  // Records per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// ✅ Fetch subjects the teacher handles with pagination
$query = "
    SELECT s.subject_name, y.year_level, c.course_name
    FROM subjects s
    JOIN year_levels y ON s.year_level_id = y.year_level_id
    JOIN courses c ON s.course_id = c.course_id
    WHERE s.teacher_id = ?
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("sii", $teacher_id, $limit, $offset);  // FIXED: "sii" for string, int, int
$stmt->execute();
$result = $stmt->get_result();

// ✅ Get total number of subjects for pagination
$total_query = "SELECT COUNT(*) AS total FROM subjects WHERE teacher_id = ?";
$total_stmt = $conn->prepare($total_query);
$total_stmt->bind_param("s", $teacher_id);  // FIXED: string
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_row = $total_result->fetch_assoc();
$total_subjects = $total_row['total'];
$total_pages = max(1, ceil($total_subjects / $limit));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Reports</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <?php include("teacher_sidebar.php"); ?>

    <!-- Main Content -->
    <div class="p-6 w-full">
        <h1 class="text-2xl font-bold mb-6">My Reports</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-md shadow">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Subject</th>
                        <th class="py-3 px-4 text-left">Course</th>
                        <th class="py-3 px-4 text-left">Year Level</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="border-t hover:bg-gray-50">
                                <td class="py-2 px-4"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                                <td class="py-2 px-4"><?php echo htmlspecialchars($row['course_name']); ?></td>
                                <td class="py-2 px-4"><?php echo htmlspecialchars($row['year_level']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-500">No reports available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <nav class="flex justify-between items-center">
                <span class="text-sm text-gray-700">
                    Showing page <?php echo $page; ?> of <?php echo $total_pages; ?>
                </span>
                <div class="space-x-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=1" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">First</a>
                        <a href="?page=<?php echo $page - 1; ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">Previous</a>
                    <?php endif; ?>
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page + 1; ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">Next</a>
                        <a href="?page=<?php echo $total_pages; ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded">Last</a>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>
