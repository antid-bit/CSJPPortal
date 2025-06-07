<?php 
include("auth_admin.php");
include("db_connection.php");

// Add Teacher
if (isset($_POST['save_teacher'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("INSERT INTO teacher (firstname, lastname, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $phone);
    $stmt->execute();
}

// Add Subject
if (isset($_POST['save_subject'])) {
    $subject_name = $_POST['subject_name'];
    $course_id = $_POST['course_id'];

    $stmt = $conn->prepare("INSERT INTO subjects (subject_name, course_id) VALUES (?, ?)");
    $stmt->bind_param("si", $subject_name, $course_id);
    $stmt->execute();
}

// Fetch Courses for Subject Dropdown
$courses = $conn->query("SELECT course_id, course_name FROM courses");

// Fetch Teachers for the teacher list (Optional, if needed)
$teachers = $conn->query("SELECT teacher_id, firstname, lastname, email, phone FROM teacher");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Teachers and Subjects</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex min-h-screen">

<?php include("admin_sidebar.php"); ?>

<!-- Main content area that grows to fit the height of the sidebar -->
<div class="flex-1 p-6 overflow-y-auto" style="height: calc(100vh - 4rem);">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Manage Teachers and Subjects</h1>

    <!-- Add Teacher Form -->
    <div class="bg-white p-6 mb-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Teacher</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label>First Name</label>
                <input type="text" name="firstname" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Last Name</label>
                <input type="text" name="lastname" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Phone</label>
                <input type="text" name="phone" class="w-full p-2 border rounded" required>
            </div>
            <div class="col-span-2 text-right">
                <button type="submit" name="save_teacher" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Add Teacher
                </button>
            </div>
        </form>
    </div>

    <!-- Add Subject Form -->
    <div class="bg-white p-6 mb-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Add New Subject</h2>
        <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label>Subject Name</label>
                <input type="text" name="subject_name" class="w-full p-2 border rounded" required>
            </div>
            <div>
                <label>Course</label>
                <select name="course_id" class="w-full p-2 border rounded" required>
                    <?php while ($course = $courses->fetch_assoc()): ?>
                        <option value="<?= $course['course_id'] ?>"><?= $course['course_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-span-2 text-right">
                <button type="submit" name="save_subject" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Add Subject
                </button>
            </div>
        </form>
    </div>

    <!-- Teacher List -->
    <div class="bg-white p-6 mb-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Current Teachers</h2>
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">First Name</th>
                    <th class="px-4 py-2 border">Last Name</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Phone</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($teacher = $teachers->fetch_assoc()): ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= $teacher['firstname'] ?></td>
                        <td class="px-4 py-2 border"><?= $teacher['lastname'] ?></td>
                        <td class="px-4 py-2 border"><?= $teacher['email'] ?></td>
                        <td class="px-4 py-2 border"><?= $teacher['phone'] ?></td>
                        <td class="px-4 py-2 border">
                            <a href="delete_teacher.php?teacher_id=<?= $teacher['teacher_id'] ?>" class="text-red-600 hover:underline"
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Subject List -->
    <div class="bg-white p-6 mb-6 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Current Subjects</h2>
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-2 border">Subject Name</th>
                    <th class="px-4 py-2 border">Course</th>
                    <th class="px-4 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $subjects_result = $conn->query("SELECT s.subject_id, s.subject_name, c.course_name FROM subjects s JOIN courses c ON s.course_id = c.course_id");
                while ($subject = $subjects_result->fetch_assoc()):
                ?>
                    <tr>
                        <td class="px-4 py-2 border"><?= $subject['subject_name'] ?></td>
                        <td class="px-4 py-2 border"><?= $subject['course_name'] ?></td>
                        <td class="px-4 py-2 border">
                            <a href="delete_subject.php?subject_id=<?= $subject['subject_id'] ?>" class="text-red-600 hover:underline"
                               onclick="return confirm('Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
