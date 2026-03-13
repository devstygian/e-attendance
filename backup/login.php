<?php
include_once '../db/config.php'; // config.php starts session

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $user, $dbPassword, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        // Compare plain text passwords
        if ($dbPassword !== null && $password === $dbPassword) {
            // Successful login
            $_SESSION['users'] = ['id' => $id, 'username' => $user, 'role' => $role];
            header("Location: ../home.php");
            exit();
        } else {
            $errors[] = "Invalid password.";
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
    <title>Login</title>
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

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <p>No account? <a href="register.php">Register here</a></p>
    </div>
</body>

</html>