-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2016 at 12:51 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 5.5.37

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `socialsonic`
--

-- --------------------------------------------------------

--
-- Table structure for table `ss_buzznews_blogs`
--

CREATE TABLE `ss_buzznews_blogs` (
  `Blog_ID` int(11) NOT NULL,
  `Blog_Title` varchar(255) NOT NULL,
  `Blog_Slug` varchar(255) NOT NULL,
  `Blog_Content` longtext NOT NULL,
  `Blog_Date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ss_buzznews_blogs`
--

INSERT INTO `ss_buzznews_blogs` (`Blog_ID`, `Blog_Title`, `Blog_Slug`, `Blog_Content`, `Blog_Date`) VALUES
(1, 'Life changing gift that made Johnny a successful entrepreneur', 'tailopez', '<p>A year back on this very day, I remember I was walking aimlessly back to my house carrying just $47 in my pocket after being rejected in an interview. As a young entrepreneur, my life had been a constant journey of wrong decisions. Unfortunately, after my graduation, I had spent most of my life in scarcity, trying to pull resources and build an empire out of my business strategies.</p><p>The few times when I did succeed the money went away paying the debts or meeting my daily needs. For every venture, I would take a loan from marketer and friend. By the time I celebrated by 27th birthday I was out of schemes and ideas. My friends suggested me to take up a job. They would say “Johnny, take up a job and maybe someday you can try your luck in business again”.  Since I owed a huge sum of money to my friends I decided to join a publishing house as a writer, doing what I thought I do the best.</p><p>The job kept me going and pulled me out of my debts, but the phase of scarcity was never over. I didn’t feel happy and very soon I realized my career was not growing either. A long string of deteriorating health kept me bed ridden and that was enough to make me broke again. The little saving that I had, vanished paying utility bills.</p><p>With no friends, no love and deteriorating health I had little prospect and hope left in life. It was then that I came across the 67 Steps formulated by Tai. I don’t remember which of my friend told me about this, but on 17th August, the day when I felt the most wretched thinking that it was my birthday and I had no reason to celebrate, I decided to do something different. I went to one of the nearby internet café and enrolled in this video course.</p>', '2016-08-18 05:14:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ss_buzznews_blogs`
--
ALTER TABLE `ss_buzznews_blogs`
  ADD PRIMARY KEY (`Blog_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ss_buzznews_blogs`
--
ALTER TABLE `ss_buzznews_blogs`
  MODIFY `Blog_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
