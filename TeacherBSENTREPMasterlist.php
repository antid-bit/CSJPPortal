<?php
session_start();
$_SESSION['course'] = 'BSENTREP'; // Ensure session reflects BSENTREP
include 'db_connection.php';

// Retrieve only BSENTREP students
$sql = "SELECT user_id, year_level_id, created_at FROM addstudent WHERE course_id = 5 ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard - BSENTREP Masterlist</title>
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
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSENTREPHome.php">
                <i class="fas fa-home mr-2"></i>Home</a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="TeacherBSENTREPSchedule.php">
                    <i class="fas fa-user-graduate mr-2"></i>Schedule
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 bg-blue-700" href="TeacherBSENTREPMasterlist.php">
                <i class="fas fa-chart-bar mr-2"></i>Masterlist</a>
            </nav>
            <div class="absolute bottom-0 w-full p-4">
                <a class="block py-2.5 px-4 text-yellow-500 hover:text-yellow-600" href="CSJPlogin.php">
                <i class="fas fa-sign-out-alt mr-2"></i>Sign Out</a>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h1 class="text-3xl font-bold mb-6 text-center">📋 BSENTREP Student Masterlist</h1>

            <div class="bg-white rounded-lg shadow-md p-6">
                <table class="w-full border border-gray-300">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="p-3">User ID</th>
                            <th class="p-3">Year Level</th>
                            <th class="p-3">Course</th>
                            <th class="p-3">Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="text-center border-t border-gray-300">
                            <td class="p-3"><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td class="p-3"><?php echo htmlspecialchars($row['year_level_id']); ?> Year</td>
                            <td class="p-3">BSENTREP</td> <!-- Display course name instead of ID -->
                            <td class="p-3"><?php echo htmlspecialchars(date("F j, Y - h:i A", strtotime($row['created_at']))); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
