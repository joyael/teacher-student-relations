<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$stmt = $conn->prepare("
    SELECT DISTINCT 
        students.class AS class, 
        subjects.id AS subject_id, 
        subjects.name AS subject_name, 
        students.id AS student_id, 
        students.name AS student_name,
        marks.marks AS marks
    FROM marks 
    INNER JOIN subjects ON marks.subject_id = subjects.id 
    INNER JOIN students ON marks.student_id = students.id
");
$stmt->execute();
$all_result_rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$all_subjects = [];
$subject_ids = [];
foreach ($all_result_rows as $row) {
    if (!in_array($row['subject_id'], $subject_ids)) {
        $subject_ids[] = $row['subject_id'];
        $all_subjects[] = [
            "subject_id" => $row['subject_id'],
            "subject_name" => $row['subject_name']
        ];
    }
}

// Handle AJAX requests
$response = [];
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $selected_subject = $_POST['subject'] ?? '';
    $selected_class = $_POST['class'] ?? '';
    $selected_student = $_POST['student'] ?? '';

    if ($action === 'getClasses') {
        $classes = array_unique(array_column(
            array_filter($all_result_rows, fn($row) => $row['subject_id'] == $selected_subject),
            'class'
        ));
        $response = ['classes' => $classes];

    } elseif ($action === 'getStudents') {
        $students = array_map(
            fn($row) => [
                "student_id" => $row['student_id'],
                "student_name" => $row['student_name']
            ],
            array_filter($all_result_rows, fn($row) => $row['subject_id'] == $selected_subject)
        );
        $response = ['students' => array_values(array_unique($students, SORT_REGULAR))];

    } elseif ($action === 'viewReport') {
        $students_table = array_filter($all_result_rows, function ($row) use ($selected_subject, $selected_class, $selected_student) {
            return $row['subject_id'] == $selected_subject &&
                ($selected_class === '' || $row['class'] == $selected_class) &&
                ($selected_student === '' || $row['student_id'] == $selected_student);
        });

        // Fetch subject data and name
        $subject_data = empty($selected_subject) ? $all_subjects : array_filter($all_subjects, fn($sub) => $sub['subject_id'] == $selected_subject);
        $subject_name = $subject_data ? reset($subject_data)['subject_name'] : 'Unknown Subject';

        // Build the HTML output
        $output = "<h2>Marks for Subject: " . htmlspecialchars($subject_name) . "</h2>";
        $output .= '<table border="1" cellpadding="5" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Student Name</th>';

        foreach ($subject_data as $subject) {
            $output .= '<th>' . htmlspecialchars($subject['subject_name']) . '</th>';
        }

        if (empty($selected_subject)) {
            $output .= '<th>Total</th><th>Percentage</th>';
        }

        $output .= '</tr></thead><tbody>';

        foreach ($students_table as $student_row) {
            $output .= '<tr>
                            <td>' . htmlspecialchars($student_row["student_id"]) . '</td>
                            <td>' . htmlspecialchars($student_row["student_name"]) . '</td>';

            $total = $stotal = 0;
            foreach ($subject_data as $subject) {
                $marks = null;
                foreach ($all_result_rows as $row) {
                    if ($row['student_id'] == $student_row["student_id"] && $row['subject_id'] == $subject["subject_id"]) {
                        $marks = $row['marks'];
                        break;
                    }
                }
                $total += $marks ?: 0;
                $stotal += $marks !== null ? 100 : 0;
                $output .= '<td>' . ($marks !== null ? htmlspecialchars($marks) : '-') . '</td>';
            }

            if (empty($selected_subject)) {
                $perc = $stotal > 0 ? ($total / $stotal) * 100 : 0;
                $output .= '<td>' . $total . '</td>
                            <td>' . number_format($perc, 2) . '%</td>';
            }

            $output .= '</tr>';
        }

        $output .= '</tbody></table>';
        $response = $output;
    }
}


if(gettype($response) == "string"){
    if(!empty($response)){
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
if(gettype($response) == "array"){
    if(count($response)>0){
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

?>




