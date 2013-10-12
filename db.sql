-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- Hoszt: localhost
-- Létrehozás ideje: 2013. Okt 10. 21:50
-- Szerver verzió: 5.0.45
-- PHP Verzió: 5.2.3
 
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
 
-- 
-- Adatbázis: `foci`
-- 
 
-- --------------------------------------------------------
 
-- 
-- Tábla szerkezet: `change`
-- 
 
CREATE TABLE `change` (
  `ID` bigint(255) NOT NULL auto_increment COMMENT 'Változás ID',
  `PID` bigint(255) NOT NULL COMMENT 'Játékos ID',
  `oCID` int(255) NOT NULL COMMENT 'régi klub ID',
  `nCID` int(255) NOT NULL COMMENT 'új klub ID',
  `CTime` int(13) NOT NULL,
  `CPrice` varchar(100) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=40 ;
 
-- 
-- Tábla adatok: `change`
-- 
 
INSERT INTO `change` VALUES (8, 1, 8, 7, 1380664800, '2000Ft');
INSERT INTO `change` VALUES (7, 1, 6, 8, 1380578400, '1000Ft');
INSERT INTO `change` VALUES (9, 1, 7, 5, 1380751200, '3000Ft');
INSERT INTO `change` VALUES (10, 1, 5, 8, 1380837600, '4000Ft');
INSERT INTO `change` VALUES (11, 1, 8, 9, 1380924000, '5000Ft');
INSERT INTO `change` VALUES (12, 1, 9, 8, 1381010400, '6000Ft');
INSERT INTO `change` VALUES (18, 2, 8, 7, 1356994800, '1000Ft');
INSERT INTO `change` VALUES (22, 1, 8, 7, 1381096800, '1000000Ft');
INSERT INTO `change` VALUES (20, 2, 7, 8, 1357167600, '10000Ft');
INSERT INTO `change` VALUES (23, 3, 1, 7, 1356994800, '1000000Ft');
INSERT INTO `change` VALUES (24, 10, 8, 1, 1370469600, '10000Ft');
INSERT INTO `change` VALUES (28, 10, 1, 6, 1376949600, '1000Ft');
INSERT INTO `change` VALUES (27, 10, 6, 5, 1380664800, '1000Ft');
INSERT INTO `change` VALUES (29, 5, 1, 10, 1313704800, '1Ft');
INSERT INTO `change` VALUES (30, 7, 1, 8, 1302645600, '1000Ft');
INSERT INTO `change` VALUES (31, 8, 1, 8, 1307656800, '1000$');
INSERT INTO `change` VALUES (32, 9, 1, 9, 1337292000, '4000€');
INSERT INTO `change` VALUES (33, 9, 9, 8, 1346018400, '3000€');
INSERT INTO `change` VALUES (34, 9, 8, 10, 1356130800, '1000€');
INSERT INTO `change` VALUES (35, 11, 8, 9, 1356994800, '777$');
INSERT INTO `change` VALUES (37, 21, 12, 14, 1380837600, '1$');
INSERT INTO `change` VALUES (38, 21, 14, 9, 1380924000, '1Ft');
INSERT INTO `change` VALUES (39, 21, 9, 12, 1381010400, '1000Ft');
 
-- --------------------------------------------------------
 
-- 
-- Tábla szerkezet: `club`
-- 
 
CREATE TABLE `club` (
  `CID` int(255) NOT NULL auto_increment COMMENT 'Klub ID',
  `CName` varchar(100) NOT NULL,
  `CFound` smallint(4) NOT NULL,
  PRIMARY KEY  (`CID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;
 
-- 
-- Tábla adatok: `club`
-- 
 
INSERT INTO `club` VALUES (1, 'MIB - Miskolci Ifjúsági Bál ', 2011);
INSERT INTO `club` VALUES (5, 'MKGV - Magyar Központi Gépgyár Válogatott', 2000);
INSERT INTO `club` VALUES (6, 'NOV - Nagyon Okos Válogatott', 1850);
INSERT INTO `club` VALUES (7, 'KOP - Központi Oktatási Pont', 1989);
INSERT INTO `club` VALUES (8, 'BBB - Bálint Bátya Bikái', 1999);
INSERT INTO `club` VALUES (9, 'MNB - Mátrai Nap Bora', 1880);
INSERT INTO `club` VALUES (10, 'HHH - Hol Hogy Haa', 2005);
INSERT INTO `club` VALUES (11, 'TTC1 - Törlés Teszt Csapat', 2013);
INSERT INTO `club` VALUES (12, 'TTC2 - Törlés Teszt Csapat2', 2013);
INSERT INTO `club` VALUES (14, 'TTC3 - Törlés Teszt Csapat3', 1999);
 
-- --------------------------------------------------------
 
-- 
-- Tábla szerkezet: `player`
-- 
 
CREATE TABLE `player` (
  `PID` bigint(255) NOT NULL auto_increment COMMENT 'Játékos ID',
  `PName` varchar(100) NOT NULL,
  `PAge` smallint(1) NOT NULL,
  `PCID` int(255) NOT NULL COMMENT 'Klub ID',
  `PNation` varchar(100) NOT NULL,
  PRIMARY KEY  (`PID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;
 
-- 
-- Tábla adatok: `player`
-- 
 
INSERT INTO `player` VALUES (1, 'Boka János', 20, 7, 'Magyar');
INSERT INTO `player` VALUES (2, 'Ferkó Béla', 17, 8, 'Magyar');
INSERT INTO `player` VALUES (3, 'Mekk Elek', 19, 7, 'Magyar');
INSERT INTO `player` VALUES (4, 'Kis Ernő', 34, 1, 'Magyar');
INSERT INTO `player` VALUES (5, 'Papp Pista', 22, 10, 'Magyar');
INSERT INTO `player` VALUES (6, 'Undok Zoltán', 37, 1, 'Magyar');
INSERT INTO `player` VALUES (7, 'Nagy Ferenc', 67, 8, 'Magyar');
INSERT INTO `player` VALUES (8, 'Miklós Miklós', 23, 8, 'Magyar');
INSERT INTO `player` VALUES (9, 'Balga Béla', 36, 10, 'Magyar');
INSERT INTO `player` VALUES (10, 'Magyar Imre Zsolt', 15, 5, 'Magyar');
INSERT INTO `player` VALUES (11, 'Bálint Sándor', 33, 9, 'Magyar');
INSERT INTO `player` VALUES (12, 'Cirkáló Tamás', 16, 9, 'Magyar');
INSERT INTO `player` VALUES (14, 'Búgó Előd', 30, 1, 'Magyar');
INSERT INTO `player` VALUES (15, 'Háros Herold', 33, 9, 'Magyar');
INSERT INTO `player` VALUES (16, 'Törlés teszt 1', 12, 11, 'Magyar');
INSERT INTO `player` VALUES (17, 'Törlés teszt 2', 22, 11, 'Magyar');
INSERT INTO `player` VALUES (18, 'Törlés teszt 3', 42, 11, 'Magyar');
INSERT INTO `player` VALUES (19, 'Törlés teszt 4', 11, 12, 'Magyar');
INSERT INTO `player` VALUES (21, 'Géza Béla', 33, 12, 'Magyar');
