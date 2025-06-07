<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

if (isset($_POST['submit'])) {
    $account_no = $_POST['student_no'];  // This is the account number (e.g., student_no or teacher_id or admin_id)
    $password = $_POST['password'];  // This is the password (birthday)

    // Debugging: Output entered account number and password
    echo "<b>Account No: </b>$account_no<br>";
    echo "<b>Password entered: </b>$password<br>";

    // Step 1: Check in the 'user' table (students)
    $stmt = $conn->prepare("SELECT * FROM user WHERE student_no = ?");
    $stmt->bind_param("s", $account_no);  // Ensure account_no is treated as a string
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows == 1) {
        $student = $result->fetch_assoc();
        // Debugging: Show student info
        echo "<b>Student found:</b><br>";
        print_r($student);
        
        // Compare entered password (birthday)
        if ($password === date('Y-m-d', strtotime($student['birthday']))) {
            $_SESSION['student_no'] = $student['student_no'];
            $_SESSION['role'] = 'student';
            $_SESSION['course_id'] = $student['course_id'];
            $_SESSION['year_level_id'] = $student['year_level_id'];
            header("Location: student_home.php");
            exit;
        } else {
            echo "<script>alert('Invalid password for student.');</script>";
        }
    } else {
        // Step 2: Check in the 'teacher' table
        $stmt = $conn->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
        $stmt->bind_param("s", $account_no); // Ensure account_no is treated as a string (e.g., teacher_id)
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows == 1) {
            $teacher = $result->fetch_assoc();
            // Debugging: Show teacher info
            echo "<b>Teacher found:</b><br>";
            print_r($teacher);
            
            // Compare entered password (birthday)
            if ($password === date('Y-m-d', strtotime($teacher['birthday']))) {
                $_SESSION['teacher_id'] = $teacher['teacher_id'];
                $_SESSION['role'] = 'teacher';
                header("Location: teacher_dashboard.php");
                exit;
            } else {
                echo "<script>alert('Invalid password for teacher.');</script>";
            }
        } else {
            // Step 3: Check in the 'admin' table
            $stmt = $conn->prepare("SELECT * FROM admin WHERE admin_id = ?");
            $stmt->bind_param("s", $account_no);  // Ensure account_no is treated as a string
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result && $result->num_rows == 1) {
                $admin = $result->fetch_assoc();
                // Debugging: Show admin info
                echo "<b>Admin found:</b><br>";
                print_r($admin);
                
                // Compare entered password (birthday)
                if ($password === date('Y-m-d', strtotime($admin['birthday']))) {
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['role'] = 'admin';
                    header("Location: admin_home.php");
                    exit;
                } else {
                    echo "<script>alert('Invalid password for admin.');</script>";
                }
            } else {
                echo "<script>alert('Invalid ID or password.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <div class="flex justify-center items-center min-h-screen">
        <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">Login to Attendance System</h2>
            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="student_no" class="block text-sm font-medium text-gray-600">Account No. (ID)</label>
                    <input type="text" name="student_no" id="student_no" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm" value="" required>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
                    <input type="password" name="password" id="password" class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm" value="" required>
                </div>

                <div class="mb-4">
                    <button type="submit" name="submit" class="w-full bg-blue-500 text-white p-3 rounded-md hover:bg-blue-600">Login</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
