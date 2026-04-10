<?php
$servername = "sql303.infinityfree.com";
$username = "if0_41264238";
$password = "8BhbqnhUe4gKHSL";
$dbname = "if0_41264238_Anirban_portfolio";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>