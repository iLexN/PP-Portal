-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2017 年 03 月 27 日 14:23
-- 伺服器版本: 5.6.15-log
-- PHP 版本： 5.6.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫： `member`
--

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_address_user`
--

DROP TABLE IF EXISTS `member_portal_address_user`;
CREATE TABLE IF NOT EXISTS `member_portal_address_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick_name` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `address_line_3` varchar(255) DEFAULT NULL,
  `address_line_4` varchar(255) DEFAULT NULL,
  `address_line_5` varchar(255) DEFAULT NULL,
  `ppmid` int(11) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `old_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- 資料表的匯出資料 `member_portal_address_user`
--

INSERT INTO `member_portal_address_user` (`id`, `nick_name`, `address_line_2`, `address_line_3`, `address_line_4`, `address_line_5`, `ppmid`, `status`, `old_id`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', 'test', 'test', 'test', 2, 'Active', NULL, '2016-09-14 12:44:51', '2016-09-14 12:44:51'),
(3, 'test', 'test', 'test', 'test', 'test', 2, 'Active', NULL, '2016-09-14 12:44:51', '2016-09-14 12:44:51'),
(4, 'test', 'test', 'test', 'test', 'test', 2, 'Active', NULL, '2016-09-14 12:44:51', '2016-09-14 12:44:51');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_bank_transfer_claim`
--

DROP TABLE IF EXISTS `member_portal_bank_transfer_claim`;
CREATE TABLE IF NOT EXISTS `member_portal_bank_transfer_claim` (
  `banker_transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) DEFAULT NULL,
  `account_user_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `bank_swift_code` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `additional_information` varchar(510) DEFAULT NULL,
  `intermediary_bank_swift_code` varchar(255) DEFAULT NULL,
  `claim_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`banker_transfer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `member_portal_bank_transfer_claim`
--

INSERT INTO `member_portal_bank_transfer_claim` (`banker_transfer_id`, `currency`, `account_user_name`, `account_number`, `iban`, `branch_code`, `bank_swift_code`, `bank_name`, `additional_information`, `intermediary_bank_swift_code`, `claim_id`) VALUES
(1, 'usd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_bank_transfer_user`
--

DROP TABLE IF EXISTS `member_portal_bank_transfer_user`;
CREATE TABLE IF NOT EXISTS `member_portal_bank_transfer_user` (
  `banker_transfer_id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) DEFAULT NULL,
  `account_user_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `bank_swift_code` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `additional_information` varchar(510) DEFAULT NULL,
  `intermediary_bank_swift_code` varchar(255) DEFAULT NULL,
  `ppmid` int(11) DEFAULT NULL,
  `nick_name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`banker_transfer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 資料表的匯出資料 `member_portal_bank_transfer_user`
--

INSERT INTO `member_portal_bank_transfer_user` (`banker_transfer_id`, `currency`, `account_user_name`, `account_number`, `iban`, `branch_code`, `bank_swift_code`, `bank_name`, `additional_information`, `intermediary_bank_swift_code`, `ppmid`, `nick_name`, `created_at`, `updated_at`) VALUES
(1, '2', '2', '2', '2', '2', '2', '2', '2', '2', 2, NULL, NULL, NULL),
(2, '2', '2', '2', '2', '2', '2', '2', '2', '2', 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_cheque`
--

DROP TABLE IF EXISTS `member_portal_cheque`;
CREATE TABLE IF NOT EXISTS `member_portal_cheque` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `first_name` varchar(45) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `address_line_2` varchar(45) DEFAULT NULL,
  `address_line_3` varchar(45) DEFAULT NULL,
  `address_line_4` varchar(45) DEFAULT NULL,
  `address_line_5` varchar(45) DEFAULT NULL,
  `claim_id` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_cheque`
--

INSERT INTO `member_portal_cheque` (`id`, `title`, `first_name`, `middle_name`, `last_name`, `address_line_2`, `address_line_3`, `address_line_4`, `address_line_5`, `claim_id`) VALUES
(1, '2', '2', '2', '2', '2', '2', '2', '2', '2');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_claim`
--

DROP TABLE IF EXISTS `member_portal_claim`;
CREATE TABLE IF NOT EXISTS `member_portal_claim` (
  `claim_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_policy_id` int(11) NOT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `amount` decimal(19,2) DEFAULT NULL,
  `date_of_treatment` date DEFAULT NULL,
  `diagnosis` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `currency_receive` varchar(255) DEFAULT NULL,
  `claimiant_ppmid` int(11) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`claim_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 資料表的匯出資料 `member_portal_claim`
--

INSERT INTO `member_portal_claim` (`claim_id`, `user_policy_id`, `currency`, `amount`, `date_of_treatment`, `diagnosis`, `payment_method`, `currency_receive`, `claimiant_ppmid`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'USD', '120.00', '2010-10-12', 'diagnosis', 'Bank transfer', 'HKD', NULL, 'Save', '2016-07-28 12:16:44', '2016-07-28 12:16:44'),
(2, 7, 'USD', '123.00', NULL, NULL, 'Cheque', NULL, NULL, 'Save', '2016-08-01 12:15:29', '2016-08-01 12:15:29');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_claim_file`
--

DROP TABLE IF EXISTS `member_portal_claim_file`;
CREATE TABLE IF NOT EXISTS `member_portal_claim_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_id` int(11) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `file_type` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- 資料表的匯出資料 `member_portal_claim_file`
--

INSERT INTO `member_portal_claim_file` (`id`, `claim_id`, `filename`, `file_type`, `status`) VALUES
(1, 1, 'a.txt', '', 'Upload'),
(2, 1, 'a.txt', '', 'Upload'),
(3, 1, 'a.txt', '', 'Delete'),
(4, 1, 'a.txt', '', 'Upload');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_contact_details`
--

DROP TABLE IF EXISTS `member_portal_contact_details`;
CREATE TABLE IF NOT EXISTS `member_portal_contact_details` (
  `contact_details_id` int(3) NOT NULL,
  `region` varchar(5) DEFAULT NULL,
  `region_full` varchar(20) DEFAULT NULL,
  `tel_1` varchar(30) DEFAULT NULL,
  `tel_2` varchar(30) DEFAULT NULL,
  `fax_1` varchar(30) DEFAULT NULL,
  `fax_2` varchar(30) DEFAULT NULL,
  `fax_3` varchar(30) DEFAULT NULL,
  `address_1` varchar(100) DEFAULT NULL,
  `address_2` varchar(100) DEFAULT NULL,
  `address_3` varchar(100) DEFAULT NULL,
  `address_4` varchar(100) DEFAULT NULL,
  `address_5` varchar(100) DEFAULT NULL,
  `gmap_lat` double DEFAULT NULL,
  `gmap_lng` double DEFAULT NULL,
  PRIMARY KEY (`contact_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_contact_details`
--

INSERT INTO `member_portal_contact_details` (`contact_details_id`, `region`, `region_full`, `tel_1`, `tel_2`, `fax_1`, `fax_2`, `fax_3`, `address_1`, `address_2`, `address_3`, `address_4`, `address_5`, `gmap_lat`, `gmap_lng`) VALUES
(1, 'HK', 'Hong Kong', '852 3113 2112 (Chinese)', '852 3113 1331 (English)', '852 3113 2332', '852 2915 7770', '852 2915 6603', 'Unit 1-11', '35th Floor', 'One Hung To Road', 'Kwun Tong', 'Hong Kong', NULL, NULL),
(2, 'SH', 'Shanghai', '86 21 6467 1304', '86 21 6445 4592', '86 21 6467 0328', NULL, NULL, '19th Floor, 1329', 'Huaihai Zhong (Middle) Road', 'Xuhui District', 'Shanghai', 'China', NULL, NULL),
(3, 'SG', 'Singapore', '65 6346 3781 (Admin)', '65 6536 6173 (Sales)', '65 6725 8041', NULL, NULL, '18 Cross Street', '#09-02A China Square Central', 'Singapore', '048423', NULL, NULL, NULL),
(4, 'DB', 'Dubai', '971 (0)42 793 800', NULL, '971 (0)43 686 181', NULL, NULL, '10th Floor', 'Platinum Tower', 'Cluster I', 'Jumeirah Lakes Towers', 'Dubai, UAE', NULL, NULL),
(5, 'BJ', 'Beijing', '86 10 5829 1763', NULL, '86 10 5829 1999', NULL, NULL, '17th Floor, Tower B, Ping An', 'International Financial Centre', 'No.3 Xin Yuan South Road', 'Chaoyang District', 'Beijing, China', NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_forgot_username`
--

DROP TABLE IF EXISTS `member_portal_forgot_username`;
CREATE TABLE IF NOT EXISTS `member_portal_forgot_username` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_insurer_plan_management`
--

DROP TABLE IF EXISTS `member_portal_insurer_plan_management`;
CREATE TABLE IF NOT EXISTS `member_portal_insurer_plan_management` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `insurer_id` varchar(255) DEFAULT NULL,
  `plan_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=139 ;

--
-- 資料表的匯出資料 `member_portal_insurer_plan_management`
--

INSERT INTO `member_portal_insurer_plan_management` (`id`, `insurer_id`, `plan_name`) VALUES
(25, '38', 'Expatline'),
(27, '5', 'Poineer'),
(28, '5', 'Summit'),
(30, '31', 'IE'),
(31, '31', 'SC Optimum'),
(32, '31', 'SC Executive'),
(33, '125', 'BWHO'),
(34, '125', 'LifeLine'),
(35, '125', 'Asean Plus'),
(36, '166', 'CGHO'),
(37, '106', 'Prestige'),
(38, '9', 'ProHealth'),
(39, '161', 'ProMedico'),
(40, '161', 'ProMedico Plus'),
(42, '165', 'WorldCare'),
(43, '31', 'SC Optimum Enhanced'),
(47, '4', 'IHP - Group'),
(48, '15', 'Prima'),
(50, '18', 'IHP - Group'),
(54, '78', 'IHHP'),
(55, '78', 'ISM'),
(58, '41', 'BWHO'),
(59, '41', 'Lifeline'),
(60, '69', 'YouGenio  World'),
(61, '69', 'YouGenio'),
(63, '84', 'Individual'),
(66, '1', 'SEA'),
(67, '1', 'Global'),
(69, '4', 'IHP Indiviudal'),
(70, '4', 'Pioneer'),
(71, '6', 'Starr CN'),
(72, '10', 'AIG China'),
(74, '152', 'LJYS Plan'),
(75, '152', 'Unity Health Plan'),
(76, '143', 'SmartCare Plan'),
(77, '16', 'IHHP'),
(78, '18', 'IHP - Individual'),
(79, '141', 'Elite Plan'),
(80, '22', 'Ambassade'),
(81, '32', 'IHP'),
(82, '141', 'Essential Plan'),
(83, '32', 'EHP'),
(84, '36', 'Medical Care'),
(85, '168', 'Individual Health Insurance'),
(86, '39', 'OVC'),
(87, '40', 'Premier'),
(88, '130', 'Expatline Plan'),
(89, '151', 'Medical Health'),
(90, '118', 'ZunXin Plan'),
(92, '118', 'Zun You Ren Sheng Plan'),
(93, '113', 'ASEAN'),
(94, '113', 'Thailand'),
(95, '41', 'Lifeline - Corporate'),
(96, '53', 'HealthFirst Elite'),
(97, '53', 'Global Health'),
(98, '53', 'Silver'),
(99, '112', 'WorldwideCare Plan'),
(100, '52', 'CMB SME'),
(101, '52', 'IPMI D Premier'),
(102, '164', 'Medical'),
(103, '59', 'New World'),
(104, '59', 'Germany'),
(105, '59', 'Old Plan'),
(106, '151', 'Travel'),
(108, '10', 'Domestic Travel'),
(109, '10', 'Global Travel'),
(110, '52', 'Local A'),
(111, '111', 'Worldcare Plan'),
(112, '65', 'Independence'),
(113, '65', 'Local Advantage'),
(114, '65', 'Inpatient'),
(115, '68', 'Xplorer'),
(116, '70', 'Henner Care & Health Asia'),
(117, '77', 'Premier Care'),
(118, '118', 'SME Plan'),
(120, '77', 'GlobalCare'),
(121, '102', 'Evolution'),
(122, '79', 'IHHP'),
(123, '79', 'ISM'),
(124, '80', 'GlobeHopper'),
(125, '80', 'Fusion'),
(126, '80', 'GlobalSelect'),
(127, '171', 'Advanced Plan'),
(128, '87', 'UltraCare'),
(129, '87', 'UltraCare - Africa'),
(130, '171', 'SME Plan'),
(131, '171', 'Premier Plan'),
(132, '171', 'Elite Choice Plan'),
(133, '171', 'Advanced IP Plan'),
(134, '170', 'First Expat'),
(137, '172', 'Asia Care Plan'),
(138, '172', 'Asia Care Plus Plan');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_medical_plan_file_list`
--

DROP TABLE IF EXISTS `member_portal_medical_plan_file_list`;
CREATE TABLE IF NOT EXISTS `member_portal_medical_plan_file_list` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_medical_plan_file_list`
--

INSERT INTO `member_portal_medical_plan_file_list` (`id`, `plan_id`, `file_name`, `file_type`) VALUES
(1, 25, 'test.pdf', 'Table of Benefits');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_medical_policy_file_list`
--

DROP TABLE IF EXISTS `member_portal_medical_policy_file_list`;
CREATE TABLE IF NOT EXISTS `member_portal_medical_policy_file_list` (
  `id` int(11) NOT NULL,
  `ppib` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_medical_policy_file_list`
--

INSERT INTO `member_portal_medical_policy_file_list` (`id`, `ppib`, `file_name`, `file_type`) VALUES
(1, 1, 'test.pdf', 'Policy Certificate');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_policy`
--

DROP TABLE IF EXISTS `member_portal_policy`;
CREATE TABLE IF NOT EXISTS `member_portal_policy` (
  `policy_id` int(11) NOT NULL,
  `insurer` varchar(100) DEFAULT NULL,
  `plan_id` int(11) NOT NULL DEFAULT '-1',
  `deductible` varchar(100) DEFAULT NULL,
  `cover` varchar(100) DEFAULT NULL,
  `options` varchar(100) DEFAULT NULL,
  `medical_currency` varchar(30) DEFAULT NULL,
  `payment_frequency` varchar(30) DEFAULT 'Annual',
  `payment_method` varchar(40) DEFAULT 'Cheque',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `Policy_Number` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_policy`
--

INSERT INTO `member_portal_policy` (`policy_id`, `insurer`, `plan_id`, `deductible`, `cover`, `options`, `medical_currency`, `payment_frequency`, `payment_method`, `start_date`, `end_date`, `responsibility_id`, `status`, `Policy_Number`) VALUES
(1, 'Best Doctors', 25, '85', '', '', '', '', '', '2015-01-30', '2020-02-17', 19, 'Active', NULL),
(2, 'AIA', 25, '85', '', '', '', '', '', '2015-02-17', '2020-02-17', 19, 'Active', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_policy_holder`
--

DROP TABLE IF EXISTS `member_portal_policy_holder`;
CREATE TABLE IF NOT EXISTS `member_portal_policy_holder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_address_line_2` varchar(255) DEFAULT NULL,
  `policy_address_line_3` varchar(255) DEFAULT NULL,
  `policy_address_line_4` varchar(255) DEFAULT NULL,
  `policy_address_line_5` varchar(255) DEFAULT NULL,
  `mail_address_line_2` varchar(255) DEFAULT NULL,
  `mail_address_line_3` varchar(255) DEFAULT NULL,
  `mail_address_line_4` varchar(255) DEFAULT NULL,
  `mail_address_line_5` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `member_portal_policy_holder`
--

INSERT INTO `member_portal_policy_holder` (`id`, `policy_address_line_2`, `policy_address_line_3`, `policy_address_line_4`, `policy_address_line_5`, `mail_address_line_2`, `mail_address_line_3`, `mail_address_line_4`, `mail_address_line_5`) VALUES
(1, 'policy_address_line_2', 'policy_address_line_3', 'policy_address_line_4', 'policy_address_line_5', 'mail_address_line_2', 'mail_address_line_3', 'mail_address_line_4', 'mail_address_line_5');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_policy_holder_update`
--

DROP TABLE IF EXISTS `member_portal_policy_holder_update`;
CREATE TABLE IF NOT EXISTS `member_portal_policy_holder_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_address_line_2` varchar(255) DEFAULT NULL,
  `policy_address_line_3` varchar(255) DEFAULT NULL,
  `policy_address_line_4` varchar(255) DEFAULT NULL,
  `policy_address_line_5` varchar(255) DEFAULT NULL,
  `mail_address_line_2` varchar(255) DEFAULT NULL,
  `mail_address_line_3` varchar(255) DEFAULT NULL,
  `mail_address_line_4` varchar(255) DEFAULT NULL,
  `mail_address_line_5` varchar(255) DEFAULT NULL,
  `holder_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `holder_id` (`holder_id`,`status`,`created_at`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `member_portal_policy_holder_update`
--

INSERT INTO `member_portal_policy_holder_update` (`id`, `policy_address_line_2`, `policy_address_line_3`, `policy_address_line_4`, `policy_address_line_5`, `mail_address_line_2`, `mail_address_line_3`, `mail_address_line_4`, `mail_address_line_5`, `holder_id`, `created_at`, `updated_at`, `status`) VALUES
(1, '1', '2', '3', '4', '5', '6', '7', '8', 1, '2016-11-24 00:00:00', '2016-11-24 00:00:00', 'Pending');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_responsibility`
--

DROP TABLE IF EXISTS `member_portal_responsibility`;
CREATE TABLE IF NOT EXISTS `member_portal_responsibility` (
  `responsibility_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `office_phone` varchar(255) DEFAULT NULL,
  `contact_details_id` int(3) DEFAULT NULL,
  PRIMARY KEY (`responsibility_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_responsibility`
--

INSERT INTO `member_portal_responsibility` (`responsibility_id`, `name`, `email`, `office_phone`, `contact_details_id`) VALUES
(19, 'Ken Chung', 'ken@kwiksure.com', '3588 2914', 1),
(106, 'Owen Ryan', 'owen@pacificprime.com', '021-24266473', 2);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_user`
--

DROP TABLE IF EXISTS `member_portal_user`;
CREATE TABLE IF NOT EXISTS `member_portal_user` (
  `ppmid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(45) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `user_name` varchar(128) DEFAULT NULL,
  `password` varchar(256) DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_1` varchar(255) DEFAULT NULL,
  `phone_2` varchar(255) DEFAULT NULL,
  `forgot_str` varchar(255) DEFAULT NULL,
  `forgot_expire` datetime DEFAULT NULL,
  `holder_id` int(11) DEFAULT NULL,
  `profile_permission` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`ppmid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_user`
--

INSERT INTO `member_portal_user` (`ppmid`, `title`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `user_name`, `password`, `nationality`, `email`, `phone_1`, `phone_2`, `forgot_str`, `forgot_expire`, `holder_id`, `profile_permission`) VALUES
(2, 'Dr', 'www', 'eee', 'rrr', '1980-10-10', 'alex', '$2y$10$JPMtTxYGjP9X.iuYRG29eOEgF4poIZsk6PdSu/..0MzSEwuwUO9MK', 'China', 'alex@kwiksure.com', '12345678', NULL, '5550522d-f8a8-4203-81c1-fa3b567157cc', '2020-07-28 16:12:33', 1, 'View'),
(9677, NULL, 'dsdsab', 'sds', 'dsd', '1981-06-06', 'peter', '$2y$10$JPMtTxYGjP9X.iuYRG29eOEgF4poIZsk6PdSu/..0MzSEwuwUO9MK', 'China', 'peter.suen@pacificprime.com', '12345678', '87654321', NULL, NULL, 1, 'View'),
(135928, 'Mrs', 'Aja', 'O', 'Gorman', '1980-12-20', 'peter2', '$2y$10$JPMtTxYGjP9X.iuYRG29eOEgF4poIZsk6PdSu/..0MzSEwuwUO9MK', 'Canadian', NULL, NULL, NULL, NULL, NULL, 1, 'View'),
(135929, 'Mr', 'Paul', 'Andrew', 'Woods', '1985-12-22', NULL, NULL, 'Canadian', NULL, NULL, NULL, NULL, NULL, 1, NULL),
(173802, 'Mr', 'Niall', 'O', 'Brien', '1980-03-04', NULL, NULL, 'Thailand', NULL, NULL, NULL, 'adf2', '2020-07-28 16:12:33', 1, NULL),
(173803, 'Mr', 'Niall', 'O', 'Brien', '1980-03-04', NULL, NULL, 'Thailand', NULL, NULL, NULL, NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_user_policy`
--

DROP TABLE IF EXISTS `member_portal_user_policy`;
CREATE TABLE IF NOT EXISTS `member_portal_user_policy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppmid` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  `premium_paid` decimal(19,2) DEFAULT '0.00',
  `relationship` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- 資料表的匯出資料 `member_portal_user_policy`
--

INSERT INTO `member_portal_user_policy` (`id`, `ppmid`, `policy_id`, `premium_paid`, `relationship`) VALUES
(1, 2, 1, '0.00', NULL),
(2, 9677, 1, '0.00', 'PolicyHolder'),
(3, 135928, 1, '0.00', NULL),
(4, 135929, 1, '0.00', NULL),
(5, 173802, 1, '0.00', NULL),
(6, 173803, 1, '0.00', NULL),
(7, 9677, 2, '0.00', NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_user_preference`
--

DROP TABLE IF EXISTS `member_portal_user_preference`;
CREATE TABLE IF NOT EXISTS `member_portal_user_preference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) DEFAULT NULL,
  `currency_receive` varchar(255) DEFAULT NULL,
  `ppmid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_user_renew`
--

DROP TABLE IF EXISTS `member_portal_user_renew`;
CREATE TABLE IF NOT EXISTS `member_portal_user_renew` (
  `renew_id` int(11) NOT NULL AUTO_INCREMENT,
  `ppmid` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `nationality` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_1` varchar(255) DEFAULT NULL,
  `phone_2` varchar(255) DEFAULT NULL,
  `address_line_2` varchar(255) DEFAULT NULL,
  `address_line_3` varchar(255) DEFAULT NULL,
  `address_line_4` varchar(255) DEFAULT NULL,
  `address_line_5` varchar(255) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`renew_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- 資料表的匯出資料 `member_portal_user_renew`
--

INSERT INTO `member_portal_user_renew` (`renew_id`, `ppmid`, `title`, `middle_name`, `first_name`, `last_name`, `date_of_birth`, `nationality`, `email`, `phone_1`, `phone_2`, `address_line_2`, `address_line_3`, `address_line_4`, `address_line_5`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'asd', 'adasd', 'asd', 'asd', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Pending', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
