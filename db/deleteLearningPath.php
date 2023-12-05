<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if pathID is provided
    if (isset($_POST['pathID'])) {
        $pathID = $_POST['pathID'];

        // Validate pathID (you may want to perform more thorough validation)
        if (is_numeric($pathID) && $pathID > 0) {

            $username = $_COOKIE['username'];

            // Fetch UserID based on username
            $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmtUser->bind_param("s", $username);
            $stmtUser->execute();
            $stmtUser->bind_result($loggedInUserID);
            $stmtUser->fetch();
            $stmtUser->close();

            // Fetch OriginalPathID and OriginalUserID based on PathID
            $stmtPathDetails = $conn->prepare("SELECT OriginalPathID, OriginalUserID FROM LearningPaths WHERE PathID = ?");
            $stmtPathDetails->bind_param("i", $pathID);
            $stmtPathDetails->execute();
            $stmtPathDetails->bind_result($originalPathID, $originalUserID);
            $stmtPathDetails->fetch();
            $stmtPathDetails->close();

            // Check if the logged-in user is the creator of the learning path
            if ($originalUserID == $loggedInUserID) {
                // Delete votes for the original learning path
                $stmtDeleteVotes = $conn->prepare("DELETE FROM Votes WHERE PathID = ?");
                $stmtDeleteVotes->bind_param("i", $originalPathID);
                $stmtDeleteVotes->execute();
                $stmtDeleteVotes->close();

                // Delete the original learning path and its imports
                $stmtDeletePath = $conn->prepare("DELETE FROM LearningPaths WHERE OriginalPathID = ?");
                $stmtDeletePath->bind_param("i", $originalPathID);

                if ($stmtDeletePath->execute()) {
                    echo '<script>alert("Learning path and its imports deleted successfully!");</script>';
                    echo '<script>window.location.href = "../pages/homePage.php";</script>';
                } else {
                    echo '<script>alert("Error deleting learning path: ' . $stmtDeletePath->error . '");</script>';
                    echo '<script>window.location.href = "../pages/homePage.php";</script>';
                }

                $stmtDeletePath->close();
            } else {
                // Delete only the imported learning path
                $stmtDeletePath = $conn->prepare("DELETE FROM LearningPaths WHERE PathID = ?");
                $stmtDeletePath->bind_param("i", $pathID);

                if ($stmtDeletePath->execute()) {
                    echo '<script>alert("Learning path deleted successfully!");</script>';
                    echo '<script>window.location.href = "../pages/homePage.php";</script>';
                } else {
                    echo '<script>alert("Error deleting learning path: ' . $stmtDeletePath->error . '");</script>';
                    echo '<script>window.location.href = "../pages/homePage.php";</script>';
                }

                $stmtDeletePath->close();
            }

            $conn->close();
        } else {
            echo '<script>alert("Invalid pathID!");</script>';
            echo '<script>window.location.href = "../pages/homePage.php";</script>';
        }
    } else {
        echo '<script>alert("Please provide a pathID!");</script>';
        echo '<script>window.location.href = "../pages/homePage.php";</script>';
    }
} else {
    echo '<script>alert("Invalid request method!");</script>';
    echo '<script>window.location.href = "../pages/homePage.php";</script>';
}
