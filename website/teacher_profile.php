<?php
include("auth_teacher.php");
include("db_connection.php");

$teacher_id = $_SESSION['teacher_id'];  // Using teacher_id for the query

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Update teacher profile info
    $stmt = $conn->prepare("UPDATE teacher SET email = ?, phone = ? WHERE teacher_id = ?");
    $stmt->bind_param("sss", $email, $phone, $teacher_id);

    if ($stmt->execute()) {
        $success_message = "Profile updated successfully.";
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}

// Fetch current teacher info
$query = "SELECT firstname, lastname, email, phone FROM teacher WHERE teacher_id = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('MySQL prepare error: ' . $conn->error);
}

$stmt->bind_param("s", $teacher_id);  // Bind the teacher_id as a string
$stmt->execute();
$stmt->bind_result($firstname, $lastname, $email, $phone);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function validateForm() {
            const phone = document.getElementById('phone').value;
            const email = document.getElementById('email').value;
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            const phonePattern = /^[0-9]{10}$/;

            const emailError = document.getElementById('email-error');
            const phoneError = document.getElementById('phone-error');

            if (!emailPattern.test(email)) {
                emailError.textContent = 'Please enter a valid email address.';
                return false;
            } else {
                emailError.textContent = '';
            }

            if (!phonePattern.test(phone)) {
                phoneError.textContent = 'Please enter a valid 10-digit phone number.';
                return false;
            } else {
                phoneError.textContent = '';
            }

            return true;
        }

        // Real-time validation
        function validateEmail() {
            const email = document.getElementById('email').value;
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            const emailError = document.getElementById('email-error');
            if (!emailPattern.test(email)) {
                emailError.textContent = 'Please enter a valid email address.';
            } else {
                emailError.textContent = '';
            }
        }

        function validatePhone() {
            const phone = document.getElementById('phone').value;
            const phonePattern = /^[0-9]{10}$/;
            const phoneError = document.getElementById('phone-error');
            if (!phonePattern.test(phone)) {
                phoneError.textContent = 'Please enter a valid 10-digit phone number.';
            } else {
                phoneError.textContent = '';
            }
        }
    </script>
</head>
<body class="bg-gray-100 flex min-h-screen">

    <?php include("teacher_sidebar.php"); ?>

    <div class="flex-1 p-6">
        <h1 class="text-2xl font-bold mb-4">Teacher Profile</h1>

        <?php if (isset($success_message)): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (isset($error_message)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" onsubmit="return validateForm()" class="bg-white p-6 rounded shadow-md max-w-xl mx-auto">
            <div class="mb-4">
                <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" disabled class="w-full mt-1 p-2 border border-gray-300 rounded bg-gray-100" />
            </div>

            <div class="mb-4">
                <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" disabled class="w-full mt-1 p-2 border border-gray-300 rounded bg-gray-100" />
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required oninput="validateEmail()" class="w-full mt-1 p-2 border border-gray-300 rounded" />
                <p id="email-error" class="text-red-500 text-xs"></p>
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required oninput="validatePhone()" class="w-full mt-1 p-2 border border-gray-300 rounded" />
                <p id="phone-error" class="text-red-500 text-xs"></p>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Profile
            </button>
        </form>
    </div>

</body>
</html>
