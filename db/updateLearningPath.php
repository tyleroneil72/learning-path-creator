<?php
include 'config.php';

$username = $_COOKIE['username'];

// Fetch the necessary details of the learning path being edited
$pathID = $_POST['pathID'];
$title = $_POST['title'];
$description = $_POST['description'];
$resources = $_POST['resources'];

// Already have an OriginalPathID field in the database
$stmtPathDetails = $conn->prepare("SELECT OriginalPathID FROM LearningPaths WHERE PathID = ?");
$stmtPathDetails->bind_param("i", $pathID);
$stmtPathDetails->execute();
$stmtPathDetails->bind_result($originalPathID);
$stmtPathDetails->fetch();
$stmtPathDetails->close();

// Check if the logged-in user is the creator of the learning path
if ($originalPathID !== null) {
    // Update the learning path being edited
    $stmtUpdatePath = $conn->prepare("UPDATE LearningPaths SET Title = ?, Description = ?, Resources = ? WHERE PathID = ?");
    $stmtUpdatePath->bind_param("sssi", $title, $description, $resources, $pathID);

    if ($stmtUpdatePath->execute()) {
        // Update all learning paths with the same OriginalPathID
        $stmtUpdateAllPaths = $conn->prepare("UPDATE LearningPaths SET Title = ?, Description = ?, Resources = ? WHERE OriginalPathID = ?");
        $stmtUpdateAllPaths->bind_param("sssi", $title, $description, $resources, $originalPathID);
        $stmtUpdateAllPaths->execute();
        $stmtUpdateAllPaths->close();

        echo '<script>';
        echo 'alert("Learning path updated successfully!");';
        echo 'window.location.href = "../pages/viewLearningPath.php?pathID=' . $pathID . '";';
        echo '</script>';
    } else {
        echo '<script>';
        echo 'alert("Error updating learning path: ' . $stmtUpdatePath->error . '");';
        echo 'window.location.href = "../pages/viewLearningPath.php?pathID=' . $pathID . '";';
        echo '</script>';
    }

    $stmtUpdatePath->close();
} else {
    echo '<script>';
    echo 'alert("OriginalPathID not found for the learning path.");';
    echo 'window.location.href = "../pages/homePage.php";';
    echo '</script>';
}

$conn->close();
