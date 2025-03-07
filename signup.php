<?php
// signup.php - User Registration Script

require 'config.php'; // Include centralized configuration

// Set the correct Content-Type header
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $bio = isset($_POST["bio"]) ? trim($_POST["bio"]) : null;

    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "All required fields are mandatory!"]);
        exit;
    }

    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Username or email already exists!"]);
        exit;
    }

    // Handle profile picture upload
    $profilePicturePath = null;
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/"; // Ensure this directory exists and is writable
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid() . "_" . basename($_FILES["profile_picture"]["name"]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $filePath)) {
            $profilePicturePath = $filePath; // Save the file path
        } else {
            echo json_encode(["success" => false, "message" => "Failed to upload profile picture!"]);
            exit;
        }
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role, profile_picture, bio) VALUES (?, ?, ?, 'user', ?, ?)");
    if ($stmt->execute([$username, $email, $passwordHash, $profilePicturePath, $bio])) {
        echo json_encode(["success" => true, "message" => "Signup successful!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Signup failed. Try again!"]);
    }
}
?>