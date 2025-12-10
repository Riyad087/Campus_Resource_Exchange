<?php
$host = "localhost";
$user = "auraamou_campus";      
$pass = "Riyad@558800";         
$db   = "auraamou_campus";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
