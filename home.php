<!DOCTYPE html>
<html>

<head>
    <title>STY QR Attendance System</title>
    <link rel="stylesheet" href="./assets/style.css">

    <!-- QR Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://kit.fontawesome.com/f02a36f28e.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
</head>

<body>

    <div class="sidebar">
        <h2>STY System</h2>
        <button onclick="showSection('dashboard')">Dashboard</button>
        <button onclick="showSection('generate')">Generate QR</button>
        <button onclick="showSection('scan')">Scan QR</button>
        <button onclick="showSection('subjects')">Manage Subjects</button>
        <button onclick="showSection('records')">Attendance Records</button>
        <a href="auth/logout.php">Logout</a>
    </div>

    <div class="main">

        <!-- DASHBOARD -->
        <div id="dashboard" class="section">
            <h2>Dashboard</h2>
            <div class="card-container">
                <div class="card">
                    <h3>Total Students</h3>
                    <p id="totalStudents">0</p>
                </div>
                <div class="card">
                    <h3>Total Attendance</h3>
                    <p id="totalAttendance">0</p>
                </div>
                <div class="card">
                    <h3>Total Subjects</h3>
                    <p id="totalSubjects">0</p>
                </div>
                
            </div>

            <h3>Attendance Per Subject</h3>
            <ul id="subjectStats"></ul>
        </div>

        <!-- GENERATE QR -->
        <div id="generate" class="section" style="display:none;">
            <h2>Generate Student QR</h2>
            <input type="text" id="studentName" placeholder="Student Name">
            <input type="text" id="studentID" placeholder="Student ID">
            <select id="subjectSelect"></select>
            <br>
            <button onclick="generateQR()">Generate QR</button>
            <div id="qrcode"></div>
            <button onclick="downloadQR()"><i class="fa-solid fa-download"></i> Download QR</button>
        </div>

        <!-- SCAN -->
        <div id="scan" class="section" style="display:none;">
            <h2>Upload QR Image to Scan</h2>
            <i class="fa-regular fa-file"></i>
            <input type="file" id="qrUpload" accept="image/*">
            <div id="uploadResult"></div>
        </div>

        <!-- SUBJECT MANAGEMENT -->
        <div id="subjects" class="section" style="display:none;">
            <h2>Manage Subjects</h2>
            <input type="text" id="newSubject" placeholder="Subject Name">
            <button onclick="addSubject()">Add Subject</button>
            <ul id="subjectList"></ul>
        </div>

        <!-- RECORDS -->
        <div id="records" class="section" style="display:none;">
            <h2>Attendance Records</h2>
            <button onclick="exportCSV()">Export CSV</button>
            <table id="attendanceTable">
                <tr>
                    <th>Name</th>
                    <th>ID</th>
                    <th>Subject</th>
                    <th>Date</th>
                </tr>
            </table>
        </div>

    </div>

    <script src="./assets/script.js"></script>
</body>

</html>