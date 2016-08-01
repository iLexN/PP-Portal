-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2016 年 08 月 01 日 12:31
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
  PRIMARY KEY (`banker_transfer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  `issue_to_whom` varchar(255) DEFAULT NULL,
  `issue_address` varchar(255) DEFAULT NULL,
  `currency_receive` varchar(255) DEFAULT NULL,
  `status` varchar(64) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`claim_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- 資料表的匯出資料 `member_portal_claim`
--

INSERT INTO `member_portal_claim` (`claim_id`, `user_policy_id`, `currency`, `amount`, `date_of_treatment`, `diagnosis`, `payment_method`, `issue_to_whom`, `issue_address`, `currency_receive`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'USD', '120.00', '2010-10-12', 'diagnosis', 'CC', 'issue_to_whom', 'issue_address', 'HKD', 'Save', '2016-07-28 12:16:44', '2016-07-28 12:16:44'),
(2, 1, 'USD', '123.00', NULL, NULL, NULL, NULL, NULL, NULL, 'Save', '2016-08-01 12:15:29', '2016-08-01 12:15:29');

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_claim_file`
--

DROP TABLE IF EXISTS `member_portal_claim_file`;
CREATE TABLE IF NOT EXISTS `member_portal_claim_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_id` int(11) NOT NULL,
  `filename` varchar(64) NOT NULL,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
  PRIMARY KEY (`contact_details_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_contact_details`
--

INSERT INTO `member_portal_contact_details` (`contact_details_id`, `region`, `region_full`, `tel_1`, `tel_2`, `fax_1`, `fax_2`, `fax_3`, `address_1`, `address_2`, `address_3`, `address_4`, `address_5`) VALUES
(1, 'HK', 'Hong Kong', '852 3113 2112 (Chinese)', '852 3113 1331 (English)', '852 3113 2332', '852 2915 7770', '852 2915 6603', 'Unit 1-11', '35th Floor', 'One Hung To Road', 'Kwun Tong', 'Hong Kong'),
(2, 'SH', 'Shanghai', '86 21 6467 1304', '86 21 6445 4592', '86 21 6467 0328', NULL, NULL, '19th Floor, 1329', 'Huaihai Zhong (Middle) Road', 'Xuhui District', 'Shanghai', 'China'),
(3, 'SG', 'Singapore', '65 6346 3781 (Admin)', '65 6536 6173 (Sales)', '65 6725 8041', NULL, NULL, '18 Cross Street', '#09-02A China Square Central', 'Singapore', '048423', NULL),
(4, 'DB', 'Dubai', '971 (0)42 793 800', NULL, '971 (0)43 686 181', NULL, NULL, '10th Floor', 'Platinum Tower', 'Cluster I', 'Jumeirah Lakes Towers', 'Dubai, UAE'),
(5, 'BJ', 'Beijing', '86 10 5829 1763', NULL, '86 10 5829 1999', NULL, NULL, '17th Floor, Tower B, Ping An', 'International Financial Centre', 'No.3 Xin Yuan South Road', 'Chaoyang District', 'Beijing, China');

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
  `ppmid` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_policy`
--

DROP TABLE IF EXISTS `member_portal_policy`;
CREATE TABLE IF NOT EXISTS `member_portal_policy` (
  `policy_id` int(11) NOT NULL,
  `insurer` varchar(100) DEFAULT NULL,
  `plan_name` varchar(120) DEFAULT NULL,
  `deductible` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `responsibility_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_policy`
--

INSERT INTO `member_portal_policy` (`policy_id`, `insurer`, `plan_name`, `deductible`, `start_date`, `end_date`, `responsibility_id`) VALUES
(1, 'Best Doctors', 'Ultracare', '85', '2015-01-30', NULL, 19),
(2, 'AIA', 'test_plan', '85', '2015-02-17', '2020-02-17', 19);

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
  `address_line_2` varchar(255) DEFAULT NULL,
  `address_line_3` varchar(255) DEFAULT NULL,
  `address_line_4` varchar(255) DEFAULT NULL,
  `address_line_5` varchar(255) DEFAULT NULL,
  `forgot_str` varchar(255) DEFAULT NULL,
  `forgot_expire` datetime DEFAULT NULL,
  PRIMARY KEY (`ppmid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 資料表的匯出資料 `member_portal_user`
--

INSERT INTO `member_portal_user` (`ppmid`, `title`, `first_name`, `middle_name`, `last_name`, `date_of_birth`, `user_name`, `password`, `nationality`, `email`, `phone_1`, `phone_2`, `address_line_2`, `address_line_3`, `address_line_4`, `address_line_5`, `forgot_str`, `forgot_expire`) VALUES
(2, 'Dr', 'www', 'eee', 'rrr', '1980-10-10', 'alex', '$2y$10$JPMtTxYGjP9X.iuYRG29eOEgF4poIZsk6PdSu/..0MzSEwuwUO9MK', 'China', 'alex@kwiksure.com', NULL, NULL, NULL, NULL, NULL, NULL, '5550522d-f8a8-4203-81c1-fa3b567157cc', '2020-07-28 16:12:33'),
(9677, NULL, 'dsdsab', 'sds', 'dsd', '1981-06-06', 'peter', '$2y$10$Gp36krtjE8K.Aa82WzHY0uVaV6.4t5TFK/NFMZOtS/GhRGVVNX8U2', 'China', 'peter.suen@pacificprime.com', '12345678', '87654321', 'Room 1000', 'Block B', 'ABC House', 'ST', NULL, NULL),
(135928, 'Mrs', 'Aja', 'O', 'Gorman', '1980-12-20', 'peter2', '$2y$10$g/Wgc8.CwugmRww1g.dE.e9UQbfTnk/xwvF33lyXwxp7W2MjFP2Eu', 'Canadian', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135929, 'Mr', 'Paul', 'Andrew', 'Woods', '1985-12-22', NULL, NULL, 'Canadian', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173802, 'Mr', 'Niall', 'O', 'Brien', '1980-03-04', NULL, NULL, 'Thailand', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173803, 'Mr', 'Niall', 'O', 'Brien', '1980-03-04', NULL, NULL, 'Thailand', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 資料表結構 `member_portal_user_policy`
--

DROP TABLE IF EXISTS `member_portal_user_policy`;
CREATE TABLE IF NOT EXISTS `member_portal_user_policy` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ppmid` int(11) NOT NULL,
  `policy_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- 資料表的匯出資料 `member_portal_user_policy`
--

INSERT INTO `member_portal_user_policy` (`id`, `ppmid`, `policy_id`) VALUES
(1, 2, 1),
(2, 9677, 1),
(3, 135928, 1),
(4, 135929, 1),
(5, 173802, 1),
(6, 173803, 1),
(7, 9677, 2);

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
