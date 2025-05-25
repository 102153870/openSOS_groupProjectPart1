-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 05:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `opensos`
--

-- --------------------------------------------------------

--
-- Table structure for table `eoi`
--

CREATE TABLE `eoi` (
  `eoi_number` int(11) NOT NULL,
  `job_ref_number` varchar(5) DEFAULT NULL,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` varchar(40) DEFAULT NULL,
  `suburb` varchar(40) DEFAULT NULL,
  `state` varchar(3) DEFAULT NULL,
  `postcode` varchar(4) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `phone_number` varchar(12) DEFAULT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `other_skills` text DEFAULT NULL,
  `status` varchar(7) DEFAULT 'NEW'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `eoi`
--

INSERT INTO `eoi` (`eoi_number`, `job_ref_number`, `first_name`, `last_name`, `dob`, `gender`, `address`, `suburb`, `state`, `postcode`, `email_address`, `phone_number`, `skills`, `other_skills`, `status`) VALUES
(1, 'H3110', 'Rodney', 'Liaw', '2003-09-03', 'Male', '42/3 Launder Street', 'Hawthorn', 'VIC', '3122', 'yungtedleoliaw@gmail.com', '0406042335', 'html, css, php, phpmyadmin, python, csharp, cplusplus, word, excel, powerpoint, msteams, git, jira', 'This is a placeholder for Rodney.', 'NEW'),
(2, 'H3110', 'Mark', 'Richards', '2025-03-03', 'Male', '1/110 John Street', 'Hawthorn', 'VIC', '3122', 'MarkRichards@gmail.com', '0123456789', 'html, css, javascript, reactjs, angular, php, phpmyadmin, mysql, postgresql, python, csharp, cplusplus, word, excel, powerpoint', '', 'NEW'),
(3, 'T4B13', 'Mark', 'Richards', '2025-03-03', 'Male', '1/110 John Street', 'Hawthorn', 'VIC', '3122', 'MarkRichards@gmail.com', '0123456789', 'html, css, javascript, reactjs, php, phpmyadmin, mysql, python, csharp, word, excel, powerpoint, msteams, git, jira', 'This is a placeholder for Mark.', 'NEW'),
(4, 'P1224', 'Ryan', 'Weber', '2025-05-07', 'Male', '112 Launder street', 'Hawthorn', 'VIC', '3122', 'ryanweber@gmail.com', '0123456789', 'html, css, javascript, reactjs, php, phpmyadmin, mysql, python, csharp, word, excel, powerpoint', 'This is a placeholder for Ryan.', 'NEW'),
(5, 'P1224', 'Henry', 'Low', '2025-03-10', 'Male', '1234 Tabuan Heights', 'Kuching', 'NSW', '3122', 'henrylow@gmail.com', '0123456789', 'html, css, javascript, php, phpmyadmin, mysql, python, word, excel, powerpoint, msteams', 'This is a placeholder for Henry.', 'NEW'),
(6, 'H3110', 'Henry', 'Low', '2025-03-10', 'Male', '1234 Tabuan Heights', 'Kuching', 'NSW', '3122', 'henrylow@gmail.com', '0123456789', 'html', '', 'NEW'),
(7, 'T4B13', 'Henry', 'Low', '2025-03-10', 'Male', '1234 Tabuan Heights', 'Kuching', 'NSW', '3122', 'henrylow@gmail.com', '0123456789', 'html, css, javascript, reactjs, angular, bootstrap, mysql, postgresql, swift', '', 'NEW');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `job_title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `key_responsibilities` text DEFAULT NULL,
  `key_attributes_essential` text DEFAULT NULL,
  `key_attributes_preferred` text DEFAULT NULL,
  `salary_min` int(11) DEFAULT NULL,
  `salary_max` int(11) DEFAULT NULL,
  `reports_to` varchar(100) DEFAULT NULL,
  `reference_code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_title`, `description`, `key_responsibilities`, `key_attributes_essential`, `key_attributes_preferred`, `salary_min`, `salary_max`, `reports_to`, `reference_code`) VALUES
(1, 'Data Analyst', 'As a Data Analyst for OpenSOS, you will play a critical role in supporting our mission by turning raw data into meaningful insights that drive informed decision-making. You will collaborate closely with cross-functional teams, including developers, program managers, and stakeholders, to identify, collect, and analyze data from a variety of sources. Your work will focus on multiple key areas of interest such as tracking and evaluating emerging issues with our technologies, monitoring and interpreting user interactions with our digital platforms, and exploring opportunities to enhance the overall effectiveness and efficiency of our programs and operations. In this role, you are not just analyzing numbers — you’re helping tell the story of how OpenSOS can grow, adapt, and improve through data-driven strategies.', 'Collecting data from reliable sources\nCleaning data and interoperating it in a meaningful way\nPresenting data in a clear way that is easy to understand', '1 Year of experience as a data analyst\nA Degree in Computer Science or a related field', 'Experience using Excel, Python, and SQL\r\nGood communication skills\r\nA positive attitude towards learning in the workspace and striving to improve\r\nExperience using various data visualization tools', 90000, 100000, 'Analytics Leader', 'H3110'),
(2, 'Programmer', 'As a Programmer at OpenSOS, you will play a key role in the development and maintenance of our software systems. You will collaborate closely with our software development team to design, implement, and refine code for a wide range of applications that support our mission. Your responsibilities will include writing clean, efficient, and well-documented code, as well as conducting regular reviews to ensure reliability, performance, and scalability. In this role, you’ll not only contribute to building new features but also help optimize existing codebases and debug issues that arise during development or testing phases. You will be encouraged to apply best practices in software engineering and stay updated with modern programming trends to continually improve the quality of our products.', 'Write clear and efficient code\r\nTest code to ensure reliability across different environments\r\nMaintain and update softwares', '1 Year of experience as a programmer\r\nA Degree in Computer Science or a related field', 'Experience using programming languages such as c#, c++ and python\r\nA background of previously worked-on programs\r\nGood communication skills\r\nA positive attitude towards learning in the workspace and striving to improve', 100000, 120000, 'Director of Software Engineering', 'T4B13'),
(3, 'Front-end Web Developer', 'As a Front-End Web Developer at OpenSOS, you will be responsible for crafting user-facing features and interfaces that are not only visually appealing but also functional, accessible, and responsive across a variety of platforms and devices. You will collaborate closely with back-end developers, UI/UX designers, and other stakeholders to transform design mockups and functional requirements into fully responsive and interactive web pages. Your work will focus on creating seamless user experiences by writing clean, efficient, and modular HTML, CSS, and JavaScript. You will also be responsible for integrating APIs, handling data transfer securely between front-end components and back-end systems, and ensuring that security and performance standards are met across all user interactions.', 'Create clean UI for web pages\r\nEnsure websites work, and are usable in a range of different environments and with a variety of users\r\nCollaborate with designers and back-end developers effectively', '1 Year of experience as a front-end developer\r\nA Degree in Computer Science or a related field\r\nExperience using HTML, CSS and JavaScript', 'A background of previously worked-on web pages\r\nGood communication skills\r\nA positive attitude towards learning in the workspace and striving to improve\r\nExperience using frameworks such as React, Angular or Vue.js', 90000, 110000, 'Web development manager', 'P1224');

-- --------------------------------------------------------

--
-- Table structure for table `manager_password`
--

CREATE TABLE `manager_password` (
  `company_password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `manager_password`
--

INSERT INTO `manager_password` (`company_password`) VALUES
('sosadmin123');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `suburb` varchar(50) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `postcode` varchar(10) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `username`, `password`, `role`, `first_name`, `last_name`, `dob`, `gender`, `address`, `suburb`, `state`, `postcode`, `phone_number`, `created_at`) VALUES
(1, 'yungtedleoliaw@gmail.com', 'Rodney Liaw', '$2y$10$zUMCcKlweWmfKr6AbFCtw.cQW/UVvwi9gTSg2KCjK1CXSJI.sacn2', 'user', 'Rodney', 'Liaw', '2003-09-03', 'Male', '42/3 Launder Street', 'Hawthorn', 'VIC', '3122', '0406042335', '2025-05-25 02:45:45'),
(2, 'opensos404@gmail.com', 'OpenSOS', '$2y$10$Mri6qrjLheqb4pHfyOA/i.KmKLnkY5azv4tPJZTsnEakKjAyARG1y', 'manager', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-25 03:03:56'),
(3, 'MarkRichards@gmail.com', 'Mark', '$2y$10$/GMileLPVLUeBFciihAr0uaYxJLsrBk8QUSdN1QylbToebR63kxNi', 'user', 'Mark', 'Richards', '2025-03-03', 'Male', '1/110 John Street', 'Hawthorn', 'VIC', '3122', '0123456789', '2025-05-25 03:05:28'),
(4, 'ryanweber@gmail.com', 'Ryan', '$2y$10$2EDS4gZln6dM5tDuJcYKgeEx75mMZrn9BrhK4AAsToJwxayF0VnK.', 'user', 'Ryan', 'Weber', '2025-05-07', 'Male', '112 Launder street', 'Hawthorn', 'VIC', '3122', '0123456789', '2025-05-25 03:08:06'),
(5, 'henrylow@gmail.com', 'Henry', '$2y$10$WthRK4MChOxbY/gIpZaeWeGUK7iHsUPtIafy.T5X32V7ykKpJVM4q', 'user', 'Henry', 'Low', '2025-03-10', 'Male', '1234 Tabuan Heights', 'Kuching', 'NSW', '3122', '0123456789', '2025-05-25 03:10:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eoi`
--
ALTER TABLE `eoi`
  ADD PRIMARY KEY (`eoi_number`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eoi`
--
ALTER TABLE `eoi`
  MODIFY `eoi_number` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
