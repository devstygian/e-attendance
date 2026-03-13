<?php
// ===============================
// HOME PAGE
// ===============================

// Include config and check login
include_once './db/config.php';
checkLogin();

// Get current user info
$user = currentUser();
$role = $user['role']; // 'admin', 'staff', or 'student'
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STY QR Attendance System</title>

    <!-- Styles -->
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">

    <!-- QR Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://kit.fontawesome.com/f02a36f28e.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body>

    <!-- Sidebar -->
    <?php include './pages/sidebar.php'; ?>

    <!-- Main Content -->
    <div class="main">

        <!-- ================= DASHBOARD ================= -->
        <div id="dashboard" class="section">
            <h2>Dashboard</h2>

            <?php if ($role === 'student'): ?>
                <!-- Student Dashboard -->
                <div class="card-container">
                    <div class="card">
                        <h3>Subjects Enrolled</h3>
                        <p id="studentSubjects">0</p>
                    </div>
                    <div class="card">
                        <h3>Absent</h3>
                        <p id="studentAbsent">0</p>
                    </div>
                </div>
                <h3>Attendance Details</h3>
                <ul id="studentAttendanceList"></ul>

            <?php else: ?>
                <!-- Admin & Staff Dashboard -->
                <div class="card-container">
                    <div class="card">
                        <h3>Total Students</h3>
                        <p id="totalStudents">0</p>
                    </div>
                    <div class="card">
                        <h3>Total Attendance</h3>
                        <p id="totalAttendance">0</p>
                    </div>
                    <div class="card">
                        <h3>Total Subjects</h3>
                        <p id="totalSubjects">0</p>
                    </div>
                </div>
                <h3>Attendance Per Subject</h3>
                <ul id="subjectStats"></ul>
            <?php endif; ?>
        </div>

        <!-- ================= GENERATE QR (Staff/Admin Only) ================= -->
        <?php if ($role === 'admin' || $role === 'staff'): ?>
            <div id="generate" class="section" style="display:none;">
                <h2>Generate Student QR</h2>
                <input type="text" id="studentName" placeholder="Student Name">
                <input type="text" id="studentID" placeholder="Student ID">
                <select id="subjectSelect"></select>
                <br>
                <button onclick="generateQR()">Generate QR</button>
                <div id="qrcode"></div>
                <button onclick="downloadQR()"><i class="fa-solid fa-download"></i> Download QR</button>
            </div>

            <!-- ================= SCAN QR (Staff/Admin Only) ================= -->
            <div id="scan" class="section" style="display:none;">
                <h2>Scan Student QR</h2>
                <input type="file" id="qrUpload" accept="image/*">
                <div id="uploadResult"></div>
            </div>

            <!-- ================= SUBJECT MANAGEMENT (Staff/Admin Only) ================= -->
            <div id="subjects" class="section" style="display:none;">
                <h2>Manage Subjects</h2>
                <input type="text" id="newSubject" placeholder="Subject Name">
                <button onclick="addSubject()">Add Subject</button>
                <ul id="subjectList"></ul>
            </div>
        <?php endif; ?>

        <!-- ================= USERS MANAGEMENT (Admin Only) ================= -->
        <?php if ($role === 'admin'): ?>
            <div id="users" class="section" style="display:none;">
                <h2>Manage Users</h2>
                <button onclick="loadUsers()">Refresh Users</button>
                <table id="usersTable">
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </table>
            </div>
        <?php endif; ?>

        <!-- ================= ATTENDANCE RECORDS (All Roles) ================= -->
        <div id="records" class="section" style="display:none;">
            <h2>Attendance Records</h2>
            <?php if ($role !== 'student'): ?>
                <button onclick="exportCSV()">Export CSV</button>
            <?php endif; ?>
            <table id="attendanceTable">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </table>
        </div>

    </div>

    <!-- ================= Sidebar & Sections JS ================= -->
    <script>
        const buttons = document.querySelectorAll('.sidebar button');
        const sections = document.querySelectorAll('.section');

        function showSection(sectionId) {
            sections.forEach(sec => sec.style.display = 'none');
            const section = document.getElementById(sectionId);
            if (section) section.style.display = 'block';

            buttons.forEach(btn => btn.classList.remove('active'));
            const activeBtn = document.getElementById('btn-' + sectionId);
            if (activeBtn) activeBtn.classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            showSection('dashboard');
        });
    </script>

    <!-- ================= Custom JS ================= -->
    <script src="./assets/script.js"></script>
</body>

</html>