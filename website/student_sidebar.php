<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSJP Sidebar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="w-64 bg-blue-900 h-screen text-white p-4">
        <div class="text-3xl font-bold mb-6 tracking-widest text-center">CSJP</div>
        <nav class="space-y-2">
            <a class="flex items-center gap-3 py-2.5 px-4 hover:bg-blue-700 rounded-md" href="student_home.php">
                <i class="fa-solid fa-home text-white"></i> <span class="flex-1">Home</span>
            </a>
            <a class="flex items-center gap-3 py-2.5 px-4 hover:bg-blue-700 rounded-md" href="student_masterlist.php">
                <i class="fa-solid fa-users text-white"></i> <span class="flex-1">My Classmates</span>
            </a>
            <a class="flex items-center gap-3 py-2.5 px-4 hover:bg-blue-700 rounded-md" href="student_schedule.php">
                <i class="fa-solid fa-calendar-alt text-white"></i> <span class="flex-1">Schedule</span>
            </a>
            <a class="flex items-center gap-3 py-2.5 px-4 hover:bg-blue-700 rounded-md" href="student_log.php">
                <i class="fa-solid fa-clock text-white"></i> <span class="flex-1">Attendance Log</span>
            </a>
            <a class="flex items-center gap-3 py-2.5 px-4 hover:bg-blue-700 rounded-md" href="student_reports.php">
                <i class="fa-solid fa-file-alt text-white"></i> <span class="flex-1">Reports</span>
            </a>
            <a class="flex items-center gap-3 py-2.5 px-4 hover:text-yellow-500 mt-8" href="login_page.php">
                <i class="fa-solid fa-sign-out-alt text-white"></i> <span class="flex-1">Sign Out</span>
            </a>
        </nav>
    </div>
</body>
</html>
