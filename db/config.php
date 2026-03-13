<?php
// ===============================
// CONFIGURATION & SESSION
// ===============================

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ===============================
// DATABASE CONNECTION
// ===============================
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "attendance_system";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// ===============================
// BASE URL
// ===============================
$base_url = "http://localhost/attendanceSystem/";

// ===============================
// 🔐 CHECK LOGIN FUNCTION
// ===============================
function checkLogin($allowedRoles = [])
{
    if (!isset($_SESSION['users']) || empty($_SESSION['users'])) {
        header("Location: {$GLOBALS['base_url']}auth/login.php");
        exit();
    }

    if (!empty($allowedRoles) && !in_array($_SESSION['users']['role'], $allowedRoles)) {
        header("Location: {$GLOBALS['base_url']}home.php");
        exit();
    }
}

// ===============================
// CURRENT USER
// ===============================
function currentUser()
{
    return $_SESSION['users'] ?? null;
}
