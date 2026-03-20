<?php
include_once __DIR__ . '/db/config.php';
checkLogin(['staff', 'admin']);
checkRole(['admin', 'staff']);

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$qrData = json_decode($data['data'], true);

if (!$qrData || !isset($qrData['student_id'], $qrData['subject_id'], $qrData['timestamp'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid QR data']);
    exit;
}

$student_id = (int)$qrData['student_id'];
$subject_id = (int)$qrData['subject_id'];
$timestamp = $qrData['timestamp'];

// Check if already recorded for today
$date = date('Y-m-d', strtotime($timestamp));
$stmt = $conn->prepare("SELECT id FROM attendance WHERE student_id=? AND subject_id=? AND DATE(date)=?");
$stmt->bind_param("iis", $student_id, $subject_id, $date);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Attendance already recorded for today']);
    exit;
}

// Determine status based on time (simple: before 9am present, 9-10 late, after absent)
$current_time = strtotime($timestamp);
$class_start = strtotime(date('Y-m-d 09:00:00', strtotime($timestamp)));
$late_threshold = strtotime(date('Y-m-d 10:00:00', strtotime($timestamp)));

if ($current_time <= $class_start) {
    $status = 'present';
} elseif ($current_time <= $late_threshold) {
    $status = 'late';
} else {
    $status = 'absent';
}

// Insert attendance
$stmt = $conn->prepare("INSERT INTO attendance (student_id, subject_id, status, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $student_id, $subject_id, $status, $timestamp);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Attendance recorded: ' . $status]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>