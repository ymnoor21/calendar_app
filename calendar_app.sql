-- phpMyAdmin SQL Dump
-- version 4.2.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 27, 2014 at 06:15 AM
-- Server version: 5.6.21
-- PHP Version: 5.5.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `calendar_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notes`
--

CREATE TABLE IF NOT EXISTS `tbl_notes` (
`note_id` int(11) NOT NULL,
  `note_uid` int(11) NOT NULL,
  `note_text` text NOT NULL,
  `note_date` date NOT NULL,
  `note_mdate` datetime NOT NULL,
  `note_cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_notes`
--

INSERT INTO `tbl_notes` (`note_id`, `note_uid`, `note_text`, `note_date`, `note_mdate`, `note_cdate`) VALUES
(19, 1, 'Temporary', '2014-10-10', '2014-10-26 21:48:39', '2014-10-27 04:48:39'),
(4, 1, 'pp1', '2014-10-10', '2014-10-26 19:46:55', '2014-10-27 02:46:55'),
(5, 1, 'Test1, test 2, test3, test 4', '2014-10-10', '2014-10-26 19:47:04', '2014-10-27 02:57:38'),
(22, 1, 'New one', '2014-10-10', '2014-10-26 22:36:54', '2014-10-27 05:36:54'),
(20, 1, 'T5 Note', '2014-10-08', '2014-10-26 22:23:46', '2014-10-27 05:23:46'),
(18, 1, 'T4 Note', '2014-10-08', '2014-10-26 22:23:52', '2014-10-27 05:23:52'),
(10, 1, 'T1 Note', '2014-10-08', '2014-10-26 22:31:07', '2014-10-27 05:31:07'),
(17, 1, 'T3 Note', '2014-10-08', '2014-10-26 22:23:59', '2014-10-27 05:23:59'),
(21, 1, 'T6 Note', '2014-10-08', '2014-10-26 22:33:16', '2014-10-27 05:33:16'),
(16, 1, 'T2 Note', '2014-10-08', '2014-10-26 22:24:17', '2014-10-27 05:24:17');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE IF NOT EXISTS `tbl_users` (
`user_id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_pass` varchar(32) NOT NULL,
  `user_fname` varchar(100) NOT NULL,
  `user_lname` varchar(100) NOT NULL,
  `user_last_login` datetime NOT NULL,
  `user_cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='table for registered user';

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_email`, `user_pass`, `user_fname`, `user_lname`, `user_last_login`, `user_cdate`) VALUES
(1, 'sample@gmail.com', '098f6bcd4621d373cade4e832627b4f6', 'Test', 'Admin', '2014-10-26 23:14:41', '2014-10-25 06:25:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
 ADD PRIMARY KEY (`note_id`), ADD KEY `uid_note` (`note_uid`), ADD KEY `date_of_note` (`note_date`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_email` (`user_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_notes`
--
ALTER TABLE `tbl_notes`
MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
