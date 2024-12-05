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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal - Login</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Bootstrap style -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap js and popper.js -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <header>
        <h1>Teacher Portal</h1>
        <nav>
            <ul>
                <li><a href="login.php" class="element-page-active">Login</a></li>
                <li><a href="insert_teacher.php">Register Teacher</a></li>
            </ul>
        </nav>
    </header>

    <div class="content-outer">
        <div class="content-box">
            <h3>Login Page</h3>
            <form method="POST" action="">
                Username: <input type="text" name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <button type="submit" class="form-submit">Login</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
    </footer>
</body>
</html>
