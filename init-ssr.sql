-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2017-05-17 18:48:30
-- 服务器版本： 5.5.53-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ssr`
--

-- --------------------------------------------------------

--
-- 表的结构 `code`
--

CREATE TABLE `code` (
  `id` int(11) NOT NULL,
  `code` varchar(16) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `month` int(11) NOT NULL DEFAULT '1',
  `reg_date` datetime NOT NULL,
  `use_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `host` varchar(32) NOT NULL,
  `method` varchar(32) NOT NULL DEFAULT 'aes-256-cfb',
  `protocol` varchar(32) NOT NULL DEFAULT 'auth_aes128_md5',
  `obfs` varchar(32) NOT NULL DEFAULT 'tls1.2_ticket_auth',
  `param` varchar(128) DEFAULT NULL,
  `name` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `traffic`
--

CREATE TABLE `traffic` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `record_date` date NOT NULL,
  `traffic` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='流量统计表';

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `name` varchar(16) NOT NULL,
  `email` varchar(32) NOT NULL,
  `qq` varchar(15) DEFAULT NULL,
  `pass` varchar(32) NOT NULL,
  `passwd` varchar(16) NOT NULL,
  `t` int(11) NOT NULL DEFAULT '0',
  `u` bigint(20) NOT NULL,
  `d` bigint(20) NOT NULL,
  `day_start` bigint(20) DEFAULT NULL,
  `total` bigint(20) NOT NULL,
  `transfer_enable` bigint(20) NOT NULL,
  `port` int(11) NOT NULL,
  `enable` tinyint(4) NOT NULL DEFAULT '1',
  `expire_date` date NOT NULL,
  `reg_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_get` datetime DEFAULT NULL,
  `sckey` varchar(64) DEFAULT NULL,
  `remarks` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `code`
--
ALTER TABLE `code`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `host` (`host`);

--
-- Indexes for table `traffic`
--
ALTER TABLE `traffic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `qq` (`qq`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `code`
--
ALTER TABLE `code`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=223;
--
-- 使用表AUTO_INCREMENT `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `traffic`
--
ALTER TABLE `traffic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=598;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
