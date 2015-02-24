-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 05, 2014 at 05:08 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `friends`
--
CREATE DATABASE IF NOT EXISTS `friends` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `friends`;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `band_post_concert`(IN `b_band_id` INT, IN `c_concert_id` INT, IN `v_venue_id` INT, IN `d_date` DATE, IN `s_start_time` TIME, IN `e_end_time` TIME, IN `c_capacity` INT, IN `a_availability` INT, IN `p_price` FLOAT)
insert into band_reg_concert(reg_id,band_id,concert_id,venue_id,date,start_time,end_time,capacity,availability,price,timestamp)
values(LAST_INSERT_ID(),b_band_id,c_concert_id,v_venue_id,d_date,s_start_time,e_end_time,c_capacity,a_availability,p_price,now())$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `band_recommendation_of_people_i_follow`(IN `u_id` VARCHAR(32))
select band_id,band_name,follow,c from
(select max(c) as max from(
select band_id,band_name,follow,count(*) as c from following,
(select username,band_id,band_name
from user natural join user_fan natural join band
where (username,band_id,band_name) not in (
select username,band_id,band_name
from user natural join user_fan natural join band 
where username=u_id))temp
where temp.username=follow and follower=u_id
group by band_id,band_name)temp2)temp3,
(select band_id,band_name,follow,count(*) as c from following,
(select username,band_id,band_name
from user natural join user_fan natural join band
where (username,band_id,band_name) not in (
select username,band_id,band_name
from user natural join user_fan natural join band
where username=u_id))temp
where temp.username=follow and follower=u_id
group by band_id,band_name)temp4
where temp4.c=temp3.max$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `concert_recommendation_of_people_i_follow`(IN `u_id` VARCHAR(32))
select b.reg_id,ur.username,ur.reco_name,c.name,v.street,v.city,v.state,v.country,b.availability,b.date,b.start_time,b.end_time
from
user_recommendation ur ,following f, band_reg_concert b,venue v ,concert c where 
f.follow=ur.username and 
f.follower=u_id and
b.concert_id=ur.concert_id and
v.venue_id=b.venue_id and
c.concert_id=ur.concert_id and
b.date between date(now()) and date_add(date(now()),interval 30 day)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `follow_user`(IN `u_follower` VARCHAR(20), IN `u_follow` VARCHAR(20))
insert into following(follower,follow) values(u_follower,u_follow)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `genre_recommendation_of_people_i_follow`(IN `u_id` VARCHAR(32))
select genre_name,sub_genre_name,follow,c from
(select max(c) as max from(
select genre_name,sub_genre_name,follow,count(*) as c from following,
(select username,genre_name,sub_genre_name
from user natural join user_genre natural join genre natural join sub_genre
where (username,genre_name,sub_genre_name) not in (
select username,genre_name,sub_genre_name
from user natural join user_genre natural join genre natural join sub_genre
where username=u_id))temp
where temp.username=follow and follower=u_id
group by genre_name,sub_genre_name)temp2)temp3,
(select genre_name,sub_genre_name,follow,count(*) as c from following,
(select username,genre_name,sub_genre_name
from user natural join user_genre natural join genre natural join sub_genre
where (username,genre_name,sub_genre_name) not in (
select username,genre_name,sub_genre_name
from user natural join user_genre natural join genre natural join sub_genre
where username=u_id))temp
where temp.username=follow and follower=u_id
group by genre_name,sub_genre_name)temp4
where temp4.c=temp3.max$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_band_concerts`(IN `bid` INT)
select band_name,name,date,start_time,end_time,street,city,state,country
from band_reg_concert natural join band natural join concert natural join venue
where band_id=bid and date>=now()$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_band_data`(IN `bid` INT)
select * from band where band_id=bid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_band_followers`(IN `bid` INT)
select username,fname,lname from user natural join user_fan where band_id=bid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_band_genre`(IN `bid` INT)
select sub_genre_name from band natural join band_genre natural join sub_genre where band_id=bid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_band_list`(IN `uid` VARCHAR(32))
select band_id,band_name from band natural join user_fan where username = uid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_feedback`(IN `uid` VARCHAR(32))
select name,rating,comments from feedback natural join concert where username=uid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_past_concert_list`(IN `uid` VARCHAR(32))
    NO SQL
    COMMENT 'past events'
SELECT concert_id,name,band_name,street,city,state,date,start_time,end_time 
FROM `band_reg_concert` 
natural join user_reg_concert
natural join concert
natural join venue 
natural join band 
where username = uid and date <now() order by date$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_upcoming_concert_list`(IN `uid` VARCHAR(32))
    COMMENT 'upcoming_concert_list'
SELECT concert_id,name,band_name,street,city,state,date,start_time,end_time 
FROM `band_reg_concert` 
natural join user_reg_concert
natural join concert
natural join venue 
natural join band 
where username = uid and date >=now() order by date$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_data`(IN `uid` VARCHAR(20))
select fname,lname,dob,age,street,city,state,country,trust_level
from user where username=uid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `likes_genre`(IN `u_id` VARCHAR(32))
SELECT g.genre_name,sg.sub_genre_name 
FROM `user_genre` natural join sub_genre sg,genre g 
where username = u_id and g.genre_id=sg.genre_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `my_concert_reco`(IN `uid` VARCHAR(32))
select reg_id,username,reco_name,name,street,city,state,country,availability,date,start_time,end_time
from user_recommendation natural join concert natural join venue natural join band_reg_concert where username=uid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `search_concert_Place_Genre`(IN `c_place` VARCHAR(20), IN `c_genre` VARCHAR(32), IN `c_day` INT)
    NO SQL
select c.name,v.city,b.availability from concert c, band_reg_concert b, sub_genre sg, genre g,venue v where
v.city=c_place and
v.venue_id=b.venue_id and
c.concert_id=b.concert_id and
c.sub_genre_id=sg.sub_genre_id and
sg.genre_id=g.genre_id and
g.genre_name=c_genre and
b.date between date(now()) and date_add(date(now()),interval c_day day)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sys_reco_band_U_id`(IN `u_id` VARCHAR(32))
select band_name,g.genre_name from band natural join band_genre natural join sub_genre natural join genre g natural join
(select T2.genre_id,T2.genre_name from
(select min(counts) as m from
(select g.genre_id ,g.genre_name,count(*) as counts
from user_fan natural join band natural join band_genre natural join sub_genre natural join genre g where 
user_fan.username=u_id
group by g.genre_id,g.genre_name) as T) as T1,

(select g.genre_id ,g.genre_name,count(*) as counts
from user_fan natural join band natural join band_genre natural join sub_genre natural join genre g where 
user_fan.username=u_id
group by g.genre_id,g.genre_name) as T2
where T2.counts=T1.m) as T3
where band_id not in (select band_id from user_fan where username=u_id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sys_reco_concert_U_id`(IN `u_id` VARCHAR(32))
    NO SQL
select c.name from concert c, band_reg_concert b,
(select reg_id from band_reg_concert b,
(select Reg from
(select T6.Treg as Reg from
(select avg(counts) as avg from
(select b.reg_id as Treg,count(*) as counts from user_reg_concert u natural join band_reg_concert b,
(select follow  from following f where follower=u_id) as T3
where u.username=T3.follow
group by b.reg_id) as T4) as T7,

(select b.reg_id as Treg,count(*) as counts from user_reg_concert u natural join band_reg_concert b,
(select follow  from following f where follower=u_id) as T5
where u.username=T5.follow
group by b.reg_id) as T6 
where T6.counts >= T7.avg) as T8
where Reg not in (select reg_id from user_reg_concert where username=u_id) ) as T9
where b.date>=date(now()) and T9.Reg=b.reg_id) as T10
where c.concert_id=b.concert_id and b.reg_id=T10.reg_id$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_login`(IN `uid` VARCHAR(32))
update user
set last_login = now() where username = uid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_logout`(IN `uid` VARCHAR(32))
update user
set last_logout=now() where username=uid$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_edit_profile`(IN `u_username` VARCHAR(32), IN `u_fname` VARCHAR(20), IN `u_lname` VARCHAR(20), IN `u_dob` VARCHAR(20), IN `u_street` VARCHAR(20), IN `u_city` VARCHAR(20), IN `u_state` VARCHAR(20), IN `u_country` VARCHAR(20))
update user
set fname = u_fname, lname = u_lname, dob = u_dob,
	age = floor(datediff(date(now()),u_dob)/365.242199),
	street = u_street,city = u_city, state = u_state, country = u_country
where username = u_username$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_fan_of_band`(IN `u_username` VARCHAR(32), IN `b_band_id` INT)
insert into user_fan (username,band_id) values (u_username,b_band_id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_following_list`(IN `u_id` VARCHAR(32))
select username,fname,country,trust_level from user where username in(
    select follow from following where follower = u_id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_last_logout`(IN `u_id` VARCHAR(32))
select b.band_name,c.name,br.date,br.availability,br.price,v.city 
from band_reg_concert br,band b,concert c,venue v
where br.concert_id=c.concert_id and
br.band_id=b.band_id and
br.venue_id=v.venue_id and
br.timestamp >= (select last_logout from user where username=u_id)$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `user_sign_up`(IN `u_name` VARCHAR(32), IN `u_password` VARCHAR(32), IN `u_fname` VARCHAR(20), IN `u_lname` VARCHAR(20), IN `u_dob` DATE)
insert into user(username,password,fname,lname,dob) values (U_name,u_password,u_fname,u_lname,u_dob)$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE IF NOT EXISTS `artist` (
  `artist_id` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) DEFAULT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `street` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  PRIMARY KEY (`artist_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`artist_id`, `password`, `fname`, `lname`, `dob`, `age`, `street`, `city`, `state`, `country`, `last_login`, `last_logout`) VALUES
('artist1', 'artist1', 'Aniket', 'Chaudhry', '1969-12-30', 45, '8th Street', 'Brooklyn', 'New York', 'USA', '2014-11-18 00:00:00', '2014-11-19 00:00:00'),
('artist10', 'artist10', 'Ricky', 'Jain', '1992-08-18', 22, 'Saurabh Lane', 'Mumbai', 'Maharashtra', 'India', '2012-10-01 00:00:00', '2012-10-09 00:00:00'),
('artist2', 'artist2', 'Suhas', 'Jain', '1992-08-18', 22, 'Sneha street', 'Mumbai', 'Maharashtra', 'India', '2014-11-17 00:00:00', '2014-11-18 00:00:00'),
('artist3', 'artist3', 'Roland', 'Wagadia', '1992-08-28', 22, 'Charkop Market', 'Mumbai', 'Maharashtra', 'India', '2014-11-18 00:00:00', '2014-11-19 00:00:00'),
('artist4', 'artist4', 'Prakash', 'Mahapadi', '1989-07-10', 25, '14th E', 'Manhattan', 'New York', 'USA', '2014-11-12 00:00:00', '2014-11-13 00:00:00'),
('artist5', 'artist5', 'Prathima', 'Dsa', '1995-01-18', 20, '257th Steet 2nd Ave', 'Manhattan', 'New York', 'USA', '2014-10-18 00:00:00', '2014-10-19 00:00:00'),
('artist6', 'artist6', 'Kavita', 'Sandesara', '1975-12-30', 39, 'Xial', 'Bejing', 'Bejing', 'China', '2014-09-12 00:00:00', '2014-09-13 00:00:00'),
('artist7', 'artist7', 'Parth', 'Mahatre', '1982-09-04', 32, 'ChunaBhatti', 'Mumbai', 'Maharashtra', 'India', '2014-08-18 00:00:00', '2014-08-19 00:00:00'),
('artist8', 'artist8', 'Sahil', 'Mane', '1992-09-29', 22, 'Rajash Lane', 'Mumbai', 'Maharashtra', 'India', '2013-02-18 00:00:00', '2013-02-18 00:00:00'),
('artist9', 'artist9', 'Monil', 'Jain', '1989-12-30', 25, 'Sion', 'Chicago', 'New York', 'USA', '2014-11-18 00:00:00', '2014-11-19 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `band`
--

CREATE TABLE IF NOT EXISTS `band` (
  `band_id` int(11) NOT NULL AUTO_INCREMENT,
  `band_name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`band_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `band`
--

INSERT INTO `band` (`band_id`, `band_name`) VALUES
(1, 'Linkin Park'),
(2, 'Daughtry'),
(3, 'Junoon'),
(4, 'AIB'),
(5, 'Jal'),
(6, 'Slipknot');

-- --------------------------------------------------------

--
-- Table structure for table `band_artist`
--

CREATE TABLE IF NOT EXISTS `band_artist` (
  `band_id` int(11) NOT NULL DEFAULT '0',
  `artist_id` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`artist_id`,`band_id`),
  KEY `band_id` (`band_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `band_artist`
--

INSERT INTO `band_artist` (`band_id`, `artist_id`) VALUES
(1, 'artist1'),
(1, 'artist2'),
(1, 'artist3'),
(1, 'artist4'),
(2, 'artist10'),
(2, 'artist8'),
(2, 'artist9'),
(3, 'artist5'),
(3, 'artist6'),
(3, 'artist7'),
(3, 'artist8'),
(4, 'artist10'),
(4, 'artist2'),
(4, 'artist3'),
(4, 'artist4'),
(4, 'artist6'),
(5, 'artist6'),
(5, 'artist9'),
(6, 'artist7');

-- --------------------------------------------------------

--
-- Table structure for table `band_genre`
--

CREATE TABLE IF NOT EXISTS `band_genre` (
  `band_id` int(11) NOT NULL DEFAULT '0',
  `sub_genre_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`band_id`,`sub_genre_id`),
  KEY `sub_genre_id` (`sub_genre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `band_genre`
--

INSERT INTO `band_genre` (`band_id`, `sub_genre_id`) VALUES
(5, 1001),
(4, 1002),
(6, 1002),
(1, 2001),
(3, 2001),
(3, 2002),
(5, 2002),
(2, 3001),
(2, 3002),
(4, 3002),
(1, 4001),
(1, 4002),
(2, 4002),
(5, 4002),
(3, 5001),
(6, 5001),
(2, 5002),
(5, 5002);

-- --------------------------------------------------------

--
-- Table structure for table `band_reg_concert`
--

CREATE TABLE IF NOT EXISTS `band_reg_concert` (
  `reg_id` int(11) NOT NULL AUTO_INCREMENT,
  `band_id` int(11) DEFAULT NULL,
  `concert_id` int(11) DEFAULT NULL,
  `venue_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `capacity` int(11) NOT NULL,
  `Availability` int(11) NOT NULL,
  `price` float DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  PRIMARY KEY (`reg_id`),
  KEY `band_id` (`band_id`),
  KEY `concert_id` (`concert_id`),
  KEY `venue_id` (`venue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `band_reg_concert`
--

INSERT INTO `band_reg_concert` (`reg_id`, `band_id`, `concert_id`, `venue_id`, `date`, `start_time`, `end_time`, `capacity`, `Availability`, `price`, `timestamp`) VALUES
(1, 1, 7, 4, '2014-10-25', '20:00:00', '23:35:00', 100, 0, 300, '2014-11-18 00:00:00'),
(2, 1, 8, 5, '2014-11-30', '13:00:00', '22:00:00', 500, 120, 300, '2014-11-18 00:00:00'),
(3, 2, 5, 3, '2014-12-17', '13:00:00', '18:00:00', 500, 270, 250, '2014-11-18 00:00:00'),
(4, 2, 6, 2, '0000-00-00', '15:30:00', '23:40:00', 500, 200, 330, '2014-11-18 00:00:00'),
(5, 3, 3, 2, '2014-08-15', '19:45:00', '22:00:00', 800, 50, 600, '2014-11-18 00:00:00'),
(6, 3, 9, 5, '2014-12-15', '19:45:00', '22:00:00', 2000, 500, 600, '2014-11-18 00:00:00'),
(7, 4, 6, 5, '2014-11-17', '18:00:00', '21:50:00', 500, 200, 1300, '2014-11-18 00:00:00'),
(8, 4, 1, 3, '2015-09-20', '14:00:00', '19:35:00', 200, 50, 300, '2014-11-18 00:00:00'),
(9, 5, 8, 2, '2015-01-08', '18:00:00', '23:00:00', 500, 120, 300, '2014-11-18 00:00:00'),
(10, 6, 1, 1, '2014-11-20', '18:00:00', '22:00:00', 200, 50, 300, '2014-11-18 00:00:00'),
(11, 6, 2, 1, '2014-11-21', '16:00:00', '20:30:00', 200, 50, 350, '2014-11-18 00:00:00'),
(12, 6, 2, 4, '2015-05-09', '15:00:00', '19:40:00', 200, 50, 520, '2014-11-18 00:00:00'),
(13, 5, 10, 2, '2015-08-15', '09:45:00', '13:00:00', 200, 50, 620, '2014-11-18 00:00:00'),
(14, 5, 11, 3, '2015-01-15', '15:25:00', '22:00:00', 1200, 575, 250, '2014-11-18 00:00:00'),
(15, 1, 12, 5, '2014-05-15', '09:15:00', '13:00:00', 550, 150, 780, '2014-11-18 00:00:00'),
(16, 3, 13, 4, '2016-06-15', '09:45:00', '15:00:00', 80, 50, 680, '2014-11-18 00:00:00'),
(17, 5, 14, 1, '2016-07-15', '18:20:00', '22:00:00', 700, 50, 650, '2014-11-18 00:00:00'),
(18, 4, 15, 2, '2014-08-15', '20:20:00', '22:22:00', 200, 100, 5500, '2014-11-18 00:00:00'),
(19, 1, 16, 1, '2016-02-15', '16:00:00', '22:00:00', 100, 40, 500, '2014-11-18 00:00:00'),
(20, 5, 17, 3, '2015-04-15', '19:45:00', '23:00:00', 500, 120, 650, '2014-11-18 00:00:00'),
(21, 6, 18, 5, '2014-07-15', '12:45:00', '18:00:00', 200, 50, 700, '2014-11-18 00:00:00'),
(22, 2, 19, 2, '2015-03-15', '13:45:00', '19:00:00', 1000, 500, 600, '2014-11-18 00:00:00'),
(23, 2, 20, 1, '2014-11-30', '19:45:00', '22:00:00', 2000, 500, 200, '2014-11-18 00:00:00'),
(24, 4, 15, 2, '2014-11-25', '20:20:00', '22:22:00', 250, 300, 5500, '2014-11-18 00:00:00'),
(25, 1, 25, 8, '2014-11-30', '02:00:00', '03:00:00', 50, 25, 550, '2014-11-24 14:59:43'),
(26, 2, 1, 5, '2014-11-30', '02:00:00', '03:00:00', 100, 56, 1000, '2014-11-28 00:00:00'),
(27, 6, 2, 1, '2014-12-20', '05:00:00', '06:00:00', 400, 100, 1500, '2014-12-03 09:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `concert`
--

CREATE TABLE IF NOT EXISTS `concert` (
  `concert_id` int(11) NOT NULL AUTO_INCREMENT,
  `sub_genre_id` int(11) NOT NULL,
  `name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`concert_id`),
  KEY `sub_genre_id` (`sub_genre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `concert`
--

INSERT INTO `concert` (`concert_id`, `sub_genre_id`, `name`) VALUES
(1, 1002, 'World Rock Tour'),
(2, 1002, 'Death Sunday'),
(3, 2002, 'Hip-Hop Rounds'),
(4, 3001, 'MTV Best of Jazz'),
(5, 3001, 'Jazz-o-Mania'),
(6, 3002, 'Razz Jazz'),
(7, 4001, 'Rock Maze'),
(8, 4002, 'Rock n Roll'),
(9, 5001, 'All star pop Concert'),
(10, 1001, 'Nothing To Live For '),
(11, 1001, 'Metalofter'),
(12, 2001, 'Hoodie Alien'),
(13, 2001, 'Jewels'),
(14, 2002, 'HoH '),
(15, 3002, 'Jazzight'),
(16, 4001, 'Kicking End'),
(17, 4002, 'Rock and Rage'),
(18, 5001, 'Ephemeral Pop'),
(19, 5002, 'Illimination'),
(20, 5002, 'Pop Nation');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `username` varchar(32) NOT NULL DEFAULT '',
  `concert_id` int(11) NOT NULL DEFAULT '0',
  `rating` int(11) DEFAULT NULL,
  `comments` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`username`,`concert_id`),
  KEY `concert_id` (`concert_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`username`, `concert_id`, `rating`, `comments`) VALUES
('nmw33', 7, 4, 'Excellent concert loved it..!'),
('sau69', 6, 1, 'good');

-- --------------------------------------------------------

--
-- Table structure for table `following`
--

CREATE TABLE IF NOT EXISTS `following` (
  `follower` varchar(32) NOT NULL DEFAULT '',
  `follow` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`follower`,`follow`),
  KEY `follow` (`follow`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `following`
--

INSERT INTO `following` (`follower`, `follow`) VALUES
('sneha58', 'abc123'),
('abc123', 'ass21'),
('nmw33', 'ass21'),
('Ro951', 'ass21'),
('nmm32', 'Moh@99'),
('abc123', 'nmw33'),
('Ro951', 'nmw33'),
('sneha58', 'nmw33'),
('abc123', 'Ro951'),
('nmw33', 'Ro951'),
('abc123', 'sau69'),
('nmm32', 'sau69'),
('Ro951', 'sau69'),
('Sneha58', 'sau69'),
('nmm32', 'Sneha58'),
('sau69', 'Sneha58'),
('nmm32', 'Varsha582'),
('abc123', 'vvm321'),
('nmw33', 'vvm321'),
('Ro951', 'vvm321'),
('sau69', 'vvm321');

-- --------------------------------------------------------

--
-- Table structure for table `genre`
--

CREATE TABLE IF NOT EXISTS `genre` (
  `genre_id` int(11) NOT NULL AUTO_INCREMENT,
  `genre_name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`genre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `genre`
--

INSERT INTO `genre` (`genre_id`, `genre_name`) VALUES
(1, 'Metal'),
(2, 'Hip-Hop'),
(3, 'Jazz'),
(4, 'Rock'),
(5, 'Pop');

-- --------------------------------------------------------

--
-- Table structure for table `sub_genre`
--

CREATE TABLE IF NOT EXISTS `sub_genre` (
  `genre_id` int(11) DEFAULT NULL,
  `sub_genre_id` int(11) NOT NULL DEFAULT '0',
  `sub_genre_name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`sub_genre_id`),
  KEY `genre_id` (`genre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_genre`
--

INSERT INTO `sub_genre` (`genre_id`, `sub_genre_id`, `sub_genre_name`) VALUES
(1, 1001, 'Soft'),
(1, 1002, 'Hard'),
(2, 2001, 'Alternative Hip-Hop'),
(2, 2002, 'Dance Hip-Hop'),
(3, 3001, 'Classic Jazz'),
(3, 3002, 'Hot Jazz'),
(4, 4001, 'Punk Rock'),
(4, 4002, 'Soft Rock'),
(5, 5001, 'Dance Pop'),
(5, 5002, 'Country Pop');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(32) DEFAULT NULL,
  `fname` varchar(32) DEFAULT NULL,
  `lname` varchar(32) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `street` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_logout` datetime DEFAULT NULL,
  `trust_level` int(3) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `fname`, `lname`, `dob`, `age`, `street`, `city`, `state`, `country`, `last_login`, `last_logout`, `trust_level`) VALUES
('abc123', 'abc123', 'Ajay', 'Chaudhry', '1969-12-30', 45, '8th Street', 'Brooklyn', 'New York', 'USA', '2014-11-30 05:30:34', '2014-11-30 06:00:51', 20),
('ass21', 'ass21', 'Amit', 'sandesara', '1975-12-30', 39, 'Xial', 'Bejing', 'Bejing', 'China', '2014-09-12 00:00:00', '2014-09-13 00:00:00', 30),
('Moh@99', 'moh@99', 'Mohnish', 'Mane', '1992-09-29', 22, 'Rajash Lane', 'Mumbai', 'Maharashtra', 'India', '2014-11-30 03:11:29', '2014-11-30 03:11:55', 10),
('nmm32', 'nmm32', 'Nimish', 'Mahatre', '1982-09-04', 32, 'ChunaBhatti', 'Mumbai', 'Maharashtra', 'India', '2014-08-18 00:00:00', '2014-08-19 00:00:00', 25),
('nmw33', 'nmw33', 'Nitin', 'Wagadia', '1992-08-28', 22, 'Charkop Market', 'Mumbai', 'Maharashtra', 'India', '2014-12-04 23:51:30', '2014-12-04 23:51:33', 40),
('pakya', 'pakya', 'Prakash', 'Patel', '1992-07-23', NULL, NULL, '', '', '', NULL, NULL, 50),
('Ro951', 'ro951', 'Roland', 'Dsa', '1995-01-18', 20, '257th Steet 2nd Ave', 'Manhattan', 'New York', 'USA', '2014-11-30 03:08:23', '2014-11-30 03:10:25', 20),
('sau69', 'sau69', 'Saurabh', 'Jain', '1992-08-18', 22, '108th', 'South Richmond Hill', 'New York', 'USA', '2014-12-04 23:51:38', '2014-12-04 03:40:51', 15),
('Sneha58', 'sneha58', 'Sneha', 'Jain', '1992-08-18', 22, 'Saurabh Lane', 'Mumbai', 'Maharashtra', 'India', '2014-11-30 06:02:27', '2014-11-30 03:11:21', 35),
('Sunny', 'e6adebe90b71bb26a9c3ab4e9b3a3d13', 'Sunny', 'Gandhi', '2014-11-26', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 45),
('Varsha582', 'varsha582', 'Varsha', 'Jain', '1989-12-30', 25, 'Sion', 'Chicago', 'New York', 'USA', '2014-11-18 00:00:00', '2014-11-19 00:00:00', 10),
('vvm321', 'vvm321', 'Varun', 'Mahapadi', '1989-07-10', 25, '14th E', 'Manhattan', 'New York', 'USA', '2014-11-12 00:00:00', '2014-11-13 00:00:00', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_fan`
--

CREATE TABLE IF NOT EXISTS `user_fan` (
  `username` varchar(32) NOT NULL DEFAULT '',
  `band_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`,`band_id`),
  KEY `band_id` (`band_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_fan`
--

INSERT INTO `user_fan` (`username`, `band_id`) VALUES
('abc123', 1),
('nmm32', 1),
('nmw33', 1),
('Ro951', 1),
('sau69', 1),
('abc123', 2),
('nmm32', 2),
('Ro951', 2),
('sau69', 2),
('abc123', 3),
('nmm32', 3),
('sau69', 3),
('abc123', 4),
('nmm32', 4),
('nmw33', 4),
('nmw33', 5),
('Ro951', 5),
('sau69', 5),
('nmw33', 6),
('Ro951', 6);

-- --------------------------------------------------------

--
-- Table structure for table `user_genre`
--

CREATE TABLE IF NOT EXISTS `user_genre` (
  `username` varchar(32) NOT NULL DEFAULT '',
  `sub_genre_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`,`sub_genre_id`),
  KEY `sub_genre_id` (`sub_genre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_genre`
--

INSERT INTO `user_genre` (`username`, `sub_genre_id`) VALUES
('abc123', 1001),
('nmm32', 1001),
('Varsha582', 1001),
('abc123', 1002),
('vvm321', 1002),
('vvm321', 2001),
('nmw33', 2002),
('ass21', 3001),
('Sneha58', 3001),
('vvm321', 3001),
('nmm32', 3002),
('Ro951', 3002),
('abc123', 4001),
('nmw33', 4001),
('sau69', 4001),
('Moh@99', 4002),
('Ro951', 5001),
('sau69', 5001),
('vvm321', 5001),
('Moh@99', 5002),
('sau69', 5002);

-- --------------------------------------------------------

--
-- Table structure for table `user_recommendation`
--

CREATE TABLE IF NOT EXISTS `user_recommendation` (
  `username` varchar(32) NOT NULL DEFAULT '',
  `concert_id` int(11) NOT NULL DEFAULT '0',
  `reco_name` varchar(100) DEFAULT NULL,
  `date_time` datetime DEFAULT NULL,
  PRIMARY KEY (`username`,`concert_id`),
  KEY `concert_id` (`concert_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_recommendation`
--

INSERT INTO `user_recommendation` (`username`, `concert_id`, `reco_name`, `date_time`) VALUES
('abc123', 10, 'Abcs Metal List', '2015-08-15 00:00:00'),
('abc123', 11, 'Abcs Metal List', '2015-08-15 00:00:00'),
('abc123', 16, 'Abcs Rock list', '2015-08-15 00:00:00'),
('nmw33', 3, 'NMW favorites', '2014-12-15 00:00:00'),
('nmw33', 7, 'NMW Reco', '2014-12-25 00:00:00'),
('nmw33', 8, 'NMW Reco', '2014-12-04 05:26:02'),
('nmw33', 14, 'NMW favorites', '2016-07-15 00:00:00'),
('sau69', 1, 'Saurabh''s Metal Music', '2014-12-03 19:16:59'),
('sau69', 2, 'Saurabh''s Metal Music', '2014-12-03 21:27:29'),
('sau69', 8, 'Saurabh''s Rock Music', '2014-12-03 18:45:54'),
('sau69', 19, 'Saurabhs Pop List', '2014-12-03 00:00:00'),
('sau69', 20, 'Saurabhs Pop list', '2015-04-15 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_reg_concert`
--

CREATE TABLE IF NOT EXISTS `user_reg_concert` (
  `reg_id` int(11) NOT NULL DEFAULT '0',
  `username` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`reg_id`,`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_reg_concert`
--

INSERT INTO `user_reg_concert` (`reg_id`, `username`) VALUES
(1, 'abc123'),
(1, 'ass21'),
(1, 'Moh@99'),
(1, 'nmm32'),
(1, 'nmw33'),
(1, 'Ro951'),
(1, 'sau69'),
(1, 'Sneha58'),
(1, 'Varsha582'),
(1, 'vvm321'),
(2, 'abc123'),
(2, 'Moh@99'),
(2, 'sau69'),
(2, 'Sneha58'),
(2, 'Varsha582'),
(3, 'abc123'),
(3, 'ass21'),
(3, 'Sneha58'),
(3, 'vvm321'),
(4, 'ass21'),
(4, 'Moh@99'),
(4, 'nmm32'),
(4, 'Ro951'),
(4, 'sau69'),
(4, 'Varsha582'),
(5, 'abc123'),
(5, 'nmm32'),
(5, 'Sneha58'),
(6, 'nmm32'),
(6, 'vvm321'),
(7, 'Moh@99'),
(7, 'nmm32'),
(7, 'Sneha58'),
(8, 'nmw33'),
(8, 'Ro951'),
(8, 'sau69'),
(8, 'Varsha582'),
(8, 'vvm321'),
(9, 'abc123'),
(9, 'ass21'),
(9, 'Moh@99'),
(9, 'nmm32'),
(9, 'nmw33'),
(9, 'Ro951'),
(9, 'sau69'),
(9, 'Sneha58'),
(9, 'Varsha582'),
(9, 'vvm321'),
(10, 'ass21'),
(10, 'nmm32'),
(10, 'Ro951'),
(11, 'Moh@99'),
(11, 'nmw33'),
(11, 'Ro951'),
(11, 'sau69'),
(11, 'Varsha582'),
(12, 'nmm32'),
(12, 'vvm321'),
(13, 'Moh@99'),
(13, 'nmm32'),
(13, 'vvm321'),
(14, 'abc123'),
(14, 'ass21'),
(14, 'nmm32'),
(14, 'Ro951'),
(15, 'nmm32'),
(15, 'sau69'),
(15, 'Sneha58'),
(16, 'Moh@99'),
(16, 'nmm32'),
(16, 'nmw33'),
(16, 'Varsha582'),
(17, 'abc123'),
(17, 'Ro951'),
(18, 'ass21'),
(18, 'nmm32'),
(18, 'sau69'),
(19, 'nmm32'),
(19, 'Sneha58'),
(20, 'Moh@99'),
(20, 'nmw33'),
(21, 'sau69'),
(21, 'Varsha582'),
(21, 'vvm321'),
(22, 'nmw33'),
(23, 'abc123'),
(27, 'sau69');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE IF NOT EXISTS `venue` (
  `venue_id` int(11) NOT NULL AUTO_INCREMENT,
  `street` varchar(32) DEFAULT NULL,
  `city` varchar(32) DEFAULT NULL,
  `state` varchar(32) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `street`, `city`, `state`, `country`) VALUES
(1, '8th Street', 'Brooklyn', 'New York', 'USA'),
(2, 'Rajash Lane', 'Mumbai', 'Maharashtra', 'India'),
(3, '14th E', 'Manhattan', 'New York', 'USA'),
(4, '108th Street', 'Brooklyn', 'New York', 'USA'),
(5, 'Sion', 'Chicago', 'New York', 'USA');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `band_artist`
--
ALTER TABLE `band_artist`
  ADD CONSTRAINT `band_artist_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artist` (`artist_id`),
  ADD CONSTRAINT `band_artist_ibfk_2` FOREIGN KEY (`band_id`) REFERENCES `band` (`band_id`);

--
-- Constraints for table `band_genre`
--
ALTER TABLE `band_genre`
  ADD CONSTRAINT `band_genre_ibfk_1` FOREIGN KEY (`sub_genre_id`) REFERENCES `sub_genre` (`sub_genre_id`);

--
-- Constraints for table `sub_genre`
--
ALTER TABLE `sub_genre`
  ADD CONSTRAINT `sub_genre_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genre` (`genre_id`);

--
-- Constraints for table `user_genre`
--
ALTER TABLE `user_genre`
  ADD CONSTRAINT `user_genre_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `user_genre_ibfk_2` FOREIGN KEY (`sub_genre_id`) REFERENCES `sub_genre` (`sub_genre_id`);

--
-- Constraints for table `user_recommendation`
--
ALTER TABLE `user_recommendation`
  ADD CONSTRAINT `user_recommendation_ibfk_1` FOREIGN KEY (`username`) REFERENCES `user` (`username`),
  ADD CONSTRAINT `user_recommendation_ibfk_2` FOREIGN KEY (`concert_id`) REFERENCES `concert` (`concert_id`);
--
-- Database: `shop`
--
CREATE DATABASE IF NOT EXISTS `shop` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `shop`;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `PHONE` varchar(10) NOT NULL,
  `BUILDING_NUM` int(11) NOT NULL,
  `STREET` varchar(20) NOT NULL,
  `APARTMENT` varchar(20) NOT NULL,
  PRIMARY KEY (`PHONE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`PHONE`, `BUILDING_NUM`, `STREET`, `APARTMENT`) VALUES
('9174447304', 17, '108', '3'),
('9174447305', 17, '108', '3'),
('9179799577', 17, '108', '3'),
('9323090957', 5, '10', '401'),
('9870684064', 12, '111', '5'),
('9870700419', 2, '111', '501');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `SNAME` varchar(20) NOT NULL,
  `SIZE` varchar(20) NOT NULL,
  `PRICE` decimal(4,2) NOT NULL,
  PRIMARY KEY (`SNAME`,`SIZE`),
  KEY `SNAME` (`SNAME`),
  KEY `SIZE` (`SIZE`),
  KEY `SNAME_2` (`SNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`SNAME`, `SIZE`, `PRICE`) VALUES
('Bacon', 'large', '3.50'),
('Bacon', 'medium', '2.50'),
('Bacon', 'small', '1.50'),
('Barros Jarpa', 'medium', '4.50'),
('Bosna', 'large', '3.50'),
('Bosna', 'medium', '5.50'),
('Chicken', 'large', '5.50'),
('Chicken', 'medium', '4.50'),
('Chicken', 'small', '2.50'),
('Falafel', 'large', '4.50'),
('Falafel', 'medium', '3.50'),
('Falafel', 'small', '2.50'),
('Hamburger', 'large', '3.50'),
('Hamburger', 'medium', '5.50'),
('Melt', 'large', '5.50'),
('Melt', 'medium', '4.50'),
('Melt', 'small', '2.50'),
('Toast', 'large', '4.50'),
('Toast', 'medium', '3.50'),
('Toast', 'small', '2.50'),
('Vada Pav', 'large', '4.50'),
('Vada Pav', 'medium', '3.50');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `PHONE` varchar(10) NOT NULL,
  `SNAME` varchar(20) NOT NULL,
  `SIZE` varchar(20) NOT NULL,
  `O_TIME` datetime NOT NULL,
  `QUANTITY` int(11) NOT NULL,
  `STATUS` varchar(10) NOT NULL,
  PRIMARY KEY (`PHONE`,`SNAME`,`SIZE`,`O_TIME`),
  KEY `PHONE` (`PHONE`,`SNAME`,`SIZE`),
  KEY `SNAME` (`SNAME`),
  KEY `SIZE` (`SIZE`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`PHONE`, `SNAME`, `SIZE`, `O_TIME`, `QUANTITY`, `STATUS`) VALUES
('9174447304', 'Bacon', 'large', '2014-11-13 15:47:53', 7, 'Complete'),
('9174447304', 'Bacon', 'large', '2014-11-13 15:54:32', 1, 'Pending'),
('9174447304', 'Barros Jarpa', 'medium', '2014-11-06 05:12:16', 2, 'Complete'),
('9174447304', 'Chicken', 'small', '2014-11-04 04:16:26', 1, 'Complete'),
('9174447304', 'Hamburger', 'large', '2014-11-08 08:16:14', 1, 'Delivering'),
('9174447304', 'Melt', 'large', '2014-11-08 06:05:24', 2, 'Delivering'),
('9174447305', 'Bacon', 'small', '2014-11-08 08:13:33', 2, 'Pending'),
('9174447305', 'Bosna', 'large', '2014-11-07 12:25:30', 1, 'Delivering'),
('9174447305', 'Bosna', 'large', '2014-11-13 15:45:12', 1, 'Pending'),
('9174447305', 'Bosna', 'medium', '2014-10-16 13:10:35', 1, 'Complete'),
('9174447305', 'Toast', 'medium', '2014-10-07 02:28:11', 2, 'Complete'),
('9870684064', 'Bacon', 'large', '2014-11-13 15:44:45', 35, 'Pending'),
('9870684064', 'Barros Jarpa', 'medium', '2014-12-03 23:20:19', 1, 'Pending'),
('9870684064', 'Bosna', 'medium', '2014-12-03 23:20:07', 1, 'Pending'),
('9870684064', 'Falafel', 'medium', '2014-11-13 15:55:18', 12, 'Pending'),
('9870684064', 'Falafel', 'small', '2014-11-13 03:08:28', 1, 'Pending'),
('9870684064', 'Hamburger', 'medium', '2014-11-13 15:46:46', 3, 'Pending'),
('9870684064', 'Melt', 'small', '2014-11-13 04:17:10', 1, 'Pending'),
('9870700419', 'Bacon', 'large', '2014-11-13 15:42:31', 2, 'Pending'),
('9870700419', 'Bacon', 'medium', '2014-11-13 01:49:58', 3, 'Pending'),
('9870700419', 'Bacon', 'medium', '2014-11-13 01:59:10', 3, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `sandwich`
--

CREATE TABLE IF NOT EXISTS `sandwich` (
  `SNAME` varchar(20) NOT NULL,
  `DESCRIPTION` varchar(100) NOT NULL,
  PRIMARY KEY (`SNAME`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sandwich`
--

INSERT INTO `sandwich` (`SNAME`, `DESCRIPTION`) VALUES
('Bacon', 'Generally served hot, and often eaten with ketchup or brown sauce'),
('Barros Jarpa', 'Ham and cheese, usually mantecoso, which is similar to farmer cheese'),
('Bosna', 'Bratwurst sausage, onions,tomato ketchup, mustard, and curry powder'),
('Chicken', 'Crispy fried chicken breast fillet and two dill pickle chips on a round white bun'),
('Falafel', 'Hot sauce, tahini-based sauces pickled vegetables, wrapped in pita bread'),
('Hamburger', 'A ground beef patty, often with vegetables, sauces and other meats'),
('Melt', 'A generic sandwich containing a filling and a layer of cheese, grilled or fried until the cheese is '),
('Toast', 'A thin slice of toast between two thin slices of bread with a layer of butter'),
('Vada pav', 'A potato fritter coated in chickpea flour in a bun');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `SANDWICH NAME` FOREIGN KEY (`SNAME`) REFERENCES `sandwich` (`SNAME`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `PHONE` FOREIGN KEY (`PHONE`) REFERENCES `customer` (`PHONE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SIZE` FOREIGN KEY (`SIZE`) REFERENCES `menu` (`SIZE`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `SNAME` FOREIGN KEY (`SNAME`) REFERENCES `menu` (`SNAME`) ON DELETE CASCADE ON UPDATE CASCADE;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
