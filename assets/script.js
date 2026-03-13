// ==========================
// 🔐 LOGIN SYSTEM
// ==========================

// Default admin account (only created once)
const defaultUser = {
    username: "admin",
    password: "1234"
};

if (!localStorage.getItem("user")) {
    localStorage.setItem("user", JSON.stringify(defaultUser));
}

// LOGIN FUNCTION
function login() {
    const username = document.getElementById("username")?.value;
    const password = document.getElementById("password")?.value;

    const storedUser = JSON.parse(localStorage.getItem("user"));

    if (username === storedUser.username && password === storedUser.password) {
        localStorage.setItem("isLoggedIn", "true");
        window.location.href = "index.html";
    } else {
        document.getElementById("loginError").innerText = "Invalid Credentials";
    }
}

// LOGOUT FUNCTION
function logout() {
    localStorage.removeItem("isLoggedIn");
    window.location.href = "login.html";
}

// PROTECT MAIN PAGE
if (window.location.pathname.includes("index.html")) {
    if (localStorage.getItem("isLoggedIn") !== "true") {
        window.location.href = "login.html";
    }
}



// ==========================
// 📂 NAVIGATION
// ==========================

function showSection(sectionId) {
    document.querySelectorAll('.section').forEach(sec => {
        sec.style.display = 'none';
    });
    document.getElementById(sectionId).style.display = 'block';
    updateDashboard();
}



// ==========================
// 📚 SUBJECT MANAGEMENT
// ==========================

function addSubject() {
    const subjectInput = document.getElementById("newSubject");
    const subject = subjectInput.value.trim();
    if (!subject) return;

    let subjects = JSON.parse(localStorage.getItem("subjects")) || [];

    if (subjects.includes(subject)) {
        alert("Subject already exists.");
        return;
    }

    subjects.push(subject);
    localStorage.setItem("subjects", JSON.stringify(subjects));

    subjectInput.value = "";
    loadSubjects();
    updateDashboard();
}

function deleteSubject(subjectName) {
    if (!confirm("Are you sure you want to delete this subject?")) return;

    let subjects = JSON.parse(localStorage.getItem("subjects")) || [];
    subjects = subjects.filter(sub => sub !== subjectName);
    localStorage.setItem("subjects", JSON.stringify(subjects));

    loadSubjects();
    updateDashboard();
}

function loadSubjects() {
    let subjects = JSON.parse(localStorage.getItem("subjects")) || [];
    const select = document.getElementById("subjectSelect");
    const list = document.getElementById("subjectList");

    if (select) {
        select.innerHTML = "<option value=''>Select Subject</option>";
    }

    if (list) {
        list.innerHTML = "";
    }

    subjects.forEach(sub => {

        if (select) {
            select.innerHTML += `<option value="${sub}">${sub}</option>`;
        }

        if (list) {
            list.innerHTML += `
                <li>
                    ${sub}
                    <button onclick="deleteSubject('${sub}')" class="delete-btn">
                        Delete
                    </button>
                </li>
            `;
        }
    });
}



// ==========================
// 📱 QR GENERATION
// ==========================

function generateQR() {
    const name = document.getElementById("studentName").value;
    const id = document.getElementById("studentID").value;
    const subject = document.getElementById("subjectSelect").value;

    if (!name || !id || !subject) {
        alert("Please complete all fields");
        return;
    }

    const data = JSON.stringify({ name, id, subject });

    document.getElementById("qrcode").innerHTML = "";

    new QRCode(document.getElementById("qrcode"), {
        text: data,
        width: 200,
        height: 200
    });
}

function downloadQR() {
    const canvas = document.querySelector("#qrcode canvas");
    if (!canvas) {
        alert("Generate QR first");
        return;
    }

    const link = document.createElement("a");
    link.download = "studentQR.png";
    link.href = canvas.toDataURL();
    link.click();
}



// ==========================
// 📷 QR SCAN (UPLOAD)
// ==========================

document.getElementById("qrUpload")?.addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const html5QrCode = new Html5Qrcode("uploadResult");

    html5QrCode.scanFile(file, true)
        .then(decodedText => {
            processAttendance(decodedText);
            document.getElementById("uploadResult").innerHTML =
                "<p style='color:lightgreen;'>QR Scanned Successfully</p>";
        })
        .catch(err => {
            document.getElementById("uploadResult").innerHTML =
                "<p style='color:red;'>Failed to scan QR</p>";
        });
});



// ==========================
// 📝 ATTENDANCE
// ==========================

function processAttendance(data) {
    const student = JSON.parse(data);
    const table = document.getElementById("attendanceTable");

    const row = table.insertRow();
    row.insertCell(0).innerHTML = student.name;
    row.insertCell(1).innerHTML = student.id;
    row.insertCell(2).innerHTML = student.subject;
    row.insertCell(3).innerHTML = new Date().toLocaleString();

    saveToLocalStorage(student);
    updateDashboard();
}

function saveToLocalStorage(student) {
    let records = JSON.parse(localStorage.getItem("attendance")) || [];

    records.push({
        name: student.name,
        id: student.id,
        subject: student.subject,
        date: new Date().toLocaleString()
    });

    localStorage.setItem("attendance", JSON.stringify(records));
}



// ==========================
// 📊 DASHBOARD
// ==========================

function updateDashboard() {
    let records = JSON.parse(localStorage.getItem("attendance")) || [];
    let subjects = JSON.parse(localStorage.getItem("subjects")) || [];

    const totalAttendance = document.getElementById("totalAttendance");
    const totalSubjects = document.getElementById("totalSubjects");
    const totalStudents = document.getElementById("totalStudents");
    const stats = document.getElementById("subjectStats");

    if (totalAttendance) totalAttendance.innerText = records.length;
    if (totalSubjects) totalSubjects.innerText = subjects.length;

    let uniqueStudents = [...new Set(records.map(r => r.id))];
    if (totalStudents) totalStudents.innerText = uniqueStudents.length;

    if (stats) {
        let subjectCount = {};
        records.forEach(r => {
            subjectCount[r.subject] = (subjectCount[r.subject] || 0) + 1;
        });

        stats.innerHTML = "";
        for (let sub in subjectCount) {
            stats.innerHTML += `<li>${sub}: ${subjectCount[sub]} attendance</li>`;
        }
    }
}



// ==========================
// 📁 EXPORT CSV
// ==========================

function exportCSV() {
    let records = JSON.parse(localStorage.getItem("attendance")) || [];
    let csv = "Name,ID,Subject,Date\n";

    records.forEach(r => {
        csv += `${r.name},${r.id},${r.subject},${r.date}\n`;
    });

    const blob = new Blob([csv], { type: "text/csv" });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = "attendance.csv";
    link.click();
}



// ==========================
// 🚀 INITIAL LOAD
// ==========================

window.onload = function () {
    loadSubjects();
    updateDashboard();
};