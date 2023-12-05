<?php
include 'db' . DIRECTORY_SEPARATOR . 'config.php';

// Check if cookies are present
if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
    header('Location: pages' . DIRECTORY_SEPARATOR . 'homePage.php');
    exit();
} else {

    // If the user does not have cookies showing logged in
    if (isset($_GET['action']) && $_GET['action'] === 'signup') {
        header('Location: pages' . DIRECTORY_SEPARATOR . 'signup.php');
        exit();
    } else {
        header('Location: pages' . DIRECTORY_SEPARATOR . 'login.php');
        exit();
    }
}
