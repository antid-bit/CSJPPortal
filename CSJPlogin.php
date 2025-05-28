<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connection.php';

$remembered_student_no = isset($_COOKIE['student_no']) ? htmlspecialchars($_COOKIE['student_no']) : '';
$remembered_password = isset($_COOKIE['password']) ? htmlspecialchars($_COOKIE['password']) : '';

if (isset($_POST['submit'])) {
    $student_no = $_POST['student_no'];
    $password = $_POST['password'];
    $remember_me = isset($_POST['remember_me']);

    // First check in admins table
    $stmt = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
    $stmt->bind_param("s", $student_no);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    if ($admin_result && $admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();

        if ($password === $admin['password']) { // Assuming password is stored in plain text
            $_SESSION['user_id'] = $admin['admin_id'];
            $_SESSION['role'] = 'admin';
            $_SESSION['course_id'] = $admin['course_id'];

            if ($remember_me) {
                setcookie('student_no', $student_no, time() + (86400 * 30), "/");
                setcookie('password', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('student_no', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            // Redirect admin based on course_id
            switch ($admin['course_id']) {
                case 1: header("Location: /CSJPPortal/AdminBSCSHome.php"); exit;
                case 2: header("Location: /CSJPPortal/AdminBSAISHome.php"); exit;
                case 3: header("Location: /CSJPPortal/AdminCHTMHome.php"); exit;
                case 4: header("Location: /CSJPPortal/AdminBSBAHome.php"); exit;
                case 5: header("Location: /CSJPPortal/AdminBSENTREPHome.php"); exit;
                default: echo "<script>alert('Admin course not recognized.');</script>";
            }
        } else {
            echo "<script>alert('Invalid admin password.');</script>";
        }
    } else {
        // Not an admin, check users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $student_no);
        $stmt->execute();
        $results = $stmt->get_result();

        if ($results && $results->num_rows > 0) {
            $user = $results->fetch_assoc();

            if ($password === $user['birthday']) {
                $_SESSION['student_id'] = $user['user_id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['course_id'] = $user['course_id'];

                if ($remember_me) {
                    setcookie('student_no', $student_no, time() + (86400 * 30), "/");
                    setcookie('password', $password, time() + (86400 * 30), "/");
                } else {
                    setcookie('student_no', '', time() - 3600, "/");
                    setcookie('password', '', time() - 3600, "/");
                }

                switch ($user['course_id']) {
                    case 1: header("Location: /CSJPPortal/BSCSHome.php"); exit;
                    case 2: header("Location: /CSJPPortal/CHTMHome.php"); exit;
                    case 3: header("Location: /CSJPPortal/BSAISHome.php"); exit;
                    case 4: header("Location: /CSJPPortal/BSBAHome.php"); exit;
                    case 5: header("Location: /CSJPPortal/BSENTREPHome.php"); exit;
                    default: echo "<script>alert('Course not recognized.');</script>";
                }
            } else {
                echo "<script>alert('Invalid password.');</script>";
            }
        } else {
            // Not found in users table, check teachers table
            $stmt2 = $conn->prepare("SELECT * FROM teachers WHERE teacher_id = ?");
            $stmt2->bind_param("s", $student_no);
            $stmt2->execute();
            $results2 = $stmt2->get_result();

            if ($results2 && $results2->num_rows > 0) {
                $teacher = $results2->fetch_assoc();

                if ($password === $teacher['birthday']) {
                    $_SESSION['student_id'] = $teacher['teacher_id'];
                    $_SESSION['role'] = 'teacher';
                    $_SESSION['course_id'] = $teacher['course_id'];

                    if ($remember_me) {
                        setcookie('student_no', $student_no, time() + (86400 * 30), "/");
                        setcookie('password', $password, time() + (86400 * 30), "/");
                    } else {
                        setcookie('student_no', '', time() - 3600, "/");
                        setcookie('password', '', time() - 3600, "/");
                    }

                    switch ($teacher['course_id']) {
                        case 1: header("Location: /CSJPPortal/TeacherBSCSHome.php"); exit;
                        case 2: header("Location: /CSJPPortal/TeacherBSAISHome.php"); exit;
                        case 3: header("Location: /CSJPPortal/TeacherCHTMHome.php"); exit;
                        case 4: header("Location: /CSJPPortal/TeacherBSBAHome.php"); exit;
                        case 5: header("Location: /CSJPPortal/TeacherBSENTREPHome.php"); exit;
                        default: echo "<script>alert('Teacher course not recognized.');</script>";
                    }
                } else {
                    echo "<script>alert('Invalid password.');</script>";
                }
            } else {
                echo "<script>alert('Invalid student/teacher/admin ID.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSJP Web-based Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        /* [Previous CSS content remains exactly the same] */
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap");

        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background: url("/Portal/images/background1.jpg") no-repeat center center;
            background-size: cover;
            background-attachment: fixed;
            backdrop-filter: blur(3px);
        }

        .page-header {
            position: absolute;
            top: 0;
            width: 100%;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            z-index: 10;
        }

        .left-header {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .left-header img {
            width: 50px;
            height: auto;
        }

        .school-name {
            font-size: 18px;
            font-weight: bold;
            color: #2e86de; /* BLUE */
        }

        .right-header a {
            color: #2e86de; /* BLUE */
            text-decoration: underline;
        }

        .main-wrapper {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .login-box img.logo-top {
            width: 80px;
            margin-bottom: 10px;
        }

        .input-box {
            position: relative;
            margin: 20px 0;
        }

        .input-box input {
            width: 100%;
            padding: 10px 40px 10px 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .input-box i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            font-size: 18px;
            background: #2e86de;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            font-size: 14px;
        }

        .options-row label {
            color: red;
        }

        .options-row a {
            color: #2e86de;
            text-decoration: none;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 35px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- HEADER -->
    <header class="page-header">
        <div class="left-header">
            <img src="/Portal/images/logo.png" alt="Logo">
            <span class="school-name">College of Saint John Paul II Arts and Sciences</span>
        </div>
        <div class="right-header">
            <p>Need help? <a href="#">Contact Us</a></p>
        </div>
    </header>

    <!-- LOGIN FORM -->
    <div class="main-wrapper">
        <form class="login-box" method="post" autocomplete="off">
            <!-- Logo inside form -->
            <img src="/Portal/images/logo.png" alt="Logo" class="logo-top">
            <h2>STUDENT PORTAL</h2>

            <div class="input-box">
                <i class='bx bx-user'></i>
                <input type="text" name="student_no" placeholder="Student Number" required value="<?php echo $remembered_student_no; ?>">
            </div>

            <div class="input-box">
                <i class='bx bx-lock'></i>
                <input type="password" name="password" id="passwordInput" placeholder="Password" required value="<?php echo $remembered_password; ?>">
                <span class="toggle-password">
                    <i class='bx bx-hide' id="togglePasswordIcon"></i>
                </span>
            </div>

            <input type="submit" name="submit" value="LOG IN" class="login-btn">

            <div class="options-row">
                <label><input type="checkbox" name="remember_me"> Remember Me</label>
                <a href="#">Forgot Password?</a>
            </div>
        </form>
    </div>

    <!-- JavaScript for toggling password visibility -->
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