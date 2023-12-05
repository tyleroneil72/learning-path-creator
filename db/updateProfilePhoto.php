<?php
include 'config.php';


$username = $_COOKIE['username'];

// Check if the form is submitted and an image is selected
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES["profilePhoto"]["name"])) {
    // Process the file upload
    $targetDir = "../assets/images/profile-photos/";
    $imageName = uniqid('profile_photo_') . '.' . pathinfo($_FILES["profilePhoto"]["name"], PATHINFO_EXTENSION);
    $targetFile = $targetDir . $imageName;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    $check = getimagesize($_FILES["profilePhoto"]["tmp_name"]);
    if ($check === false) {
        echo '<script>alert("File is not an image.");</script>';
        echo '<script>window.location.href = "../pages/profile.php";</script>';
        exit();
    }

    // Check file size
    if ($_FILES["profilePhoto"]["size"] > 1000000) {
        echo '<script>alert("Sorry, your file is too large.");</script>';
        echo '<script>window.location.href = "../pages/profile.php";</script>';
        exit();
    }

    // Allow certain file formats
    $allowedExtensions = ["jpg", "jpeg", "png", "gif", "heif"];
    if (!in_array($imageFileType, $allowedExtensions)) {
        echo '<script>alert("Sorry, only JPG, JPEG, HEIF, PNG & GIF files are allowed.");</script>';
        echo '<script>window.location.href = "../pages/profile.php";</script>';
        exit();
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo '<script>alert("Sorry, your file was not uploaded.");</script>';
        echo '<script>window.location.href = "../pages/profile.php";</script>';
        exit();
    } else {
        // If everything is OK, try to upload the file
        if (move_uploaded_file($_FILES["profilePhoto"]["tmp_name"], $targetFile)) {
            // Update the database with the new path
            $newPath = "../assets/images/profile-photos/" . $imageName;
            $stmt = $conn->prepare("UPDATE users SET profile_photo = ? WHERE username = ?");
            $stmt->bind_param("ss", $newPath, $username);

            if ($stmt->execute()) {
                echo '<script>alert("Profile photo changed successfully!");</script>';
                echo '<script>window.location.href = "../pages/profile.php";</script>';
                exit();
            } else {
                echo '<script>alert("Error updating profile photo in the database.");</script>';
                echo '<script>window.location.href = "../pages/profile.php";</script>';
                exit();
            }

            $stmt->close();
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
            echo '<script>window.location.href = "../pages/profile.php";</script>';
            exit();
        }
    }
} else {
    echo '<script>alert("Please select an image to upload.");</script>';
    echo '<script>window.location.href = "../pages/profile.php";</script>';
    exit();
}

$conn->close();
