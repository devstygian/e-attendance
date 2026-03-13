<?php
include_once './db/config.php';
checkLogin(['staff', 'admin']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Subjects</title>
    <link rel="stylesheet" href="./assets/style.css">
    <link rel="stylesheet" href="./assets/sidebar.css">
</head>

<body>
    <?php include './pages/sidebar.php'; ?>

    <div class="main">
        <div class="section">
            <h2>Manage Subjects</h2>
            <input type="text" id="newSubject" placeholder="Subject Name">
            <button onclick="addSubject()">Add Subject</button>
            <ul id="subjectList">
                <?php
                $res = $conn->query("SELECT * FROM subjects");
                while ($row = $res->fetch_assoc()):
                ?>
                    <li><?php echo $row['subject_name']; ?></li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <script>
        function addSubject() {
            const subject = document.getElementById('newSubject').value;
            if (!subject) return alert('Enter subject name');
            fetch('addSubject.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    subject
                })
            }).then(res => res.json()).then(data => {
                alert(data.message);
                if (data.success) location.reload();
            });
        }
    </script>
</body>

</html>