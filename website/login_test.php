<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php'; // Make sure this includes $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_no = trim($_POST['student_no']);
    $password = trim($_POST['password']);

    echo "<strong>Debug Output:</strong><br>";

    // Step 1: Query user
    $stmt = $conn->prepare("SELECT * FROM user WHERE student_no = ?");
    $stmt->bind_param("s", $student_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();

        echo "✅ Student found in DB<br>";
        echo "Submitted Password: '$password'<br>";
        echo "Database Birthday: '" . $student['birthday'] . "'<br>";

        if ($password === $student['birthday']) {
            echo "✅ Password matched!<br>";
            $_SESSION['student_no'] = $student['student_no'];
            echo "✅ Session started.<br>";
        } else {
            echo "❌ Password mismatch.<br>";
        }
    } else {
        echo "❌ No student found with ID '$student_no'<br>";
    }
}
?>

<form method="POST">
    <label>Student No: <input type="text" name="student_no" value="S22-0059"></label><br><br>
    <label>Password (Birthday): <input type="text" name="password" value="2001-07-22"></label><br><br>
    <button type="submit">Login Test</button>
</form>
