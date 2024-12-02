<?php
include 'db_connection.php';
session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch available teachers
$teacher_result = $conn->query("SELECT id, name FROM teachers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $teacher_id = $_POST['teacher_id'];

    $sql = "INSERT INTO subjects (name, teacher_id) VALUES ('$name', $teacher_id)";

    if ($conn->query($sql) === TRUE) {
        echo "Subject added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h3>Add Subject</h3>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Teacher:
    <select name="teacher_id" required>
        <?php while ($row = $teacher_result->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select><br>
    <button type="submit">Add Subject</button>
</form>


<br> <br>  <a href="dashboard.php">Back to DashBoard</a>
<br> <br>  <a href="logout.php">Logout</a>