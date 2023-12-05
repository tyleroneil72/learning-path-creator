<?php
include 'config.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['changePassword'])) {
    // Process the change password form
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];

    // Assuming you have a username stored in cookies
    $username = $_COOKIE['username'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($userId, $storedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (password_verify($currentPassword, $storedPassword)) {
        // Update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updateStmt->bind_param("si", $hashedPassword, $userId);

        if ($updateStmt->execute()) {
            // Close the database connection
            $updateStmt->close();
            $conn->close();

            // Send a success message to be displayed using JavaScript alert
            echo '<script>alert("Password changed successfully!");</script>';

            // Redirect to the profile page after successful password change
            echo '<script>window.location.href = "../pages/profile.php";</script>';
            exit();
        } else {
            // Handle error 
            echo "Error: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo '<script>alert("Incorrect current password!");</script>';
    }
}

$conn->close();
