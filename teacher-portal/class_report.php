<?php
include 'db_connection.php'; // Include your database connection file

session_start();

// Login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch classes
$classes = $conn->query("SELECT DISTINCT class FROM students");

// Fetch subjects
$subjects = $conn->query("SELECT id, name FROM subjects");
$subject_data = $subjects->fetch_all(MYSQLI_ASSOC); // Cache subject data for multiple uses

// Initialize variables
$selected_class = '';
$students_result = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_class = $_POST['class'];

    // Validate and filter input
    if (!empty($selected_class)) {
        $stmt = $conn->prepare("SELECT id, name FROM students WHERE class = ?");
        $stmt->bind_param("s", $selected_class);
        $stmt->execute();
        $students_result = $stmt->get_result();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <h1>View Class Marks</h1>
    
    <form method="POST" action="">
        <label for="class">Select Class</label>
        <select name="class" id="class" class="half-width" required>
            <option value="">-- Select a Class --</option>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?= htmlspecialchars($row['class']) ?>" <?= ($selected_class == $row['class']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['class']) ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        <button type="submit" class="form-submit">View Report</button>
    </form>

    <?php if ($selected_class && $students_result): ?>
        <h2>Marks for Class: <?= htmlspecialchars($selected_class) ?></h2>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Student Name</th>
                    <?php foreach ($subject_data as $subject): ?>
                        <th><?= htmlspecialchars($subject['name']) ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>
                    <th>Percentage</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                
                while ($student_row = $students_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($student_row['id']); ?></td>
                        <td><?= htmlspecialchars($student_row['name']); ?></td>
                        <?php $total=0;$grade="";$stotal=0;
                        $perc=0;
                        foreach ($subject_data as $subject): ?>
                            <?php
                            $stmt = $conn->prepare("SELECT marks FROM marks WHERE student_id = ? AND subject_id = ?");
                            $stmt->bind_param("ii", $student_row['id'], $subject['id']);
                            $stmt->execute();
                            $marks_result = $stmt->get_result();
                            $marks_row = $marks_result->fetch_assoc();
                            if($marks_row){
                                $total=$total+$marks_row['marks'];
                                $stotal=$stotal+100;
                            }
                            ?>
                            <td><?= $marks_row ? htmlspecialchars($marks_row['marks']) : '-' ?></td>
                            
                        <?php endforeach; ?>
                        <?php
                                $perc=($total/$stotal)*100;
                        ?>
                        <td><?= $total ?></td>
                        <td><?= $perc ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php include "footer.php"; ?>
</body>
</html>
