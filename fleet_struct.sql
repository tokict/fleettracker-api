/*
SQLyog Community v12.4.3 (64 bit)
MySQL - 5.7.19-0ubuntu0.16.04.1 : Database - fleettracker
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`fleettracker` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `fleettracker`;

/*Table structure for table `assignments` */

DROP TABLE IF EXISTS `assignments`;

CREATE TABLE `assignments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned DEFAULT NULL,
  `type` enum('vehicle','issue','renewal') DEFAULT NULL,
  `contact_id` int(10) unsigned NOT NULL,
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `notify` tinyint(1) DEFAULT '1',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `odo_start` int(11) DEFAULT NULL,
  `odo_end` int(11) DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `company_id` (`company_id`),
  CONSTRAINT `assignments_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  CONSTRAINT `assignments_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `type` enum('issue','service','renewal','vendor','vehicle') DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `text` mediumtext,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `parent_comment_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `status` enum('active','reported','removed') NOT NULL DEFAULT 'active',
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_user1_idx` (`user_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8;

/*Table structure for table `companies` */

DROP TABLE IF EXISTS `companies`;

CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `contact_phone` varchar(45) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `tax_id` varchar(45) DEFAULT NULL,
  `contact_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `itrack_token` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_company_countries1_idx` (`country_id`),
  KEY `fk_company_contact1_idx` (`contact_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `companies_ibfk_1` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_company_contact` FOREIGN KEY (`contact_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_company_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

/*Table structure for table `contacts` */

DROP TABLE IF EXISTS `contacts`;

CREATE TABLE `contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `birth_date` datetime DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile_phone` varchar(45) DEFAULT NULL,
  `home_phone` varchar(45) DEFAULT NULL,
  `work_phone` varchar(45) DEFAULT NULL,
  `other_phone` varchar(45) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `address_2` varchar(100) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `employee_number` varchar(45) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `leave_date` datetime DEFAULT NULL,
  `driver` tinyint(1) DEFAULT '0',
  `driver_license_number` varchar(45) DEFAULT NULL,
  `driver_license_class` varchar(45) DEFAULT NULL,
  `driver_license_region` varchar(45) DEFAULT NULL,
  `hourly_rate` int(10) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `image_id` int(10) unsigned DEFAULT NULL,
  `company_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `itrack_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`),
  KEY `country_id` (`country_id`),
  KEY `deleted_by` (`deleted_by`),
  KEY `image_id` (`image_id`),
  KEY `company_id` (`company_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `contacts_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  CONSTRAINT `contacts_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`),
  CONSTRAINT `contacts_ibfk_3` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `contacts_ibfk_4` FOREIGN KEY (`image_id`) REFERENCES `media` (`id`),
  CONSTRAINT `contacts_ibfk_5` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `contacts_ibfk_6` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

/*Table structure for table `countries` */

DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `eu` tinyint(4) NOT NULL DEFAULT '0',
  `priority` smallint(6) NOT NULL DEFAULT '0',
  `code` varchar(2) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `groups` */

DROP TABLE IF EXISTS `groups`;

CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `company_id` int(11) unsigned NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `company_id` (`company_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `issues` */

DROP TABLE IF EXISTS `issues`;

CREATE TABLE `issues` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned NOT NULL,
  `reported_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `summary` varchar(140) NOT NULL,
  `description` mediumtext,
  `odometer` int(10) unsigned DEFAULT NULL,
  `reported_by` int(10) unsigned DEFAULT NULL,
  `assigned_to` int(10) unsigned DEFAULT NULL,
  `submitted_by` int(10) unsigned NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `photo_ids` varchar(254) DEFAULT NULL,
  `document_ids` varchar(254) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` enum('open','resolved') DEFAULT 'open',
  `company_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_issue_vehicle1_idx` (`vehicle_id`),
  KEY `fk_issue_contact1_idx` (`reported_by`),
  KEY `fk_issue_contact2_idx` (`assigned_to`),
  KEY `submitted_by` (`submitted_by`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `fk_issue_contact` FOREIGN KEY (`reported_by`) REFERENCES `contacts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_issue_contact_2` FOREIGN KEY (`assigned_to`) REFERENCES `contacts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_issue_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `issues_ibfk_1` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `issues_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8;

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('image','video','document') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `title` varchar(45) DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `directory` varchar(255) NOT NULL DEFAULT '""',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `uploaded_by` int(10) unsigned DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uploaded_by` (`company_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `media_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`),
  CONSTRAINT `media_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

/*Table structure for table `media_links` */

DROP TABLE IF EXISTS `media_links`;

CREATE TABLE `media_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `issue_id` int(10) unsigned DEFAULT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `media_id` int(10) unsigned DEFAULT NULL,
  `service_id` int(10) unsigned DEFAULT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_links_document_id_foreign` (`issue_id`),
  KEY `media_links_campaign_id_foreign` (`vehicle_id`),
  KEY `media_links_media_id_foreign` (`media_id`),
  KEY `media_links_donor_id_foreign` (`user_id`),
  KEY `media_links_person_id_foreign` (`service_id`),
  CONSTRAINT `media_links_issue_id_foreign` FOREIGN KEY (`issue_id`) REFERENCES `issues` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `media_links_media_id_foreign` FOREIGN KEY (`media_id`) REFERENCES `media` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `media_links_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `media_links_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `media_links_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('renewal','service','other') NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `text` mediumtext NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `subtype` enum('noticeOdo1000','noticeOdo500','noticeTime15','noticeTime3','noticeTimeUser','noticeOdoUser','noticeTime') DEFAULT NULL,
  `by_email` tinyint(4) NOT NULL DEFAULT '0',
  `by_sms` tinyint(4) NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned DEFAULT NULL,
  `contact_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `contact_id` (`contact_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=275 DEFAULT CHARSET=utf8;

/*Table structure for table `odometer_entries` */

DROP TABLE IF EXISTS `odometer_entries`;

CREATE TABLE `odometer_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `odo_end` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vehicleId` (`vehicle_id`),
  KEY `created_by` (`created_by`),
  KEY `deleted_by` (`deleted_by`),
  KEY `updated_by` (`updated_by`),
  CONSTRAINT `odometer_entries_ibfk_1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`),
  CONSTRAINT `odometer_entries_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `odometer_entries_ibfk_3` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `odometer_entries_ibfk_4` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3134 DEFAULT CHARSET=utf8;

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Table structure for table `reminders` */

DROP TABLE IF EXISTS `reminders`;

CREATE TABLE `reminders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `service_type_id` int(10) unsigned DEFAULT NULL,
  `odometer_interval` int(10) unsigned DEFAULT NULL,
  `time_interval` int(10) unsigned DEFAULT NULL,
  `odometer_threshold` int(10) unsigned DEFAULT NULL,
  `time_threshold` int(10) unsigned DEFAULT NULL,
  `time_interval_unit` enum('days','weeks','months','years') DEFAULT NULL,
  `time_threshold_unit` enum('days','weeks','months','years') DEFAULT NULL,
  `email` tinyint(4) NOT NULL DEFAULT '0',
  `renewal_type_id` int(10) DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `sms` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `company_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reminders_service_types1_idx` (`service_type_id`),
  KEY `created_by` (`created_by`),
  KEY `deleted_by` (`deleted_by`),
  KEY `renewal_type_id` (`renewal_type_id`),
  CONSTRAINT `fk_reminders_service_types` FOREIGN KEY (`service_type_id`) REFERENCES `service_types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `reminders_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `reminders_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `reminders_ibfk_3` FOREIGN KEY (`renewal_type_id`) REFERENCES `renewal_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

/*Table structure for table `renewal_types` */

DROP TABLE IF EXISTS `renewal_types`;

CREATE TABLE `renewal_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

/*Table structure for table `service_types` */

DROP TABLE IF EXISTS `service_types`;

CREATE TABLE `service_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `multiple` tinyint(4) NOT NULL DEFAULT '0',
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `owner` int(10) unsigned DEFAULT NULL,
  `category` enum('liquids','engine','filters','brakes','suspension','other') NOT NULL DEFAULT 'other',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `service_types_ibfk_1` FOREIGN KEY (`owner`) REFERENCES `companies` (`id`),
  CONSTRAINT `service_types_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

/*Table structure for table `services` */

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `serviced_at` datetime NOT NULL,
  `vehicle_id` int(10) unsigned NOT NULL,
  `odometer` int(10) unsigned DEFAULT NULL,
  `performed_service_types` varchar(200) DEFAULT NULL,
  `resolved_issues` varchar(200) DEFAULT NULL,
  `vendor_id` int(10) unsigned DEFAULT NULL,
  `reference` varchar(45) DEFAULT NULL,
  `labor_price` int(10) unsigned DEFAULT NULL,
  `parts_price` int(10) unsigned DEFAULT NULL,
  `tax` int(10) unsigned DEFAULT NULL,
  `total` int(10) unsigned DEFAULT NULL,
  `tax_type` enum('percent','sum') DEFAULT 'percent',
  `created_by` int(10) unsigned NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `document_ids` varchar(200) DEFAULT NULL,
  `photo_ids` varchar(200) DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `performed_renewal_types` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_service_vehicle1_idx` (`vehicle_id`),
  KEY `fk_service_vendor1_idx` (`vendor_id`),
  KEY `created_by` (`created_by`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `fk_service_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_service_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `services_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `services_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8;

/*Table structure for table `subscriptions` */

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `contact_id` int(10) unsigned DEFAULT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `type` enum('renewal','service','issue','comment') CHARACTER SET latin1 NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `vehicle_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subscription_user1_idx` (`contact_id`),
  KEY `deleted_by` (`deleted_by`),
  KEY `vehicle_id` (`vehicle_id`),
  CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `subscriptions_ibfk_3` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=577 DEFAULT CHARSET=utf8;

/*Table structure for table `user_activations` */

DROP TABLE IF EXISTS `user_activations`;

CREATE TABLE `user_activations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `token` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `activated` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `user_activations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(500) NOT NULL,
  `contact_id` int(10) unsigned DEFAULT NULL,
  `driver` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(10) unsigned DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'inactive',
  `super_admin` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `company_id` int(10) unsigned DEFAULT NULL,
  `remember_token` varchar(60) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `fk_user_company1_idx` (`company_id`),
  KEY `fk_user_contact1_idx` (`contact_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `fk_user_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

/*Table structure for table `vehicle_makers` */

DROP TABLE IF EXISTS `vehicle_makers`;

CREATE TABLE `vehicle_makers` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_vehicle_maker_countries1_idx` (`country_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `fk_vehicle_maker_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vehicle_makers_ibfk_1` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `vehicle_models` */

DROP TABLE IF EXISTS `vehicle_models`;

CREATE TABLE `vehicle_models` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `vehicle_maker_id` int(10) unsigned NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_vehicle_model_vehicle_maker1_idx` (`vehicle_maker_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `vehicle_models_ibfk_1` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vehicle_models_ibfk_2` FOREIGN KEY (`vehicle_maker_id`) REFERENCES `vehicle_makers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2119 DEFAULT CHARSET=utf8;

/*Table structure for table `vehicles` */

DROP TABLE IF EXISTS `vehicles`;

CREATE TABLE `vehicles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `vin` varchar(45) DEFAULT NULL,
  `type` enum('light','light_commercial','heavy','machinery') NOT NULL,
  `year` int(4) DEFAULT NULL,
  `maker_id` int(10) unsigned DEFAULT NULL,
  `model_id` int(10) unsigned DEFAULT NULL,
  `plate` varchar(45) DEFAULT NULL,
  `photo_ids` varchar(200) DEFAULT NULL,
  `status` enum('active','inactive','out_of_service','sold','in_shop') DEFAULT NULL,
  `group_id` int(10) unsigned DEFAULT NULL,
  `operator_id` int(10) unsigned DEFAULT NULL,
  `ownership` enum('owned','rental','financial_leasing','operational_leasing') NOT NULL,
  `color` varchar(45) DEFAULT NULL,
  `body` enum('hatchback','sedan','estate','coupe','minivan','pickup','van','tractor','semi_trailer','trailer','machinery','other') NOT NULL,
  `msrp` int(10) unsigned DEFAULT NULL,
  `length` int(10) unsigned DEFAULT NULL,
  `bed_length` int(10) unsigned DEFAULT NULL,
  `curb_weight` int(10) unsigned DEFAULT NULL,
  `max_payload` int(10) unsigned DEFAULT NULL,
  `cargo_volume` int(10) unsigned DEFAULT NULL,
  `epa_city` float unsigned zerofill DEFAULT NULL,
  `epa_highway` float DEFAULT NULL,
  `epa_combined` float DEFAULT NULL,
  `drive_type` enum('front','back','all') DEFAULT NULL,
  `front_tire_type` varchar(45) DEFAULT NULL,
  `rear_tire_type` varchar(45) DEFAULT NULL,
  `fuel_type` enum('petrol','diesel','electric','lpg','cng','hybrid') DEFAULT NULL,
  `fuel_tank_1_capacity` int(11) DEFAULT NULL,
  `fuel_tank_2_capacity` int(11) DEFAULT NULL,
  `oil_capacity` float DEFAULT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `document_ids` varbinary(200) DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `itrack_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_vehicle_vehicle_maker_idx` (`maker_id`),
  KEY `fk_vehicle_vehicle_model1_idx` (`model_id`),
  KEY `fk_vehicle_group1_idx` (`group_id`),
  KEY `fk_vehicle_contact1_idx` (`operator_id`),
  KEY `fk_vehicle_company1_idx` (`company_id`),
  KEY `deleted_by` (`deleted_by`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `fk_vehicle_company_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicle_contact_1` FOREIGN KEY (`operator_id`) REFERENCES `contacts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicle_group_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vehicle_vehicle_model_1` FOREIGN KEY (`model_id`) REFERENCES `vehicle_models` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vehicles_ibfk_1` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vehicles_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `vehicles_ibfk_3` FOREIGN KEY (`maker_id`) REFERENCES `vehicle_makers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8;

/*Table structure for table `vendors` */

DROP TABLE IF EXISTS `vendors`;

CREATE TABLE `vendors` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `city` varchar(45) DEFAULT NULL,
  `zip` varchar(45) DEFAULT NULL,
  `region` varchar(45) DEFAULT NULL,
  `country_id` int(10) unsigned DEFAULT NULL,
  `contact_person_name` varchar(45) DEFAULT NULL,
  `contact_person_email` varchar(100) DEFAULT NULL,
  `contact_person_phone` varchar(45) DEFAULT NULL,
  `company_id` int(10) unsigned DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `deleted_by` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_vendor_company1_idx` (`company_id`),
  KEY `fk_vendor_countries1_idx` (`country_id`),
  KEY `deleted_by` (`deleted_by`),
  CONSTRAINT `fk_vendor_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_vendor_countries` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `vendors_ibfk_1` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
