<?php
session_start();
include("db_connection.php");

if (isset($_POST['submit'])) {
    $student_no = $_POST['student_no'];
    $password = $_POST['password'];

    // Check for Student login
    $sql = "SELECT * FROM user WHERE student_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_no); // Bind student_no as a string
    $stmt->execute();
    $results = $stmt->get_result();

    if ($results->num_rows > 0) {
        $user = $results->fetch_assoc();

        // Check if the entered password matches the user's birthday
        if ($password == $user['birthday']) {
            $_SESSION['student_no'] = $user['student_no'];
            $_SESSION['role'] = 'student';
            header("Location: student_home.php"); // Redirect to student home page
            exit();
        } else {
            echo "<script>alert('Invalid password for student!');</script>";
        }
    }

    // Check for Teacher login
    $sql_teacher = "SELECT * FROM teacher WHERE teacher_id = ?";
    $stmt_teacher = $conn->prepare($sql_teacher);
    $stmt_teacher->bind_param("s", $student_no);
    $stmt_teacher->execute();
    $results_teacher = $stmt_teacher->get_result();

    if ($results_teacher->num_rows > 0) {
        $teacher = $results_teacher->fetch_assoc();

        // Check if the entered password matches the teacher's birthday
        if ($password == $teacher['birthday']) {
            $_SESSION['teacher_id'] = $teacher['teacher_id'];
            $_SESSION['role'] = 'teacher';
            header("Location: teacher_dashboard.php"); // Redirect to teacher dashboard
            exit();
        } else {
            echo "<script>alert('Invalid password for teacher!');</script>";
        }
    }

    // Check for Admin login
    $sql_admin = "SELECT * FROM admin WHERE admin_id = ?";
    $stmt_admin = $conn->prepare($sql_admin);
    $stmt_admin->bind_param("s", $student_no);
    $stmt_admin->execute();
    $results_admin = $stmt_admin->get_result();

    if ($results_admin->num_rows > 0) {
        $admin = $results_admin->fetch_assoc();

        // Check if the entered password matches the admin's birthday
        if ($password == $admin['birthday']) {
            $_SESSION['admin_id'] = $admin['admin_id'];
            $_SESSION['role'] = 'admin';
            header("Location: admin_home.php"); // Redirect to admin home page
            exit();
        } else {
            echo "<script>alert('Invalid password for admin!');</script>";
        }
    } else {
        echo "<script>alert('Invalid student number or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSJP Web-based Attendance System</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="icon" href="images/logo.png">
</head>
<body>
    <div class="main-wrapper">
        <header>
            <div class="left-side">
                <img src="images/logo.png" alt="Logo">
                <h1>College of Saint John Paul II Arts and Sciences</h1>
            </div>
            <div class="right-side">
                <p>Need help? <a href="#">Contact Us</a></p>
            </div>
        </header>

        <form class="login-box" method="post">
            <img src="images/logo.png" class="logo" alt="Logo">
            <h2>LOGIN</h2>
            <img src="images/logo.png" class="login-bg-logo">

            <div class="input-box">
                <i class='bx bx-user'></i>
                <input type="text" name="student_no" placeholder="Student/Teacher/Admin ID" required>
            </div>
            <div class="input-box">
                <i class='bx bx-lock'></i>
                <input type="password" name="password" id="passwordInput" placeholder="Password" required>
                <span class="toggle-password">
                    <i class='bx bx-hide' id="togglePasswordIcon"></i>
                </span>
            </div>
            <input type="submit" name="submit" value="LOG IN" class="login-btn">

            <div class="remember-forgot">
                <a href="#">Forgot Password?</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('togglePasswordIcon');

            toggleIcon.addEventListener('click', function () {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    toggleIcon.classList.remove('bx-hide');
                    toggleIcon.classList.add('bx-show');
                } else {
                    passwordInput.type = "password";
                    toggleIcon.classList.remove('bx-show');
                    toggleIcon.classList.add('bx-hide');
                }
            });
        });
    </script>
</body>
</html>
