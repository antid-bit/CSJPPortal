<?php
session_start();
include('db_connection.php');

// Fetch CHTM rooms (course_id = 3)
$stmt = $conn->prepare("SELECT * FROM rooms WHERE course_id = 3");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CHTM Room List</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 min-h-screen">
            <div class="p-4">
                <div class="text-white text-3xl font-bold mb-4 tracking-widest">CSJP</div>
            </div>
            <nav class="text-white">
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminCHTMHome.php">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminCHTMAddStudent.php">
                    <i class="fas fa-user-plus mr-2"></i>Add Student
                </a>
                <a class="block py-2.5 px-4 bg-blue-800 rounded-md mb-2" href="AdminCHTMRoom.php">
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
            <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
                <h1 class="text-2xl font-semibold mb-4 text-center">CHTM Room List</h1>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="py-2 px-4 border">Course Name</th>
                            <th class="py-2 px-4 border">Room Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr class="text-center bg-gray-50">
                                <td class="py-2 px-4 border">CHTM</td>
                                <td class="py-2 px-4 border"><?php echo $row['room_name']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
