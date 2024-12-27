<?php
ob_start();
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}


$stmt = $conn->prepare("SELECT DISTINCT students.class AS class, subjects.id AS subject_id, subjects.name AS subject_name, students.id AS student_id, students.name AS student_name, marks.marks AS marks
    FROM marks 
    INNER JOIN subjects ON marks.subject_id = subjects.id 
    INNER JOIN students ON marks.student_id = students.id");
$stmt->execute();
$all_result = $stmt->get_result();
$all_result_rows = [];
$all_classes = [];


$all_subjects = [];
$subject_ids=[];
while ($row = $all_result->fetch_assoc()) {
    $all_result_rows[]=$row;
    $all_classes[] = $row['class'];
    $temp = [];
    if(!(in_array($row['subject_id'],$subject_ids))){
        $subject_ids[] = $row['subject_id'];
        $temp["subject_id"] = $row['subject_id'];
        $temp["subject_name"] = $row['subject_name'];
        $all_subjects[] = $temp;
    }
}
$all_classes = array_unique($all_classes);



$action = $_POST['action'] ?? '';
if ($action === 'getClasses') {
    header('Content-Type: application/json');
    ob_end_clean();
    echo json_encode(['classes' => $all_classes]);
}

// Handle AJAX requests
$response = [];
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'getSubjects') {
        $selected_class = $_POST['class'] ?? '';

        if (!empty($selected_class)) {
            $subject_ids=[];
            $subjects = [];

            foreach ($all_result_rows as $row)  {
                $temp = [];
                if($row['class']==$selected_class){
                    if(!(in_array($row['subject_id'],$subject_ids))){
                        $subject_ids[] = $row['subject_id'];
                        $temp["subject_id"] = $row['subject_id'];
                        $temp["subject_name"] = $row['subject_name'];
                        $subjects[] = $temp;
                    }
                }
            }
            $response = ['subjects' => $subjects];
        }
    } elseif ($action === 'getStudents') {
        $selected_class = $_POST['class'] ?? '';
        if (!empty($selected_class)) {
            
            $student_ids=[];
            $students = [];

            foreach ($all_result_rows as $row)  {
                $temp = [];
                if($row['class']==$selected_class){
                    if(!(in_array($row['student_id'],$student_ids))){
                        $student_ids[] = $row['student_id'];
                        $temp["student_id"] = $row['student_id'];
                        $temp["student_name"] = $row['student_name'];
                        $students[] = $temp;
                    }
                }
            }
            $response = ['students' => $students];

        }
    }
    elseif ($action === 'viewReport') {
        // Fetch report data based on POST parameters
        $selected_subject = $_POST['subject'] ?? '';
        $selected_class = $_POST['class'] ?? '';
        $selected_student = $_POST['student'] ?? '';


        if (!empty($selected_class)) {
    
            if (!empty($selected_subject) && !empty($selected_student)){

                $student_ids = [];
                $students_table = [];

                foreach ($all_result_rows as $row)  {
                    $temp = [];
                    if($row['subject_id']==$selected_subject && $row['student_id']==$selected_student && $row['class']==$selected_class){
                        if(!(in_array($row['student_id'],$student_ids))){
                            $student_ids[] = $row['student_id'];
                            $temp["student_id"] = $row['student_id'];
                            $temp["student_name"] = $row['student_name'];
                            $temp["class"] = $row['class'];
                            $temp["marks"] = $row['marks'];
                            $students_table[] = $temp;
                        }
                    }
                }


            }
            else if (!empty($selected_subject) && empty($selected_student)){
        
                $student_ids = [];
                $students_table = [];

                foreach ($all_result_rows as $row) {
                    $temp = [];
                    if($row['subject_id']==$selected_subject && $row['class']==$selected_class){
                        if(!(in_array($row['student_id'],$student_ids))){
                            $student_ids[] = $row['student_id'];
                            $temp["student_id"] = $row['student_id'];
                            $temp["student_name"] = $row['student_name'];
                            $temp["class"] = $row['class'];
                            $temp["marks"] = $row['marks'];
                            $students_table[] = $temp;
                        }
                    }
                }
            }
            else if (empty($selected_subject) && !empty($selected_student)){

                $student_ids = [];
                $students_table = [];

                foreach ($all_result_rows as $row)  {
                    $temp = [];
                    if($row['class']==$selected_class && $row['student_id']==$selected_student){
                        if(!(in_array($row['student_id'],$student_ids))){
                            $student_ids[] = $row['student_id'];
                            $temp["student_id"] = $row['student_id'];
                            $temp["student_name"] = $row['student_name'];
                            $temp["class"] = $row['class'];
                            $temp["marks"] = $row['marks'];
                            $students_table[] = $temp;
                        }
                    }
                }
            }
            else{

                $student_ids = [];
                $students_table = [];

                foreach ($all_result_rows as $row)  {
                    $temp = [];
                    if($row['class']==$selected_class){
                        if(!(in_array($row['student_id'],$student_ids))){
                            $student_ids[] = $row['student_id'];
                            $temp["student_id"] = $row['student_id'];
                            $temp["student_name"] = $row['student_name'];
                            $temp["class"] = $row['class'];
                            $temp["marks"] = $row['marks'];
                            $students_table[] = $temp;
                        }
                    }
                }
            }
        }
    
        // Fetch subject name
        $subject_name = '';
        if (empty($selected_subject)) {
            $subject_data = $all_subjects;
        }
        else{
            $subject_data = [];
            foreach($all_subjects as $sub){
                if($sub["subject_id"]==$selected_subject){
                    $subject_data[]=$sub;
                    $subject_name=$sub["subject_name"];
                }
            }
        }
        if(empty($subject_name)){
            $subject_name = 'Unknown Subject';
        }


        $tablerows=[];
        $tablerow=[];
        // Start building the HTML output
        $output = "<h2>Marks for Subject: " . htmlspecialchars($subject_name) . "</h2>";
        $tabletitlerow[] = "Id";
        $tabletitlerow[] = "Student_Name";
    
        foreach ($subject_data as $subject) {
            $tabletitlerow[] = $subject['subject_name'];
        }
    
        if (empty($selected_subject)) {
            $tabletitlerow[] = "Total";
            $tabletitlerow[] = "Percentage";
        }

    
        foreach ( $students_table as $student_row) {
            $tablerow["Id"]=$student_row["student_id"];
            $tablerow["Student_Name"]=$student_row["student_name"];
    
            $total = 0;
            $stotal = 0;
    
            foreach ($subject_data as $subject) {
                $marks=null;
                $tablerow[$subject['subject_name']] = "";
                foreach ($all_result_rows as $row)  {
                    if($row['student_id']==$student_row["student_id"] && $row['subject_id']==$subject["subject_id"]){
                        $marks=$row['marks'];
                        $tablerow[$subject['subject_name']] = $marks;
                    }
                }
    
                if ($marks) {
                    $total += $marks;
                    $stotal += 100; // Assuming each subject is out of 100
                }
    
            }
    
            $perc = $stotal > 0 ? ($total / $stotal) * 100 : 0; // Avoid division by zero
            if (empty($selected_subject)) {
                $output .= '<td>' . $total . '</td>
                            <td>' . number_format($perc, 2) . '%</td>'; // Format percentage to 2 decimal places
                            $tablerow["Total"] = $total;
                            $tablerow["Percentage"] = number_format($perc, 2);

            }

            $tablerows[]=$tablerow;
        }

        $output = null;
        // Output the complete HTML
        ob_end_clean();
        $response = ["tablerows"=>$tablerows]; 
    }
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(gettype($response) == "string"){
    if(!empty($response)){
        print_r($response);
        var_dump($response); // or use error_log(print_r($response, true));
        header('Content-Type: application/json');

        ob_end_clean();
        echo json_encode($response);
    }
}
if(gettype($response) == "array"){
    if(count($response)>0){
        print_r($response);
        var_dump($response); // or use error_log(print_r($response, true));
        header('Content-Type: application/json');
        ob_end_clean();
        echo json_encode($response);
    }
}
?>