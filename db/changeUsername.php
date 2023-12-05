<?php
include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is the change username form
    if (isset($_POST['changeUsername'])) {
        // Process the change username form
        $newUsername = $_POST['newUsername'];

        $currentUsername = $_COOKIE['username'];

        // Check if the new username already exists
        $checkUsername = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $checkUsername->bind_param("s", $newUsername);
        $checkUsername->execute();
        $checkUsername->store_result();

        if ($checkUsername->num_rows > 0) {
            // Username already exists, display alert and redirect back to profile page
            echo "<script>alert('Username is already taken. Please choose a different username.'); window.location.href='../pages/profile.php';</script>";
            exit();
        }

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->bind_param("ss", $newUsername, $currentUsername);

        if ($stmt->execute()) {
            // Update the username in cookies
            setcookie('username', $newUsername, time() + 86400, '/'); // 86400 seconds = 1 day

            // Display success message
            echo "<script>alert('Username successfully changed.'); window.location.href='../pages/profile.php';</script>";
            exit();
        } else {
            // Handle error
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
