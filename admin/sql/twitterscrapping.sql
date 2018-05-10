-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2016 at 04:10 AM
-- Server version: 5.6.26
-- PHP Version: 5.5.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `twitterscrapping`
--

-- --------------------------------------------------------

--
-- Table structure for table `twscrapp_category`
--

CREATE TABLE IF NOT EXISTS `twscrapp_category` (
  `twscrapp_category_id` int(10) NOT NULL,
  `twscrapp_category_name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `twscrapp_infuence`
--

CREATE TABLE IF NOT EXISTS `twscrapp_infuence` (
  `infuence_id` int(10) NOT NULL,
  `infuence_category_id` int(10) NOT NULL,
  `influncer_twitter_id` varchar(250) NOT NULL,
  `influncer_twitter_screenname` varchar(250) NOT NULL,
  `influncer_cursor_id` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `twscrapp_user`
--

CREATE TABLE IF NOT EXISTS `twscrapp_user` (
  `user_id` int(10) NOT NULL,
  `user_twitter_oauth_token` varchar(250) NOT NULL,
  `user_twitter_oauth_token_secret` varchar(250) NOT NULL,
  `user_twitter_id` varchar(50) NOT NULL,
  `user_twitter_screenname` varchar(250) NOT NULL,
  `user_twitter_name` varchar(250) NOT NULL,
  `user_twitter_oauth_id` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `twscrapp_category`
--
ALTER TABLE `twscrapp_category`
  ADD PRIMARY KEY (`twscrapp_category_id`);

--
-- Indexes for table `twscrapp_infuence`
--
ALTER TABLE `twscrapp_infuence`
  ADD PRIMARY KEY (`infuence_id`);

--
-- Indexes for table `twscrapp_user`
--
ALTER TABLE `twscrapp_user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `twscrapp_category`
--
ALTER TABLE `twscrapp_category`
  MODIFY `twscrapp_category_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `twscrapp_infuence`
--
ALTER TABLE `twscrapp_infuence`
  MODIFY `infuence_id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `twscrapp_user`
--
ALTER TABLE `twscrapp_user`
  MODIFY `user_id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
