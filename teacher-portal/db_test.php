<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "testuser";
$password = "password";
$dbname = "testdb";


//connection
$conn = new mysqli($servername,$username,$password,$dbname);

//Check connection
if($conn->connect_error){
die("Connection failed:" . $conn->connect_error);
}
echo "Connected successfully";

$sql ="CREATE TABLE IF NOT EXISTS students(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50),
    class VARCHAR(50)
)";
if($conn->query($sql) === TRUE){
    echo "Table created successfully";
}
else{
    echo "Error creating table: ".$conn->error;
}

$conn->close();
?>
