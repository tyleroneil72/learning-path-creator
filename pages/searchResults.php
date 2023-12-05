<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<?php
include '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
    $searchQuery = '%' . $_GET['query'] . '%'; // Using '%' for partial match

    $username = $_COOKIE['username'];

    // Fetch UserID based on username
    $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmtUser->bind_param("s", $username);
    $stmtUser->execute();
    $stmtUser->bind_result($userID);
    $stmtUser->fetch();
    $stmtUser->close();

    // Perform the search in the database (based on Title and Description)
    $stmtSearch = $conn->prepare("SELECT PathID, Title, Description FROM LearningPaths WHERE UserID = ? AND (Title LIKE ? OR Description LIKE ?)");
    $stmtSearch->bind_param("iss", $userID, $searchQuery, $searchQuery);
    $stmtSearch->execute();
    $result = $stmtSearch->get_result();
    $searchResults = $result->fetch_all(MYSQLI_ASSOC);
    $stmtSearch->close();

    // Display the search results
    echo '<div class="container mt-4">';
    echo '<h1 class="display-4">Search Results</h1>';

    if (!empty($searchResults)) {
        foreach ($searchResults as $result) {
            echo '<div class="card mb-4">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $result['Title'] . '</h5>';
            echo '<p class="card-text">' . $result['Description'] . '</p>';
            echo '<a href="viewLearningPath.php?pathID=' . $result['PathID'] . '" class="btn btn-info">View Details</a>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<p>No matching learning paths found.</p>';
    }

    echo '</div>';
} else {
    // Handle the case when no search query is provided
    echo "No search query provided.";
}

$conn->close();

?> 

<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php'; ?>