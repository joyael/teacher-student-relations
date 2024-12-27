<?php
include 'db_connection.php'; // Include your database connection file

session_start();

// Login session checking
if (!isset($_SESSION['teacher_id'])) {
    header("Location: login.php");
    exit();
}



// Fetch subjects
$stmt = $conn->prepare("SELECT id AS subject_id, name AS subject_name FROM subjects");
$stmt->execute();
$all_subject_data = $stmt->get_result();

// Initialize variables
$selected_class = '';
$selected_subject = '';
$selected_student = '';
$students_result = null;
$classes_from_subjects_result = null; 
$students_from_subjects_result = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_subject = $_POST['subject'];

    if (isset($_POST['class'])) {
        $selected_class = $_POST['class'];
        if($selected_class == "all"){
            $selected_class='';
        }
    }
    if (isset($_POST['student'])) {
        $selected_student = $_POST['student'];
        if($selected_student == "all"){
            $selected_student='';
        }
    }

    // Validate and filter input
    if (!empty($selected_subject)) {
        $stmt = $conn->prepare("SELECT id AS subject_id, name AS subject_name FROM subjects WHERE subjects.id = ?");
        $stmt->bind_param("i", $selected_subject);
        $stmt->execute();
        $subject_data = $stmt->get_result();

        if (!empty($selected_class) && !empty($selected_student)){
            $stmt = $conn->prepare("SELECT DISTINCT students.id AS student_id, students.name AS student_name , students.class AS class
            FROM marks 
            INNER JOIN subjects ON marks.subject_id = subjects.id 
            INNER JOIN students ON marks.student_id = students.id 
            WHERE class = ?
            AND subjects.id = ?
            AND students.id = ?
            ");
            $stmt->bind_param("sii", $selected_class,$selected_subject,$selected_student);
        }
        else if (!empty($selected_class) && empty($selected_student)){
            $stmt = $conn->prepare("SELECT DISTINCT students.id AS student_id, students.name AS student_name , students.class AS class
            FROM marks 
            INNER JOIN subjects ON marks.subject_id = subjects.id 
            INNER JOIN students ON marks.student_id = students.id 
            WHERE subjects.id = ?
            AND class = ?
            ");
            $stmt->bind_param("is", $selected_subject, $selected_class);
        }
        else if (empty($selected_class) && !empty($selected_student)){
            $stmt = $conn->prepare("SELECT DISTINCT students.id AS student_id, students.name AS student_name , students.class AS class
            FROM marks 
            INNER JOIN subjects ON marks.subject_id = subjects.id 
            INNER JOIN students ON marks.student_id = students.id 
            WHERE subjects.id = ?
            AND students.id = ?
            ");
            $stmt->bind_param("ii", $selected_subject,$selected_student);
        }
        else{
            $stmt = $conn->prepare("SELECT students.id AS student_id, students.name AS student_name FROM students WHERE students.id = ?");
            $stmt->bind_param("i", $selected_subject);
        }

        $stmt->execute();
        $students_result = $stmt->get_result();
    }


    if (!empty($selected_subject)) {
        $stmt = $conn->prepare("SELECT DISTINCT students.class AS class
            FROM marks 
            INNER JOIN subjects ON marks.subject_id = subjects.id 
            INNER JOIN students ON marks.student_id = students.id 
            WHERE subjects.id = ?
        ");
        $stmt->bind_param("i", $selected_subject);
        $stmt->execute();
        $classes_from_subjects_result = $stmt->get_result();

        $stmt = $conn->prepare("SELECT DISTINCT students.id AS student_id, students.name AS student_name 
            FROM marks 
            INNER JOIN subjects ON marks.subject_id = subjects.id 
            INNER JOIN students ON marks.student_id = students.id 
            WHERE subjects.id = ?
        ");
        $stmt->bind_param("i", $selected_subject);
        $stmt->execute();
        $students_from_subjects_result = $stmt->get_result();
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Filter Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <h1>View Class Marks</h1>
    
    <form method="POST" action="">
                <label for="subject">Select Subject</label>
                <select name="subject" id="subject" class="half-width" required>
                    <option value="all">-- All --</option>
                    <?php while($row = $all_subject_data->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['subject_id']) ?>" <?= ($selected_subject == $row['subject_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['subject_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select><br>

                

    <!-- ------------classes----------- -->

            <?php if ($selected_subject && $classes_from_subjects_result): ?>
                <label for="class">Select Class</label>
                <select name="class" id="class" class="half-width" required>
                    <option value="">-- Select a Class --</option>
                    <?php while ($row = $classes_from_subjects_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['class']) ?>" <?= ($selected_class == $row['class']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['class']) ?>
                        </option>
                    <?php endwhile; ?>
                </select><br>
            <?php endif; ?>

    <!-- --------------------------------- -->

    <!-- --------students from subject------ -->

            <?php if ($selected_subject && $students_from_subjects_result): ?>
                <label for="student">Select Student</label>
                <select name="student" id="student" class="half-width" required>
                    <option value="all">-- All --</option>
                    <?php while($row = $students_from_subjects_result->fetch_assoc()): ?>
                        <option value="<?= htmlspecialchars($row['student_id']) ?>" <?= ($selected_student == $row['student_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['student_name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select><br>
            <?php endif; ?>

    <!-- ---------------------------------- -->
    
    <button type="submit" class="form-submit">View Report</button>
    </form>

    <?php if ($selected_subject && $students_result): ?>
        <?php
            $stmt = $conn->prepare("SELECT name FROM subjects WHERE subjects.id = ?");
            $stmt->bind_param("i", $selected_subject);
            $stmt->execute();
            $subject_result = $stmt->get_result();
            $subject_row = $subject_result->fetch_assoc();
            $subject_name = $subject_row['name'];
        ?>
        <h2>Marks for Subject: <?= htmlspecialchars($subject_name) ?></h2>

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Student Name</th>
                    <?php foreach ($subject_data as $subject): ?>
                        <th><?= htmlspecialchars($subject['subject_name']) ?></th>
                    <?php endforeach; ?>

                    <?php if (empty($selected_subject)): ?>
                    <th>Total</th>
                    <th>Percentage</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php 
                
                
                while ($student_row = $students_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($student_row['student_id']); ?></td>
                        <td><?= htmlspecialchars($student_row['student_name']); ?></td>
                        <?php $total=0;$grade="";$stotal=0;
                        $perc=0;
                        foreach ($subject_data as $subject): ?>
                            <?php
                            $stmt = $conn->prepare("SELECT marks FROM marks WHERE student_id = ? AND subject_id = ?");
                            $stmt->bind_param("ii", $student_row['student_id'], $subject['subject_id']);
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
                        <?php if (empty($selected_subject)): ?>
                        <td><?= $total ?></td>
                        <td><?= $perc ?></td>
                        <?php endif; ?>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php include "footer.php"; ?>
</body>
</html>
