<?php
include_once __DIR__ . '/db/config.php';
checkLogin(['staff', 'admin']);
checkRole(['admin', 'staff']);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$subject_name = trim($data['subject']);

if (empty($subject_name)) {
    echo json_encode(['success' => false, 'message' => 'Subject name is required']);
    exit;
}

// Check if subject already exists
$stmt = $conn->prepare("SELECT id FROM subjects WHERE subject_name=?");
$stmt->bind_param("s", $subject_name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Subject already exists']);
    exit;
}

// Insert subject
$stmt = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
$stmt->bind_param("s", $subject_name);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Subject added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>