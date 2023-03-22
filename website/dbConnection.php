<?php
$servername = "localhost";
$username_db = "cosc360user";
$password_db = "1234";
$dbname = "cosc360project";
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>