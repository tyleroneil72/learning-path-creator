<?php

$host = "localhost"; // Change this to your host if not "localhost"
$username = "root"; // Change this to your user if not "root"
$password = ""; // Change this to your password if you have set one
$database = "project_db"; // Change this to your database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
