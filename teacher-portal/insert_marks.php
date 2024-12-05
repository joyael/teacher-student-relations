<?php
include 'db_connection.php';
session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch available students and subjects
$student_result = $conn->query("SELECT id, name FROM students");
$subject_result = $conn->query("SELECT id, name FROM subjects");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $marks = $_POST['marks'];

    $sql = "INSERT INTO marks (subject_id, student_id, marks) VALUES ($subject_id, $student_id, $marks)";

    if ($conn->query($sql) === TRUE) {
        echo "Marks added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
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


<h3>Add Marks</h3>
<form method="POST">
    Student:<br>
    <select name="student_id" class="half-width" required>
        <?php while ($row = $student_result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>
    Subject:<br>
    <select name="subject_id" class="half-width" required>
        <?php while ($row = $subject_result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>
    Marks: <input type="number" name="marks" required><br><br>
    <button type="submit" class="form-submit">Add Marks</button>
</form>


    </div>
    </div>

    
    <footer>
        <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
    </footer>
</body>
</html>

