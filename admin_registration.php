<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$surname = $_SESSION['lastName'] ?? 'lastName';
$firstName = $_SESSION['firstName'] ?? 'firstName';
$userType = $_SESSION['user_type'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_admin-dashboard.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Student Registration</title>
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
                    <a href="admin_dashboard.php">
                        <span class="icon"><i class="fa fa-home" aria-hidden="true"></i></span>
                        <span class="title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="admin_attendance.php">
                        <span class="icon"><i class="fa fa-calendar-check-o" aria-hidden="true"></i></span>
                        <span class="title">Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="admin_l-materials.php"> 
                        <span class="icon"><i class="fa fa-book" aria-hidden="true"></i></span>
                        <span class="title">Learning Material</span>
                    </a>
                </li>
                 <li>
                    <a href="admin_registration.php"> 
                        <span class="icon"><i class="fa fa-book" aria-hidden="true"></i></span>
                        <span class="title">Student Registration</span>
                    </a>
                </li>
                <li>
                    <a href="admin_student-list.php">
                        <span class="icon"><i class="fa fa-list" aria-hidden="true"></i></span>
                        <span class="title">List of Students</span>
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
            <h2>Student Registration</h2>
            <form action="process_registration.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="photo">Upload Photo:</label>
                    <input type="file" name="photo" id="photo" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" placeholder="Enter full name" required>
                </div>
                <div class="form-group">
                    <label for="student_id">Student ID:</label>
                    <input type="text" name="student_id" id="student_id" placeholder="Enter student ID" required>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" placeholder="Enter address" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" name="email" id="email" placeholder="Enter email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number:</label>
                    <input type="text" name="phone" id="phone" placeholder="Enter phone number" required>
                </div>
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select name="gender" id="gender" required>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-buttons">
                    <button type="reset" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    <script>
        function confirmLogout() {
            if (confirm("Are you sure you want to log out?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>
</html>
