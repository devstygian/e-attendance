<?php
include_once __DIR__ . '/../db/config.php'; // config.php starts session

$errors = [];

// Redirect already logged-in users to home
if (isset($_SESSION['users'])) {
    header("Location: ../home.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Prepare SQL to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $user, $hashed, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        if ($hashed && password_verify($password, $hashed)) {
            // Store user info in session
            $_SESSION['users'] = [
                'id' => $id,
                'username' => $user,
                'role' => $role
            ];

            // Redirect based on role (optional)
            header("Location: ../home.php");
            exit();
        } else {
            $errors[] = "Incorrect password.";
        }
    } else {
        $errors[] = "Username not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - STY QR Attendance</title>
    <link rel="stylesheet" href="../assets/auth.css">
</head>

<body>
    <div class="auth-container">
        <h2>Login</h2>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $err) echo "<p>$err</p>"; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['registered'])): ?>
            <div class="success">Registration successful! Please login.</div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p>No account? <a href="register.php">Register here</a></p>
    </div>
</body>

</html>