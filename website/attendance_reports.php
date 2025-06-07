<?php include("auth_teacher.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Attendance Reports</title>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <div class="flex">
    <?php include("teacher_sidebar.php"); ?>
    <div class="ml-64 p-8 w-full">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">Attendance Reports</h1>

      <!-- Attendance report content -->
      <div class="bg-white shadow rounded-lg p-6">
        <div id="attendanceReports">
          <!-- Dynamic content will be injected here -->
        </div>
      </div>
    </div>
  </div>

  <script>
    async function loadAttendanceReports() {
      const res = await fetch("get_attendance_reports.php");
      const data = await res.json();
      const container = document.getElementById("attendanceReports");

      let currentCourse = '';
      let currentYearLevel = '';
      let currentSubject = '';
      let reportHTML = '';

      data.forEach(row => {
        // Course section
        if (currentCourse !== row.course_name) {
          if (currentCourse !== '') reportHTML += '</tbody></table>'; // Close previous table if course changes
          currentCourse = row.course_name;
          reportHTML += `<h2 class="text-2xl font-bold text-blue-600 mt-6">${row.course_name}</h2>`;
        }

        // Year Level section
        if (currentYearLevel !== row.year_level) {
          if (currentYearLevel !== '') reportHTML += '</tbody></table>'; // Close previous year level section
          currentYearLevel = row.year_level;
          reportHTML += `<h3 class="text-xl font-semibold text-green-600 mt-4">Year Level: ${row.year_level}</h3>`;
        }

        // Subject section
        if (currentSubject !== row.subject_name) {
          if (currentSubject !== '') reportHTML += '</tbody></table>'; // Close previous subject section
          currentSubject = row.subject_name;
          reportHTML += `<h4 class="text-lg font-medium text-yellow-600 mt-2">Subject: ${row.subject_name}</h4>`;
        }

        // Initialize table if this is the first record for the subject
        if (reportHTML.indexOf("<table") === -1 || reportHTML.indexOf(row.subject_name) === -1) {
          reportHTML += `
            <table class="min-w-full table-auto border mt-2 mb-4">
              <thead class="bg-gray-200">
                <tr>
                  <th class="border px-4 py-2 text-left">Date</th>
                  <th class="border px-4 py-2 text-left">Student Name</th>
                  <th class="border px-4 py-2 text-left">Status</th>
                </tr>
              </thead>
              <tbody>
          `;
        }

        // Add row to the table
        reportHTML += `
          <tr>
            <td class="border px-4 py-2">${row.date}</td>
            <td class="border px-4 py-2">${row.firstname} ${row.lastname}</td>
            <td class="border px-4 py-2">${row.status}</td>
          </tr>
        `;
      });

      // Close any remaining open tags for the table
      reportHTML += '</tbody></table>';
      container.innerHTML = reportHTML;
    }

    loadAttendanceReports();
  </script>
</body>
</html>
