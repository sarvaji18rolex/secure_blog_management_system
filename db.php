<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "blog1";  // or "auth" if using separate DB

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
