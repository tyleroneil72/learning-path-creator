<?php
include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php';

// Fetch the PathID from the URL
$pathID = $_GET['pathID'];

// Fetch learning path details from the database based on PathID
include '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'config.php';
$stmtPathDetails = $conn->prepare("SELECT Title, Description, Resources, OriginalPathID, OriginalUserID FROM LearningPaths WHERE PathID = ?");
$stmtPathDetails->bind_param("i", $pathID);
$stmtPathDetails->execute();
$stmtPathDetails->bind_result($title, $description, $resources, $originalPathID, $originalUserID);
$stmtPathDetails->fetch();
$stmtPathDetails->close();

// Fetch user details based on OriginalUserID
$stmtUserDetails = $conn->prepare("SELECT username, profile_photo FROM users WHERE id = ?");
$stmtUserDetails->bind_param("i", $originalUserID);
$stmtUserDetails->execute();
$stmtUserDetails->bind_result($username, $profilePhoto);
$stmtUserDetails->fetch();
$stmtUserDetails->close();

// Fetch total upvotes and downvotes
$stmtVotes = $conn->prepare("SELECT COUNT(*) FROM Votes WHERE PathID = ? AND Vote = 'upvote'");
$stmtVotes->bind_param("i", $originalPathID);
$stmtVotes->execute();
$stmtVotes->bind_result($totalUpvotes);
$stmtVotes->fetch();
$stmtVotes->close();

$stmtVotes = $conn->prepare("SELECT COUNT(*) FROM Votes WHERE PathID = ? AND Vote = 'downvote'");
$stmtVotes->bind_param("i", $originalPathID);
$stmtVotes->execute();
$stmtVotes->bind_result($totalDownvotes);
$stmtVotes->fetch();
$stmtVotes->close();

// Display learning path details
echo '<div class="container mt-4">';
echo '<div class="card">';
echo '<div class="card-body">';
echo '<h1 class="card-title">' . $title . '</h1>';
echo '<p class="card-subtitle mb-2 text-muted">Created by: ' . $username . '</p>';
echo '<div class="profile-photo-container">';
echo '<img src="' . $profilePhoto . '" alt="User Profile Photo" class="img-fluid profile-photo mb-3 rounded-circle">';
echo '</div>';
echo '<p class="card-text">' . $description . '</p>';
echo '<p class="card-text"><strong>Resources:</strong> ' . $resources . '</p>';

// Display total upvotes and downvotes
echo '<p class="card-text"><strong>Total Upvotes:</strong> ' . $totalUpvotes . '</p>';
echo '<p class="card-text"><strong>Total Downvotes:</strong> ' . $totalDownvotes . '</p>';

// Check if the logged-in user is the creator
if (isset($_COOKIE['username']) && $_COOKIE['username'] === $username) {
    // Display edit button for the creator
    echo '<div class="mt-3">';
    echo '<a href="editLearningPath.php?pathID=' . $pathID . '" class="btn btn-warning mr-2">Edit</a>';
    echo '</div>';
} elseif (isset($_COOKIE['username'])) {
    // Display upvote and downvote buttons for other users
    echo '<div class="mt-3">';
    echo '<form action="../db/vote.php" method="post">';
    echo '<input type="hidden" name="pathID" value="' . $originalPathID . '">';
    echo '<button type="submit" class="btn btn-success mr-2" name="voteValue" value="upvote">Upvote</button>';
    echo '<button type="submit" class="btn btn-danger" name="voteValue" value="downvote">Downvote</button>';
    echo '</form>';
    echo '</div>';
}

echo '</div>';
echo '</div>';
echo '</div>';

include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php';
