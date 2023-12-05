<?php
include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php';

// Fetch original learning paths with the highest upvotes from the database
include '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'config.php';
$stmtLearningPaths = $conn->prepare("SELECT lp.PathID, lp.Title, lp.Description, lp.Resources, lp.UserID, u.username, COUNT(v.Vote) AS Upvotes
                                     FROM LearningPaths lp 
                                     INNER JOIN users u ON lp.UserID = u.id 
                                     LEFT JOIN Votes v ON lp.PathID = v.PathID AND v.Vote = 'upvote'
                                     WHERE lp.PathID = lp.OriginalPathID
                                     GROUP BY lp.PathID
                                     ORDER BY Upvotes DESC");
$stmtLearningPaths->execute();
$stmtLearningPaths->bind_result($pathID, $title, $description, $resources, $userID, $username, $upvotes);

// Display original learning paths in a grid
echo '<div class="container mt-4">';
if ($stmtLearningPaths->fetch()) {
    echo '<h1 class="display-4">My Feed</h1>';
    echo '<div class="row">';
    do {
        echo '<div class="col-md-4 mb-4">';
        echo '<div class="card">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $title . '</h5>';
        echo '<p class="card-text">' . $description . '</p>';
        echo '<p class="card-text"><strong>Resources:</strong> ' . $resources . '</p>';
        echo '<p class="card-text"><strong>Created by:</strong> ' . $username . '</p>';
        echo '<p class="card-text"><strong>Upvotes:</strong> ' . $upvotes . '</p>';
        echo '<a href="viewLearningPath.php?pathID=' . $pathID . '" class="btn btn-primary">View Details</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    } while ($stmtLearningPaths->fetch());
    echo '</div>';
} else {
    echo '<p>No original paths yet.</p>';
}
echo '</div>';

$stmtLearningPaths->close();
$conn->close();

include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php';
