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

echo "Welcome, Teacher!";
?>

<br>
<a href="insert_student.php">Add Student</a><br>
<a href="insert_marks.php">Add Marks</a><br>
<a href="insert_subject.php">Add Subject</a><br>
<a href="view_students.php">View Students</a><br>
<a href="view_subjects.php">View Subjects</a><br>


<a href="student_marks.php"> Student Marks</a><br>
<a href="subject_marks.php"> Subject Marks</a>


<br> <br>  <a href="logout.php">Logout</a>