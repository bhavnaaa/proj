<?php
$servername = "localhost";  // or the IP address of your MySQL server
$username = "root";
$password = "";
$db = "shipoline";      // Make sure this matches the actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


