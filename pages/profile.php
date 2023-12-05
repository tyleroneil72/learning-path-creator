<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'header.php'; ?>
<?php
include '..' . DIRECTORY_SEPARATOR . 'db' . DIRECTORY_SEPARATOR . 'config.php';

$username = $_COOKIE['username'];

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT profile_photo FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($profilePhotoPath);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<div class="container mt-5">
    <h1 class="mb-4">Profile</h1>

    <div class="row">
        <div class="col-md-4">
            <h3>Profile Photo</h3>
            <div class="mb-3">
                <img src="<?php echo $profilePhotoPath; ?>" alt="Profile Photo" class="img-fluid profile-photo" id="profilePhotoPreview">
            </div>
            <form action="../db/updateProfilePhoto.php" method="post" enctype="multipart/form-data">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="profilePhotoInput" name="profilePhoto" accept="image/*" onchange="readURL(this);">
                    <label class="custom-file-label" for="profilePhotoInput">Choose Photo</label>
                </div>
                <button type="submit" class="btn btn-primary mt-3" name="changeUsername">Save Photo</button>
            </form>
        </div>

        <div class="col-md-4">
            <h3>Change Username</h3>
            <form method="POST" action="../db/changeUsername.php">
                <div class="form-group">
                    <label for="newUsername">New Username</label>
                    <input type="text" class="form-control" id="newUsername" name="newUsername" required>
                </div>
                <button type="submit" class="btn btn-primary" name="changeUsername">Change Username</button>
            </form>
        </div>

        <div class="col-md-4">
            <h3>Change Password</h3>
            <form action="../db/changePassword.php" method="POST">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <button type="submit" class="btn btn-primary" name="changePassword">Change Password</button>
            </form>
        </div>
    </div>
</div>

<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = function(e) {
                $('#profilePhotoPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php include '..' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'footer.php'; ?>