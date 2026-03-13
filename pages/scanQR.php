<?php
include_once './db/config.php';
checkLogin(['staff', 'admin']);
$user = currentUser();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Scan QR</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">
    <script src="./assets/html5-qrcode.min.js"></script>
    <script src="./assets/qrcode.min.js"></script>
</head>

<body>
    <?php include './pages/sidebar.php'; ?>

    <div class="main">
        <div class="section">
            <h2>Scan QR</h2>
            <div id="reader" style="width: 500px;"></div>
            <div id="scanResult" style="margin-top:20px;"></div>
        </div>
    </div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            document.getElementById('scanResult').innerHTML =
                '<p>QR Scanned Successfully!</p><pre>' + decodedText + '</pre>';

            // Optional: Send AJAX to PHP to record attendance
            fetch('recordAttendance.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    data: decodedText
                })
            }).then(res => res.json()).then(data => {
                alert(data.message);
            });
        }

        // Initialize scanner
        const html5QrcodeScanner = new Html5Qrcode("reader");
        html5QrcodeScanner.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: 250
            },
            onScanSuccess
        );
    </script>
</body>

</html>