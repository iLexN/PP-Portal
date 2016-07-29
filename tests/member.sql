-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2016 年 07 月 29 日 11:40
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
CREATE DATABASE IF NOT EXISTS `member` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `member`;

DELIMITER $$
--
-- Procedure
--
DROP PROCEDURE IF EXISTS `BupaClientCheck`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `BupaClientCheck`(IN cmdlist LONGTEXT, OUT str LONGTEXT)
BEGIN
	DECLARE comma INT DEFAULT 0; 
	DECLARE mylist LONGTEXT DEFAULT cmdlist; 
	DECLARE temp LONGTEXT DEFAULT ''; 
	DECLARE strlen int DEFAULT LENGTH(cmdlist);
	DECLARE outstr LONGTEXT DEFAULT '';
	LOOP1: WHILE strlen > 0 DO 
		SET comma = LOCATE(';',mylist);
		SET temp = TRIM(SUBSTRING(mylist,1,comma-1));
		SET temp = CONCAT(temp, " ORDER BY Start_Date_Of_Relationship LIMIT 1;");
		SET mylist = TRIM(SUBSTRING(mylist FROM comma+1)); 
		SET strlen = LENGTH(mylist);
		SET @result = '';
		
		SET @sql = CONCAT("SELECT CONCAT(Start_Date_Of_Relationship,'') INTO @result FROM `client` ", temp);
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
		IF @result <> '' THEN
			SET outstr = CONCAT(outstr, @result, ',');
			ITERATE LOOP1;
		END IF;
		
		DEALLOCATE PREPARE stmt;
		SET @sql = CONCAT("SELECT CONCAT(Start_Date_Of_Relationship,'') INTO @result FROM `client_sh` ", temp);
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
		IF @result <> '' THEN
			SET outstr = CONCAT(outstr, @result, ',');
			ITERATE LOOP1;
		END IF;
			
		DEALLOCATE PREPARE stmt;
		SET @sql = CONCAT("SELECT CONCAT(Start_Date_Of_Relationship,'') INTO @result FROM `client_sg` ", temp);
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
		IF @result <> '' THEN
			SET outstr = CONCAT(outstr, @result, ',');
			ITERATE LOOP1;
		END IF;
		DEALLOCATE PREPARE stmt;
		SET @sql = CONCAT("SELECT CONCAT(Start_Date_Of_Relationship,'') INTO @result FROM `client_cn` ", temp);
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
		IF @result <> '' THEN
			SET outstr = CONCAT(outstr, @result, ',');
			ITERATE LOOP1;
		END IF;
			
		SET outstr = CONCAT(outstr, 'f,');	
		DEALLOCATE PREPARE stmt;
	END WHILE LOOP1; 
	SET str = outstr;
    END$$

DROP PROCEDURE IF EXISTS `cp_create`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_create`(in p_dt datetime,in p_ppid int, in p_user varchar(50), out o_rid int)
BEGIN
	DECLARE v_edate datetime;

	select policy_end_date into v_edate from policies where policy_id=p_ppid;
	
	insert into cp_list (policy_id, end_date, member_role, update_user, update_time) values (p_ppid, v_edate, 'Employee', p_user, p_dt);
	
	select rid into o_rid from cp_list where update_time=p_dt;
	
END$$

DROP PROCEDURE IF EXISTS `cp_createTemplate`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_createTemplate`(in p_user varchar(50), in p_col text)
BEGIN
	insert into cp_list_sett(s_key, s_company, s_val, s_val2, del)values('exp_tmpt',p_user,NOW(),p_col,0);
END$$

DROP PROCEDURE IF EXISTS `cp_getAge`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_getAge`(in p_ppid int, in p_skey varchar(255), in p_rid int, out o_age int)
BEGIN
	DECLARE v_sval varchar(255);
	DECLARE v_sdate datetime;
	Declare v_role varchar(255);	
	
	select c.s_val into v_sval from policies p, cp_list_sett c 
	where p.policy_id=p_ppid and p.insurance_company=c.s_company and c.s_key=p_skey;
		
	select member_role into v_role from cp_list where rid=p_rid;	

	select start_date into v_sdate from cp_list where rid=p_rid;

	if (v_sval='employee_start_date') then
		if (v_role='Employee') then
			select start_date into v_sdate from cp_list where rid=p_rid;			
		else
			select b.start_date into v_sdate from cp_list a, cp_list b 
			where a.family_id=b.family_id and b.member_role='Employee' and b.del=0 	and a.rid=p_rid;
		end if;
	elseif (v_sval='policy_start_date') then
		select start_date into v_sdate from policies where policy_id=p_ppid;
	end if;

	select FLOOR(DATEDIFF(v_sdate, dob)/365) as age into o_age 
	from cp_list 
	where rid=p_rid;

	
END$$

DROP PROCEDURE IF EXISTS `cp_getOne`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_getOne`(in p_rid int)
BEGIN
	select Sub_group, family_id, Policy_no, member_no, title, member_name, member_sname, member_gname, member_role, dob+0 dob, Age, Residence, Gender, nationality, 
			area_cover, plan_code, plan_name, start_date+0 start_date, end_date+0 end_date, Days_of_Cover, premium_loading, exclusion, ccy, 
			format(Annual_Premium,2) as Annual_Premium, 
			format(Renew_Premium,2) Renew_Premium, format(additional_Premium,2) additional_Premium, format(refund_Premium,2) refund_Premium, 
			format(actual_Premium,2) actual_Premium, format(annual_premium_s1,2) annual_premium_s1, format(annual_premium_s2,2) annual_premium_s2, 
			format(q1_Premium,2) q1_Premium, format(q2_Premium,2) q2_Premium, format(q3_Premium,2) q3_Premium, format(q4_Premium,2) q4_Premium,
			format(m1_Premium,2) m1_Premium, format(m2_Premium,2) m2_Premium, format(m3_Premium,2) m3_Premium, format(m4_Premium,2) m4_Premium, 
			format(m5_Premium,2) m5_Premium, format(m6_Premium,2) m6_Premium, format(m7_Premium,2) m7_Premium, format(m8_Premium,2) m8_Premium, 
			format(m9_Premium,2) m9_Premium, format(m10_Premium,2) m10_Premium, format(m11_Premium,2) m11_Premium, format(m12_Premium,2) m12_Premium, 
			format(final_adjustment,2) final_adjustment, 
			policy_issue_date+0 policy_issue_date, email, first_join_date+0 first_join_date, postal_addr, home_addr, phone,
			idno, employee_id, cost_centre, annual_salary, pay_method, bank, bank_code, bank_acc,
			spare1, spare2, spare3, 
			remarks 
	from cp_list 
	where rid=p_rid
	and del=0;
END$$

DROP PROCEDURE IF EXISTS `cp_getOneTemplate`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_getOneTemplate`(in p_user varchar(50), in p_ts varchar(50))
BEGIN
	select s_val2 from cp_list_sett where s_key='exp_tmpt' and s_company=p_user and del=0 and s_val=p_ts;
END$$

DROP PROCEDURE IF EXISTS `cp_getTemplate`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_getTemplate`(in p_user varchar(50))
BEGIN
	select min(s_val) from cp_list_sett where s_key='exp_tmpt' and s_company=p_user and del=0 group by s_val2 order by s_val desc;
END$$

DROP PROCEDURE IF EXISTS `cp_list`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_list`(in p_policy_id int)
BEGIN
	DECLARE curRow INT(11);
	SET @curRow := 0;

	select rid, @curRow := @curRow + 1 AS row_number, concat(family_id,' ') family_id, Policy_no, 		
		case when member_name is not null then member_name else member_gname+' '+member_sname end as member_name,
		member_role, dob, concat(Age,' ') age, Residence, Gender, nationality, start_date, end_date, Days_of_Cover, ccy, format(Annual_Premium,2) as Annual_Premium, 
		format(Renew_Premium,2) Renew_Premium, remarks 
	from cp_list 
	where policy_id=p_policy_id	
	and del=0
	order by CONVERT(family_id,UNSIGNED INTEGER), rid;
END$$

DROP PROCEDURE IF EXISTS `cp_update`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `cp_update`(in p_rid int, in p_col varchar(255), in p_val varchar(255))
proc:BEGIN
	if(p_val = 'null') then
		set p_val := null;
	end if;	

	if (p_col='sub_group') then
		update cp_list set sub_group=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='family_id') then
		update cp_list set family_id=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='policy_no') then
		update cp_list set policy_no=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='member_no') then
		update cp_list set member_no=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='title') then
		update cp_list set title=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='member_name') then
		update cp_list set member_name=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='member_sname') then
		update cp_list set member_sname=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='member_gname') then
		update cp_list set member_gname=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='member_role') then
		update cp_list set member_role=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='dob') then
		update cp_list set dob=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='age') then
		update cp_list set age=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='residence') then
		update cp_list set residence=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='gender') then
		update cp_list set gender=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='nationality') then
		update cp_list set nationality=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='area_cover') then
		update cp_list set area_cover=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='plan_code') then
		update cp_list set plan_code=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='plan_name') then
		update cp_list set plan_name=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='start_date') then
		update cp_list set start_date=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='end_date') then
		update cp_list set end_date=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='days_of_cover') then
		update cp_list set days_of_cover=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='premium_loading') then
		update cp_list set premium_loading=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='exclusion') then
		update cp_list set exclusion=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='ccy') then
		update cp_list set ccy=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='annual_premium') then
		update cp_list set annual_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='renew_premium') then
		update cp_list set renew_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='additional_premium') then
		update cp_list set additional_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='refund_premium') then
		update cp_list set refund_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='actual_premium') then
		update cp_list set actual_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='annual_premium_s1') then
		update cp_list set annual_premium_s1=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='annual_premium_s2') then
		update cp_list set annual_premium_s2=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;

	if (p_col='q1_premium') then
		update cp_list set q1_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;

	if (p_col='q2_premium') then
		update cp_list set q2_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;

	if (p_col='q3_premium') then
		update cp_list set q3_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;

	if (p_col='q4_premium') then
		update cp_list set q4_premium=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;

	if (p_col='final_adjustment') then
		update cp_list set final_adjustment=replace(p_val,',' ,'') where rid=p_rid;
		leave proc;
	end if;
	if (p_col='policy_issue_date') then
		update cp_list set policy_issue_date=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='email') then
		update cp_list set email=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='first_join_date') then
		update cp_list set first_join_date=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='postal_addr') then
		update cp_list set postal_addr=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='home_addr') then
		update cp_list set home_addr=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='phone') then
		update cp_list set phone=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='idno') then
		update cp_list set idno=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='employee_id') then
		update cp_list set employee_id=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='cost_centre') then
		update cp_list set cost_centre=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='annual_salary') then
		update cp_list set annual_salary=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='pay_method') then
		update cp_list set pay_method=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='bank') then
		update cp_list set bank=p_val where rid=p_rid;
	leave proc;
	end if;
	if (p_col='bank_code') then
		update cp_list set bank_code=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='bank_acc') then
		update cp_list set bank_acc=p_val where rid=p_rid;
		leave proc;
	end if;
	if (p_col='remarks') then
		update cp_list set remarks=p_val where rid=p_rid;
	leave proc;
	end if;
	if (p_col='del') then
		update cp_list set del=1 where rid=p_rid;
		leave proc;
	end if;
END$$

DROP PROCEDURE IF EXISTS `FindExistingClient`$$
CREATE DEFINER=`kwikkelvinwong`@`%` PROCEDURE `FindExistingClient`(IN cmdlist LONGTEXT, OUT str LONGTEXT)
BEGIN
	DECLARE comma INT DEFAULT 0; 
	DECLARE mylist LONGTEXT DEFAULT cmdlist; 
	DECLARE temp LONGTEXT DEFAULT ''; 
	DECLARE strlen int DEFAULT LENGTH(cmdlist);
	DECLARE outstr LONGTEXT DEFAULT '';
	WHILE strlen > 0 DO 
		SET comma = LOCATE(';',mylist);
		SET temp = TRIM(SUBSTRING(mylist,1,comma)); 
		SET mylist = TRIM(SUBSTRING(mylist FROM comma+1)); 
		SET strlen = LENGTH(mylist); 
		
		SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client` ', temp);
		PREPARE stmt FROM @sql;
		EXECUTE stmt;
		IF @countid = 0 THEN
			DEALLOCATE PREPARE stmt;
			SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_sh` ', temp);
			PREPARE stmt FROM @sql;
			EXECUTE stmt;
			IF @countid = 0 THEN
				DEALLOCATE PREPARE stmt;
				SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_sg` ', temp);
				PREPARE stmt FROM @sql;
				EXECUTE stmt;
				IF @countid = 0 THEN
					DEALLOCATE PREPARE stmt;
					SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_cn` ', temp);
					PREPARE stmt FROM @sql;
					EXECUTE stmt;
					IF @countid = 0 THEN
						DEALLOCATE PREPARE stmt;
						SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_uae` ', temp);
						PREPARE stmt FROM @sql;
						EXECUTE stmt;
						IF @countid = 0 THEN
							DEALLOCATE PREPARE stmt;
							SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_ms` ', temp);
							PREPARE stmt FROM @sql;
							EXECUTE stmt;
							IF @countid = 0 THEN
								DEALLOCATE PREPARE stmt;
								SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_wgc` ', temp);
								PREPARE stmt FROM @sql;
								EXECUTE stmt;
								IF @countid = 0 THEN
									DEALLOCATE PREPARE stmt;
									SET @sql = CONCAT('SELECT count(Client_NO) INTO @countid FROM `client_bupa` ', temp);
									PREPARE stmt FROM @sql;
									EXECUTE stmt;
									IF @countid = 0 THEN
										SET outstr = CONCAT(outstr, 'f,');
									ELSE
										SET outstr = CONCAT(outstr, 't,');
									END IF;
								ELSE
									SET outstr = CONCAT(outstr, 't,');
								END IF;
							ELSE
								SET outstr = CONCAT(outstr, 't,');
							END IF;
						ELSE
							SET outstr = CONCAT(outstr, 't,');
						END IF;
					ELSE
						SET outstr = CONCAT(outstr, 't,');
					END IF;
				ELSE
					SET outstr = CONCAT(outstr, 't,');
				END IF;
			ELSE
				SET outstr = CONCAT(outstr, 't,');
			END IF;
		ELSE
			SET outstr = CONCAT(outstr, 't,');
		END IF;
		DEALLOCATE PREPARE stmt;
	END WHILE; 
	SET str = outstr;
    END$$

DELIMITER ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
