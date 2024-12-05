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
        <h3 class="center-text">Add student</h3>
        <form method="POST">
        Name: <input type="text" name="name" required><br>
        Class: <input type="text" name="class" required><br>
        Age: <input type="number" name="age" required><br>
        <button type="submit" class="form-submit">Add Student</button>
        </form>
    </div>
    </div>

    
    <footer>
        <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
    </footer>
</body>
</html>