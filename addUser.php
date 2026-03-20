<?php
include_once __DIR__ . '/db/config.php';
checkLogin(['admin', 'staff']);
checkRole(['admin', 'staff']);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username']);
$password = $data['password'];
$role = $data['role'];

if (empty($username) || empty($password) || empty($role)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit;
}

if (!in_array($role, ['student', 'staff', 'admin'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid role']);
    exit;
}

// Check username uniqueness
$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username already exists']);
    exit;
}

// Insert new user
$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
$stmt->bind_param("sss", $username, $hashed, $role);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>