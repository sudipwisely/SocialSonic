-- phpMyAdmin SQL Dump
-- version 4.4.13.1
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2016 at 07:35 AM
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
-- Table structure for table `twscrapp_influence`
--

CREATE TABLE IF NOT EXISTS `twscrapp_influence` (
  `influence_id` int(10) NOT NULL,
  `influence_category_id` int(10) NOT NULL,
  `influncer_twitter_id` varchar(250) NOT NULL,
  `influncer_twitter_screenname` varchar(250) NOT NULL,
  `influncer_twitter_bio` varchar(2500) NOT NULL,
  `influencer_website` varchar(250) NOT NULL,
  `influencer_profile_pic` varchar(500) NOT NULL,
  `influncer_cursor_id` varchar(250) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `twscrapp_influence`
--

INSERT INTO `twscrapp_influence` (`influence_id`, `influence_category_id`, `influncer_twitter_id`, `influncer_twitter_screenname`, `influncer_twitter_bio`, `influencer_website`, `influencer_profile_pic`, `influncer_cursor_id`) VALUES
(1, 1, '5768872', 'garyvee', 'Family 1st! but after that, Businessman. CEO of @vaynermedia. Host of #AskGaryVee show and a dude who Loves the Hustle, the @NYJets .. snapchat - garyvee', 'https://t.co/jEiTgZmdt9', 'http://pbs.twimg.com/profile_images/698589176627863553/AmR3qEnk_normal.jpg', '1537029247332190097'),
(2, 1, '14788764', 'GrantCardone', 'Rated 10 Most Influential CEO''s in World NYT BestSelling #Author #Speaker #RealEstateInvestor #Husband #Father Creator of BizNetwork https://t.co/pAQNuu8ERo', 'https://t.co/rZuabawOAI', 'http://pbs.twimg.com/profile_images/720060725213937664/cI8XyWoR_normal.jpg', '1537025953070375148'),
(3, 1, '1322691', 'neilpatel', 'Entrepreneur, investor & influencer. Columnist for Forbes, Inc, Entrepreneur, Huffington Post & more. Founded @CrazyEgg with @hnshah.', 'https://t.co/ETTeIqvDIc', 'http://pbs.twimg.com/profile_images/598873990040391682/E6qH8p23_normal.jpg', '1537021649715667308');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `twscrapp_influence`
--
ALTER TABLE `twscrapp_influence`
  ADD PRIMARY KEY (`influence_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `twscrapp_influence`
--
ALTER TABLE `twscrapp_influence`
  MODIFY `influence_id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
