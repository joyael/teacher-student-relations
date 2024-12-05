<?php
include 'db_connection.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO teachers (name, email, username, password) VALUES ('$name', '$email', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Teacher added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
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
                <li><a href="login.php">Login</a></li>
                <li><a href="insert_teacher.php" class="element-page-active">Register Teacher</a></li>
            </ul>
        </nav>
    </header>

    <div class="content-outer">
        <div class="content-box">
            <h3 class="center-text">Teacher Registration</h3>
            <form method="POST">
                Name: <input type="text" name="name" required><br>
                Email: <input type="email" name="email" required><br>
                Username: <input type="text" name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <button type="submit" class="form-submit">Add Teacher</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 Teacher Portal. All rights reserved.</p>
    </footer>
</body>
</html>


