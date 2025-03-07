<?php
require './config.php'; // Include centralized configuration
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fragrance Haven</title>
    <style>
        :root {
            --primary-color: #2A2A2A;
            --accent-color: #E63946;
            --text-light: #F8F9FA;
            --background-light: #FFFFFF;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--background-light);
            color: var(--primary-color);
            line-height: 1.6;
        }

        /* Header Styles */
        .site-header {
            background: var(--background-light);
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1440px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }

        /* Logo Section */
        .logo-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo-image {
            height: 40px;
            width: auto;
        }

        .logo-text {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.5rem;
        }

        /* Navigation Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-list {
            display: flex;
            list-style: none;
            gap: 2rem;
        }

        .nav-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            position: relative;
            transition: var(--transition);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: var(--transition);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        /* Dropdown Menu */
        .dropdown-content {
            display: none;
            position: absolute;
            background: white;
            box-shadow: var(--shadow);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            min-width: 200px;
        }

        .dropdown-content a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .dropdown-content a:hover {
            background: #f8f9fa;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Search Bar */
        .search-bar {
            flex-grow: 1;
            margin: 0 2rem;
        }

        .search-form {
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1.25rem;
            border: 2px solid #eee;
            border-radius: 2rem;
            font-size: 1rem;
            transition: var(--transition);
        }

        .search-input:focus {
            border-color: var(--accent-color);
            outline: none;
        }

        .search-button {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }

        .search-icon {
            width: 1.25rem;
            height: 1.25rem;
            fill: var(--primary-color);
        }

        /* User Auth Section */
        .user-auth {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .auth-buttons a {
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: var(--transition);
        }

        .login {
            background: var(--accent-color);
            color: var(--text-light);
        }

        .signup {
            background: #007bff;
            color: var(--text-light);
        }

        .auth-buttons a:hover {
            opacity: 0.9;
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-image {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            cursor: pointer;
            transition: var(--transition);
        }

        .profile-image:hover {
            transform: scale(1.05);
        }

        .profile-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            background: white;
            box-shadow: var(--shadow);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            min-width: 200px;
        }

        .profile-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: var(--primary-color);
            text-decoration: none;
            transition: var(--transition);
        }

        .profile-menu a:hover {
            background: #f8f9fa;
        }

        .profile-dropdown:hover .profile-menu {
            display: block;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            padding: 0.5rem;
            cursor: pointer;
        }

        .hamburger {
            display: block;
            width: 25px;
            height: 2px;
            background: var(--primary-color);
            position: relative;
        }

        .hamburger::before,
        .hamburger::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: inherit;
            left: 0;
            transition: var(--transition);
        }

        .hamburger::before {
            top: -6px;
        }

        .hamburger::after {
            top: 6px;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .header-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav-links {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                background: white;
                width: 100%;
                box-shadow: var(--shadow);
                padding: 1rem;
            }

            .nav-active .nav-links {
                display: flex;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .nav-active .hamburger {
                background: transparent;
            }

            .nav-active .hamburger::before {
                transform: rotate(45deg) translate(4px, 4px);
            }

            .nav-active .hamburger::after {
                transform: rotate(-45deg) translate(4px, -4px);
            }
        }

        @media (max-width: 768px) {
            .logo-text {
                display: none;
            }

            .search-bar {
                margin: 0;
            }
        }
    </style>
</head>
<body>
<header class="site-header">
    <div class="header-container">
        <!-- Logo and Brand Name -->
        <div class="logo-section">
            <a href="index.php" class="logo">
                <img src="assets/logo.png" alt="Fragrance Haven Logo" class="logo-image">
                <span class="logo-text">Fragrance Haven</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="nav-links">
            <ul class="nav-list">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="trending.php" class="nav-link">Trending</a></li>
                <li class="nav-item"><a href="forums.php" class="nav-link">Forums</a></li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link">More ▾</a>
                    <div class="dropdown-content">
                        <a href="leaderboard.php">Leaderboard</a>
                        <a href="brands.php">Brands</a>
                        <a href="collections.php">Collections</a>
                    </div>
                </li>
            </ul>
        </nav>

        <!-- Search Bar -->
        <div class="search-bar">
            <form id="searchForm" method="GET" action="search_results.php" class="search-form">
                <input type="text" id="searchInput" name="query" 
                       class="search-input" 
                       placeholder="Search perfumes, notes, or perfumers..."
                       aria-label="Search">
                <button type="submit" class="search-button" aria-label="Search">
                    <svg class="search-icon" viewBox="0 0 24 24">
                        <path d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0 .41-.41.41-1.08 0-1.49L15.5 14zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                </button>
            </form>
        </div>

        <!-- User Auth Section -->
        <div class="user-auth">
            <?php if (isset($_SESSION["user_id"])): ?>
                <div class="profile-dropdown">
                    <img src="<?php echo htmlspecialchars($_SESSION['profile_picture'] ?? 'assets/default-avatar.png'); ?>" 
                         alt="Profile" 
                         class="profile-image">
                    <div class="profile-menu">
                        <a href="settings.php" class="menu-item"><i class="icon-settings"></i>Settings</a>
                        <a href="logout.php" class="menu-item logout"><i class="icon-logout"></i>Log Out</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="auth-buttons">
                    <a href="login.html" class="auth-button login">Sign In</a>
                    <a href="signup.html" class="auth-button signup">Join Free</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const header = document.querySelector('.site-header');

    // Toggle mobile menu
    mobileMenuToggle.addEventListener('click', function() {
        header.classList.toggle('nav-active');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        if (!header.contains(event.target)) {
            header.classList.remove('nav-active');
        }
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>
</body>
</html>