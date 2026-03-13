<?php
include_once './db/config.php';
checkLogin(['student', 'staff', 'admin']);
$user = currentUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generate QR</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body>
    <?php include './pages/sidebar.php'; ?>

    <div class="main">
        <div class="section">
            <h2>Generate My QR</h2>
            <?php if ($user['role'] == 'student'): ?>
                <p>Select Subject:</p>
                <select id="subjectSelect">
                    <?php
                    $res = $conn->query("SELECT * FROM subjects");
                    while ($row = $res->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['subject_name']; ?></option>
                    <?php endwhile; ?>
                </select>
            <?php endif; ?>
            <button onclick="generateQR()">Generate QR</button>
            <div id="qrcode"></div>
        </div>
    </div>

    <script>
        function generateQR() {
            const qrContainer = document.getElementById('qrcode');
            qrContainer.innerHTML = '';
            const studentId = '<?php echo $user['id']; ?>';
            const subjectId = document.getElementById('subjectSelect')?.value || '';
            const payload = {
                student_id: studentId,
                subject_id: subjectId,
                timestamp: new Date().toISOString()
            };
            new QRCode(qrContainer, {
                text: JSON.stringify(payload),
                width: 200,
                height: 200
            });
        }
    </script>
</body>

</html>