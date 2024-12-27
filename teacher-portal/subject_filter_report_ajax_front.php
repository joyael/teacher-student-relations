
<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['teacher_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Filter Report</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <?php 
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    ?>
    <form id="filterForm">
        <label for="subject">Select Subject</label>
        <select name="subject" id="subject">
            <option value="">-- All --</option>
            <!-- Options will be populated by AJAX -->
        </select>
        <br>

        <label for="class">Select Class</label>
        <select name="class" id="class">
            <option value="">-- Select a Class --</option>
        </select><br>

        <label for="student">Select Student</label>
        <select name="student" id="student">
            <option value="">-- Select a Student --</option>
        </select><br>

        <button type="button" class="form-submit" id="viewReport">View Report</button>
    </form>

    <div id="reportContainer">
        
    </div>
    <table border="1" cellpadding="5" cellspacing="0" id="reporttable">
        <thead>
            <tr id="reporttabletitlerow">

            </tr>
        </thead>
        <tbody id="reporttablebody">

        </tbody>
    </table>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const subjectDropdown = document.getElementById('subject');
            const classDropdown = document.getElementById('class');
            const studentDropdown = document.getElementById('student');
            const reportContainer = document.getElementById('reportContainer');

            // Fetch subjjects at first
            fetch('subject_filter_report_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'getSubjects' }) // Change action to getSubjects
            })
            .then(response => response.json())
            .then(data => {
                subjectDropdown.innerHTML = '<option value="">-- All --</option>'; // Default option
                // Populate the subject dropdown with the fetched subjects
                data.subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.subject_id; // Use subject_id as the value
                    option.textContent = subject.subject_name; // Use subject_name as the display text
                    subjectDropdown.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching subjects:', error);
            });

            subjectDropdown.addEventListener('change', () => {
                const subjectId = subjectDropdown.value;

                // Fetch classes for the selected subject
                fetch('subject_filter_report_ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'getClasses', subject: subjectId })
                })
                .then(response => response.json())
                .then(data => {
                    classDropdown.innerHTML = '<option value="">-- Select a Class --</option>';
                    data.classes.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls;
                        option.textContent = cls;
                        classDropdown.appendChild(option);
                    });
                });

                // Fetch students for the selected subject
                fetch('subject_filter_report_ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'getStudents', subject: subjectId })
                })
                .then(response => response.json())
                .then(data => {
                    studentDropdown.innerHTML = '<option value="">-- Select a Student --</option>';
                    data.students.forEach(student => {
                        const option = document.createElement('option');
                        option.value = student.student_id;
                        option.textContent = student.student_name;
                        studentDropdown.appendChild(option);
                    });
                });
            });

            document.getElementById('viewReport').addEventListener('click', () => {
                const subjectId = subjectDropdown.value;
                const classValue = classDropdown.value;
                const studentId = studentDropdown.value;

                // Fetch and display the report
                fetch('subject_filter_report_ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'viewReport', subject: subjectId, class: classValue, student: studentId })
                })
                .then(response => response.json()) // Parse the response as JSON
                .then(data => {
                    const reporttabletitlerow = document.getElementById('reporttabletitlerow');
                    const reporttablebody = document.getElementById('reporttablebody');

                    // Clear existing table headers and rows
                    reporttabletitlerow.innerHTML = ''; // Clear all <th> elements
                    reporttablebody.innerHTML = ''; // Clear all <tr> elements

                    // Extract keys from the first object to create table headers
                    let keys = Object.keys(data.tablerows[0]);
                    keys.forEach(key => {
                        const th = document.createElement('th');
                        th.textContent = key; // Correct capitalization of `textContent`
                        reporttabletitlerow.appendChild(th);
                    });

                    // Populate the table rows with data
                    data.tablerows.forEach(row => {
                        const tr = document.createElement('tr');
                        keys.forEach(key => {
                            const td = document.createElement('td');
                            td.textContent = row[key]; // Correct capitalization of `textContent`
                            tr.appendChild(td);
                        });
                        reporttablebody.appendChild(tr);
                    });
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>

<?php include "footer.php"; ?>
</body>
</html>