<?php
include_once './db/config.php';
checkLogin(['admin']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">
</head>

<body>
    <?php include './pages/sidebar.php'; ?>

    <div class="main">
        <div class="section">
            <h2>Manage Users</h2>
            <input type="text" id="username" placeholder="Username">
            <input type="text" id="fullname" placeholder="Full Name">
            <input type="password" id="password" placeholder="Password">
            <select id="role">
                <option value="student">Student</option>
                <option value="staff">Staff</option>
                <option value="admin">Admin</option>
            </select>
            <button onclick="addUser()">Add User</button>

            <h3>Existing Users</h3>
            <ul>
                <?php
                $res = $conn->query("SELECT id, username, role FROM users");
                while ($row = $res->fetch_assoc()):
                ?>
                    <li><?php echo "{$row['username']} ({$row['role']})"; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <script>
        function addUser() {
            const username = document.getElementById('username').value;
            const fullname = document.getElementById('fullname').value;
            const password = document.getElementById('password').value;
            const role = document.getElementById('role').value;

            if (!username || !fullname || !password) return alert('Fill all fields');

            fetch('addUser.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username,
                    fullname,
                    password,
                    role
                })
            }).then(res => res.json()).then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
        }
    </script>
</body>

</html>