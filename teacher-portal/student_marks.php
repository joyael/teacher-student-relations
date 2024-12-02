<?php
include 'db_connection.php'; // Include your database connection file

session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch students for the dropdown
$students_result = $conn->query("SELECT id, name FROM students");

// Initialize variables
$selected_student_id = null;
$student_name = '';
$marks_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_student_id = $_POST['student_id'];

    // Get the selected student's name
    $student_result = $conn->query("SELECT name FROM students WHERE id = $selected_student_id");
    $student_row = $student_result->fetch_assoc();
    $student_name = $student_row['name'];

    // Fetch subjects and marks for the selected student
    $marks_query = "
        SELECT subjects.name AS subject_name, marks.marks 
        FROM marks
        INNER JOIN subjects ON marks.subject_id = subjects.id
        WHERE marks.student_id = $selected_student_id
    ";
    $marks_result = $conn->query($marks_query);

    // Store the marks data
    while ($row = $marks_result->fetch_assoc()) {
        $marks_data[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Marks</title>
</head>
<body>
    <h1>View Student Marks</h1>
    <form method="POST" action="">
        <label for="student_id">Select Student:</label>
        <select name="student_id" id="student_id" required>
            <option value="">-- Select a Student --</option>
            <?php while ($student = $students_result->fetch_assoc()): ?>
                <option value="<?= $student['id'] ?>" <?= ($selected_student_id == $student['id']) ? 'selected' : '' ?>>
                    <?= $student['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">View Marks</button>
    </form>

    <?php if ($selected_student_id && $marks_data): ?>
        <h2>Marks for <?= htmlspecialchars($student_name) ?></h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($marks_data as $data): ?>
                    <tr>
                        <td><?= htmlspecialchars($data['subject_name']) ?></td>
                        <td><?= htmlspecialchars($data['marks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($selected_student_id): ?>
        <p>No marks found for this student.</p>
    <?php endif; ?>
</body>
</html>


<br> <br>  <a href="dashboard.php">Back to DashBoard</a>
<br> <br>  <a href="logout.php">Logout</a>