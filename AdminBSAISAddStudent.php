<?php
session_start();
$_SESSION['course'] = 'BSAIS'; // Changed to BSAIS
include('db_connection.php');

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $year_level_id = intval($_POST['year_level_id'] ?? 1);
    $course_id = 2; // Hardcoded to BSAIS (course_id = 2)

    if (!$user_id) {
        $message = "User ID is required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO addstudent (user_id, year_level_id, course_id) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sii", $user_id, $year_level_id, $course_id);

        if ($stmt->execute()) {
            $message = "BSAIS student added successfully with ID: " . htmlspecialchars($user_id);
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
    <title>Dashboard - BSAIS AddStudent</title>
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
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminBSAISHome.php">
                <i class="fas fa-home mr-2"></i>Home</a>
                <a class="block py-2.5 px-4 bg-blue-800 rounded-md mb-2" href="AdminBSAISAddStudent.php">
                <i class="fas fa-user-plus mr-2"></i>Add Student</a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminBSAISRoom.php">
                <i class="fas fa-door-open mr-2"></i>Room</a>
            </nav>
            <div class="absolute bottom-0 w-full p-4">
               <a class="block py-2.5 px-4 text-yellow-500 hover:text-yellow-600" href="CSJPlogin.php">
          <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
        </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-6 text-center">➕ Add BSAIS Student</h1>

            <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
                <?php if ($message): ?>
                    <p class="font-semibold <?php echo strpos($message, 'Error') !== false ? 'text-red-600' : 'text-green-600'; ?> mb-4">
                        <?php echo $message; ?>
                    </p>
                <?php endif; ?>

                <form method="post" action="" class="space-y-4">
                    <div>
                        <label for="user_id" class="block text-gray-700 font-medium mb-1">User ID:</label>
                        <input type="text" id="user_id" name="user_id" required 
                               class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="year_level_id" class="block text-gray-700 font-medium mb-1">Year Level:</label>
                        <select id="year_level_id" name="year_level_id" 
                                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>

                    <input type="hidden" name="course_id" value="2"> <!-- Hidden field for BSAIS course_id -->

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">
                            Add BSAIS Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>