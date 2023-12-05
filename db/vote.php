<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if pathID and voteValue are provided
    if (isset($_POST['pathID'], $_POST['voteValue'])) {
        $pathID = $_POST['pathID'];
        $voteValue = $_POST['voteValue'];

        $username = $_COOKIE['username'];

        // Fetch UserID based on username
        $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmtUser->bind_param("s", $username);
        $stmtUser->execute();
        $stmtUser->bind_result($userID);
        $stmtUser->fetch();
        $stmtUser->close();

        // Check if the user has already voted for this path
        $stmtCheckVote = $conn->prepare("SELECT VoteID FROM Votes WHERE UserID = ? AND PathID = ?");
        $stmtCheckVote->bind_param("ii", $userID, $pathID);
        $stmtCheckVote->execute();
        $stmtCheckVote->bind_result($existingVoteID);
        $stmtCheckVote->fetch();
        $stmtCheckVote->close();

        if ($existingVoteID) {
            // Update the existing vote
            $stmtUpdateVote = $conn->prepare("UPDATE Votes SET Vote = ? WHERE VoteID = ?");
            $stmtUpdateVote->bind_param("si", $voteValue, $existingVoteID);

            if ($stmtUpdateVote->execute()) {
                // Vote updated successfully
                $stmtUpdateVote->close();
                $conn->close();

                // Add JavaScript to show an alert and redirect
                echo '<script>';
                echo 'alert("Your vote has been updated!");';
                echo 'window.location.href = document.referrer;';
                echo '</script>';
                exit();
            } else {
                // Error updating vote
                $message = "Error updating vote: " . $stmtUpdateVote->error;
            }
        } else {
            // Insert a new vote
            $stmtInsertVote = $conn->prepare("INSERT INTO Votes (UserID, PathID, Vote) VALUES (?, ?, ?)");
            $stmtInsertVote->bind_param("iis", $userID, $pathID, $voteValue);

            if ($stmtInsertVote->execute()) {
                // Vote recorded successfully
                $stmtInsertVote->close();
                $conn->close();

                // Add JavaScript to show an alert and redirect
                echo '<script>';
                echo 'alert("Your vote has been recorded!");';
                echo 'window.location.href = document.referrer;';
                echo '</script>';
                exit();
            } else {
                // Error recording vote
                $message = "Error recording vote: " . $stmtInsertVote->error;
            }
        }
    } else {
        // Invalid parameters
        $message = "Invalid parameters.";
    }

    // Display an alert and redirect
    echo '<script>';
    echo 'alert("' . $message . '");';
    echo 'window.location.href = document.referrer;';
    echo '</script>';
}
