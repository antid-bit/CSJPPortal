<?php
// AdminBSBARoom.php
session_start();
include('db_connection.php');

// Initialize variables
$error = '';
$success_message = '';
$editing = false;
$current_room = null;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room = $_POST['room'];
    $course_id = 4; // Hardcoded to BSBA (course_id = 4)

    try {
        // Check if we're editing an existing room
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
            $stmt = $conn->prepare("UPDATE rooms SET room_name = ?, course_id = ? WHERE id = ?");
            $stmt->bind_param("sii", $room, $course_id, $id);
            $success_message = "Room updated successfully!";
        } else {
            // Insert new room
            $stmt = $conn->prepare("INSERT INTO rooms (room_name, course_id) VALUES (?, ?)");
            $stmt->bind_param("si", $room, $course_id);
            $success_message = "Room added successfully!";
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = $success_message;
            header("Location: AdminBSBARoomview.php");
            exit();
        } else {
            $error = "Database error: " . $stmt->error;
        }
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Check if we're editing a room
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    try {
        $stmt = $conn->prepare("SELECT * FROM rooms WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $current_room = $result->fetch_assoc();
        $editing = true;
    } catch (Exception $e) {
        $error = "Error loading room: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $editing ? 'Edit' : 'Add'; ?> BSBA Room</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 min-h-screen">
            <div class="p-4">
                <div class="text-white text-3xl font-bold mb-4 tracking-widest">CSJP</div>
            </div>
            <nav class="text-white">
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminBSBAHome.php">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="AdminBSBAAddStudent.php">
                    <i class="fas fa-user-plus mr-2"></i>Add Student
                </a>
                <a class="block py-2.5 px-4 bg-blue-800 rounded-md mb-2" href="AdminBSBARoom.php">
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
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-semibold mb-6 text-center">
                    <?php echo $editing ? 'Edit' : 'Add'; ?> BSBA Room
                </h1>

                <form class="space-y-5" method="POST" action="">
                    <?php if ($editing): ?>
                        <input type="hidden" name="id" value="<?php echo $current_room['id']; ?>">
                    <?php endif; ?>
                    <div>
                        <label for="room" class="block text-gray-700 font-medium mb-2">Select Room</label>
                        <select id="room" name="room" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="" disabled <?php echo !$editing ? 'selected' : ''; ?>>Select a room</option>
                            <option value="CL1" <?php echo ($editing && $current_room['room_name'] == 'CL1') ? 'selected' : ''; ?>>CL1</option>
                            <option value="CL2" <?php echo ($editing && $current_room['room_name'] == 'CL2') ? 'selected' : ''; ?>>CL2</option>
                            <option value="CL3" <?php echo ($editing && $current_room['room_name'] == 'CL3') ? 'selected' : ''; ?>>CL3</option>
                            <option value="203" <?php echo ($editing && $current_room['room_name'] == '203') ? 'selected' : ''; ?>>203</option>
                            <option value="201" <?php echo ($editing && $current_room['room_name'] == '201') ? 'selected' : ''; ?>>201</option>
                            <option value="202" <?php echo ($editing && $current_room['room_name'] == '202') ? 'selected' : ''; ?>>202</option>
                            <option value="401" <?php echo ($editing && $current_room['room_name'] == '401') ? 'selected' : ''; ?>>401</option>
                        </select>
                    </div>

                    <div>
                        <label for="course" class="block text-gray-700 font-medium mb-2">Select Course</label>
                        <select id="course" name="course" required class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="4" selected>BSBA</option> <!-- Locked to BSBA -->
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-md transition-colors duration-200">
                        <?php echo $editing ? 'Update Room' : 'Add Room'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
