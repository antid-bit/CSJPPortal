<?php
// AdminCHTMAddStudent.php
session_start();
$_SESSION['course'] = 'CHTM'; // Hardcoded to CHTM
include 'db_connection.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $year_level_id = intval($_POST['year_level_id'] ?? 1);
    $course_id = 3; // Hardcoded to CHTM (course_id = 3)

    if (!$user_id) {
        $message = "User ID is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO addstudent (user_id, year_level_id, course_id) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sii", $user_id, $year_level_id, $course_id);

        if ($stmt->execute()) {
            $message = "CHTM student added successfully with ID: " . htmlspecialchars($user_id);
        } else {
            $message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - CHTM Add Student</title>
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
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminCHTMHome.php">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a class="block py-2.5 px-4 bg-blue-800 rounded-md mb-2" href="AdminCHTMAddStudent.php">
                    <i class="fas fa-user-plus mr-2"></i>Add Student
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminCHTMRoom.php">
                    <i class="fas fa-door-open mr-2"></i>Room
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4">
                <a class="block py-2.5 px-4 text-yellow-500 hover:text-yellow-600" href="CSJPlogin.php">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-6 text-center">➕ Add CHTM Student</h1>

            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <?php if ($message): ?>
                    <p class="font-semibold text-green-600"><?php echo $message; ?></p>
                <?php endif; ?>

                <form method="post" action="">
                    <label>User ID:</label><br>
                    <input type="text" name="user_id" required class="border rounded-md p-2"><br><br>

                    <label>Year Level:</label><br>
                    <select name="year_level_id" class="border rounded-md p-2">
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select><br><br>

                    <label>Course:</label><br>
                    <select name="course_id" class="border rounded-md p-2">
                        <option value="3" selected>CHTM</option> <!-- Locked to CHTM -->
                    </select><br><br>

                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md">Add Student</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
