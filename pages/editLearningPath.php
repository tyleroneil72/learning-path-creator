<?php
include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php';

// Fetch the PathID from the URL
$pathID = $_GET['pathID'];

// Fetch learning path details from the database based on PathID
include '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'config.php';
$stmtPathDetails = $conn->prepare("SELECT Title, Description, Resources FROM LearningPaths WHERE PathID = ?");
$stmtPathDetails->bind_param("i", $pathID);
$stmtPathDetails->execute();
$stmtPathDetails->bind_result($title, $description, $resources);
$stmtPathDetails->fetch();
$stmtPathDetails->close();

// Check if the logged-in user is the creator
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];

    // Fetch UserID based on username
    $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $stmtUser->bind_result($userID);
    $stmtUser->fetch();
    $stmtUser->close();

    // Check if the logged-in user is the creator of the learning path
    $stmtCheckCreator = $conn->prepare("SELECT 1 FROM LearningPaths WHERE PathID = ? AND UserID = ?");
    $stmtCheckCreator->bind_param("ii", $pathID, $userID);
    $stmtCheckCreator->execute();
    $isCreator = $stmtCheckCreator->fetch();
    $stmtCheckCreator->close();

    if ($isCreator) {
        // Display the form for editing
        echo '<div class="container mt-4">';
        echo '<h1>Edit Learning Path</h1>';

        echo '<form action="../db/updateLearningPath.php" method="POST">';
        echo '<input type="hidden" name="pathID" value="' . $pathID . '">';

        echo '<div class="form-group">';
        echo '<label for="title">Title</label>';
        echo '<input type="text" class="form-control" id="title" name="title" value="' . $title . '" required>';
        echo '</div>';

        echo '<div class="form-group">';
        echo '<label for="description">Description</label>';
        echo '<textarea class="form-control" id="description" name="description" rows="3" required>' . $description . '</textarea>';
        echo '</div>';

        echo '<div class="form-group">';
        echo '<label for="resources">Resources (URLs, separated by commas)</label>';
        echo '<input type="text" class="form-control" id="resources" name="resources" value="' . $resources . '" required>';
        echo '</div>';

        echo '<button type="submit" class="btn btn-primary" name="updateLearningPath">Update Learning Path</button>';
        echo '</form>';

        echo '</div>';
    } else {
        echo '<p class="mt-3">You do not have permission to edit this learning path.</p>';
    }
} else {
    echo "<p class='mt-3'>User not found.</p>";
}

include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php';
