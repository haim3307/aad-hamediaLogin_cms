-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2018 at 10:27 PM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `social_network`
--

-- --------------------------------------------------------

--
-- Table structure for table `followers`
--

CREATE TABLE `followers` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `followers`
--

INSERT INTO `followers` (`id`, `uid`, `fid`) VALUES
(37, 4, 5),
(38, 5, 6),
(39, 3, 6),
(40, 8, 9),
(41, 5, 9),
(42, 5, 10),
(43, 9, 5),
(45, 10, 5),
(52, 5, 13),
(53, 10, 13),
(54, 9, 13),
(56, 8, 13),
(135, 5, 5),
(136, 8, 5),
(137, 5, 17),
(138, 13, 17),
(139, 11, 17),
(140, 17, 5),
(141, 6, 3),
(142, 11, 3),
(143, 13, 3),
(144, 9, 3),
(145, 16, 3);

-- --------------------------------------------------------

--
-- Table structure for table `forget_tokens`
--

CREATE TABLE `forget_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(200) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `front_users`
--

CREATE TABLE `front_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `front_users`
--

INSERT INTO `front_users` (`id`, `name`, `password`, `email`, `profile_img`) VALUES
(3, 'haim3308', '$2y$10$WVM84m3PZS6nJOt9xWNBQeRUKHbItxpGC3bwQPBDwBOypSEFW4jzG', 'haim3307@gmail.com', '2018.01.21.11.24.30-laptop.png'),
(4, 'h1234567', '$2y$10$FSlpViS8aEJn3mSQ4HoMpe2dxxUPQqxQRJpugWTlY3EWL30X0MDTu', 'h1234567@gmail.com', NULL),
(5, 'liat3307', '$2y$10$Bg95cg8aGGR6bX.QYN02zOpN7C5e31ha1WVPYr27VeQOQwxBGuape', 'ha00007@gmail.com', '2018.02.03.22.28.40-laptop.png'),
(6, 'Yoni12345', '$2y$10$FSg49ecsqwL6WM8cD77TMe6T4z9aOwsJ0qvNpmcw0jvhTHnGN9aKO', 'Yoni12345@gmail.com', '2018.01.27.15.43.26-20161101_181034.jpg'),
(7, 'natan123', '$2y$10$jxkFAagcuDhniiTWwlK1R.ijHU8dYBKdlJloAjKI0ykFMTNTibtXm', 'natan123@gmail.com', '2018.02.03.15.39.46-Layer 9 copy@1X.png'),
(8, 'uri123456', '$2y$10$LqgeEhf2i37vpYpONmfrOuobjVyju01sNs4FEyXoFJGjP6ZBOFSk2', 'uri123456@gmail.com', '2018.02.09.15.15.57-news_channel2_600X600_fb.jpg'),
(9, 'yulia123456', '$2y$10$3OE7vKgMQ6H7HSVgI9acY.tRNTFUhczhrW/QYN9E2cbAa4hF68w0S', 'yulia123456@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(10, 'dsadasda', '$2y$10$dIM.hcq9oLc8aL.7ttkbyu592rC/HbTQz31j4UOIJ51EVy9iE8jV2', 'dsadasda@gmail.com', NULL),
(11, 'avi123456', '$2y$10$w/KHA75AHP5sg3Aur0U0teI4ryVbknEVjQCHgm9Q4djF9rTmiO4k2', 'avi123456@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(12, 'haim123', '$2y$10$o3S6NU1uDiNh8OefVL3mpuS5AotXXVEycKoQJJXttJ3evm47zdCSS', 'haim123@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(13, 'shmuel23', '$2y$10$n3ZlTxtqM6RFfgP88p9xT.toWT5fy.5j2zaSnWPXWoEKuPClcAzAy', 'shmuel23@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(14, 'yuda123456', '$2y$10$3OE7vKgMQ6H7HSVgI9acY.tRNTFUhczhrW/QYN9E2cbAa4hF68w0S', 'yuda123456@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(15, 'nini1234', '$2y$10$3OE7vKgMQ6H7HSVgI9acY.tRNTFUhczhrW/QYN9E2cbAa4hF68w0S', 'nini1234@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(16, 'yigal888', '$2y$10$3OE7vKgMQ6H7HSVgI9acY.tRNTFUhczhrW/QYN9E2cbAa4hF68w0S', 'yigal888@walla.com', '2018.02.09.15.23.16-facebook_no_profile_pic2-jpg.gif'),
(17, 'shlomi12345', '$2y$10$BoTzEAsUq/keXF0PN5JLtO2GA4bzIKMJqFrRvIRQsK0WBKPRrZqYu', 'shlomi12345@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `liked_posts`
--

CREATE TABLE `liked_posts` (
  `id` int(11) NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `liked_posts`
--

INSERT INTO `liked_posts` (`id`, `pid`, `uid`) VALUES
(29, 194, 5),
(32, 197, 5),
(38, 224, 5),
(39, 204, 5),
(40, 203, 5),
(41, 200, 5),
(42, 199, 5),
(43, 198, 5),
(54, 228, 5),
(56, 225, 5),
(57, 176, 5),
(58, 166, 5),
(61, 265, 5),
(66, 273, 5),
(67, 274, 5),
(69, 111, 5),
(70, 276, 5),
(75, 279, 5),
(87, 284, 5),
(131, 288, 7),
(135, 287, 7),
(136, 286, 7),
(137, 290, 7),
(138, 279, 7),
(139, 279, 3),
(140, 194, 3),
(141, 288, 3),
(142, 290, 3),
(150, 310, 7),
(167, 313, 7),
(168, 315, 7),
(169, 312, 7),
(170, 298, 7),
(182, 311, 5),
(188, 312, 5),
(193, 315, 5),
(195, 290, 5),
(196, 313, 5),
(197, 314, 5),
(199, 318, 5),
(200, 326, 5),
(201, 326, 8),
(202, 324, 8),
(203, 321, 8),
(204, 316, 8),
(205, 315, 8),
(206, 313, 8),
(207, 290, 8),
(208, 327, 8),
(209, 327, 9),
(210, 326, 9),
(211, 324, 9),
(212, 329, 9),
(213, 279, 9),
(214, 327, 10),
(215, 326, 10),
(216, 315, 10),
(217, 290, 10),
(218, 330, 5),
(221, 327, 5),
(222, 334, 5),
(223, 326, 11),
(224, 315, 11),
(225, 290, 11),
(226, 265, 11),
(227, 223, 11),
(228, 194, 11),
(229, 191, 11),
(230, 337, 12),
(231, 334, 12),
(232, 327, 12),
(233, 326, 12),
(234, 321, 12),
(235, 318, 12),
(236, 316, 12),
(237, 315, 12),
(238, 290, 12),
(239, 279, 12),
(240, 269, 12),
(241, 264, 12),
(243, 337, 13),
(244, 334, 13),
(246, 326, 13),
(247, 315, 13),
(248, 290, 13),
(249, 274, 13),
(250, 265, 13),
(251, 248, 13),
(252, 327, 13),
(253, 339, 13),
(255, 337, 5),
(256, 340, 5),
(259, 326, 17),
(260, 338, 17),
(262, 343, 17),
(263, 339, 17),
(264, 336, 17),
(265, 343, 5),
(266, 343, 3),
(267, 337, 3),
(268, 334, 3);

-- --------------------------------------------------------

--
-- Table structure for table `login_tokens`
--

CREATE TABLE `login_tokens` (
  `id` int(11) NOT NULL,
  `token` char(64) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `c_name` varchar(20) DEFAULT NULL,
  `last_used` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `login_tokens`
--

INSERT INTO `login_tokens` (`id`, `token`, `uid`, `c_name`, `last_used`) VALUES
(41, 'd1f7f258db2f80322aa3cf4049e2e1dd46eb5a79', 13, 'SNID', '2018-02-11 16:00:55'),
(51, '40a37b2df6736b5f4fdd0f7139d4fb3cbddd8121', 3, 'SNID', '2018-02-14 22:48:04');

-- --------------------------------------------------------

--
-- Table structure for table `posted`
--

CREATE TABLE `posted` (
  `id` int(11) NOT NULL,
  `to` int(11) DEFAULT NULL,
  `by` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posted`
--

INSERT INTO `posted` (`id`, `to`, `by`, `po_id`) VALUES
(1, 3, 5, 112),
(2, 6, 5, 113),
(3, 3, 6, 133),
(4, 5, 5, 243),
(5, 5, 5, 244),
(6, 5, 5, 245),
(7, 5, 5, 246),
(8, 5, 5, 247),
(9, 5, 5, 248),
(10, 5, 5, 249),
(11, 5, 5, 250),
(12, 5, 5, 251),
(13, 5, 5, 252),
(14, 5, 5, 253),
(15, 5, 5, 254),
(16, 5, 5, 255),
(17, 5, 5, 256),
(18, 5, 5, 257),
(19, 5, 5, 258),
(20, 5, 5, 259),
(21, 5, 5, 260),
(22, 5, 5, 261),
(23, 5, 5, 262),
(24, 5, 5, 263),
(25, 5, 5, 264),
(26, 5, 5, 265),
(27, 5, 5, 266),
(28, 5, 5, 267),
(29, 5, 5, 268),
(30, 5, 5, 269),
(31, 3, 5, 270),
(32, 3, 5, 271),
(33, 5, 5, 272),
(34, 5, 5, 273),
(35, 5, 5, 274),
(36, 5, 5, 275),
(37, 5, 5, 276),
(38, 5, 5, 277),
(39, 3, 5, 276),
(40, 3, 5, 277),
(41, 3, 5, 278),
(42, 3, 5, 279),
(43, 3, 5, 280),
(44, 3, 5, 281),
(45, 5, 5, 282),
(46, 7, 7, 291),
(47, 7, 7, 292),
(48, 7, 7, 293),
(49, 7, 7, 294),
(50, 7, 7, 295),
(51, 7, 7, 296),
(52, 7, 7, 297),
(53, 7, 7, 298),
(54, 7, 7, 299),
(55, 7, 7, 300),
(56, 7, 7, 305),
(57, 7, 7, 306),
(58, 7, 7, 307),
(59, 7, 7, 308),
(60, 7, 7, 309),
(61, 7, 7, 310),
(62, 7, 7, 311),
(63, 7, 7, 312),
(64, 7, 7, 313),
(65, 7, 7, 314),
(66, 7, 7, 315),
(67, 8, 8, 327),
(68, 9, 9, 328),
(69, 8, 9, 329),
(70, 5, 10, 330),
(71, 9, 5, 331),
(72, 13, 13, 340),
(73, 17, 17, 344),
(74, 17, 17, 345),
(75, 17, 17, 346),
(76, 17, 17, 347),
(77, 17, 17, 348),
(78, 17, 17, 349),
(79, 3, 3, 350);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `front_img` varchar(255) DEFAULT NULL,
  `activated` tinyint(4) DEFAULT '0',
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `uid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `front_img`, `activated`, `added_date`, `uid`) VALUES
(82, 'vvccccxvvcxvxc', '', 1, '2018-01-22 13:37:07', 3),
(83, 'cccccccccccccccccccccccccccccc', '', 1, '2018-01-22 13:37:46', 3),
(84, '11111111111111111111', '', 1, '2018-01-22 13:37:57', 3),
(85, 'ccccccccccccccccccccccccc', '', 1, '2018-01-22 13:49:06', 3),
(86, 'fdsfsdfds', '', 1, '2018-01-22 13:49:57', 3),
(87, 'vvvvv', '', 1, '2018-01-22 13:51:06', 3),
(88, 'ccccc', '', 1, '2018-01-22 13:51:27', 3),
(89, 'cccccc', '', 1, '2018-01-22 13:52:06', 3),
(90, 'cccccccc', '', 1, '2018-01-22 13:52:16', 3),
(91, 'cxzczxczxczxczxczx', '', 1, '2018-01-22 13:52:53', 3),
(92, 'ccccc', '', 1, '2018-01-22 13:53:13', 3),
(93, 'ccccc', '', 1, '2018-01-22 13:53:44', 3),
(94, 'cccccccccccccccc', '', 1, '2018-01-22 13:53:52', 3),
(95, 'ccccc', '', 1, '2018-01-22 13:54:35', 3),
(96, 'vcxvcxv', '', 1, '2018-01-22 13:56:40', 3),
(97, 'czxcxzc', '', 1, '2018-01-22 13:56:56', 3),
(98, 'cccc', '', 1, '2018-01-22 13:57:15', 3),
(99, '545465464565465', '', 1, '2018-01-22 13:57:37', 3),
(100, 'sadasdasdasdasdasd', '', 1, '2018-01-22 13:57:51', 3),
(101, 'cccccccccccccdsafdsfds', '', 1, '2018-01-22 13:59:08', 3),
(102, ' bbbbbbb', '', 1, '2018-01-22 13:59:35', 3),
(103, 'ytryrtytr', '', 1, '2018-01-22 13:59:50', 3),
(104, 'bbbbbbbbbbbbbbbbbbbbbbbb', '', 1, '2018-01-22 14:00:05', 3),
(105, 'ccccccc', '', 1, '2018-01-22 14:00:35', 3),
(106, 'ccccccccccccccccc', '', 1, '2018-01-22 14:00:52', 3),
(107, 'dfsfdsfdsf', '', 1, '2018-01-22 14:00:55', 3),
(108, 'vcxbvcbvc', '', 1, '2018-01-22 14:00:58', 3),
(109, 'vcxbvcbvcsadasdsa', '', 1, '2018-01-22 14:01:27', 3),
(110, 'sdsadasdas', '', 1, '2018-01-22 14:03:15', 3),
(111, 'sdsadasdasd', '', 1, '2018-01-22 14:03:20', 3),
(112, 'liafafdfdsf', '', 1, '2018-01-22 21:21:02', 5),
(113, 'bbbbbbbbbbbbbbb', '', 1, '2018-01-22 21:21:39', 5),
(114, 'cccccccccccccccccccccccc', '', 1, '2018-01-22 21:21:43', 5),
(115, 'ccccccccccccccccccccccxxxxxxxxxxxxx', '', 1, '2018-01-22 21:21:48', 5),
(116, 'NP', '', 1, '2018-01-22 21:21:54', 5),
(117, 'NP', '', 1, '2018-01-22 21:22:03', 5),
(118, 'NNPNPNPNP', '', 1, '2018-01-22 21:22:07', 5),
(119, 'NPNPNPNP', '', 1, '2018-01-22 21:22:11', 5),
(120, 'vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv', '', 1, '2018-01-22 21:23:43', 5),
(121, 'cccccccccccccccccccccc', '', 1, '2018-01-22 21:23:55', 5),
(122, '1', '', 1, '2018-01-22 21:24:10', 5),
(123, '2', '', 1, '2018-01-22 21:24:12', 5),
(124, '3', '', 1, '2018-01-22 21:24:18', 5),
(125, '4', '', 1, '2018-01-22 21:24:23', 5),
(126, '5', '', 1, '2018-01-22 21:24:26', 5),
(127, '6', '', 1, '2018-01-22 21:25:04', 5),
(128, '7', '', 1, '2018-01-22 21:25:06', 5),
(129, '8', '', 1, '2018-01-22 21:25:14', 5),
(130, '9', '', 1, '2018-01-22 21:25:41', 5),
(131, '10', '', 1, '2018-01-22 21:25:44', 5),
(132, '11', '', 1, '2018-01-22 21:25:46', 5),
(133, '12', '', 1, '2018-01-22 21:25:49', 5),
(134, '13', '', 1, '2018-01-22 21:25:51', 5),
(135, '14', '', 1, '2018-01-22 21:25:54', 5),
(136, '15', '', 1, '2018-01-22 21:25:56', 5),
(137, 'a', '', 1, '2018-01-22 21:27:37', 5),
(138, 'b', '', 1, '2018-01-22 21:27:41', 5),
(139, 'c', '', 1, '2018-01-22 21:27:44', 5),
(141, 'e', '', 1, '2018-01-22 21:27:49', 5),
(142, 'f', '', 1, '2018-01-22 21:27:52', 5),
(143, 'g', '', 1, '2018-01-22 21:27:55', 5),
(144, 'h', '', 1, '2018-01-22 21:27:57', 5),
(145, 'i', '', 1, '2018-01-22 21:28:00', 5),
(146, 'cccc', '', 1, '2018-01-26 17:32:12', 5),
(147, 'ccccc', '', 1, '2018-01-26 17:46:21', 5),
(148, 'cccccccc', '', 1, '2018-01-26 17:46:27', 5),
(149, 'ccccvbbbbbbb', '', 1, '2018-01-26 17:58:56', 5),
(152, 'xxxxx', '', 1, '2018-01-26 18:28:44', 5),
(153, 'xxxxxdsad', '', 1, '2018-01-26 18:28:47', 5),
(154, 'xxxxxdsad', '', 1, '2018-01-26 18:28:50', 5),
(155, 'cxzczxcxzc', '', 1, '2018-01-26 18:30:20', 5),
(156, 'dsadasdasd', '', 1, '2018-01-26 18:31:18', 5),
(157, 'cxzcvcxvxcv', '', 1, '2018-01-26 18:31:21', 5),
(158, 'ccccc', '', 1, '2018-01-26 19:06:46', 5),
(159, 'fvvvvvvvvvvvvvv', '', 1, '2018-01-26 19:08:33', 5),
(160, 'dsadasd', '', 1, '2018-01-26 19:09:14', 5),
(161, 'sdadas', '', 1, '2018-01-26 19:12:44', 5),
(162, 'dddd', '', 1, '2018-01-26 19:12:48', 5),
(163, 'dsadsad', '', 1, '2018-01-26 19:27:33', 5),
(164, 'gfdgdfgfdg', '', 1, '2018-01-26 19:27:48', 5),
(165, '\';l\'l;\'', '', 1, '2018-01-26 19:31:05', 5),
(166, 'sdfsdfsdf', '', 1, '2018-01-26 19:31:52', 5),
(167, 'lpk[k', '', 1, '2018-01-26 19:31:59', 5),
(168, 'khkyhkhg', '', 1, '2018-01-26 19:33:52', 5),
(169, 'l;kpm,k', '', 1, '2018-01-26 19:33:57', 5),
(170, 'ghjkgtigf', '', 1, '2018-01-26 19:34:50', 5),
(171, 'gifgjgu', '', 1, '2018-01-26 19:35:15', 5),
(172, 'ddddd', '', 1, '2018-01-26 19:37:14', 5),
(173, 'sadasdas', '', 1, '2018-01-26 19:37:29', 5),
(174, 'dsadsadas', '', 1, '2018-01-26 19:38:06', 5),
(176, 'dsadsa', '', 1, '2018-01-26 19:40:20', 5),
(177, 'sdadas', '', 1, '2018-01-26 19:41:58', 5),
(178, 'dsadasd', '', 1, '2018-01-26 19:42:09', 5),
(179, 'dasdasd', '', 1, '2018-01-26 19:42:14', 5),
(180, 'ccccccccccccccccccccccccccc', '', 1, '2018-01-26 19:42:18', 5),
(181, 'dsadsad', '', 1, '2018-01-26 19:42:36', 5),
(182, 'dsadasd', '', 1, '2018-01-26 19:42:55', 5),
(183, 'dsadsad', '', 1, '2018-01-26 19:43:00', 5),
(184, 'dsada', '', 1, '2018-01-26 19:43:10', 5),
(185, 'vvvvvvvvvvvvvvvvvvvvvvvvvvv', '', 1, '2018-01-26 19:43:15', 5),
(186, 'dsadsadasd', '', 1, '2018-01-26 19:43:27', 5),
(187, 'cccccccccccccccccccccc', '', 1, '2018-01-26 19:43:31', 5),
(188, 'dsadasd', '', 1, '2018-01-26 19:47:49', 5),
(189, 'dsadasdasdas', '', 1, '2018-01-26 19:47:52', 5),
(190, 'dsadasd', '', 1, '2018-01-26 19:47:56', 5),
(191, 'sadasdsad', '', 1, '2018-01-26 19:48:35', 5),
(192, 'dsadsa', '', 1, '2018-01-26 19:48:39', 5),
(193, 'dasda', '', 1, '2018-01-26 19:48:41', 5),
(194, 'dsadasd', '', 1, '2018-01-26 19:49:12', 5),
(195, 'dsadsadad', '', 1, '2018-01-26 19:49:33', 5),
(196, 'dsadasdsa', '', 1, '2018-01-26 19:50:23', 5),
(197, 'dsadasd', '', 1, '2018-01-26 19:51:30', 5),
(198, 'dsada', '', 1, '2018-01-26 19:54:49', 5),
(199, 'sadada', '', 1, '2018-01-26 19:55:05', 5),
(200, 'dsadadas', '', 1, '2018-01-26 19:55:32', 5),
(203, 'fdsfdsf', '', 1, '2018-01-26 19:56:16', 5),
(204, 'sdsadas', '', 1, '2018-01-26 19:56:20', 5),
(217, '\' OR 1=1', '', 1, '2018-01-26 20:26:04', 5),
(219, 'dsadas', '', 1, '2018-01-27 09:34:59', 5),
(220, '&lt;h1&gt; hi &lt;/h1&gt;', '', 1, '2018-01-27 09:35:49', 5),
(221, 'על נתן P', '', 1, '2018-01-27 15:01:51', 5),
(223, 'גנן גידל גנן בגן גנן גדול גדל בגן', '', 1, '2018-01-28 14:43:52', 6),
(224, 'מה קרה קרה משהו?', '', 1, '2018-01-28 14:45:13', 6),
(225, 'dsadasdccccccccccccccc', '', 1, '2018-01-28 15:20:56', 6),
(226, 'ccccccccccccccccc', '', 1, '2018-01-28 15:21:22', 6),
(227, 'vdfsdfsdf', '', 1, '2018-01-30 15:27:02', 5),
(228, 'gfdgdfg', '', 1, '2018-01-30 15:32:41', 5),
(229, 'dddddsadasdasd', '', 1, '2018-01-31 14:33:06', 5),
(230, 'ddddddddddddddxxxxx', '', 1, '2018-01-31 14:38:15', 5),
(231, 'ccccccccc', '', 1, '2018-01-31 14:48:14', 5),
(232, 'vcxvxcv', '', 1, '2018-01-31 14:49:09', 5),
(233, 'dsadas', '', 1, '2018-01-31 15:08:52', 5),
(234, 'cdfsfdsf', '', 1, '2018-01-31 15:11:39', 5),
(235, 'cxzczxc', '', 1, '2018-01-31 15:14:59', 5),
(236, 'cxzczxc', '', 1, '2018-01-31 15:15:07', 5),
(237, 'gfdgdfg', '', 1, '2018-01-31 15:20:09', 5),
(248, 'cccccccccccccccccccc', '', 1, '2018-01-31 15:41:40', 5),
(249, 'cccccccccccccccccccc', '', 1, '2018-01-31 15:42:11', 5),
(250, 'xzczxczxczxc', '', 1, '2018-01-31 15:42:28', 5),
(251, 'xzczxczxczxcccccccccccccccccccc', '', 1, '2018-01-31 15:42:32', 5),
(252, 'fdsfsd', '', 1, '2018-01-31 15:43:05', 5),
(258, 'fdsfsddfsdfdsad', '', 1, '2018-01-31 15:43:48', 5),
(262, 'fdsfsddfsdfdsad', '', 1, '2018-01-31 15:43:49', 5),
(263, 'fdsfsddfsdfdsad', '', 1, '2018-01-31 15:43:49', 5),
(264, 'dsadasd', '', 1, '2018-01-31 15:45:59', 5),
(265, 'dsad', '', 1, '2018-01-31 15:46:03', 5),
(266, 'ccccc', '', 1, '2018-01-31 15:46:05', 5),
(267, 'cxzczxczx', '', 1, '2018-01-31 15:46:08', 5),
(268, 'dsadas', '', 1, '2018-01-31 15:51:02', 5),
(269, 'bbbbbbbbbbbbbbbbbbbbbbbb', '', 1, '2018-01-31 16:35:28', 5),
(270, 'cxzczxczxc', '', 1, '2018-01-31 16:36:00', 5),
(271, 'gfdgdfgdfgdfg', '', 1, '2018-01-31 16:36:24', 5),
(272, 'ccxzcxzc', '', 1, '2018-01-31 16:44:52', 5),
(273, 'dsads', '', 1, '2018-01-31 16:49:43', 5),
(274, 'dsad', '', 1, '2018-01-31 16:49:47', 5),
(275, 'fdsfsdf', '', 1, '2018-01-31 16:49:58', 5),
(276, 'ddsadasd', '', 1, '2018-02-02 16:06:35', 5),
(277, '&lt;script&gt;M&gt;script&gt;', '', 1, '2018-02-02 16:06:57', 5),
(278, 'alert(123)', '', 1, '2018-02-02 16:11:04', 5),
(279, 'what\'s up', '', 1, '2018-02-02 16:13:23', 5),
(280, 'dsadasd', '', 1, '2018-02-02 16:22:59', 5),
(281, 'cxzczxc', '', 1, '2018-02-02 16:23:11', 5),
(282, 'gfg', '', 1, '2018-02-02 17:21:45', 5),
(283, 'hgfhfg', '', 1, '2018-02-02 17:26:56', 5),
(284, 'gfdg', '', 1, '2018-02-02 17:53:33', 5),
(286, 'fdgdfg', '', 1, '2018-02-02 18:40:03', 5),
(287, 'fdgdfg', '', 1, '2018-02-02 18:40:05', 5),
(288, 'dfdsf', '', 1, '2018-02-02 18:41:32', 5),
(290, 'fdsfdsf', '', 1, '2018-02-02 18:50:52', 5),
(291, 'הפוסט של נתן פ', '', 1, '2018-02-03 16:12:49', 7),
(292, 'הפוסט של נתן פ 1', '', 1, '2018-02-03 16:14:25', 7),
(298, 'dsadasdas', '', 1, '2018-02-03 16:22:52', 7),
(299, 'dsadasd', '', 1, '2018-02-03 16:23:56', 7),
(310, 'dsadasd', '', 1, '2018-02-03 16:38:32', 7),
(311, 'dfsfsdfsdfds', '', 1, '2018-02-03 16:39:03', 7),
(312, 'hgfhgfh', '', 1, '2018-02-03 16:40:34', 7),
(313, 'dsasdasdassdcccccccccccccccccccccc', '', 1, '2018-02-03 16:41:04', 7),
(314, 'dsadasd', '', 1, '2018-02-03 16:41:26', 7),
(315, 'cxzczxc', '', 1, '2018-02-03 16:43:40', 7),
(316, 'דגן דיגן דגן בגן ', '', 1, '2018-02-04 14:48:04', 5),
(317, 'בבבבבבבבבב\nבבבבבבבב', '', 1, '2018-02-05 16:44:59', 5),
(318, 'dsadasdadccc', '', 1, '2018-02-05 16:49:53', 5),
(319, 'vcxvxcvx', '', 1, '2018-02-07 21:10:12', 5),
(320, 'fdsfdsdsf', '', 1, '2018-02-08 16:39:30', 5),
(321, 'test', '', 1, '2018-02-08 16:41:32', 5),
(322, 'test1', '', 1, '2018-02-08 17:32:05', 5),
(323, 'test2', '', 1, '2018-02-08 17:33:57', 5),
(324, 'test3', '', 1, '2018-02-08 17:34:39', 5),
(326, 'last test1', '', 1, '2018-02-08 17:42:46', 5),
(327, 'פוסט ראשון של אורי', '', 1, '2018-02-09 14:19:00', 8),
(328, 'נח נחמ נחמן מאומן', '', 1, '2018-02-09 14:34:53', 9),
(329, 'שלום אורי אני מציקה', '', 1, '2018-02-09 14:35:09', 9),
(330, 'היי ליאת', '', 1, '2018-02-09 15:42:35', 10),
(331, 'את רוסייה?', '', 1, '2018-02-09 18:03:33', 5),
(332, 'בלוג', '', 1, '2018-02-09 22:19:43', 5),
(333, 'היום יום שמח', '', 1, '2018-02-09 22:26:43', 5),
(334, 'מחר גם', '', 1, '2018-02-10 13:00:12', 5),
(335, 'בדיקה\nבדיקה', '', 1, '2018-02-10 20:06:22', 5),
(336, 'test br\ntest', '', 1, '2018-02-10 20:10:05', 5),
(337, 'dsadas\ndsadasd', '', 1, '2018-02-10 20:11:29', 5),
(338, 'אני חדש', '', 1, '2018-02-11 12:50:58', 11),
(339, 'היי כולם', '', 1, '2018-02-11 13:55:31', 12),
(340, 'אני שמואל', '', 1, '2018-02-11 15:05:27', 13),
(343, 'cccc', '', 1, '2018-02-12 18:58:12', 5),
(350, 'אהלן,\nנראה לי שזה מספיק לבנתיים', '', 1, '2018-02-14 16:50:15', 3);

-- --------------------------------------------------------

--
-- Table structure for table `posts_comments`
--

CREATE TABLE `posts_comments` (
  `id` int(11) NOT NULL,
  `content` mediumtext NOT NULL,
  `pid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts_comments`
--

INSERT INTO `posts_comments` (`id`, `content`, `pid`, `uid`, `added_date`) VALUES
(1, 'my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment my comment ', 316, 5, '2018-02-03 14:11:31'),
(2, 'התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי התגובה שלי ', 316, 6, '2018-02-02 07:31:00'),
(3, 'dsadasdasdsa', 315, 5, '2018-02-06 15:12:57'),
(4, 'dsadsad', 316, 5, '2018-02-06 15:13:32'),
(5, 'fdsfsdf', 314, 5, '2018-02-06 15:15:35'),
(6, 'גדכדגכ', 316, 5, '2018-02-04 15:17:08'),
(7, 'dsadas', 315, 5, '2018-02-06 15:24:41'),
(8, 'הייי', 318, 5, '2018-02-06 15:35:19'),
(9, 'ביי', 318, 5, '2018-02-03 17:14:10'),
(10, 'בוקר טוב', 318, 5, '2018-02-06 17:16:03'),
(11, 'ccccccc', 317, 5, '2018-02-06 17:16:44'),
(12, 'ccccc', 317, 5, '2018-02-06 17:16:54'),
(13, 'vvvvvv', 317, 5, '2018-02-06 17:17:32'),
(14, 'ccccc', 318, 5, '2018-02-06 17:17:50'),
(15, 'cxzcxzc', 318, 5, '2018-02-04 17:35:07'),
(16, 'היי', 314, 5, '2018-02-06 17:48:08'),
(17, 'היי', 314, 5, '2018-02-05 17:48:21'),
(18, 'dsadasd', 318, 5, '2018-02-07 23:10:33'),
(19, 'dsadas', 316, 5, '2018-02-08 16:08:42'),
(20, 'dsadasdsadas', 316, 5, '2018-02-08 16:08:44'),
(21, 'תגובה חדשה', 315, 5, '2018-02-08 18:34:49'),
(22, '1', 320, 5, '2018-02-08 18:41:00'),
(23, '1', 320, 5, '2018-02-08 18:41:00'),
(24, '1', 320, 5, '2018-02-08 18:41:00'),
(25, '1', 321, 5, '2018-02-08 18:41:40'),
(26, '2', 321, 5, '2018-02-08 18:41:45'),
(27, '3', 321, 5, '2018-02-08 18:41:48'),
(28, '4', 321, 5, '2018-02-08 18:41:49'),
(29, '5', 321, 5, '2018-02-08 18:41:52'),
(30, '6', 321, 5, '2018-02-08 18:42:04'),
(31, '7', 322, 5, '2018-02-08 19:32:10'),
(32, '76', 322, 5, '2018-02-08 19:32:12'),
(33, '5', 322, 5, '2018-02-08 19:32:17'),
(34, '4', 322, 5, '2018-02-08 19:32:19'),
(35, '3', 322, 5, '2018-02-08 19:32:22'),
(36, '2', 322, 5, '2018-02-08 19:32:27'),
(37, '1', 322, 5, '2018-02-08 19:32:29'),
(38, 'dddd', 323, 5, '2018-02-08 19:34:26'),
(39, '1', 324, 5, '2018-02-08 19:34:46'),
(40, '2', 324, 5, '2018-02-08 19:34:48'),
(41, '3', 324, 5, '2018-02-08 19:34:50'),
(42, '4', 324, 5, '2018-02-08 19:35:00'),
(45, '1', 326, 5, '2018-02-08 19:42:54'),
(46, '2', 326, 5, '2018-02-08 19:42:57'),
(47, '3', 326, 5, '2018-02-08 19:42:58'),
(48, '4', 326, 5, '2018-02-08 19:43:00'),
(49, '5', 326, 5, '2018-02-08 19:43:02'),
(50, '6', 326, 5, '2018-02-08 19:43:03'),
(51, '7', 326, 5, '2018-02-08 19:43:05'),
(52, '8', 326, 5, '2018-02-08 19:57:47'),
(53, '9', 326, 5, '2018-02-08 20:00:11'),
(54, '10', 326, 5, '2018-02-08 20:00:31'),
(55, '11', 326, 5, '2018-02-08 20:07:33'),
(56, '12', 326, 5, '2018-02-08 20:07:44'),
(57, '13', 326, 5, '2018-02-08 20:12:03'),
(58, '14', 326, 5, '2018-02-08 20:12:06'),
(59, '15', 326, 5, '2018-02-08 20:14:45'),
(60, '16~c', 326, 5, '2018-02-08 20:14:52'),
(61, '17', 326, 5, '2018-02-08 20:14:55'),
(62, '18', 326, 5, '2018-02-08 20:16:21'),
(63, '19ccc\nalert(123)', 326, 5, '2018-02-08 20:16:48'),
(64, '20', 326, 5, '2018-02-08 20:17:02'),
(65, '21', 326, 8, '2018-02-09 16:19:38'),
(66, 'היי אורי', 327, 9, '2018-02-09 16:33:34'),
(69, 'dsadasdasd', 328, 5, '2018-02-09 20:45:54'),
(70, 'הי חבוב', 330, 5, '2018-02-09 21:56:48'),
(71, 'nice', 332, 5, '2018-02-10 00:21:07'),
(72, 'יפה', 334, 5, '2018-02-10 15:09:58'),
(73, 'hi', 291, 5, '2018-02-10 18:49:30'),
(74, 'WTF', 275, 5, '2018-02-10 18:56:04'),
(75, 'yers\nwhoo!', 337, 5, '2018-02-10 22:12:45'),
(76, 'test ', 337, 5, '2018-02-10 22:13:00'),
(77, 'test\ntest2', 337, 5, '2018-02-10 22:13:10'),
(78, 'jjjjj', 337, 5, '2018-02-10 22:28:30'),
(79, 'kkkkk', 337, 5, '2018-02-10 22:30:55'),
(80, 'כן', 185, 11, '2018-02-11 14:52:30'),
(81, 'היי אבי', 338, 12, '2018-02-11 15:55:43'),
(82, 'hi', 339, 13, '2018-02-11 15:59:31'),
(83, 'ffff', 339, 13, '2018-02-11 18:07:15'),
(84, 'היי', 337, 17, '2018-02-13 18:08:30'),
(87, 'היי 2', 337, 17, '2018-02-13 18:09:06'),
(88, 'bye', 337, 17, '2018-02-13 18:12:00'),
(89, 'hi', 339, 3, '2018-02-14 18:49:31'),
(90, 'מגניב', 334, 3, '2018-02-14 18:49:44');

-- --------------------------------------------------------

--
-- Table structure for table `profiles_info`
--

CREATE TABLE `profiles_info` (
  `uid` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `birth_date` datetime DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `sex` varchar(100) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles_info`
--

INSERT INTO `profiles_info` (`uid`, `first_name`, `last_name`, `birth_date`, `city`, `sex`, `description`) VALUES
(5, 'ליאת', 'טרגנו', '1993-09-01 00:00:00', 'בת ים', 'female', 'אני ליאת לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית מוסן מנת. להאמית קרהשק סכעיט דז מא, מנכם למטכין נשואי מנורך. קולהע צופעט למרקוח איבן איף, ברומץ כלרשט מיחוצים. קלאצי סחטיר בלובק. תצטנפל בלינדו למרקל אס לכימפו, דול, צוט ומעיוט - לפתיעם ברשג - ולתיעם גדדיש. קוויז דומור ליאמום בלינך רוגצה. לפמעט מוסן מנת. לורם איפסום דולור סיט אמט, קונסקטורר אדיפיסינג אלית. סת אלמנקום ניסי נון ניבאה. דס איאקוליס וולופטה דיאם. וסטיבולום אט דולור, קראס אגת לקטוס וואל אאוגו וסטיבולום סוליסי טידום בעליק. קו'),
(6, 'יוני', 'לוי', '0000-00-00 00:00:00', 'ראשון', 'unset', ''),
(7, 'נתן', 'נתלין', '0000-00-00 00:00:00', '', 'male', '\r\n'),
(8, 'חיים', 'טרגנו', '0000-00-00 00:00:00', 'בת ים ', 'male', '\r\n               dsadsadasdasd '),
(9, 'יוליה', 'שוורנגר', '0000-00-00 00:00:00', 'כפר סבא', 'female', 'לורם איפסום דולור סיט אמט'),
(10, 'tali', 'fdsf', '0000-00-00 00:00:00', 'בת ים ', 'female', '\r\n                '),
(17, 'שלומי', 'להב', '1978-01-01 00:00:00', 'לא ידוע', 'male', 'הפרופיל של שלומי להב בתקווה שהוא יעדכן לפרטים יותר נכונים:)');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `type`) VALUES
(1, 'admin'),
(2, 'editor'),
(3, 'reporter');

-- --------------------------------------------------------

--
-- Table structure for table `users_roles`
--

CREATE TABLE `users_roles` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `rid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `followers`
--
ALTER TABLE `followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forget_tokens`
--
ALTER TABLE `forget_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `forget_tokens_token_uindex` (`token`);

--
-- Indexes for table `front_users`
--
ALTER TABLE `front_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `front_users_name_uindex` (`name`),
  ADD UNIQUE KEY `front_users_email_uindex` (`email`);

--
-- Indexes for table `liked_posts`
--
ALTER TABLE `liked_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liked_posts_posts_id_fk` (`pid`),
  ADD KEY `liked_posts_front_users_id_fk` (`uid`);

--
-- Indexes for table `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_tokens_token_uindex` (`token`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `posted`
--
ALTER TABLE `posted`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_ibfk_2` (`uid`);

--
-- Indexes for table `posts_comments`
--
ALTER TABLE `posts_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_comments_users_id_fk` (`uid`),
  ADD KEY `posts_comments_posts_id_fk` (`pid`);

--
-- Indexes for table `profiles_info`
--
ALTER TABLE `profiles_info`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_roles`
--
ALTER TABLE `users_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `followers`
--
ALTER TABLE `followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `forget_tokens`
--
ALTER TABLE `forget_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `front_users`
--
ALTER TABLE `front_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `liked_posts`
--
ALTER TABLE `liked_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=269;

--
-- AUTO_INCREMENT for table `login_tokens`
--
ALTER TABLE `login_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `posted`
--
ALTER TABLE `posted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=351;

--
-- AUTO_INCREMENT for table `posts_comments`
--
ALTER TABLE `posts_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_roles`
--
ALTER TABLE `users_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `liked_posts`
--
ALTER TABLE `liked_posts`
  ADD CONSTRAINT `liked_posts_front_users_id_fk` FOREIGN KEY (`uid`) REFERENCES `front_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `liked_posts_posts_id_fk` FOREIGN KEY (`pid`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `login_tokens`
--
ALTER TABLE `login_tokens`
  ADD CONSTRAINT `login_tokens_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `front_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`uid`) REFERENCES `front_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `posts_comments`
--
ALTER TABLE `posts_comments`
  ADD CONSTRAINT `posts_comments_posts_id_fk` FOREIGN KEY (`pid`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `profiles_info`
--
ALTER TABLE `profiles_info`
  ADD CONSTRAINT `profiles_info_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `front_users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
