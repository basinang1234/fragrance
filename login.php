<?php
require 'config.php'; // Include centralized configuration
// Set the correct Content-Type header
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
        exit;
    }

    try {
        // Fetch user details
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password_hash"])) {
            // Store user session
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Determine redirect URL based on role (if applicable)
            $redirectUrl = "index.php"; // Default redirect for all users

            echo json_encode([
                "success" => true,
                "message" => "Login successful!",
                "redirectUrl" => $redirectUrl
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid email or password!"]);
        }
    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "An unexpected error occurred."]);
    }
}
?>