<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if pathID is provided in the URL
    if (isset($_GET['pathID'])) {
        $pathIDFromURL = $_GET['pathID'];

        // Validate the URL structure and extract pathID, If implementing this into your own project, you should use a more secure method of validating the URL structure
        if (preg_match('/^.*\?pathID=(\d+)$/', $pathIDFromURL, $matches)) {
            $pathID = $matches[1];

            // Fetch the learning path details based on pathID
            $stmtPathDetails = $conn->prepare("SELECT Title, Description, Resources, UserID, OriginalUserID FROM LearningPaths WHERE PathID = ?");
            $stmtPathDetails->bind_param("i", $pathID);
            $stmtPathDetails->execute();
            $stmtPathDetails->bind_result($title, $description, $resources, $userID, $originalUserID);
            $stmtPathDetails->fetch();
            $stmtPathDetails->close();

            // Check if the learning path exists
            if ($title !== null) {

                $username = $_COOKIE['username'];

                // Fetch UserID based on username
                $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
                $stmtUser->bind_param("s", $username);
                $stmtUser->execute();
                $stmtUser->bind_result($loggedInUserID);
                $stmtUser->fetch();
                $stmtUser->close();

                // Check if the logged-in user is the creator of the learning path
                if ($loggedInUserID != $userID) {
                    // Set OriginalUserID to the same as PathID for the importer
                    $originalPathID = $pathID;

                    // Insert the learning path for the logged-in user
                    $stmtInsertPath = $conn->prepare("INSERT INTO LearningPaths (UserID, OriginalUserID, OriginalPathID, Title, Description, Resources) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmtInsertPath->bind_param("iiisss", $loggedInUserID, $originalUserID, $originalPathID, $title, $description, $resources);

                    if ($stmtInsertPath->execute()) {
                        echo '<script>';
                        echo 'alert("Learning path imported successfully!");';
                        echo 'window.location.href = "../pages/homePage.php";';
                        echo '</script>';
                    } else {
                        echo '<script>';
                        echo 'alert("Error importing learning path: ' . $stmtInsertPath->error . '");';
                        echo 'window.location.href = "../pages/homePage.php";';
                        echo '</script>';
                    }

                    $stmtInsertPath->close();
                } else {
                    echo '<script>';
                    echo 'alert("You cannot import your own learning path!");';
                    echo 'window.location.href = "../pages/homePage.php";';
                    echo '</script>';
                }
            } else {
                echo '<script>';
                echo 'alert("Learning path not found with ID: ' . $pathID . '");';
                echo 'window.location.href = "../pages/homePage.php";';
                echo '</script>';
            }

            $conn->close();
        } else {
            echo '<script>';
            echo 'alert("Invalid URL format!");';
            echo 'window.location.href = "../pages/homePage.php";';
            echo '</script>';
        }
    } else {
        echo '<script>';
        echo 'alert("Please provide a pathID!");';
        echo 'window.location.href = "../pages/homePage.php";';
        echo '</script>';
    }
} else {
    echo '<script>';
    echo 'alert("Invalid request method!");';
    echo 'window.location.href = "../pages/homePage.php";';
    echo '</script>';
}
