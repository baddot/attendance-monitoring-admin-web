-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jan 03, 2017 at 09:09 PM
-- Server version: 10.0.28-MariaDB
-- PHP Version: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `thesis_app_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_fullname` varchar(20) NOT NULL,
  `admin_username` varchar(20) NOT NULL,
  `admin_password` varchar(1000) NOT NULL,
  `admin_status` tinyint(1) NOT NULL,
  `admin_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_fullname`, `admin_username`, `admin_password`, `admin_status`, `admin_added_time`) VALUES
(1, 'Administrator', 'administrator', '$2y$10$bNXhufs0dm15NjQt5jNyD.tmU2dZLb4DQsLgm38FlmhoNgTE7nsEW', 1, '2016-11-13 06:35:48');

-- --------------------------------------------------------

--
-- Table structure for table `assistant`
--

CREATE TABLE IF NOT EXISTS `assistant` (
  `assistant_id` int(11) NOT NULL AUTO_INCREMENT,
  `assistant_fullname` varchar(50) DEFAULT NULL,
  `assistant_email` varchar(100) NOT NULL,
  `assistant_password` varchar(1000) DEFAULT NULL,
  `assistant_status` tinyint(1) NOT NULL DEFAULT '1',
  `assistant_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`assistant_id`),
  UNIQUE KEY `assistant_email` (`assistant_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`assistant_id`, `assistant_fullname`, `assistant_email`, `assistant_password`, `assistant_status`, `assistant_added_time`, `admin_id`) VALUES
(13, 'Lloric Garcia', 'lmgarcia@dmc.edu.ph', NULL, 1, '2016-11-19 14:20:42', 1),
(14, NULL, 'asa@aaa.aa', NULL, 1, '0000-00-00 00:00:00', 1),
(15, NULL, 'asa@aaa.aaa', NULL, 1, '0000-00-00 00:00:00', 1),
(16, NULL, 'a@sw.snn', NULL, 1, '2016-11-19 14:25:28', 1),
(17, NULL, 'aa@sssss.ssssk', NULL, 1, '2016-11-19 14:31:28', 1),
(18, NULL, 'asa@aaa.aaaq', NULL, 1, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
  `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
  `attendance_date` varchar(20) NOT NULL,
  `attendance_status` enum('present','absent') NOT NULL DEFAULT 'absent',
  `teacher_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `assistant_id` int(11) NOT NULL,
  `attendance_day` varchar(10) NOT NULL,
  `attendance_date_timestap` varchar(50) NOT NULL,
  `attendance_approve` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`attendance_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `attendance_date`, `attendance_status`, `teacher_id`, `schedule_id`, `assistant_id`, `attendance_day`, `attendance_date_timestap`, `attendance_approve`) VALUES
(14, '2016:11:14', 'absent', 3, 11, 13, 'Sun', '1479007014', 0),
(15, '2016:11:14', 'absent', 3, 10, 13, 'Sun', '1479007048', 0),
(16, '2016:11:14', 'present', 4, 14, 13, 'Sun', '1479007816', 0),
(17, '2017:01:03', 'present', 3, 10, 13, 'Tue', '1483444544', 1),
(18, '2017:01:03', 'present', 4, 14, 13, 'Tue', '1483444547', 0);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `course_id` int(11) NOT NULL AUTO_INCREMENT,
  `course_desc` varchar(50) NOT NULL,
  `course_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`course_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_desc`, `course_added_time`, `admin_id`) VALUES
(2, 'BSIT', '0000-00-00 00:00:00', 1),
(3, 'my Course', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hash_key`
--

CREATE TABLE IF NOT EXISTS `hash_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hash_key_value` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `schedule_start_time` time NOT NULL,
  `schedule_end_time` time NOT NULL,
  `schedule_day_monday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_day_tuesday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_day_wednesday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_day_thursday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_day_friday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_day_saturday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_day_sunday` tinyint(1) NOT NULL DEFAULT '0',
  `schedule_room` varchar(10) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `schedule_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `schedule_sy` varchar(10) NOT NULL,
  `schedule_semester` enum('1','2','3') NOT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedule_id`, `schedule_start_time`, `schedule_end_time`, `schedule_day_monday`, `schedule_day_tuesday`, `schedule_day_wednesday`, `schedule_day_thursday`, `schedule_day_friday`, `schedule_day_saturday`, `schedule_day_sunday`, `schedule_room`, `teacher_id`, `schedule_added_time`, `admin_id`, `subject_id`, `course_id`, `schedule_sy`, `schedule_semester`) VALUES
(10, '06:00:00', '07:00:00', 1, 1, 1, 1, 1, 1, 1, 'room1', 3, '2016-11-12 06:45:16', 1, 3, 2, '2016-2017', '1'),
(12, '07:00:00', '08:00:00', 0, 0, 0, 0, 0, 1, 0, 'rroom', 3, '0000-00-00 00:00:00', 1, 4, 2, '2016-2017', '1'),
(13, '06:00:00', '08:00:00', 0, 0, 0, 0, 0, 1, 0, 'rrrr', 4, '0000-00-00 00:00:00', 1, 5, 2, '2016-2017', '1'),
(14, '09:00:00', '12:00:00', 0, 0, 0, 0, 0, 0, 1, 'open field', 4, '0000-00-00 00:00:00', 1, 6, 2, '2016-2017', '1'),
(15, '06:00:00', '07:00:00', 1, 0, 0, 0, 0, 0, 0, 'aaaaaa', 4, '0000-00-00 00:00:00', 1, 4, 2, '2016-2017', '1'),
(16, '06:00:00', '10:00:00', 0, 0, 0, 0, 0, 1, 0, 'fsfsdf', 3, '0000-00-00 00:00:00', 1, 6, 2, '2016-2017', '1'),
(17, '17:00:00', '19:00:00', 0, 0, 0, 1, 0, 0, 0, 'my rooom', 3, '0000-00-00 00:00:00', 1, 3, 2, '2016-2017', '1'),
(18, '17:00:00', '19:00:00', 0, 0, 0, 1, 0, 0, 0, 'meee', 5, '0000-00-00 00:00:00', 1, 7, 3, '2018-2019', '3'),
(19, '06:00:00', '12:00:00', 0, 0, 0, 0, 1, 0, 0, 'yuuiyu', 3, '0000-00-00 00:00:00', 1, 3, 2, '2016-2017', '1');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `subject_id` int(11) NOT NULL AUTO_INCREMENT,
  `subject_code` varchar(10) NOT NULL,
  `subject_desc` varchar(100) NOT NULL,
  `subject_unit` int(11) NOT NULL,
  `subject_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  PRIMARY KEY (`subject_id`),
  UNIQUE KEY `subject_code` (`subject_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_code`, `subject_desc`, `subject_unit`, `subject_added_time`, `admin_id`) VALUES
(3, 'IT12', 'Programming', 3, '0000-00-00 00:00:00', 1),
(4, 'TEST123', 'My Subject', 2, '0000-00-00 00:00:00', 1),
(5, 'QW12', 'tech subject', 2, '0000-00-00 00:00:00', 1),
(6, 'NSTP1', 'Reserve Officer Training Course 1', 2, '0000-00-00 00:00:00', 1),
(7, 'qqqq', 'QQQQQQQQQQQQQQQQQQ', 3333, '0000-00-00 00:00:00', 1),
(8, 'aaa', 'AAAAAAAAAAAAA', 1, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
  `teacher_id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_fullname` varchar(50) NOT NULL,
  `teacher_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  `teacher_email` varchar(100) NOT NULL,
  `teacher_school_id` varchar(10) NOT NULL,
  `teacher_device` varchar(20) NOT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `teacher_username` (`teacher_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_fullname`, `teacher_added_time`, `admin_id`, `teacher_email`, `teacher_school_id`, `teacher_device`) VALUES
(3, 'test fetcher', '2017-01-03 11:42:18', 1, 'emorickfighter@gmail.com', '1234-1233', 'test device'),
(4, 'my teacher', '0000-00-00 00:00:00', 1, 'w@w.w', '1223-1223', ''),
(5, 'makoko full', '0000-00-00 00:00:00', 1, 'makoo@qq.q', '2344-1234', ''),
(6, 'aaaaaaaa', '0000-00-00 00:00:00', 1, 'a@a.a', '1111-1111', 'deevice');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;