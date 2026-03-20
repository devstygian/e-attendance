<?php
include_once __DIR__ . '/../db/config.php'; // config.php starts session

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($confirm)) {
        $errors[] = "All fields are required.";
    } elseif ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }

    // Check username uniqueness
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Username already exists.";

    // Insert new user
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?,?,?)");
        $stmt->bind_param("sss", $username, $hashed, $role);
        if ($stmt->execute()) {
            header("Location: login.php?registered=1");
            exit();
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/auth.css">
</head>

<body>
    <div class="auth-container">
        <h2>Register</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <select name="role" required>
                <option value="student">Student</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>