<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $resources = $_POST['resources'];

    $username = $_COOKIE['username'];

    // Fetch UserID based on username
    $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $stmtUser->bind_result($userID);
    $stmtUser->fetch();
    $stmtUser->close();

    // Set OriginalUserID to the same as UserID for the creator
    $originalUserID = $userID;

    // Insert into LearningPaths
    $stmtPath = $conn->prepare("INSERT INTO LearningPaths (UserID, OriginalUserID, Title, Description, Resources) VALUES (?, ?, ?, ?, ?)");
    $stmtPath->bind_param("iisss", $userID, $originalUserID, $title, $description, $resources);

    if ($stmtPath->execute()) {
        // Learning path created successfully, fetch PathID
        $stmtGetPathID = $conn->prepare("SELECT PathID FROM LearningPaths WHERE Title = ? AND UserID = ?");
        $stmtGetPathID->bind_param("si", $title, $userID);
        $stmtGetPathID->execute();
        $stmtGetPathID->bind_result($pathID);
        $stmtGetPathID->fetch();
        $stmtGetPathID->close();

        // Update OriginalPathID with the fetched PathID
        $stmtUpdateOriginalPathID = $conn->prepare("UPDATE LearningPaths SET OriginalPathID = ? WHERE PathID = ?");
        $stmtUpdateOriginalPathID->bind_param("ii", $pathID, $pathID);
        $stmtUpdateOriginalPathID->execute();
        $stmtUpdateOriginalPathID->close();

        $message = "Learning path created successfully!";
    } else {
        $message = "Error creating learning path: " . $stmtPath->error;
    }

    $stmtPath->close();
    $conn->close();

    // Use JavaScript to alert and redirect
    echo '<script>';
    echo 'alert("' . $message . '");';
    echo 'window.location.href = "../pages/homePage.php";';
    echo '</script>';
}
