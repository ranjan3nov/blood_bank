
<?php

$servername = "localhost";
$username = "root";
$passowrd = "";
$db_name = "blood_bank";


$conn = new mysqli($servername, $username, $passowrd, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
