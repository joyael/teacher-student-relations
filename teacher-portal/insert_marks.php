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
<h3>Add Marks</h3>
<form method="POST">
    Student:
    <select name="student_id" required>
        <?php while ($row = $student_result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br>
    Subject:
    <select name="subject_id" required>
        <?php while ($row = $subject_result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br>
    Marks: <input type="number" name="marks" required><br>
    <button type="submit">Add Marks</button>
</form>

<br> <br>  <a href="dashboard.php">Back to DashBoard</a>
<br> <br>  <a href="logout.php">Logout</a>
