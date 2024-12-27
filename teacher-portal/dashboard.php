<?php


include 'db_connection.php';
session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

    $teacher_id=$_SESSION['teacher_id'];
    $teacher_result = $conn->query("SELECT name FROM teachers WHERE id = '$teacher_id' ");
    $teacher_row = $teacher_result->fetch_assoc();
    $teacher_name = $teacher_row['name'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Teacher Portal</h1>
        <nav>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="insert_student.php">Add Student</a></li>
                <li><a href="insert_subject.php">Add Subject</a></li>
                <li><a href="insert_mark.php">Add Marks</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="content-outer">
    <div class="content-box">
        <h2>Welcome, 
            <?php
            echo $teacher_name;
            ?>
        </h2>
        

        <div class="sections-outer">
            <div class="section">
            <table class="teacher-dashboard-table" class="center-text">
                <thead>
                    <tr>
                        <th class="center-text">Student Management</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><a href="insert_student.php" class="center-text">Add Student</a></td></tr>
                    <tr><td><a href="view_students.php" class="center-text">Display Students</a></td></tr>
                </tbody>
            </table>
            </div>

            <div class="section">
            <table class="teacher-dashboard-table" class="center-text">
                <thead>
                    <tr>
                        <th class="center-text">Subject Management</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><a href="insert_subject.php" class="center-text">Add Subject</a></td></tr>
                    <tr><td><a href="view_subjects.php" class="center-text">Display Subjects</a></td></tr>
                </tbody>
            </table>
            </div>
            
            <div class="section">
            <table class="teacher-dashboard-table" class="center-text">
                <thead>
                    <tr>
                        <th class="center-text">Marks Management</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><a href="insert_marks.php" class="center-text">Add Marks for Subject</a></td></tr>
                    <tr><td><a href="subject_marks.php" class="center-text">View Marks (Subject Wise)</a></td></tr>
                    <tr><td><a href="student_marks.php" class="center-text">View Marks (Student Wise)</a></td></tr>
                </tbody>
            </table>
            </div>

            <div class="section">
            <table class="teacher-dashboard-table" class="center-text">
                <thead>
                    <tr>
                        <th class="center-text">Reports</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><a href="class_filter_report.php" class="center-text">Class Filter Report</a></td></tr>
                    <tr><td><a href="subject_filter_report.php" class="center-text">Subject Filter Report</a></td></tr>
                    <tr><td><a href="subject_filter_report_ajax_front.php" class="center-text">Subject Filter Report (AJAX)</a></td></tr>
                    <tr><td><a href="class_filter_report_ajax_front.php" class="center-text">Class Filter Report (AJAX)</a></td></tr>
                </tbody>
            </table>
            </div>
        </div>
        
        


    </div>
    </div>

    <footer>
        <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
    </footer>
</body>
</html>






    
