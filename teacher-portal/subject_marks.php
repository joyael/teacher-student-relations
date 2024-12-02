<?php
include 'db_connection.php'; // Include your database connection file
session_start();
// login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}


// Fetch subjects for the dropdown
$subjects_result = $conn->query("SELECT id, name FROM subjects");

// Initialize variables
$selected_subject_id = null;
$subject_name = '';
$marks_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_subject_id = $_POST['subject_id'];

    // Get the selected subject's name
    $subject_result = $conn->query("SELECT name FROM subjects WHERE id = $selected_subject_id");
    $subject_row = $subject_result->fetch_assoc();
    $subject_name = $subject_row['name'];

    // Fetch students and their marks for the selected subject
    $marks_query = "
        SELECT students.name AS student_name, marks.marks 
        FROM marks
        INNER JOIN students ON marks.student_id = students.id
        WHERE marks.subject_id = $selected_subject_id
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
    <title>Subject Marks</title>
</head>
<body>
    <h1>View Marks by Subject</h1>
    <form method="POST" action="">
        <label for="subject_id">Select Subject:</label>
        <select name="subject_id" id="subject_id" required>
            <option value="">-- Select a Subject --</option>
            <?php while ($subject = $subjects_result->fetch_assoc()): ?>
                <option value="<?= $subject['id'] ?>" <?= ($selected_subject_id == $subject['id']) ? 'selected' : '' ?>>
                    <?= $subject['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>
        <button type="submit">View Marks</button>
    </form>

    <?php if ($selected_subject_id && $marks_data): ?>
        <h2>Marks for Subject: <?= htmlspecialchars($subject_name) ?></h2>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($marks_data as $data): ?>
                    <tr>
                        <td><?= htmlspecialchars($data['student_name']) ?></td>
                        <td><?= htmlspecialchars($data['marks']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($selected_subject_id): ?>
        <p>No marks found for this subject.</p>
    <?php endif; ?>
    <a>
</body>
</html>


<br> <br>  <a href="dashboard.php">Back to DashBoard</a>
<br> <br>  <a href="logout.php">Logout</a>