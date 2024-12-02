<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$user = 'testuser';
$password = 'password';
$dbname = 'testdb';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<div class="connection-message-success">
<span> 

<?php

echo 'Connected Successfully';

?>
</span>
<br>
<br>
</div>

