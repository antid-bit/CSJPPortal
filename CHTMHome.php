<?php
session_start();
$_SESSION['course'] = 'CHTM';
include 'db_connection.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-900 h-screen relative">
            <div class="p-4">
                <div class="text-white text-3xl font-bold mb-4 tracking-widest">CSJP</div>
            </div>
            <nav class="text-white">
                <a class="block py-2.5 px-4 bg-blue-700 rounded-md mb-2" href="CHTMHome.php">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="CHTMSchedule.php">
                    <i class="fas fa-user-graduate mr-2"></i>Schedule
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="CHTMlog.php">
                    <i class="fas fa-book mr-2"></i>Attendance Log
                </a>
                <a class="block py-2.5 px-4 hover:bg-blue-700 rounded-md mb-2" href="CHTMReports.php">
                    <i class="fas fa-chart-bar mr-2"></i>Reports
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4">
                <a class="block py-2.5 px-4 text-yellow-500 hover:text-yellow-600" href="CSJPlogin.php">
                    <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="relative w-1/2">
                    <input class="w-full py-2 px-4 rounded-md border border-gray-300" placeholder="Search" type="text"/>
                    <i class="fas fa-search absolute top-3 right-3 text-gray-400"></i>
                </div>
            </div>

            <!-- Welcome Section -->
            <div class="bg-blue-700 text-white p-6 rounded-md mb-6">
                <h1 class="text-2xl font-bold">Welcome to Student Portal</h1>
				<h2 class="text-2x2 font-bold">CHTM 3</h2>
            </div>

            <!-- Dashboard Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Attendance Calendar -->
                <div class="bg-white p-6 rounded-md shadow-md">
                    <h2 class="text-xl font-bold mb-4">Attendance Calendar</h2>
                    <div class="grid grid-cols-7 gap-2 text-center text-sm">
                        <div class="font-bold">Sun</div><div class="font-bold">Mon</div><div class="font-bold">Tue</div>
                        <div class="font-bold">Wed</div><div class="font-bold">Thu</div><div class="font-bold">Fri</div><div class="font-bold">Sat</div>
                        <div></div><div></div><div></div><div class="p-2 border rounded-md"><span>1</span></div>
                        <div class="p-2 border rounded-md"><span>2</span></div>
                        <div class="p-2 border rounded-md"><span>3</span></div>
                        <div class="p-2 border rounded-md"><span>4</span></div>
                        <div class="p-2 border rounded-md"><span>5</span></div>
                        <div class="p-2 border rounded-md"><span>6</span></div>
                        <div class="p-2 border rounded-md"><span>7</span></div>
                        <div class="p-2 border rounded-md"><span>8</span></div>
                        <div class="p-2 border rounded-md"><span>9</span></div>
                        <div class="p-2 border rounded-md"><span>10</span></div>
                        <div class="p-2 border rounded-md"><span>11</span></div>
                        <div class="p-2 border rounded-md"><span>12</span></div>
                        <div class="p-2 border rounded-md"><span>13</span></div>
                        <div class="p-2 border rounded-md"><span>14</span></div>
                        <div class="p-2 border rounded-md"><span>15</span></div>
                        <div class="p-2 border rounded-md"><span>16</span></div>
                        <div class="p-2 border rounded-md"><span>17</span></div>
                        <div class="p-2 border rounded-md"><span>18</span></div>
                        <div class="p-2 border rounded-md"><span>19</span></div>
                        <div class="p-2 border rounded-md"><span>20</span></div>
                        <div class="p-2 border rounded-md"><span>21</span></div>
                        <div class="p-2 border rounded-md"><span>22</span></div>
                        <div class="p-2 border rounded-md"><span>23</span></div>
                        <div class="p-2 border rounded-md"><span>24</span></div>
                        <div class="p-2 border rounded-md"><span>25</span></div>
                        <div class="p-2 border rounded-md"><span>26</span></div>
                        <div class="p-2 border rounded-md"><span>27</span></div>
                        <div class="p-2 border rounded-md"><span>28</span></div>
                        <div class="p-2 border rounded-md"><span>29</span></div>
                        <div class="p-2 border rounded-md"><span>30</span></div>
                        <div class="p-2 border rounded-md"><span>31</span></div>
                    </div>
                </div>

                <!-- CSJP Facebook Post -->
                <div class="bg-white p-6 rounded-md shadow-md">
                    <h2 class="text-xl font-bold mb-4">CSJP Facebook Post</h2>
                    <div class="w-full overflow-hidden">
                        <iframe src="https://www.facebook.com/plugins/post.php?href=https://www.facebook.com/100064641764297/posts/1105082471656468"
                                class="w-full h-[500px] md:h-[600px] lg:h-[650px]"
                                style="border:none;overflow:hidden"
                                scrolling="no"
                                frameborder="0"
                                allowfullscreen="true"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

