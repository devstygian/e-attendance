<?php
include_once __DIR__ . '/../db/config.php';
checkLogin();

$role = $_SESSION['users']['role'];
?>

<div class="sidebar">
    <h2>STY System</h2>

    <div class="menu-buttons">
        <button id="btn-dashboard" onclick="showSection('dashboard')">Dashboard</button>

        <?php if ($role == 'admin' || $role == 'staff'): ?>
            <button id="btn-generate" onclick="location.href='generateQR.php'">Generate QR</button>
            <button id="btn-scan" onclick="location.href='scanQR.php'">Scan QR</button>
            <button id="btn-subjects" onclick="location.href='subjects.php'">Manage Subjects</button>
        <?php endif; ?>

        <?php if ($role == 'admin'): ?>
            <button id="btn-users" onclick="location.href='users.php'">Manage Users</button>
        <?php endif; ?>

        <button id="btn-records" onclick="location.href='attendanceRecords.php'">Attendance Records</button>
    </div>

    <a href="<?php echo $base_url; ?>auth/logout.php" class="logout-btn">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>