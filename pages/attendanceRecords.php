<?php
include_once __DIR__ . '/../db/config.php';
checkLogin(['staff', 'admin']);
checkRole(['admin', 'staff']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Attendance Records</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">
</head>

<body>
    <?php include './pages/sidebar.php'; ?>

    <div class="main">
        <div class="section">
            <h2>Attendance Records</h2>
            <button onclick="exportCSV()">Export CSV</button>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
                <?php
                $res = $conn->query("SELECT a.*, u.username, s.subject_name 
                FROM attendance a
                JOIN users u ON u.id=a.student_id
                JOIN subjects s ON s.id=a.subject_id
                ORDER BY a.date DESC");
                while ($row = $res->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['subject_name']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <script>
        function exportCSV() {
            window.location.href = 'exportAttendance.php';
        }
    </script>
</body>

</html>