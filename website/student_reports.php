<?php
include("auth_student.php");  // Ensure the user is authenticated as a student
include("db_connection.php");  // Connect to the database

$student_no = $_SESSION['student_no'];  // Using student_no from session

// Prepare SQL query
$query = "SELECT a.date, a.status, s.subject_name
          FROM attendance a
          JOIN subjects s ON a.subject_id = s.subject_id
          WHERE a.student_no = ? 
          ORDER BY a.date DESC";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);  // Error if prepare fails
}

$stmt->bind_param("i", $student_no);  // Bind student_no as an integer parameter
$stmt->execute();
if (!$stmt->execute()) {
    die("Execute Error: " . $stmt->error);  // Error if execute fails
}
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Reports</title>
    <!-- ✅ Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex">
    <?php include("student_sidebar.php"); ?>  <!-- Sidebar inclusion -->

    <div class="p-6 w-full">
        <h2 class="text-xl font-bold mb-4">Attendance Reports</h2>

        <table class="table-auto w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Subject</th>
                    <th class="px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2"><?php echo date("F j, Y", strtotime($row['date'])); ?></td>
                            <td class="px-4 py-2"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                            <td class="px-4 py-2 capitalize">
                                <?php 
                                // Displaying status in a more readable format with emojis
                                switch (strtolower($row['status'])) {
                                    case 'early':
                                        echo "🕒 Early";
                                        break;
                                    case 'present':
                                        echo "✅ Present";
                                        break;
                                    case 'late':
                                        echo "⏰ Late";
                                        break;
                                    case 'absent':
                                        echo "❌ Absent";
                                        break;
                                    default:
                                        echo ucfirst($row['status']); // Fallback
                                        break;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" class="text-center text-gray-600 py-4">No attendance records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
