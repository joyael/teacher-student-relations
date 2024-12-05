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
</span>
</div>



        
    


    <div class="info-popup" id="infoPopup">
        <span class="close-btn" id="closeBtn">&times;</span>
        <p>
                <?php
                echo 'Connected Successfully';
                ?>
        </p>
    </div>

    <script>
        // Get the popup and close button elements
        const popup = document.getElementById('infoPopup');
        const closeBtn = document.getElementById('closeBtn');

        // Show the popup when the page loads
        window.onload = function() {
            popup.style.display = 'block';
        };

        // Close the popup when the close button is clicked
        closeBtn.onclick = function() {
            popup.style.display = 'none';
        };
    </script>



