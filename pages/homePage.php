<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php'; ?>

<div class="container mt-4">
    <h1 class="display-4">My Learning Paths</h1>

    <?php
    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];
        echo "<p class='lead'>Welcome, $username!</p>";

        echo '<a href="createLearningPath.php" class="btn btn-primary mt-3">Create Learning Path</a>';

        // Fetch the user's learning paths
        include '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'config.php';

        // Fetch UserID based on username
        $stmtUser = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmtUser->bind_param("s", $_COOKIE['username']);
        $stmtUser->execute();
        $stmtUser->bind_result($userID);
        $stmtUser->fetch();
        $stmtUser->close();

        // Check if UserID is fetched successfully
        if (!$userID) {
            die("Error fetching UserID from the database.");
        }

        $stmtPaths = $conn->prepare("SELECT PathID, Title, Description, Resources FROM LearningPaths WHERE UserID = ?");
        $stmtPaths->bind_param("i", $userID);
        $stmtPaths->execute();

        // Fetch all learning paths
        $result = $stmtPaths->get_result();
        $learningPaths = $result->fetch_all(MYSQLI_ASSOC);

        // Display learning paths
        if (!empty($learningPaths)) {
            echo '<div class="row mt-4">';
            foreach ($learningPaths as $path) {
                echo '<div class="col-md-4 mb-4">';
                echo '<div class="card">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $path['Title'] . '</h5>';
                echo '<p class="card-text">' . $path['Description'] . '</p>';
                echo '<p class="card-text"><strong>Resources:</strong> ' . $path['Resources'] . '</p>';
                echo '<a href="viewLearningPath.php?pathID=' . $path['PathID'] . '" class="btn btn-info mr-2">View Details</a>';
                echo '<form action="../db/deleteLearningPath.php" method="post" class="d-inline-block">';
                echo '<input type="hidden" name="pathID" value="' . $path['PathID'] . '">';
                echo '<button type="submit" class="btn btn-danger">Delete</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo '<p class="mt-4">No learning paths found.</p>';
        }

        // Import Learning Path Section
        echo '<form action="../db/importLearningPath.php" method="get" class="mt-4">';
        echo '<div class="form-group">';
        echo '<label for="importPathLink">Import Learning Path:</label>';
        echo '<div class="input-group">';
        echo '<input type="text" name="pathID" id="importPathLink" class="form-control" placeholder="Enter path URL" required>';
        echo '<div class="input-group-append">';
        echo '<button type="submit" class="btn btn-primary">Import</button>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '</form>';

        $stmtPaths->close();
        $conn->close();
    } else {
        echo "<p class='lead mt-4'>User not found.</p>";
    }
    ?>
</div>

<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php'; ?>