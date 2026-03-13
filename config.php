<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "attendance_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ===============================
// 🔐 Protect Pages Function
// ===============================
function checkLogin() {
    if (!isset($_SESSION['users']) || empty($_SESSION['users'])) {
        header("Location: login.php");
        exit();
    }
}

?>