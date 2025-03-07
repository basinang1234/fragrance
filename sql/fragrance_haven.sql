-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2025 at 01:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fragrance_haven`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `parent_comment_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_comments`
--

INSERT INTO `forum_comments` (`id`, `post_id`, `user_id`, `parent_comment_id`, `content`, `image`, `created_at`) VALUES
(1, 1, 2, NULL, 'test test testsrtestsetset', NULL, '2025-03-07 12:02:48'),
(2, 3, 2, NULL, 'asdasdasd', NULL, '2025-03-07 12:04:04');

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perfume_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`id`, `user_id`, `perfume_id`, `title`, `content`, `image`, `created_at`) VALUES
(1, 2, NULL, 'test', 'test', 'uploads/Screenshot 2025-02-25 154708.png', '2025-03-06 21:13:37'),
(2, 2, NULL, 'test', 'test', 'uploads/Screenshot 2025-02-25 154708.png', '2025-03-06 21:13:45'),
(3, 2, NULL, 'testttt', 'testtts', 'uploads/445978893_1192873175406393_7499982469768553730_n_3b3e749b-6fc6-4382-8157-2e05aed99615.webp', '2025-03-07 11:11:50');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `message_text` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `perfumers`
--

CREATE TABLE `perfumers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `expertise` text DEFAULT NULL,
  `most_loved_perfume_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perfumers`
--

INSERT INTO `perfumers` (`id`, `name`, `tagline`, `expertise`, `most_loved_perfume_id`, `image`, `created_at`) VALUES
(1, 'Alberto Morillas', 'Master of Modern Fragrance', 'Floral and Citrus Notes', 2, 'uploads/alberto_morillas.jpg', '2025-03-06 20:59:13'),
(2, 'Francis Kurkdjian', 'Innovator of Luxury Scents', 'Oriental and Woody Accords', 3, 'uploads/francis_kurkdjian.jpg', '2025-03-06 20:59:13');

-- --------------------------------------------------------

--
-- Table structure for table `perfumes`
--

CREATE TABLE `perfumes` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `perfume_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `accords` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `fashion_styles` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `perfumer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `perfumes`
--

INSERT INTO `perfumes` (`id`, `brand_name`, `perfume_name`, `description`, `accords`, `notes`, `fashion_styles`, `image`, `created_at`, `perfumer_id`) VALUES
(2, 'Parfums Astraux', 'Yosi Break', 'na', 'na', 'na', 'na', 'na', '2025-03-06 18:57:54', 1),
(3, 'Parfums Astraux', 'Yosi Break', 'na', 'na', 'na', 'na', 'na', '2025-03-06 20:05:56', 2);

-- --------------------------------------------------------

--
-- Table structure for table `perfume_comparisons`
--

CREATE TABLE `perfume_comparisons` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perfume1_id` int(11) DEFAULT NULL,
  `perfume2_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `perfume3_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `content_type` enum('post','comment') DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `perfume_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `scent_impression` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `perfume_id`, `rating`, `review_text`, `scent_impression`, `created_at`) VALUES
(1, 2, 2, 1, 'asdasd', 'asdasdasd', '2025-03-06 20:10:30');

-- --------------------------------------------------------

--
-- Table structure for table `trending_perfumes`
--

CREATE TABLE `trending_perfumes` (
  `id` int(11) NOT NULL,
  `perfume_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','moderator','admin') DEFAULT 'user',
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `profile_picture`, `bio`, `created_at`) VALUES
(2, 'basinangedriel3@gmail.com', 'basinangedriel3@gmail.com', '$2y$10$GMKvyVnl7AOwpvqnlmXK3us7vNDOu9/172jKUCqe8oK7H5EAMYwuK', 'admin', 'uploads/67c9cd4e9871e_Screenshot 2025-02-25 154537.png', 'basinangedriel3@gmail.com admin', '2025-03-06 16:29:02'),
(3, 'rflubao@gmail.com', 'rflubao@gmail.com', '$2y$10$I4ogzJUC4P/sokle/Kcxn.xAmk0dNsVWWUBlUkIzNckbO49gc/DaS', 'moderator', 'uploads/67c9d0bb5af83_Screenshot 2025-02-25 154706.png', 'rflubao@gmail.comrflubao@gmail.com', '2025-03-06 16:43:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_follows`
--

CREATE TABLE `user_follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) DEFAULT NULL,
  `following_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` enum('post','comment') NOT NULL,
  `content_id` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL CHECK (`value` in (-1,1)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `content_type`, `content_id`, `value`, `created_at`) VALUES
(1, 2, 'post', 2, 1, '2025-03-06 21:14:59'),
(3, 2, 'post', 3, 1, '2025-03-07 11:13:27'),
(4, 2, 'post', 1, 1, '2025-03-07 11:38:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `parent_comment_id` (`parent_comment_id`),
  ADD KEY `fk_post` (`post_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perfume_id` (`perfume_id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `perfumers`
--
ALTER TABLE `perfumers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `most_loved_perfume_id` (`most_loved_perfume_id`);

--
-- Indexes for table `perfumes`
--
ALTER TABLE `perfumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_perfumer` (`perfumer_id`);

--
-- Indexes for table `perfume_comparisons`
--
ALTER TABLE `perfume_comparisons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `perfume1_id` (`perfume1_id`),
  ADD KEY `perfume2_id` (`perfume2_id`),
  ADD KEY `perfume3_id` (`perfume3_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reporter_id` (`reporter_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `perfume_id` (`perfume_id`);

--
-- Indexes for table `trending_perfumes`
--
ALTER TABLE `trending_perfumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `perfume_id` (`perfume_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follower_id` (`follower_id`),
  ADD KEY `following_id` (`following_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_vote` (`user_id`,`content_type`,`content_id`),
  ADD UNIQUE KEY `idx_unique_vote` (`user_id`,`content_type`,`content_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `perfumers`
--
ALTER TABLE `perfumers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `perfumes`
--
ALTER TABLE `perfumes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `perfume_comparisons`
--
ALTER TABLE `perfume_comparisons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `trending_perfumes`
--
ALTER TABLE `trending_perfumes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_follows`
--
ALTER TABLE `user_follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `fk_post` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_comments_ibfk_3` FOREIGN KEY (`parent_comment_id`) REFERENCES `forum_comments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `forum_posts_ibfk_2` FOREIGN KEY (`perfume_id`) REFERENCES `perfumes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `perfumers`
--
ALTER TABLE `perfumers`
  ADD CONSTRAINT `perfumers_ibfk_1` FOREIGN KEY (`most_loved_perfume_id`) REFERENCES `perfumes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `perfumes`
--
ALTER TABLE `perfumes`
  ADD CONSTRAINT `fk_perfumer` FOREIGN KEY (`perfumer_id`) REFERENCES `perfumers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `perfume_comparisons`
--
ALTER TABLE `perfume_comparisons`
  ADD CONSTRAINT `perfume_comparisons_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfume_comparisons_ibfk_2` FOREIGN KEY (`perfume1_id`) REFERENCES `perfumes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfume_comparisons_ibfk_3` FOREIGN KEY (`perfume2_id`) REFERENCES `perfumes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `perfume_comparisons_ibfk_4` FOREIGN KEY (`perfume3_id`) REFERENCES `perfumes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`perfume_id`) REFERENCES `perfumes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trending_perfumes`
--
ALTER TABLE `trending_perfumes`
  ADD CONSTRAINT `trending_perfumes_ibfk_1` FOREIGN KEY (`perfume_id`) REFERENCES `perfumes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_follows`
--
ALTER TABLE `user_follows`
  ADD CONSTRAINT `user_follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_follows_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
