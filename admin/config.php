<?php
$host = 'localhost';
$db   = 'photo_site';
$user = 'root';
$pass = ''; // парола ако има

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Грешка при свързване: " . $conn->connect_error);
}
?>