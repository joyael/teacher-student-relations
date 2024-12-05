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
        <div class="dashboard">
        <table class="teacher-dashboard-table" class="center-text">
        <thead>
            <tr>
                <th class="center-text">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a href="insert_student.php" class="center-text">Add Student</a></td>
            </tr>
            <tr>
                <td><a href="insert_subject.php" class="center-text">Add Subject</a></td>
            </tr>
            <tr>
                <td><a href="insert_marks.php" class="center-text">Add Marks for Subject</a></td>
            </tr>
            <tr>
                <td><a href="view_students.php" class="center-text">Display Students</a></td>
            </tr>
            <tr>
                <td><a href="view_subjects.php" class="center-text">Display Subjects</a></td>
            </tr>
            <tr>
                <td><a href="subject_marks.php" class="center-text">View Marks (Subject Wise)</a></td>
            </tr>
            <tr>
                <td><a href="student_marks.php" class="center-text">View Marks (Student Wise)</a></td>
            </tr>
        </tbody>
        </table>
        </div>
    </div>
    </div>

    <footer>
        <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
    </footer>
</body>
</html>



<!-- <br>
<a href="insert_student.php">Add Student</a><br>
<a href="insert_marks.php">Add Marks</a><br>
<a href="insert_subject.php">Add Subject</a><br>
<a href="view_students.php">View Students</a><br>
<a href="view_subjects.php">View Subjects</a><br>


<a href="student_marks.php"> Student Marks</a><br>
<a href="subject_marks.php"> Subject Marks</a>


<br> <br>  <a href="logout.php">Logout</a> -->




    
