-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 15, 2016 at 01:53 PM
-- Server version: 5.6.28-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `socialsonic`
--

-- --------------------------------------------------------

--
-- Table structure for table `ss_categories`
--

CREATE TABLE IF NOT EXISTS `ss_categories` (
  `twscrapp_category_id` int(10) NOT NULL AUTO_INCREMENT,
  `twscrapp_category_name` varchar(250) NOT NULL,
  PRIMARY KEY (`twscrapp_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ss_categories`
--

INSERT INTO `ss_categories` (`twscrapp_category_id`, `twscrapp_category_name`) VALUES
(1, 'Marketing');

-- --------------------------------------------------------

--
-- Table structure for table `ss_customers`
--

CREATE TABLE IF NOT EXISTS `ss_customers` (
  `Cust_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cust_Email` varchar(255) NOT NULL,
  `Cust_Password` varchar(255) NOT NULL,
  `Cust_FirstName` varchar(255) NOT NULL,
  `Cust_LastName` varchar(255) NOT NULL,
  `Cust_UserName` varchar(255) NOT NULL,
  `Cust_API_Key` varchar(255) NOT NULL,
  `Cust_API_Secret` varchar(255) NOT NULL,
  `Cust_Twitter_ID` varchar(255) NOT NULL,
  `Cust_Screen_Name` varchar(255) NOT NULL,
  `Cust_Access_Token` longtext NOT NULL,
  `Cust_Token_Secret` longtext NOT NULL,
  `Cust_hopCode` varchar(255) NOT NULL,
  `Cust_Payment_Status` enum('yes','no') DEFAULT 'no',
  `Cust_Order_ID` varchar(255) NOT NULL,
  `Cust_Login_Status` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `Cust_Last_Login_Time` datetime NOT NULL,
  `Cust_RegisteredOn` datetime NOT NULL,
  PRIMARY KEY (`Cust_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ss_customers`
--

INSERT INTO `ss_customers` (`Cust_ID`, `Cust_Email`, `Cust_Password`, `Cust_FirstName`, `Cust_LastName`, `Cust_UserName`, `Cust_API_Key`, `Cust_API_Secret`, `Cust_Twitter_ID`, `Cust_Screen_Name`, `Cust_Access_Token`, `Cust_Token_Secret`, `Cust_hopCode`, `Cust_Payment_Status`, `Cust_Order_ID`, `Cust_Login_Status`, `Cust_Last_Login_Time`, `Cust_RegisteredOn`) VALUES
(1, 'surajit@wisely.co', 'e99e9d9f0886d883b8b28e8d48db136b4f52b1ff', 'Surajit', 'Pramanik', 'surajit', 'lZPubafcP3fEYT9RAkb0fs0LD', 'EF9i8wfXchTVYcWWWJurqPzDMCMOY6KRHRzoojt9zG0QTMTgXe', '381745512', 'JitDXpert', '381745512-NurKgeo8vzQ5C4QC485MJDXG5ry5vI47KoUkGXWV', 'KUhbBHIWXS2bnt9ztd2gi3Si5ek9cIXLBYoRjEreG0fi1', '151542', 'yes', '', 'YES', '2016-08-13 16:20:29', '2016-08-12 07:17:37'),
(2, 'sudip@wisely.co', 'c984aed014aec7623a54f0591da07a85fd4b762d', 'Sudip', 'Sen', 'sudipsen04', '8Oqi9YO9wAab3T4SI83rPu7NG', 'peoavLHT63m7zZZjydxkRpYxtu2JR30LL44thgy4IuzzfDt9pu', '730804266147811328', 'contacttosudip', '730804266147811328-HkoLCRBEEfAR6yJ3OpuIPgt7FTgvxKk', 'wT2BB4f6Q8XW47CIOcZPfeZt9pWEsNHHm9Oj4pZzlhsME', 'SS-01', 'yes', '987456', 'YES', '2016-08-14 09:26:22', '2016-07-29 14:27:42');

-- --------------------------------------------------------

--
-- Table structure for table `ss_direct_message`
--

CREATE TABLE IF NOT EXISTS `ss_direct_message` (
  `direct_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `direct_message` varchar(500) NOT NULL,
  PRIMARY KEY (`direct_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss_dm_status`
--

CREATE TABLE IF NOT EXISTS `ss_dm_status` (
  `check_message_id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `twitter_screen_id` varchar(50) NOT NULL,
  `message_status` int(10) NOT NULL,
  `message_created_time` datetime NOT NULL,
  PRIMARY KEY (`check_message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss_favourite`
--

CREATE TABLE IF NOT EXISTS `ss_favourite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) NOT NULL,
  `tweet_id` varchar(50) NOT NULL,
  `tweet_screen_name` varchar(250) NOT NULL,
  `fav_status` enum('pending','complete') NOT NULL DEFAULT 'pending',
  `tweet_favourite_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss_followers_cursor`
--

CREATE TABLE IF NOT EXISTS `ss_followers_cursor` (
  `Row_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cursor_ID` varchar(255) NOT NULL,
  `User_ID` varchar(255) NOT NULL,
  PRIMARY KEY (`Row_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ss_followers_cursor`
--

INSERT INTO `ss_followers_cursor` (`Row_ID`, `Cursor_ID`, `User_ID`) VALUES
(1, '1541305109206087249', '2');

-- --------------------------------------------------------

--
-- Table structure for table `ss_influencers`
--

CREATE TABLE IF NOT EXISTS `ss_influencers` (
  `influence_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `influence_category_id` int(11) NOT NULL,
  `influncer_twitter_id` varchar(255) NOT NULL,
  `influncer_twitter_screenname` varchar(255) NOT NULL,
  `influncer_twitter_bio` longtext NOT NULL,
  `influencer_website` varchar(255) NOT NULL,
  `influencer_profile_pic` longtext NOT NULL,
  `influncer_cursor_id` varchar(255) NOT NULL,
  PRIMARY KEY (`influence_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ss_influencers`
--

INSERT INTO `ss_influencers` (`influence_id`, `user_id`, `influence_category_id`, `influncer_twitter_id`, `influncer_twitter_screenname`, `influncer_twitter_bio`, `influencer_website`, `influencer_profile_pic`, `influncer_cursor_id`) VALUES
(1, 0, 1, '17820232', 'Spaulify', 'Founder & CEO, @playbookai | Passionate about Product Management & Digital Marketing |', 'http://t.co/8TlzmW8BQm', 'http://pbs.twimg.com/profile_images/623921824955396096/bagsdq2D_normal.jpg', '1540947707562519660');

-- --------------------------------------------------------

--
-- Table structure for table `ss_nurtureship`
--

CREATE TABLE IF NOT EXISTS `ss_nurtureship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prospect_id` int(11) NOT NULL,
  `twitter_user_id` varchar(250) NOT NULL,
  `twitter_user_name` varchar(450) NOT NULL,
  `user_id` varchar(250) NOT NULL,
  `relationship_status` enum('pending','confirm','oneside_confirm','unfollow') NOT NULL DEFAULT 'pending',
  `relationship_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss_products`
--

CREATE TABLE IF NOT EXISTS `ss_products` (
  `product_ID` int(11) NOT NULL AUTO_INCREMENT,
  `category_ID` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` longtext NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_url` varchar(255) NOT NULL,
  `product_created_time` datetime NOT NULL,
  PRIMARY KEY (`product_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss_product_category`
--

CREATE TABLE IF NOT EXISTS `ss_product_category` (
  `category_ID` int(11) NOT NULL AUTO_INCREMENT,
  `category_Name` varchar(255) NOT NULL,
  `category_Description` longtext NOT NULL,
  PRIMARY KEY (`category_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ss_product_category`
--

INSERT INTO `ss_product_category` (`category_ID`, `category_Name`, `category_Description`) VALUES
(1, 'Marketing', 'But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness.');

-- --------------------------------------------------------

--
-- Table structure for table `ss_prospects`
--

CREATE TABLE IF NOT EXISTS `ss_prospects` (
  `Prospect_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cust_ID` int(11) NOT NULL,
  `Talks_About` varchar(255) NOT NULL,
  `Category` int(11) NOT NULL,
  `Influencers` varchar(255) NOT NULL,
  `SearchOn` datetime NOT NULL,
  PRIMARY KEY (`Prospect_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ss_schedule_tweet`
--

CREATE TABLE IF NOT EXISTS `ss_schedule_tweet` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `twitter_user_id` varchar(50) NOT NULL,
  `twitter_user_screen_id` varchar(250) NOT NULL,
  `tweet_text` varchar(500) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `status` enum('pending','complete') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
