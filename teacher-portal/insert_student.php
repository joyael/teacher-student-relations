<?php
include 'db_connection.php';

session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $class = $_POST['class'];
    $age = $_POST['age'];

    $sql = "INSERT INTO students (name, class, age) VALUES ('$name', '$class', $age)";

    if ($conn->query($sql) === TRUE) {
        echo "Student added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="POST">
    Name: <input type="text" name="name" required><br>
    Class: <input type="text" name="class" required><br>
    Age: <input type="number" name="age" required><br>
    <button type="submit">Add Student</button>
</form>


<br> <br>  <a href="dashboard.php">Back to DashBoard</a>
<br> <br>  <a href="logout.php">Logout</a>