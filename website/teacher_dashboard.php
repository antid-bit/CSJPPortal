<?php
include("auth_teacher.php");
include("db_connection.php");

if (!isset($_SESSION['teacher_id'])) {
    echo "Teacher not logged in!";
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher's first name
$name_query = "SELECT firstname FROM teacher WHERE teacher_id = ?";
$name_stmt = $conn->prepare($name_query);

if (!$name_stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$name_stmt->bind_param("s", $teacher_id); // FIXED: "s" for string
$name_stmt->execute();
$name_stmt->bind_result($firstname);
$name_stmt->fetch();
$name_stmt->close();

if (empty($firstname)) {
    $firstname = "Teacher";
}

// Fetch subjects handled by the teacher
$subject_query = "SELECT subject_name FROM subjects WHERE teacher_id = ?";
$subject_stmt = $conn->prepare($subject_query);

if (!$subject_stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$subject_stmt->bind_param("s", $teacher_id); // FIXED: "s" for string
$subject_stmt->execute();
$subjects = $subject_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Home</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <?php include("teacher_sidebar.php"); ?>
    
    <div class="p-6 w-full">
        <h1 class="text-2xl font-bold mb-4">Welcome, Teacher  <?php echo htmlspecialchars($firstname); ?>!</h1>
        <p class="text-gray-700 mb-6">Use the sidebar to manage schedules, attendance reports, and student lists.</p>

        <h2 class="text-xl font-semibold mb-2">Your Subjects:</h2>
        <ul class="space-y-2">
            <?php if ($subjects->num_rows > 0): ?>
                <?php while ($row = $subjects->fetch_assoc()): ?>
                    <li class="bg-white p-4 rounded shadow-md"><?php echo htmlspecialchars($row['subject_name']); ?></li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="bg-yellow-100 p-4 rounded shadow-md">No subjects assigned yet. Please contact the admin.</li>
            <?php endif; ?>
        </ul>
		<div class="flex justify-center items-start min-h-screen bg-gray-100 p-4">
    <div class="bg-white p-6 rounded-md shadow-md w-[500px] h-[300px] flex flex-col justify-center items-center mt-8">
        <h2 class="text-lg font-bold mb-3 text-center">CSJP Post</h2>
        <div class="w-full h-full overflow-hidden">
            <iframe src="https://www.facebook.com/plugins/post.php?href=https://www.facebook.com/100064641764297/posts/1105082471656468"
                    class="w-full h-full"
                    style="border:none;overflow:hidden"
                    scrolling="no"
                    frameborder="0"
                    allowfullscreen="true"></iframe>
        </div>
    </div>
</div>

    </div>
	
</body>
</html>
