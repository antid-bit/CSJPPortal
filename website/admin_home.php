<!-- admin_home.php -->
<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.js" defer></script>
</head>
<body class="bg-gray-100 flex">
<?php include("admin_sidebar.php"); ?>
        <!-- Main Content -->
        <div class="flex-1 p-10">
            <h2 class="text-2xl font-bold mb-6">Welcome, Admin!</h2>
            <p class="text-gray-700">Use the sidebar to manage schedules, rooms, and users.</p>
        </div>
		    <div class="bg-white p-6 rounded-md shadow-md w-[500px] h-[300px] flex flex-col justify-center items-center mt-8">
        <h2 class="text-lg font-bold mb-3 text-center">CSJP Post</h2>
        <div class="w-full h-full overflow-hidden">
            <iframe src="https://www.facebook.com/plugins/post.php?href=https://www.facebook.com/100064641764297/posts/1105082471656468"
                    class="w-full h-full"
                    style="border:none;overflow:hidden"
                    scrolling="no"
                    frameborder="0"
                    allowfullscreen="true"></iframe>
        </div>
    </div>
    </div>

    <script>
        // Toggle sidebar on mobile
        document.querySelector('button').addEventListener('click', () => {
            document.querySelector('.lg\\:w-64').classList.toggle('w-64');
            document.querySelector('.lg\\:w-64').classList.toggle('w-0');
        });
    </script>

</body>
</html>
