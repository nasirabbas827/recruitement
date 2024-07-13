-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 13, 2024 at 09:37 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobs_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `candidate_id` int(11) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `experience` int(11) DEFAULT NULL,
  `salary_expectation` int(11) DEFAULT NULL,
  `cv_url` varchar(255) DEFAULT NULL,
  `status` enum('applied','shortlisted','interviewed','rejected','accepted') DEFAULT NULL,
  `applied_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `candidate_id`, `job_id`, `experience`, `salary_expectation`, `cv_url`, `status`, `applied_at`, `updated_at`) VALUES
(1, 3, 1, 3, 12345, 'uploads/103728.pdf', 'interviewed', '2024-05-25 09:35:17', '2024-05-25 09:35:17');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `candidate_id` int(11) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `resume_url` varchar(255) DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `experience` text DEFAULT NULL,
  `education` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`candidate_id`, `first_name`, `last_name`, `address`, `phone_number`, `resume_url`, `skills`, `experience`, `education`) VALUES
(3, 'NASIR', 'ABBAS', 'Street jeff xxxx\r\nAp04', '3176526827', 'uploads/doc_2.pdf', 'Programming', 'None', 'BSCS');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `feedback_type` enum('website','recruiter','candidate') NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `feedback_type`, `message`, `created_at`) VALUES
(1, 3, 'website', 'He is Good', '2024-05-25 13:34:58');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(11) NOT NULL,
  `recruiter_id` int(11) DEFAULT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_description` text NOT NULL,
  `job_requirements` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `industry` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `recruiter_id`, `job_title`, `job_description`, `job_requirements`, `location`, `industry`, `keywords`, `status`, `created_at`) VALUES
(1, 4, 'Software Engineer', 'Design, develop, and test software', 'BSCS', 'lahore', 'IT', 'software development, programming', 'active', '2024-05-25 07:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_text` text DEFAULT NULL,
  `sent_datetime` datetime DEFAULT current_timestamp(),
  `reply_text` text DEFAULT NULL,
  `reply_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message_text`, `sent_datetime`, `reply_text`, `reply_datetime`) VALUES
(1, 4, 3, 'Heelo Canidate', '2024-05-25 12:59:35', 'Yes', '2024-05-25 13:08:35'),
(2, 4, 3, 'Hy', '2024-05-25 12:59:48', 'Yes', '2024-05-25 13:08:35');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `candidate_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `candidate_id`, `job_id`, `message`, `created_at`) VALUES
(1, 3, 1, 'You are selected', '2024-05-25 13:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `recruiters`
--

CREATE TABLE `recruiters` (
  `recruiter_id` int(11) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `company_phone` varchar(20) DEFAULT NULL,
  `company_email` varchar(100) DEFAULT NULL,
  `job_posting_status` enum('active','deactivated') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recruiters`
--

INSERT INTO `recruiters` (`recruiter_id`, `company_name`, `company_address`, `company_phone`, `company_email`, `job_posting_status`) VALUES
(4, 'www.miniclip.com', 'Street jeff xxxx\r\nAp04', '03176526827', 'miniclip@gmail.com', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `usertype` varchar(20) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `cnic` varchar(50) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `town` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phone`, `age`, `usertype`, `name`, `cnic`, `address`, `town`, `region`, `postcode`, `country`) VALUES
(3, 'candidate1', '$2y$10$NRLdS6iLQYbbWuk4Z6fCFuPoWwXBs0c4cnK6GseHZ3XDGnMrewP7S', 'candidate@gmail.com', '31765268278', 23, 'candidate', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 'rec1', '$2y$10$47uLeD0llPLv1sKKWqxwbeBicqeSWJS7DpwqsaMve0UoaarFYWZvC', 'rec@gmail.com', '317652682782', 23, 'recruiter', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 'can1', '$2y$10$JZV7XRjiVGEdCCO8huvIPuvVti3.beStwORYasJyFP/KpFmWSmztu', 'can@gmail.com', '03176526827', 23, 'candidate', 'Candiadte', '21343124323', 'Tehsil Jampur District Rajanpur', 'Jampur', 'BA', '33000', 'Pakistan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`candidate_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `recruiter_id` (`recruiter_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `candidate_id` (`candidate_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `recruiters`
--
ALTER TABLE `recruiters`
  ADD PRIMARY KEY (`recruiter_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`candidate_id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`);

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`recruiter_id`) REFERENCES `recruiters` (`recruiter_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`candidate_id`),
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`);

--
-- Constraints for table `recruiters`
--
ALTER TABLE `recruiters`
  ADD CONSTRAINT `recruiters_ibfk_1` FOREIGN KEY (`recruiter_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
