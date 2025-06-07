<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSJP Navigation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">

    <div class="w-64 bg-blue-900 h-screen text-white p-4">
        <div class="text-3xl font-bold mb-6 tracking-widest">CSJP</div>
        <nav>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 flex items-center" href="admin_home.php">
                <i class="fas fa-home mr-3 text-white"></i> Home
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 flex items-center" href="admin_studentlist.php">
                <i class="fas fa-users mr-3 text-white"></i> Students Masterlist
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 flex items-center" href="admin_add_student.php">
                <i class="fas fa-user-plus mr-3 text-white"></i> Add New Student
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 flex items-center" href="admin_addteach&subject.php">
                <i class="fas fa-chalkboard-teacher mr-3 text-white"></i> Manage Teacher & Subjects
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 flex items-center" href="admin_room.php">
                <i class="fas fa-door-open mr-3 text-white"></i> Manage Room
            </a>
            <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2 flex items-center" href="admin_schedule.php">
                <i class="fas fa-calendar-alt mr-3 text-white"></i> Manage Schedule
            </a>
            <a class="block py-2.5 px-4 hover:text-yellow-500 mt-8 flex items-center" href="login_page.php">
                <i class="fas fa-sign-out-alt mr-3 text-white"></i> Sign Out
            </a>
        </nav>
    </div>

</body>
</html>
