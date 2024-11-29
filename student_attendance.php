<?php
session_start();

// Check if the user is logged in, if not, redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Include database connection
include 'config.php';

// Retrieve session details
$surname = $_SESSION['lastName'] ?? 'lastName';
$firstName = $_SESSION['firstName'] ?? 'firstName';
$userType = $_SESSION['user_type'] ?? 'Admin';
$studentId = $_SESSION['student_id'] ?? null;

// Initialize attendanceRecords
$attendanceRecords = [];

// Fetch attendance records for the logged-in user
if ($studentId) {
    $sql = "SELECT date, time_in, time_out, TIMEDIFF(time_out, time_in) AS duration, remarks 
            FROM attendance 
            WHERE student_id = :student_id 
            ORDER BY date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['student_id' => $studentId]);
    $attendanceRecords = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_student-dashboard.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Attendance</title>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <style>
        #qr-scanner-container {
            display: none;
            text-align: center;
            margin: 20px auto;
        }
        #qr-reader {
            width: 300px;
            height: 300px;
            margin: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="als-logo.svg" alt="Logo" />
        </div>
        <h1>ALTERNATIVE LEARNING SYSTEM - BUENAVISTA CHAPTER</h1>
    </header>

    <div class="container">
        <div class="navigation" id="navigationDrawer">
            <ul>
                <li>
                    <span class="profile_icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                    <div class="profile-details">
                        <h2><?php echo "$surname, $firstName"; ?></h2>
                        <p><?php echo ucfirst($userType); ?></p>
                    </div>
                </li>
                <li>
                    <a href="student_dashboard.php">
                        <span class="icon"><i class="fa fa-home" aria-hidden="true"></i></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="student_attendance.php">
                        <span class="icon"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
                        <span class="title">Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="student_l-materials.php">
                        <span class="icon"><i class="fa fa-book" aria-hidden="true"></i></span>
                        <span class="title">Learning Material</span>
                    </a>
                </li>
                <li>
                    <a href="#" onclick="confirmLogout()">
                        <span class="icon"><i class="fa fa-sign-out" aria-hidden="true"></i></span>
                        <span class="title">Log Out</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <h2>Attendance Records</h2>
            <button onclick="toggleQRScanner()">Scan QR Code</button>
            <div id="qr-scanner-container">
                <div id="qr-reader"></div>
                <button onclick="stopQRScanner()">Stop Scanner</button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                        <th>Duration</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($attendanceRecords) > 0): ?>
                        <?php foreach ($attendanceRecords as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['time_in']); ?></td>
                                <td><?php echo htmlspecialchars($record['time_out'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($record['duration'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($record['remarks']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No attendance records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script>
        let qrScanner;

        function toggleQRScanner() {
            const qrContainer = document.getElementById('qr-scanner-container');
            qrContainer.style.display = qrContainer.style.display === 'none' ? 'block' : 'none';

            if (!qrScanner) {
                qrScanner = new Html5Qrcode("qr-reader");
                qrScanner.start(
                    { facingMode: "environment" }, 
                    {
                        fps: 10,
                        qrbox: { width: 250, height: 250 }
                    },
                    (decodedText) => {
                        alert("QR Code Scanned: " + decodedText);
                        fetch('submit_attendance.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({ qrCode: decodedText })
                        })
                        .then(response => response.json())
                        .then(data => alert(data.message))
                        .catch(err => console.error(err));
                        stopQRScanner();
                    },
                    (errorMessage) => {
                        console.error("QR Scanner Error: ", errorMessage);
                    }
                ).catch(err => console.error("Error initializing QR scanner: ", err));
            }
        }

        function stopQRScanner() {
            if (qrScanner) {
                qrScanner.stop().then(() => {
                    qrScanner = null;
                    document.getElementById('qr-scanner-container').style.display = 'none';
                }).catch(err => console.error(err));
            }
        }

        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
