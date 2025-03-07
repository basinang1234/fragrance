<?php
require 'config.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch admin profile
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $bio = trim($_POST['bio']);

    // Handle profile picture upload
    $profilePicturePath = $admin['profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
        $filePath = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $filePath)) {
            $profilePicturePath = $filePath;
        }
    }

    // Update profile
    $stmt = $pdo->prepare("
        UPDATE users 
        SET username = ?, email = ?, bio = ?, profile_picture = ?
        WHERE id = ?
    ");
    $stmt->execute([$username, $email, $bio, $profilePicturePath, $_SESSION['user_id']]);

    $_SESSION['username'] = $username;
    $_SESSION['profile_picture'] = $profilePicturePath;

    header("Location: admin_edit_profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Fragrance Haven</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .profile-form input, .profile-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="profile-container">
        <h2>Edit Profile</h2>
        <form class="profile-form" method="POST" enctype="multipart/form-data">
            <input type="text" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
            <textarea name="bio"><?= htmlspecialchars($admin['bio']) ?></textarea>
            <input type="file" name="profile_picture">
            <button type="submit">Update Profile</button>
        </form>
    </div>
</body>
</html>