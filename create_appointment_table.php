<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "register"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    reason TEXT NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'appointments' created successfully or already exists.";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>

