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
<h3>Teacher Registration</h3>
<form method="POST">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Add Teacher</button>
</form>
<br>
<br>
<div>
    <a href="login.php"> Log in </a>
</div>
