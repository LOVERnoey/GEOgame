-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 18, 2025 at 05:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geo_game`
--

-- --------------------------------------------------------

--
-- Table structure for table `gamecommu`
--

CREATE TABLE `gamecommu` (
  `game_id` bigint(20) NOT NULL,
  `gamename` varchar(255) NOT NULL,
  `gameprofile` varchar(255) DEFAULT NULL,
  `gamebg` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `gameavgdata` text DEFAULT NULL,
  `gameplaceforsale` text DEFAULT NULL,
  `guide_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gamecommu`
--

INSERT INTO `gamecommu` (`game_id`, `gamename`, `gameprofile`, `gamebg`, `user_id`, `gameavgdata`, `gameplaceforsale`, `guide_id`) VALUES
(1, 'Gris', '67b3065da5801.jpg', '67b3065da5921.gif', 1, 'Gris is a hopeful young girl lost in her own world, dealing with a painful experience in her life. Her journey through sorrow is manifested in her dress, which grants new abilities to better navigate her faded reality. As the story unfolds, Gris will grow emotionally and see her world in a different way, revealing new paths to explore using her new abilities.\r\n\r\nGRIS is a serene and evocative experience, free of danger, frustration or death. Players will explore a meticulously designed world brought to life with delicate art, detailed animation, and an elegant original score. Through the game light puzzles, platforming sequences, and optional skill-based challenges will reveal themselves as more of Gris’s world becomes accessible.\r\n\r\nGRIS is an experience with almost no text, only simple control reminders illustrated through universal icons. The game can be enjoyed by anyone regardless of their spoken language.', 'steam', NULL),
(2, 'bleach rebirth of souls', '67b311d9ee7f3.jpg', '67b311d9ee977.jpg', 1, 'ควย', 'หน้าบ้านกู', NULL),
(3, 'XONeverDraw', '67b31847f28f2.png', '67b31847f29ee.png', 1, 'แม่มึงเล่น xo กับกู', 'บ้านกูเท่านั้น', NULL),
(4, 'Dota 2', '67b349f7d0003.jpg', '67b349f7d00fa.jpg', 1, 'The most-played game on Steam.\r\nEvery day, millions of players worldwide enter battle as one of over a hundred Dota heroes. And no matter if it\'s their 10th hour of play or 1,000th, there\'s always something new to discover. With regular updates that ensure a constant evolution of gameplay, features, and heroes, Dota 2 has truly taken on a life of its own.\r\n\r\nOne Battlefield. Infinite Possibilities.\r\nWhen it comes to diversity of heroes, abilities, and powerful items, Dota boasts an endless array—no two games are the same. Any hero can fill multiple roles, and there\'s an abundance of items to help meet the needs of each game. Dota doesn\'t provide limitations on how to play, it empowers you to express your own style.\r\n\r\nAll heroes are free.\r\nCompetitive balance is Dota\'s crown jewel, and to ensure everyone is playing on an even field, the core content of the game—like the vast pool of heroes—is available to all players. Fans can collect cosmetics for heroes and fun add-ons for the world they inhabit, but everything you need to play is already included before you join your first match.\r\n\r\nBring your friends and party up.\r\nDota is deep, and constantly evolving, but it\'s never too late to join.\r\nLearn the ropes playing co-op vs. bots. Sharpen your skills in the hero demo mode. Jump into the behavior- and skill-based matchmaking system that ensures you\'ll\r\nbe matched with the right players each game.', 'Steam', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `guide`
--

CREATE TABLE `guide` (
  `guide_id` bigint(20) NOT NULL,
  `guidename` varchar(255) NOT NULL,
  `guideprofile` varchar(255) DEFAULT NULL,
  `guiderating` int(11) DEFAULT 0,
  `guideimage` varchar(255) DEFAULT NULL,
  `guidedescription` text DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `game_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guide`
--

INSERT INTO `guide` (`guide_id`, `guidename`, `guideprofile`, `guiderating`, `guideimage`, `guidedescription`, `user_id`, `game_id`) VALUES
(1, 'How to open game!!!!!!!!!!!!!', '67b34bfe1a384.jpg', 4, '67b34bfe1a48d.png', 'Do you wanna know how i got into these Game? A lot of Gamer don’t even know how to get into game So i’m Here to tell the \r\ntell from god that whisper in my ear Just Click “PLAY” Button\r\nThat’s it now you can enjoy you beloved game', 1, 1),
(2, 'How to open game!!!!!!!!!!!!!', '67b47992479ca.jpg', 0, '67b4799247af3.jpg', 'dddd', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image` varchar(250) NOT NULL,
  `county` varchar(100) NOT NULL,
  `user_email` varchar(300) NOT NULL,
  `bio` text NOT NULL,
  `x` varchar(255) NOT NULL,
  `facebook` varchar(255) NOT NULL,
  `instagram` varchar(255) NOT NULL,
  `youtube` varchar(255) NOT NULL,
  `background_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `user_name`, `password`, `date`, `image`, `county`, `user_email`, `bio`, `x`, `facebook`, `instagram`, `youtube`, `background_image`) VALUES
(1, 3392, 'bass2', '123456', '2025-02-18 12:10:01', '67b1fba5e28f5.gif', '', '', 'GEOgame เป็นเว็บไซต์ที่สร้างขึ้นเพื่อให้ผู้ใช้ได้สนุกกับเกมที่เกี่ยวข้องกับภูมิศาสตร์ พร้อมทั้งเป็นแพลตฟอร์มที่เปิดโอกาสให้ผู้ใช้สามารถจัดการโปรไฟล์ของตนเองได้อย่างอิสระ ไม่ว่าจะเป็นการแก้ไขข้อมูลส่วนตัว อัปโหลดรูปโปรไฟล์ และเพิ่มข้อมูลโซเชียลมีเดียต่างๆ\r\n\r\nเว็บนี้ถูกออกแบบมาให้ใช้งานง่าย มีอินเทอร์เฟซที่สะอาดตาและเป็นมิตรต่อผู้ใช้ รองรับทั้งเดสก์ท็อปและมือถือ เป้าหมายหลักของเราคือการสร้างชุมชนสำหรับคนที่รักการเล่นเกมเชิงการศึกษา รวมถึงให้ผู้ใช้สามารถเชื่อมต่อกับกันและกันผ่านแพลตฟอร์มโซเชียลต่างๆ\r\n\r\nGEOgame ไม่เพียงแต่เป็นแค่เกม แต่ยังเป็นพื้นที่สำหรับการเรียนรู้และพัฒนาทักษะด้านภูมิศาสตร์ไปพร้อมกับความสนุกอีกด้วย', 'L', 'Ratchakit Sriprapai', 'ratcha_skir', 'LOVERnoey', '67b201bcd7061.gif'),
(2, 220348657821, 'กอล์ฟ', '123456', '2025-02-11 13:58:43', '', '', '', '', '', '', '', '', NULL),
(3, 40348405639316575, 'bass', '123456', '2025-02-18 12:02:51', '67adf32a51e62.jpg', 'Thailand', '', '', '', '', '', '', '67b476eb7cf5f.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gamecommu`
--
ALTER TABLE `gamecommu`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `guide`
--
ALTER TABLE `guide`
  ADD PRIMARY KEY (`guide_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `gamecommu`
--
ALTER TABLE `gamecommu`
  MODIFY `game_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `guide`
--
ALTER TABLE `guide`
  MODIFY `guide_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gamecommu`
--
ALTER TABLE `gamecommu`
  ADD CONSTRAINT `gamecommu_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guide`
--
ALTER TABLE `guide`
  ADD CONSTRAINT `guide_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `guide_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `gamecommu` (`game_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
