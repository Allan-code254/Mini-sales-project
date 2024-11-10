<?php
// Database configuration
$host = 'localhost';     // Hostname (usually 'localhost' for local development)
$db = 'your_database';   // Database name (replace with your actual database name)
$user = 'your_username'; // Database username (replace with your actual username)
$pass = 'your_password'; // Database password (replace with your actual password)

// Create a new MySQLi connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);  // Error message if connection fails
}

// Optional: Set character set to UTF-8 to handle special characters properly
$conn->set_charset("utf8");

?>
