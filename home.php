<?php
include_once __DIR__ . '/db/config.php';
checkLogin();
$user = currentUser();
checkRole(['admin', 'staff']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>STY QR Attendance System</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">
</head>

<body>

    <?php include './pages/sidebar.php'; ?>

    <div class="main">
        <div id="dashboard" class="section">
            <h2>Dashboard</h2>

            <!-- Staff/Admin Dashboard Stats -->
            <div class="card-container">
                <div class="card">
                    <h3>Total Students</h3>
                    <p><?php echo $conn->query("SELECT COUNT(*) FROM users WHERE role='student'")->fetch_row()[0]; ?></p>
                </div>
                <div class="card">
                    <h3>Total Attendance</h3>
                    <p><?php echo $conn->query("SELECT COUNT(*) FROM attendance")->fetch_row()[0]; ?></p>
                </div>
                <div class="card">
                    <h3>Total Subjects</h3>
                    <p><?php echo $conn->query("SELECT COUNT(*) FROM subjects")->fetch_row()[0]; ?></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const buttons = document.querySelectorAll('.sidebar button');
        const sections = document.querySelectorAll('.section');

        function showSection(id) {
            sections.forEach(sec => sec.style.display = 'none');
            const s = document.getElementById(id);
            if (s) s.style.display = 'block';
            buttons.forEach(b => b.classList.remove('active'));
            const btn = document.getElementById('btn-' + id);
            if (btn) btn.classList.add('active');
        }
        document.addEventListener('DOMContentLoaded', () => showSection('dashboard'));
    </script>
    <script src="./assets/script.js"></script>
</body>

</html>