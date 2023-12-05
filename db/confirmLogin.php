<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set cookies upon successful login
            setcookie('username', $row['username'], time() + 86400, '/'); // 86400 seconds = 1 day

            // Redirect to the homepage after successful login
            header('Location: ../pages/homePage.php');
            exit();
        } else {
            // Redirect to login page with alert
            header('Location: ../pages/login.php?alert=Invalid%20password%21');
            exit();
        }
    } else {
        // Redirect to login page with alert
        header('Location: ../pages/login.php?alert=User%20not%20found%21');
        exit();
    }
}

$conn->close();
