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
        <h3>Add Subject</h3>
        <form method="POST">
            Name: <input type="text" name="name" required><br><br>
            Teacher: <br>
            <select name="teacher_id" class="half-width" required>
                <?php while ($row = $teacher_result->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"> <?= $row['name'] ?> </option>
                <?php endwhile; ?>
            </select><br><br>
            <button type="submit" class="form-submit">Add Subject</button>
        </form>
    </div>
    </div>


        <footer>
            <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
        </footer>
</body>
</html>