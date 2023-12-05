<?php
if (!isset($_COOKIE['username'])) {
    header('Location: ../index.php'); // Redirect to the index.php page if the user is not logged in
    exit();
}

function getPageTitle()
{
    $currentPage = basename($_SERVER['PHP_SELF'], '.php');
    // This doesn't apply to the login and sign-in since they do not use this header.php
    switch ($currentPage) {
        case 'profile':
            return 'Profile';
        case 'editLearningPath':
            return 'Edit Learning Path';
        case 'viewLearningPath':
            return 'View Learning Path';
        case 'createLearningPath':
            return 'Create Learning Path';
        case 'feed':
            return 'Feed';
        default:
            return 'Home Page';
    }
}

// Check if the logout button is clicked
if (isset($_POST['logout'])) {
    // Clear cookies
    setcookie('user_id', '', time() - 3600, '/');
    setcookie('username', '', time() - 3600, '/');
    // Redirect to the login page
    header('Location: ../index.php?action=signin');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo getPageTitle(); ?> </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script defer src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../assets/styles.css">
    <link type="image/png" sizes="32x32" rel="icon" href="../assets/images/favicon.png">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <span class="navbar-brand">Learning Path Creator</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/homePage.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/feed.php">Feed</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/profile.php">Profile</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="../pages/searchResults.php">
                    <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search" aria-label="Search">
                    <button class="btn btn-secondary my-2 my-sm-0" type="submit" onclick="return validateSearch()">Search</button>
                </form>
                <form class="form-inline my-2 my-lg-0" method="POST">
                    <div class="ml-lg-2">
                        <button class="btn btn-primary my-2 my-sm-0" type="submit" name="logout">Logout</button>
                    </div>
                </form>
            </div>
        </nav>
    </header>

    <script>
        function validateSearch() {
            let query = document.querySelector('input[name="query"]').value.trim();
            if (query === '') {
                // If the search query is empty, prevent form submission
                alert("Please enter a search query.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>