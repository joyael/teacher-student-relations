<?php
session_start();
include 'db_connection.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $result = $conn->query("SELECT * FROM teachers WHERE username='$username'");
    $teacher = $result->fetch_assoc();

    if ($teacher && password_verify($password, $teacher['password'])) {
        session_regenerate_id();
        $_SESSION['teacher_id'] = $teacher['id'];
        header("Location: dashboard.php");
    } else {
        echo "Invalid credentials.";
    }

    $conn->close();
}
?>
<div class="login-div">
<h3>Login Page</h3>
<form method="POST" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<div>
