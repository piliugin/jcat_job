# ************************************************************
# Sequel Pro SQL dump
# Версия 4096
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Адрес: 127.0.0.1 (MySQL 5.5.40-0+wheezy1)
# Схема: jcat
# Время создания: 2014-11-04 14:24:29 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы location
# ------------------------------------------------------------

DROP TABLE IF EXISTS `location`;

CREATE TABLE `location` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realty_id` int(10) unsigned NOT NULL,
  `country` varchar(255) NOT NULL COMMENT 'Страна, в которой расположен объект',
  `region` varchar(255) DEFAULT NULL COMMENT 'Регион указанной страны',
  `district` varchar(255) DEFAULT NULL COMMENT 'Район указанного региона',
  `locality_name` varchar(255) DEFAULT NULL COMMENT 'Название населенного пункта',
  `sub_locality_name` varchar(255) DEFAULT NULL COMMENT 'Район населенного пункта',
  `non_admin_sub_locality` varchar(255) DEFAULT NULL COMMENT 'Неадминистративный район города или ориентир',
  `address` varchar(255) DEFAULT NULL COMMENT 'Улица и номер дома',
  `direction` varchar(255) DEFAULT NULL COMMENT 'Шоссе (только для Москвы)',
  `distance` varchar(255) DEFAULT NULL COMMENT 'Расстояние по шоссе до МКАД',
  `latitude` varchar(100) DEFAULT NULL COMMENT 'Географическая широта',
  `longitude` varchar(100) DEFAULT NULL COMMENT 'Географическая долгота',
  `railway_station` varchar(100) DEFAULT NULL COMMENT 'Ближайшая ж/д станция (для загородной недвижимости)',
  PRIMARY KEY (`id`),
  KEY `FK_location_realty_id_idx` (`realty_id`),
  CONSTRAINT `FK_location_realty_id` FOREIGN KEY (`realty_id`) REFERENCES `realty` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Адреса недвижимости';



# Дамп таблицы metro
# ------------------------------------------------------------

DROP TABLE IF EXISTS `metro`;

CREATE TABLE `metro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `location_id` int(10) unsigned NOT NULL,
  `name` varchar(150) DEFAULT NULL COMMENT 'Название станции',
  `time_on_transport` int(5) DEFAULT NULL COMMENT 'Время до метро в минутах на транспорте',
  `time_on_foot` int(5) DEFAULT NULL COMMENT 'Время до метро в минутах пешком',
  PRIMARY KEY (`id`),
  KEY `FK_metro_location_id_idx` (`location_id`),
  CONSTRAINT `FK_metro_location_id` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Информация о метро';



# Дамп таблицы realty
# ------------------------------------------------------------

DROP TABLE IF EXISTS `realty`;

CREATE TABLE `realty` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('sale','rent') NOT NULL COMMENT 'Тип сделки',
  `property_type` enum('living') NOT NULL COMMENT 'Тип недвижимости',
  `category` enum('flat','room','house','lot','cottage') NOT NULL COMMENT 'Категория объекта',
  `url` varchar(2000) NOT NULL COMMENT 'Ссылка на объявление',
  `creation_date` timestamp NULL DEFAULT NULL,
  `last_update_date` timestamp NULL DEFAULT NULL,
  `expire_date` timestamp NULL DEFAULT NULL,
  `payed_adv` tinyint(1) NOT NULL COMMENT 'Признак оплаченного объявления',
  `manually_added` tinyint(1) NOT NULL COMMENT 'Признак объявления, добавленного вручную',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Объявления о недвижимости';



# Дамп таблицы sales_agent
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sales_agent`;

CREATE TABLE `sales_agent` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `realty_id` int(10) unsigned NOT NULL,
  `name` varchar(150) DEFAULT NULL COMMENT 'имя продавца/арендодателя или агента',
  `category` enum('owner','agency') NOT NULL COMMENT 'тип продавца или арендодателя',
  `organization` varchar(150) DEFAULT NULL COMMENT 'название агентства',
  `agency_id` int(11) DEFAULT NULL COMMENT 'внутренний ID агентства в базе партнера',
  `url` varchar(255) DEFAULT NULL COMMENT 'сайт агентства',
  `phone` varchar(45) DEFAULT NULL COMMENT 'телефон',
  `email` varchar(100) DEFAULT NULL COMMENT 'электронный адрес продавца',
  `partner` varchar(255) DEFAULT NULL COMMENT 'название партнера, предоставившего объявление',
  PRIMARY KEY (`id`),
  KEY `FK_sales_agent_realty_id_idx` (`realty_id`),
  CONSTRAINT `FK_sales_agent_realty_id` FOREIGN KEY (`realty_id`) REFERENCES `realty` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Информация о продавцах';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
