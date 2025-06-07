<?php 
include("auth_admin.php"); 
include("db_connection.php");

// Fetch courses and year levels from the database
$course_result = $conn->query("SELECT course_id, course_name FROM courses");
$year_result = $conn->query("SELECT year_level_id, year_level FROM year_levels");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <?php include("admin_sidebar.php"); ?>

    <div class="flex-1 p-6 overflow-y-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Add New Student</h1>
        <div class="bg-white shadow-md rounded-lg p-6 max-w-4xl mx-auto">
            <form method="POST" action="process_add_student.php">
                <div class="mb-4">
                    <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" id="firstname" name="firstname" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" id="lastname" name="lastname" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="birthday" class="block text-sm font-medium text-gray-700">Birthday</label>
                    <input type="date" id="birthday" name="birthday" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="student_no" class="block text-sm font-medium text-gray-700">Student Number</label>
                    <input type="text" id="student_no" name="student_no" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                </div>
                <div class="mb-4">
                    <label for="course_id" class="block text-sm font-medium text-gray-700">Course</label>
                    <select id="course_id" name="course_id" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        <option value="">-- Select Course --</option>
                        <?php while($row = $course_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['course_id']; ?>"><?php echo htmlspecialchars($row['course_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="year_level_id" class="block text-sm font-medium text-gray-700">Year Level</label>
                    <select id="year_level_id" name="year_level_id" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        <option value="">-- Select Year Level --</option>
                        <?php while($row = $year_result->fetch_assoc()): ?>
                            <option value="<?php echo $row['year_level_id']; ?>"><?php echo htmlspecialchars($row['year_level']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="mb-6">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
