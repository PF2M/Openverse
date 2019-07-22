SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE `blocks` (
  `block_id` int(11) NOT NULL,
  `block_to` int(11) NOT NULL,
  `block_by` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `communities` (
  `community_id` int(11) NOT NULL,
  `community_title` int(11) NOT NULL,
  `community_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `community_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `community_icon` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `community_banner` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `community_platform` int(1) NOT NULL COMMENT '0 = 3DS, 1 = Wii U, 2 = 3DS/Wii U, 3 or more = N/A',
  `community_type` int(1) NOT NULL,
  `community_perms` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_unicode_520_ci;

CREATE TABLE `empathies` (
  `yeah_id` int(11) NOT NULL,
  `yeah_type` int(1) NOT NULL,
  `yeah_to` int(11) NOT NULL,
  `yeah_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `favorites` (
  `favorite_id` int(11) NOT NULL,
  `favorite_by` int(11) NOT NULL,
  `favorite_to` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `follows` (
  `follow_id` int(11) NOT NULL,
  `follow_to` int(11) NOT NULL,
  `follow_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `friendships` (
  `friend_id` int(11) NOT NULL,
  `friend_date` datetime NOT NULL,
  `friend_to` int(11) NOT NULL,
  `friend_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `friend_requests` (
  `request_id` int(11) NOT NULL,
  `request_to` int(11) NOT NULL,
  `request_by` int(11) NOT NULL,
  `request_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `login_tokens` (
  `token_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `token_for` int(11) NOT NULL,
  `token_created` datetime NOT NULL DEFAULT current_timestamp(),
  `token_status` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `message_to` int(11) NOT NULL,
  `message_by` int(11) NOT NULL,
  `message_feeling_id` int(1) NOT NULL,
  `message_content` text NOT NULL,
  `message_is_spoiler` int(1) NOT NULL,
  `message_screenshot` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `notifications` (
  `notif_id` int(11) NOT NULL,
  `notif_type` int(1) NOT NULL,
  `notif_to` int(11) NOT NULL,
  `notif_by` int(11) NOT NULL,
  `notif_topic` int(11) NOT NULL,
  `notif_date` datetime NOT NULL,
  `notif_read` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `post_feeling_id` int(1) NOT NULL,
  `post_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `post_screenshot` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `post_drawing` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `post_url` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `post_is_spoiler` int(1) DEFAULT 0,
  `post_has_title_depended_value` int(1) NOT NULL,
  `post_date` datetime NOT NULL,
  `post_community` int(11) NOT NULL,
  `post_by` int(11) NOT NULL,
  `post_status` int(1) NOT NULL,
  `post_edited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `post_reports` (
  `report_id` int(11) NOT NULL,
  `report_to` int(11) NOT NULL,
  `report_by` int(11) NOT NULL,
  `report_type` int(1) NOT NULL,
  `report_body` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_520_ci NOT NULL,
  `report_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE `post_yeahs` (
  `yeah_id` int(11) NOT NULL,
  `yeah_post` int(11) NOT NULL,
  `yeah_by` int(11) NOT NULL,
  `yeah_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `reply_to` int(11) NOT NULL,
  `reply_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `reply_feeling_id` int(1) NOT NULL,
  `reply_screenshot` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `reply_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `reply_date` datetime NOT NULL,
  `reply_is_spoiler` int(1) NOT NULL,
  `reply_by` int(11) NOT NULL,
  `reply_status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `reply_reports` (
  `rreport_id` int(11) NOT NULL,
  `rreport_to` int(11) NOT NULL,
  `rreport_by` int(11) NOT NULL,
  `rreport_type` int(1) NOT NULL,
  `rreport_body` tinytext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `rreport_date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

CREATE TABLE `reply_yeahs` (
  `ryeah_id` int(11) NOT NULL,
  `ryeah_reply` int(11) NOT NULL,
  `ryeah_by` int(11) NOT NULL,
  `ryeah_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `titles` (
  `title_id` int(11) NOT NULL,
  `title_type` int(1) NOT NULL,
  `title_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `title_icon` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `title_banner` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `title_platform` int(1) NOT NULL COMMENT '0 = 3DS, 1 = Wii U, 2 = 3DS/Wii U, 3 or more = N/A'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

CREATE TABLE `users` (
  `user_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `user_pid` int(11) NOT NULL,
  `user_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_date` datetime NOT NULL,
  `user_rank` int(1) NOT NULL DEFAULT 0,
  `user_avatar` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_profile_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_country` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_birthday` date NOT NULL,
  `user_website` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `user_skill` int(1) NOT NULL DEFAULT 0,
  `user_systems` int(1) NOT NULL DEFAULT 0,
  `user_favorite_post` int(11) NOT NULL,
  `user_favorite_post_type` int(1) NOT NULL,
  `user_nnid` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `user_ip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `user_code` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `user_email_confirmed` int(1) NOT NULL,
  `user_relationship_visibility` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `blocks`
  ADD PRIMARY KEY (`block_id`);

ALTER TABLE `communities`
  ADD PRIMARY KEY (`community_id`);

ALTER TABLE `empathies`
  ADD PRIMARY KEY (`yeah_id`);

ALTER TABLE `favorites`
  ADD PRIMARY KEY (`favorite_id`);

ALTER TABLE `follows`
  ADD PRIMARY KEY (`follow_id`);

ALTER TABLE `friendships`
  ADD PRIMARY KEY (`friend_id`);

ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`request_id`);

ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`token_id`),
  ADD KEY `token_for` (`token_for`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`);

ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notif_id`);

ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

ALTER TABLE `post_reports`
  ADD PRIMARY KEY (`report_id`);

ALTER TABLE `post_yeahs`
  ADD PRIMARY KEY (`yeah_id`);

ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`);

ALTER TABLE `reply_reports`
  ADD PRIMARY KEY (`rreport_id`);

ALTER TABLE `reply_yeahs`
  ADD PRIMARY KEY (`ryeah_id`);

ALTER TABLE `titles`
  ADD PRIMARY KEY (`title_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_pid`),
  ADD UNIQUE KEY `user_pid` (`user_pid`),
  ADD UNIQUE KEY `user_id` (`user_id`);

ALTER TABLE `blocks`
  MODIFY `block_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `communities`
  MODIFY `community_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `empathies`
  MODIFY `yeah_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `favorites`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `follows`
  MODIFY `follow_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `friendships`
  MODIFY `friend_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `friend_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `notifications`
  MODIFY `notif_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `post_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `post_yeahs`
  MODIFY `yeah_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `reply_reports`
  MODIFY `rreport_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `reply_yeahs`
  MODIFY `ryeah_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `titles`
  MODIFY `title_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `user_pid` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;