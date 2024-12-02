<?php
include 'db_connection.php'; // Include your database connection file

session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch all subjects
$subjects_query = "
    SELECT subjects.id, subjects.name AS subject_name, teachers.name AS teacher_name
    FROM subjects
    LEFT JOIN teachers ON subjects.teacher_id = teachers.id
";
$subjects_result = $conn->query($subjects_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Subjects</title>
</head>
<body>
    <h1>All Subjects</h1>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Subject ID</th>
                <th>Subject Name</th>
                <th>Teacher Name</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($subjects_result->num_rows > 0): ?>
                <?php while ($row = $subjects_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['subject_name']) ?></td>
                        <td><?= htmlspecialchars($row['teacher_name'] ?? 'Unassigned') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No subjects found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>



<br> <br>  <a href="dashboard.php">Back to DashBoard</a>
<br> <br>  <a href="logout.php">Logout</a>