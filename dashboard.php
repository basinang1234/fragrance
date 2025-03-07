<?php
require 'config.php'; // Include centralized configuration

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Redirect admins/moderators to admin dashboard
if ($_SESSION["role"] === 'admin' || $_SESSION["role"] === 'moderator') {
    header("Location: admin_dashboard.php");
    exit;
}

// Proceed for regular users
$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$role = $_SESSION["role"];

// Fetch ALL user details (matches your database schema)
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Fragrance Haven</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            line-height: 1.6;
        }

        /* Container */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background-color: #fff;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #007bff;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 1rem;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .sidebar .logout {
            margin-top: auto;
        }

        /* Main Content */
        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .profile-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }

        .profile-pic {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }

        .profile-section h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .profile-section p {
            font-size: 1rem;
            color: #555;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                box-shadow: none;
                padding: 10px;
            }

            .sidebar ul li {
                margin-bottom: 5px;
            }

            .main-content {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <h2>Fragrance Haven</h2>
            <ul>
                <li><a href="edit_profile.php">Edit Profile</a></li>
                <li><a href="my_reviews.php">My Reviews</a></li>
                <li><a href="my_comparisons.php">Saved Comparisons</a></li>
                <li><a href="messages.php">Messages</a></li>
            </ul>
            <div class="logout">
                <a href="logout.php" class="logout-button">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Profile Section -->
            <div class="profile-section">
                <img src="<?php echo $user['profile_picture'] ?: 'default-avatar.png'; ?>" alt="Profile Picture" class="profile-pic">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
                <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio'] ?: "No bio set."); ?></p>
            </div>
        </main>
    </div>
</body>
</html>