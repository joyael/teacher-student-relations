<?php
include 'db_connection.php'; // Include your database connection file

session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all students
$students_query = "SELECT * FROM students";
$students_result = $conn->query($students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    include 'header.php';
    ?>
    <h1>All Students</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Student ID</th>
                <th>Student Name</th>
                <th>Class</th>
                <th>Age</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($students_result->num_rows > 0): ?>
                <?php while ($row = $students_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['class']) ?></td>
                        <td><?= htmlspecialchars($row['age']) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No students found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php
    include "footer.php"; 
    ?>
    
</body>
</html>


