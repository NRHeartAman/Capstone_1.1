<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "cravecast_db"; // The name we just created

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>