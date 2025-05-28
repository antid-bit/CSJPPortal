<?php
include 'db_connection.php';

$today = date('l');

// Fetch CHTM (`course_id = 3`) subjects only
$sql = "SELECT s.subject_name, cs.day_of_week, cs.time_slot 
        FROM course_schedule cs
        JOIN subjects s ON cs.subject_id = s.subject_id
        WHERE cs.course_id = 3
        ORDER BY FIELD(cs.day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')";

$result = $conn->query($sql);

if (!$result) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Dashboard - CHTM Schedule</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <div class="w-64 bg-blue-900 h-screen">
      <div class="p-4">
        <div class="text-white text-3xl font-bold mb-4 tracking-widest">CSJP</div>
      </div>
      <nav class="text-white">
        <a class="block py-2.5 px-4 rounded-md mb-2" href="CHTMHome.php">
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
    <div class="flex-1 p-8 overflow-auto">
      <h1 class="text-3xl font-bold mb-6 text-center">📅 Weekly Class Schedule (CHTM)</h1>

      <div class="overflow-x-auto">
        <table class="min-w-full w-full bg-white border border-gray-300 text-center text-lg">
          <thead>
            <tr class="bg-blue-500 text-white text-xl">
              <th class="py-4 px-6 border">Subject Name</th>
              <th class="py-4 px-6 border">Time Slot</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $current_day = null;
            while ($row = $result->fetch_assoc()):
              $day = $row['day_of_week'];
              $is_today = ($day === $today);

              if ($current_day !== $day):
                $current_day = $day;
            ?>
              <tr class="bg-gray-200 text-left text-lg font-bold">
                <td colspan="2" class="py-2 px-4 <?php echo $is_today ? 'bg-yellow-200' : ''; ?>">
                  <?php echo htmlspecialchars($day); ?> <?php echo $is_today ? '(Today)' : ''; ?>
                </td>
              </tr>
            <?php endif; ?>
              <tr class="<?php echo $is_today ? 'bg-blue-100 font-semibold' : ''; ?> hover:bg-gray-100">
                <td class="py-3 px-6 border"><?php echo htmlspecialchars($row['subject_name']); ?></td>
                <td class="py-3 px-6 border"><?php echo htmlspecialchars($row['time_slot']); ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
