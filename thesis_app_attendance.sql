-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2016 at 04:23 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

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
`admin_id` int(11) NOT NULL,
  `admin_fullname` varchar(20) NOT NULL,
  `admin_username` varchar(20) NOT NULL,
  `admin_password` varchar(1000) NOT NULL,
  `admin_status` tinyint(1) NOT NULL,
  `admin_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

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
`assistant_id` int(11) NOT NULL,
  `assistant_fullname` varchar(50) DEFAULT NULL,
  `assistant_email` varchar(100) NOT NULL,
  `assistant_password` varchar(1000) DEFAULT NULL,
  `assistant_status` tinyint(1) NOT NULL DEFAULT '1',
  `assistant_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assistant`
--

INSERT INTO `assistant` (`assistant_id`, `assistant_fullname`, `assistant_email`, `assistant_password`, `assistant_status`, `assistant_added_time`, `admin_id`) VALUES
(13, 'Lloric Garcia', 'lmgarcia@dmc.edu.ph', NULL, 1, '2016-11-12 06:42:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE IF NOT EXISTS `attendance` (
`attendance_id` int(11) NOT NULL,
  `attendance_date` varchar(20) NOT NULL,
  `attendance_status` enum('present','absent') NOT NULL DEFAULT 'absent',
  `teacher_id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `assistant_id` int(11) NOT NULL,
  `attendance_day` varchar(10) NOT NULL,
  `attendance_date_timestap` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `attendance_date`, `attendance_status`, `teacher_id`, `schedule_id`, `assistant_id`, `attendance_day`, `attendance_date_timestap`) VALUES
(14, '2016:11:13', 'absent', 3, 11, 13, 'Sun', '1479007014'),
(15, '2016:11:13', 'absent', 3, 10, 13, 'Sun', '1479007048'),
(16, '2016:11:13', 'present', 4, 14, 13, 'Sun', '1479007816');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
`course_id` int(11) NOT NULL,
  `course_desc` varchar(50) NOT NULL,
  `course_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_desc`, `course_added_time`, `admin_id`) VALUES
(2, 'BSIT', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `hash_key`
--

CREATE TABLE IF NOT EXISTS `hash_key` (
`id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hash_key_value` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
`schedule_id` int(11) NOT NULL,
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
  `schedule_semester` enum('1','2','3') NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedule_id`, `schedule_start_time`, `schedule_end_time`, `schedule_day_monday`, `schedule_day_tuesday`, `schedule_day_wednesday`, `schedule_day_thursday`, `schedule_day_friday`, `schedule_day_saturday`, `schedule_day_sunday`, `schedule_room`, `teacher_id`, `schedule_added_time`, `admin_id`, `subject_id`, `course_id`, `schedule_sy`, `schedule_semester`) VALUES
(10, '06:00:00', '07:00:00', 1, 1, 1, 1, 1, 1, 1, 'room1', 3, '2016-11-12 06:45:16', 1, 3, 2, '2016-2017', '1'),
(11, '06:00:00', '17:00:00', 1, 1, 1, 1, 1, 1, 1, 'aaaaaaa', 3, '2016-11-12 06:45:18', 1, 3, 2, '2016-2017', '1'),
(12, '07:00:00', '08:00:00', 0, 0, 0, 0, 0, 1, 0, 'rroom', 3, '0000-00-00 00:00:00', 1, 4, 2, '2016-2017', '1'),
(13, '06:00:00', '08:00:00', 0, 0, 0, 0, 0, 1, 0, 'rrrr', 4, '0000-00-00 00:00:00', 1, 5, 2, '2016-2017', '1'),
(14, '09:00:00', '12:00:00', 0, 0, 0, 0, 0, 0, 1, 'open field', 4, '0000-00-00 00:00:00', 1, 6, 2, '2016-2017', '1'),
(15, '06:00:00', '07:00:00', 1, 0, 0, 0, 0, 0, 0, 'aaaaaa', 4, '0000-00-00 00:00:00', 1, 4, 2, '2016-2017', '1'),
(16, '06:00:00', '10:00:00', 0, 0, 0, 0, 0, 1, 0, 'fsfsdf', 3, '0000-00-00 00:00:00', 1, 6, 2, '2016-2017', '1');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
`subject_id` int(11) NOT NULL,
  `subject_code` varchar(10) NOT NULL,
  `subject_desc` varchar(100) NOT NULL,
  `subject_unit` int(11) NOT NULL,
  `subject_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject_id`, `subject_code`, `subject_desc`, `subject_unit`, `subject_added_time`, `admin_id`) VALUES
(3, 'IT12', 'Programming', 3, '0000-00-00 00:00:00', 1),
(4, 'TEST123', 'My Subject', 2, '0000-00-00 00:00:00', 1),
(5, 'QW12', 'tech subject', 2, '0000-00-00 00:00:00', 1),
(6, 'NSTP1', 'Reserve Officer Training Course 1', 2, '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE IF NOT EXISTS `teacher` (
`teacher_id` int(11) NOT NULL,
  `teacher_fullname` varchar(50) NOT NULL,
  `teacher_added_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  `teacher_email` varchar(100) NOT NULL,
  `teacher_school_id` varchar(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_id`, `teacher_fullname`, `teacher_added_time`, `admin_id`, `teacher_email`, `teacher_school_id`) VALUES
(3, 'test fetcher', '2016-11-12 06:52:16', 1, 'emorickfighter@gmail.com', '1234-1233'),
(4, 'my teacher', '0000-00-00 00:00:00', 1, 'w@w.w', '1223-1223');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
 ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `assistant`
--
ALTER TABLE `assistant`
 ADD PRIMARY KEY (`assistant_id`), ADD UNIQUE KEY `assistant_email` (`assistant_email`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
 ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
 ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `hash_key`
--
ALTER TABLE `hash_key`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
 ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
 ADD PRIMARY KEY (`subject_id`), ADD UNIQUE KEY `subject_code` (`subject_code`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
 ADD PRIMARY KEY (`teacher_id`), ADD UNIQUE KEY `teacher_username` (`teacher_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `assistant`
--
ALTER TABLE `assistant`
MODIFY `assistant_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `hash_key`
--
ALTER TABLE `hash_key`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
