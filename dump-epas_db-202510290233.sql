-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: epas_db
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcement_comments`
--

DROP TABLE IF EXISTS `announcement_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement_comments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcement_comments_announcement_id_foreign` (`announcement_id`),
  KEY `announcement_comments_user_id_foreign` (`user_id`),
  CONSTRAINT `announcement_comments_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcement_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement_comments`
--

LOCK TABLES `announcement_comments` WRITE;
/*!40000 ALTER TABLE `announcement_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement_reads`
--

DROP TABLE IF EXISTS `announcement_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement_reads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `announcement_reads_announcement_id_user_id_unique` (`announcement_id`,`user_id`),
  KEY `announcement_reads_user_id_foreign` (`user_id`),
  CONSTRAINT `announcement_reads_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcement_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement_reads`
--

LOCK TABLES `announcement_reads` WRITE;
/*!40000 ALTER TABLE `announcement_reads` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement_reads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcement_user`
--

DROP TABLE IF EXISTS `announcement_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcement_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `announcement_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `announcement_user_announcement_id_user_id_unique` (`announcement_id`,`user_id`),
  KEY `announcement_user_user_id_foreign` (`user_id`),
  CONSTRAINT `announcement_user_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  CONSTRAINT `announcement_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcement_user`
--

LOCK TABLES `announcement_user` WRITE;
/*!40000 ALTER TABLE `announcement_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcement_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_urgent` tinyint(1) NOT NULL DEFAULT 0,
  `publish_at` timestamp NULL DEFAULT NULL,
  `deadline` timestamp NULL DEFAULT NULL,
  `target_roles` varchar(255) NOT NULL DEFAULT 'all',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `announcements_user_id_foreign` (`user_id`),
  CONSTRAINT `announcements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'New Topic Published','New topic \'Electric History\' (Topic ) has been added to Information Sheet 1.1 in Module Module 1 of Electronic Products Assembly and Servicing NCII.',1,0,0,NULL,NULL,'all','2025-10-28 09:52:22','2025-10-28 09:52:22');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `checklists`
--

DROP TABLE IF EXISTS `checklists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `checklists` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `information_sheet_id` bigint(20) unsigned NOT NULL,
  `checklist_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'JSON array of checklist items with ratings and remarks' CHECK (json_valid(`items`)),
  `total_score` int(11) NOT NULL DEFAULT 0,
  `max_score` int(11) NOT NULL DEFAULT 0,
  `completed_by` bigint(20) unsigned NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `evaluated_by` bigint(20) unsigned DEFAULT NULL,
  `evaluated_at` timestamp NULL DEFAULT NULL,
  `evaluator_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `checklists_evaluated_by_foreign` (`evaluated_by`),
  KEY `checklists_information_sheet_id_checklist_number_index` (`information_sheet_id`,`checklist_number`),
  KEY `checklists_completed_by_completed_at_index` (`completed_by`,`completed_at`),
  CONSTRAINT `checklists_completed_by_foreign` FOREIGN KEY (`completed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `checklists_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `checklists_information_sheet_id_foreign` FOREIGN KEY (`information_sheet_id`) REFERENCES `information_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `checklists`
--

LOCK TABLES `checklists` WRITE;
/*!40000 ALTER TABLE `checklists` DISABLE KEYS */;
/*!40000 ALTER TABLE `checklists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `courses`
--

DROP TABLE IF EXISTS `courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `courses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_name` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `sector` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `courses_course_code_unique` (`course_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `courses`
--

LOCK TABLES `courses` WRITE;
/*!40000 ALTER TABLE `courses` DISABLE KEYS */;
INSERT INTO `courses` VALUES (1,'Electronic Products Assembly and Servicing NCII','EPAS-NCII','This course covers the competencies required to assemble and service electronic products according to industry standards.','Electronics',1,1,'2025-10-28 09:02:36','2025-10-28 09:02:36');
/*!40000 ALTER TABLE `courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departments_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Sample Department','This is a sample department.','2025-10-28 09:06:23','2025-10-28 09:06:23');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homework_submissions`
--

DROP TABLE IF EXISTS `homework_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `homework_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `homework_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `work_hours` decimal(4,2) DEFAULT NULL COMMENT 'Hours spent on the homework',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `score` int(11) DEFAULT NULL,
  `max_points` int(11) NOT NULL DEFAULT 100,
  `evaluator_notes` text DEFAULT NULL,
  `evaluated_by` bigint(20) unsigned DEFAULT NULL,
  `evaluated_at` timestamp NULL DEFAULT NULL,
  `is_late` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `homework_submissions_evaluated_by_foreign` (`evaluated_by`),
  KEY `homework_submissions_homework_id_user_id_index` (`homework_id`,`user_id`),
  KEY `homework_submissions_user_id_submitted_at_index` (`user_id`,`submitted_at`),
  CONSTRAINT `homework_submissions_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `homework_submissions_homework_id_foreign` FOREIGN KEY (`homework_id`) REFERENCES `homeworks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `homework_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homework_submissions`
--

LOCK TABLES `homework_submissions` WRITE;
/*!40000 ALTER TABLE `homework_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `homework_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `homeworks`
--

DROP TABLE IF EXISTS `homeworks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `homeworks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `information_sheet_id` bigint(20) unsigned NOT NULL,
  `homework_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructions` text NOT NULL,
  `requirements` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`requirements`)),
  `submission_guidelines` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`submission_guidelines`)),
  `reference_images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`reference_images`)),
  `due_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `max_points` int(11) NOT NULL DEFAULT 100,
  `allow_late_submission` tinyint(1) NOT NULL DEFAULT 0,
  `late_penalty` int(11) NOT NULL DEFAULT 0 COMMENT 'Penalty percentage per day late',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `homeworks_information_sheet_id_homework_number_index` (`information_sheet_id`,`homework_number`),
  KEY `homeworks_due_date_index` (`due_date`),
  CONSTRAINT `homeworks_information_sheet_id_foreign` FOREIGN KEY (`information_sheet_id`) REFERENCES `information_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `homeworks`
--

LOCK TABLES `homeworks` WRITE;
/*!40000 ALTER TABLE `homeworks` DISABLE KEYS */;
/*!40000 ALTER TABLE `homeworks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `information_sheets`
--

DROP TABLE IF EXISTS `information_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `information_sheets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` bigint(20) unsigned NOT NULL,
  `sheet_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `information_sheets_module_id_foreign` (`module_id`),
  CONSTRAINT `information_sheets_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `information_sheets`
--

LOCK TABLES `information_sheets` WRITE;
/*!40000 ALTER TABLE `information_sheets` DISABLE KEYS */;
INSERT INTO `information_sheets` VALUES (1,1,'1.1','Introduction to Electronic and Electricity','Introduction to Electricity and electronics, and its History',1,'2025-10-28 09:44:08','2025-10-28 09:44:08');
/*!40000 ALTER TABLE `information_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_sheet_steps`
--

DROP TABLE IF EXISTS `job_sheet_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_sheet_steps` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_sheet_id` bigint(20) unsigned NOT NULL,
  `step_number` int(11) NOT NULL,
  `instruction` text NOT NULL,
  `expected_outcome` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `warnings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of safety warnings' CHECK (json_valid(`warnings`)),
  `tips` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of helpful tips' CHECK (json_valid(`tips`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_sheet_steps_job_sheet_id_step_number_index` (`job_sheet_id`,`step_number`),
  CONSTRAINT `job_sheet_steps_job_sheet_id_foreign` FOREIGN KEY (`job_sheet_id`) REFERENCES `job_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_sheet_steps`
--

LOCK TABLES `job_sheet_steps` WRITE;
/*!40000 ALTER TABLE `job_sheet_steps` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_sheet_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_sheet_submissions`
--

DROP TABLE IF EXISTS `job_sheet_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_sheet_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_sheet_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `completed_steps` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'JSON array of completed step numbers' CHECK (json_valid(`completed_steps`)),
  `observations` text NOT NULL,
  `challenges` text DEFAULT NULL,
  `solutions` text DEFAULT NULL,
  `time_taken` int(11) DEFAULT NULL COMMENT 'Time taken in minutes',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `evaluator_notes` text DEFAULT NULL,
  `evaluated_by` bigint(20) unsigned DEFAULT NULL,
  `evaluated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_sheet_submissions_evaluated_by_foreign` (`evaluated_by`),
  KEY `job_sheet_submissions_job_sheet_id_user_id_index` (`job_sheet_id`,`user_id`),
  KEY `job_sheet_submissions_user_id_submitted_at_index` (`user_id`,`submitted_at`),
  CONSTRAINT `job_sheet_submissions_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `job_sheet_submissions_job_sheet_id_foreign` FOREIGN KEY (`job_sheet_id`) REFERENCES `job_sheets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `job_sheet_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_sheet_submissions`
--

LOCK TABLES `job_sheet_submissions` WRITE;
/*!40000 ALTER TABLE `job_sheet_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_sheet_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_sheets`
--

DROP TABLE IF EXISTS `job_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_sheets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `information_sheet_id` bigint(20) unsigned NOT NULL,
  `sheet_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `objective` text NOT NULL,
  `procedures` text NOT NULL,
  `safety_precautions` text DEFAULT NULL,
  `performance_criteria` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_sheets_information_sheet_id_foreign` (`information_sheet_id`),
  CONSTRAINT `job_sheets_information_sheet_id_foreign` FOREIGN KEY (`information_sheet_id`) REFERENCES `information_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_sheets`
--

LOCK TABLES `job_sheets` WRITE;
/*!40000 ALTER TABLE `job_sheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'2025_08_05_120434_create_departments_table',1),(3,'2025_08_05_120435_create_users_table',1),(4,'2025_09_18_160545_add_lrn_id_to_users_table',1),(5,'2025_10_02_072649_add_section_and_room_number_to_users_table',1),(6,'2025_10_13_074940_create_modules_table',1),(7,'2025_10_13_075223_create_qualifications_table',1),(8,'2025_10_13_075223_create_sectors_table',1),(9,'2025_10_13_075223_create_units_table',1),(10,'2025_10_13_075227_create_module_pages_table',1),(11,'2025_10_17_183502_create_user_progress_table',1),(12,'2025_10_17_183542_create_quiz_attempts_table',1),(13,'2025_10_18_071734_create_topics_table',1),(14,'2025_10_18_154924_create_pending_activities_table',1),(15,'2025_10_21_093946_create_announcement_table',1),(16,'2025_10_21_102230_add_email_verification_to_users_table',1),(17,'2025_10_25_074015_create_courses_table',1),(18,'2025_10_25_080358_add_course_id_to_modules_table',1),(19,'2025_10_25_084811_add_course_id_to_existing_modules',1),(20,'2025_10_26_085805_fix_announcements_table_structure',1),(21,'2025_10_26_195001_create_all_content_tables',1),(22,'2025_10_28_151635_create_announcement_user_table',1),(23,'2025_10_28_152151_fix_announcements_table_columns',1),(24,'2025_10_28_152835_check_and_fix_announcements_columns',1),(25,'2025_10_28_173421_add_advisory_section_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_pages`
--

DROP TABLE IF EXISTS `module_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_pages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_pages`
--

LOCK TABLES `module_pages` WRITE;
/*!40000 ALTER TABLE `module_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `module_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` bigint(20) unsigned DEFAULT NULL,
  `sector` varchar(255) NOT NULL DEFAULT 'Electronics',
  `qualification_title` varchar(255) NOT NULL,
  `unit_of_competency` varchar(255) NOT NULL,
  `module_title` varchar(255) NOT NULL,
  `module_number` varchar(255) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `table_of_contents` text DEFAULT NULL,
  `how_to_use_cblm` text DEFAULT NULL,
  `introduction` text DEFAULT NULL,
  `learning_outcomes` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modules_course_id_foreign` (`course_id`),
  CONSTRAINT `modules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,1,'Electronics','Electronic Products Assembly And Servicing NCII','Assemble Electronic Products','Assembling Electronic Products','Module 1','Competency based learning material','Coming Soon...','Coming Soon...','Coming Soon...','Coming Soon...',1,1,'2025-10-28 09:42:51','2025-10-28 09:42:51');
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pending_activities`
--

DROP TABLE IF EXISTS `pending_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pending_activities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `module_id` bigint(20) unsigned DEFAULT NULL,
  `assigned_by` bigint(20) unsigned NOT NULL,
  `status` enum('pending','completed','overdue') NOT NULL DEFAULT 'pending',
  `deadline` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pending_activities_user_id_foreign` (`user_id`),
  KEY `pending_activities_module_id_foreign` (`module_id`),
  KEY `pending_activities_assigned_by_foreign` (`assigned_by`),
  CONSTRAINT `pending_activities_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`),
  CONSTRAINT `pending_activities_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  CONSTRAINT `pending_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pending_activities`
--

LOCK TABLES `pending_activities` WRITE;
/*!40000 ALTER TABLE `pending_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `pending_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `performance_criteria`
--

DROP TABLE IF EXISTS `performance_criteria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `performance_criteria` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `related_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `criteria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'JSON array of criteria with observed status and remarks' CHECK (json_valid(`criteria`)),
  `score` decimal(5,2) NOT NULL DEFAULT 0.00,
  `evaluator_notes` text DEFAULT NULL,
  `evaluated_by` bigint(20) unsigned DEFAULT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `performance_criteria_evaluated_by_foreign` (`evaluated_by`),
  KEY `performance_criteria_type_related_id_index` (`type`,`related_id`),
  KEY `performance_criteria_user_id_completed_at_index` (`user_id`,`completed_at`),
  CONSTRAINT `performance_criteria_evaluated_by_foreign` FOREIGN KEY (`evaluated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `performance_criteria_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `performance_criteria`
--

LOCK TABLES `performance_criteria` WRITE;
/*!40000 ALTER TABLE `performance_criteria` DISABLE KEYS */;
/*!40000 ALTER TABLE `performance_criteria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qualifications`
--

DROP TABLE IF EXISTS `qualifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `qualifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualifications`
--

LOCK TABLES `qualifications` WRITE;
/*!40000 ALTER TABLE `qualifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quiz_attempts`
--

DROP TABLE IF EXISTS `quiz_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quiz_attempts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `self_check_id` bigint(20) unsigned NOT NULL,
  `score` int(11) NOT NULL,
  `max_score` int(11) NOT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answers`)),
  `correct_answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`correct_answers`)),
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quiz_attempts_user_id_foreign` (`user_id`),
  KEY `quiz_attempts_self_check_id_foreign` (`self_check_id`),
  CONSTRAINT `quiz_attempts_self_check_id_foreign` FOREIGN KEY (`self_check_id`) REFERENCES `self_checks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quiz_attempts`
--

LOCK TABLES `quiz_attempts` WRITE;
/*!40000 ALTER TABLE `quiz_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `quiz_attempts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sectors`
--

DROP TABLE IF EXISTS `sectors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sectors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sectors`
--

LOCK TABLES `sectors` WRITE;
/*!40000 ALTER TABLE `sectors` DISABLE KEYS */;
/*!40000 ALTER TABLE `sectors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `self_check_questions`
--

DROP TABLE IF EXISTS `self_check_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `self_check_questions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `self_check_id` bigint(20) unsigned NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','identification','essay','matching','enumeration') NOT NULL,
  `points` int(11) NOT NULL DEFAULT 1,
  `options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of options for multiple choice/matching' CHECK (json_valid(`options`)),
  `correct_answer` text DEFAULT NULL COMMENT 'Correct answer based on question type',
  `explanation` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `self_check_questions_self_check_id_order_index` (`self_check_id`,`order`),
  CONSTRAINT `self_check_questions_self_check_id_foreign` FOREIGN KEY (`self_check_id`) REFERENCES `self_checks` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `self_check_questions`
--

LOCK TABLES `self_check_questions` WRITE;
/*!40000 ALTER TABLE `self_check_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `self_check_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `self_check_submissions`
--

DROP TABLE IF EXISTS `self_check_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `self_check_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `self_check_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `total_points` int(11) NOT NULL,
  `percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `passed` tinyint(1) NOT NULL DEFAULT 0,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'JSON object of user answers' CHECK (json_valid(`answers`)),
  `time_taken` int(11) DEFAULT NULL COMMENT 'Time taken in seconds',
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `self_check_submissions_self_check_id_user_id_index` (`self_check_id`,`user_id`),
  KEY `self_check_submissions_user_id_completed_at_index` (`user_id`,`completed_at`),
  CONSTRAINT `self_check_submissions_self_check_id_foreign` FOREIGN KEY (`self_check_id`) REFERENCES `self_checks` (`id`) ON DELETE CASCADE,
  CONSTRAINT `self_check_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `self_check_submissions`
--

LOCK TABLES `self_check_submissions` WRITE;
/*!40000 ALTER TABLE `self_check_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `self_check_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `self_checks`
--

DROP TABLE IF EXISTS `self_checks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `self_checks` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `information_sheet_id` bigint(20) unsigned NOT NULL,
  `check_number` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `answer_key` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `self_checks_information_sheet_id_foreign` (`information_sheet_id`),
  CONSTRAINT `self_checks_information_sheet_id_foreign` FOREIGN KEY (`information_sheet_id`) REFERENCES `information_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `self_checks`
--

LOCK TABLES `self_checks` WRITE;
/*!40000 ALTER TABLE `self_checks` DISABLE KEYS */;
/*!40000 ALTER TABLE `self_checks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('lGlxv2ibaBVQb8WUZNb7ohKLn7YQL2IZFeBKJJGj',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiczRiQldmeDlGaGZCYXpOU1VkZVdZSndGU1paQmZzNVcyUEFidEhUaCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjYxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvaW5mb3JtYXRpb24tc2hlZXRzLzEvdGFzay1zaGVldHMvY3JlYXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9',1761676214);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_sheet_items`
--

DROP TABLE IF EXISTS `task_sheet_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_sheet_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_sheet_id` bigint(20) unsigned NOT NULL,
  `part_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `expected_finding` text NOT NULL,
  `acceptable_range` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_sheet_items_task_sheet_id_order_index` (`task_sheet_id`,`order`),
  CONSTRAINT `task_sheet_items_task_sheet_id_foreign` FOREIGN KEY (`task_sheet_id`) REFERENCES `task_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_sheet_items`
--

LOCK TABLES `task_sheet_items` WRITE;
/*!40000 ALTER TABLE `task_sheet_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_sheet_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_sheet_submissions`
--

DROP TABLE IF EXISTS `task_sheet_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_sheet_submissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `task_sheet_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `findings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'JSON object of user findings for each item' CHECK (json_valid(`findings`)),
  `observations` text DEFAULT NULL,
  `challenges` text DEFAULT NULL,
  `time_taken` int(11) DEFAULT NULL COMMENT 'Time taken in minutes',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_sheet_submissions_task_sheet_id_user_id_index` (`task_sheet_id`,`user_id`),
  KEY `task_sheet_submissions_user_id_submitted_at_index` (`user_id`,`submitted_at`),
  CONSTRAINT `task_sheet_submissions_task_sheet_id_foreign` FOREIGN KEY (`task_sheet_id`) REFERENCES `task_sheets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `task_sheet_submissions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_sheet_submissions`
--

LOCK TABLES `task_sheet_submissions` WRITE;
/*!40000 ALTER TABLE `task_sheet_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_sheet_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `task_sheets`
--

DROP TABLE IF EXISTS `task_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `task_sheets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `information_sheet_id` bigint(20) unsigned NOT NULL,
  `sheet_number` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `objective` text NOT NULL,
  `instructions` text NOT NULL,
  `materials_needed` text DEFAULT NULL,
  `performance_criteria` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_sheets_information_sheet_id_foreign` (`information_sheet_id`),
  CONSTRAINT `task_sheets_information_sheet_id_foreign` FOREIGN KEY (`information_sheet_id`) REFERENCES `information_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `task_sheets`
--

LOCK TABLES `task_sheets` WRITE;
/*!40000 ALTER TABLE `task_sheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `task_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `topics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `information_sheet_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `topics_information_sheet_id_order_index` (`information_sheet_id`,`order`),
  CONSTRAINT `topics_information_sheet_id_foreign` FOREIGN KEY (`information_sheet_id`) REFERENCES `information_sheets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `topics`
--

LOCK TABLES `topics` WRITE;
/*!40000 ALTER TABLE `topics` DISABLE KEYS */;
INSERT INTO `topics` VALUES (1,1,'Electric History','For hundreds of years electricity has fascinated many scientists. Around <em>600 BC</em>, Greek philosophers discovered that by rubbing amber against a cloth, lightweight objects would stick to it. Just like rubbing a balloon on a cloth makes the balloon stick to other objects. It was not until around the year <em>1600</em>, that any real research was done on this phenomenon. A scientist by the name of <b>Dr. William Gilbert</b> researched the effects of amber and magnets and wrote the theory of <b>magnetism</b>. In fact, <b>Dr. Gilbert</b> was the first to use the word electric in his theory. <b>Dr. William Gilbert&apos;s</b> research and theories opened the door for more discoveries into <b>magnetism</b> and the development of electricity.\r\nElectricity is produced when the <em>electrons</em> flow on a <em>conductor</em>.\r\n\r\nFollowing Illustrations are those who contribute\r\na lot in the <b>History of Electricity</b>:\r\n\r\n1. <b>James Watt</b> (<em>1736-1819</em>)\r\n   James Watt was a Scottish inventor who made improvements to the steam engine during the late <em>1700s</em>. Soon, factories and mining companies began to use Watt&apos;s new-and-improved steam engine for their machinery. This helped jumpstart the <b>Industrial Revolution</b>, a period in the early <em>1800s</em> that saw many new machines invented and an increase in the number of factories. After his death, Watt&apos;s name was used to describe the electrical unit of <em>watt</em>.\r\n\r\n2. <b>Alessandro Volta</b> (<em>1745-1827</em>)\r\n   Using zinc, copper and cardboard, this Italian professor invented the <em>first battery</em>. Volta&apos;s battery produced a reliable, steady current of electricity. The unit of <em>voltage</em> is now named after <b>Volta</b> (<em>volt</em>).\r\n\r\n3. <b>André-Marie Ampère</b> (<em>1775-1836</em>) <b>André-Marie Ampère</b>, a French physicist and science teacher, played a big role in discovering <b>electromagnetism</b>. He also helped describe a way to measure the flow of electricity. The <em>ampere</em>, which is the unit for measuring electric current, was named in honour of him.\r\n\r\n4. <b>Georg Ohm</b> (<em>1787-1854</em>)\r\n   German physicist and teacher <b>Georg Ohm</b> researched the relationship between <em>voltage</em>, <em>current</em> and <em>resistance</em>. In <em>1827</em>, he proved that the amount of electrical current that can flow through a substance depends on its resistance to electrical flow. This is known as <em>Ohm&apos;s Law</em>.\r\n\r\n5. <b>Michael Faraday</b> (<em>1791-1867</em>) <b>Michael Faraday</b>, a British physicist and chemist, was the first person to discover that moving a magnet near a coil of copper wire produced an <em>electric current</em> in the wire.\r\n\r\n6. <b>Henry Woodward</b>\r\n   (exact birth and death unknown) <b>Henry Woodward</b>, a Canadian medical student, played a major role in developing the <em>electric light bulb</em>. In <em>1874</em>, Woodward and a colleague named <b>Mathew Evans</b> placed a thin metal rod inside a glass bulb. They forced the air out of the bulb and replaced it with a gas called <em>nitrogen</em>. The rod glowed when an <em>electric current</em> passed through it, creating the <em>first electric lamp</em>. Unfortunately, Woodward and Evans couldn&apos;t afford to develop their idea further. So in <em>1889</em>, they sold their patent to <b>Thomas Edison</b>.\r\n\r\n7. <b>Thomas Edison</b> (<em>1847-1931</em>)\r\n   American inventor <b>Thomas Edison</b> purchased Henry Woodward&apos;s patent and began to work on improving the idea. He attached wires to a thin strand of paper, or filament, inside a glass globe. The filament began to glow, which generated some light. This became the first <em>incandescent light bulb</em>. A thin, iron wire later replaced the paper filament.\r\n\r\n8. <b>Nikola Tesla</b> (<em>1856-1943</em>)\r\n   A Serbian inventor named <b>Nikola Tesla</b> invented the <em>first electric motor</em> by reversing the flow of electricity on Thomas Edison&apos;s generator. In <em>1885</em>, he sold his patent rights to an American businessman who was the head of the <b>Westinghouse Electric Company</b>. In <em>1893</em>, the company used Tesla&apos;s ideas to light the <em>Chicago World&apos;s Fair</em> with a quarter of a million lights.\r\n\r\n9. <b>Sir Adam Beck</b> (<em>1857-1925</em>)\r\n   In the early <em>1900s</em>, manufacturer and politician <b>Sir Adam Beck</b> pointed out that private power companies were charging customers too much for electricity. He believed that all citizens had the right to cheap electric light and power. So he worked to get the <b>Ontario government</b> to create the <b>Hydro-Electric Power Commission</b> in <em>1910</em>. He headed up this commission, which provided inexpensive electricity to many Ontario towns and cities. To do this, the commission built huge generating stations and set up transmission lines that carried power from <em>Niagara Falls</em> to places across Ontario. Because of his efforts, he earned the nickname <b>The Hydro Knight</b>.',1,'2025-10-28 09:52:22','2025-10-28 09:52:22');
/*!40000 ALTER TABLE `topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_progress`
--

DROP TABLE IF EXISTS `user_progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_progress` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `module_id` bigint(20) unsigned NOT NULL,
  `progressable_type` varchar(255) NOT NULL,
  `progressable_id` bigint(20) unsigned NOT NULL,
  `status` enum('not_started','in_progress','completed','passed','failed') NOT NULL DEFAULT 'not_started',
  `score` int(11) DEFAULT NULL,
  `max_score` int(11) DEFAULT NULL,
  `time_spent` int(11) NOT NULL DEFAULT 0,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `answers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`answers`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_progress_unique` (`user_id`,`module_id`,`progressable_type`,`progressable_id`),
  KEY `user_progress_module_id_foreign` (`module_id`),
  KEY `user_progress_progressable_type_progressable_id_index` (`progressable_type`,`progressable_id`),
  CONSTRAINT `user_progress_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_progress`
--

LOCK TABLES `user_progress` WRITE;
/*!40000 ALTER TABLE `user_progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  `verification_token_expires_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `ext_name` varchar(50) DEFAULT NULL,
  `student_id` varchar(15) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expires` timestamp NULL DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'student',
  `profile_image` varchar(255) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `stat` varchar(255) NOT NULL DEFAULT '0',
  `department_id` bigint(20) unsigned DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL,
  `advisory_section` varchar(255) DEFAULT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Juswa@gmail.com',NULL,NULL,NULL,'$2y$12$Cy3M36ucvO1OfhpymCdovOisi5XKESYjMR88cPv.JXbIlVcs/YvJa','Khirvie Clifford','N.','Bautista','','MAR140500672357',NULL,NULL,'admin',NULL,'2025-10-28 09:07:07','1',1,NULL,NULL,NULL,'2025-10-28 09:06:32','2025-10-28 09:07:07'),(2,'karl142412@gmail.com',NULL,NULL,NULL,'$2y$12$yY7.eebsAG1F8VdQG4KBi.1JxWGtDd48rNjmydsqejGdFgPUfc8Z2','Karl Lynuz','B.','Rapada','','MAR616962061979',NULL,NULL,'instructor',NULL,NULL,'1',1,NULL,'D1',NULL,'2025-10-28 09:06:32','2025-10-28 09:40:35'),(3,'Sheila1112421152@gmail.com',NULL,NULL,NULL,'$2y$12$OuVQs48Vk18PvDmUxzo3k.fXEUDGCcv43KSUefjqsYjDLrT1Nr5Y.','Sheila Marie','M.','Merida','','MAR930554930474',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:32','2025-10-28 09:06:32'),(4,'Jerry152511@gmail.com',NULL,NULL,NULL,'$2y$12$j4MVt3qXOc/Zw.PBSmiQVuvb30w7yIZ8E7gN1J39NeTcSxo5Q6Vkm','Jerry','A.','Reyes','','MAR448016969583',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:33','2025-10-28 09:06:33'),(5,'mikaellayap2513152@gmail.com',NULL,NULL,NULL,'$2y$12$hNJFpI0OnewJOjfKB84XBevOLW4CSXiJOQFZU/xQYxJfNP3dN8CHK','Mikaella Rosalia','Y.','Torre','','MAR404705363815',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:33','2025-10-28 09:06:33'),(6,'kookyl51yan112@gmail.com',NULL,NULL,NULL,'$2y$12$TiWx/hwR.IDuJ9R8bDMh4OYImc0MsxNYS2IcQOt2gkVPo2.ieZIG2','Kooky Lyann','A.','Arabia','','MAR816014675237',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:33','2025-10-28 09:06:33'),(7,'khirviecliffordbautista15132@gmail.com',NULL,NULL,NULL,'$2y$12$i1cQ1zjk0dgKh.WH91Dm/Oic89sjYy4JbdQqHIVZfE9dLgrA4LvXy','Khirvie Clifford','N.','Bautista','','MAR451562130861',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:33','2025-10-28 09:06:33'),(8,'AngelLov251e31@gmail.com',NULL,NULL,NULL,'$2y$12$s.PQWCK.LfwZ1SgvNiRvoOf16d048brYsDNkxOef3bHxgyqyeROA.','Angel Love','D.','Poblete','','MAR404866585031',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:33','2025-10-28 09:06:33'),(9,'AngeloPascual112513@gmail.com',NULL,NULL,NULL,'$2y$12$6rj6MZwiVUN12SnD/VNTS.lqncYdTgxeY9jpMaPAWqdbAaj9tUOy2','Angelo','A.','Pascual','','MAR294576515468',NULL,NULL,'student',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:33','2025-10-28 09:06:33'),(10,'KebinSy2121252@gmail.com',NULL,NULL,NULL,'$2y$12$lUpKAxupej8cyqTnu.qXoeOKsajUlb8XTNfRN5ESkomHp9LzLJ2D6','Andrei Kevin','A.','Sy','','MAR184559032982',NULL,NULL,'instructor',NULL,NULL,'1',1,NULL,NULL,NULL,'2025-10-28 09:06:34','2025-10-28 09:06:34'),(11,'khirviebautista955@gmail.com',NULL,NULL,NULL,'$2y$12$Wj8ztMx63NwtEqc2r8P22uBP2v1YV7et4UDgXfywg0xXGZlKgjh2e','Khirvie','N.','Bautista','','MAR494344204038',NULL,NULL,'instructor',NULL,NULL,'1',1,'D1','F1',NULL,'2025-10-28 09:06:23','2025-10-28 09:40:22'),(12,'karlrapada853@gmail.com',NULL,NULL,NULL,'$2y$12$0oSxf3t2YYKZlukeoCYB7e24k9/PN8GSTVmxrgU5kNNLB9lfB/H0W','Karl','L.','Rapada','','MAR929231769075',NULL,NULL,'student',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:23','2025-10-28 09:06:23'),(13,'sheilamerida159@gmail.com',NULL,NULL,NULL,'$2y$12$u70Pq83Bue4Vig/mFVE.cuhH30otQmSsBWqMXLAdHbUZHruyLtL.S','Sheila','D.','Merida','','MAR461897583811',NULL,NULL,'student',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:23','2025-10-28 09:06:23'),(14,'jerryreyes575@gmail.com',NULL,NULL,NULL,'$2y$12$nu.SkSd.3/6PUdfYY5re/.7ssGka/Fr3ise8ojUxFNi6az77S3T3O','Jerry','K.','Reyes','','MAR539908730423',NULL,NULL,'student',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:24','2025-10-28 09:06:24'),(15,'mikaellatorre805@gmail.com',NULL,NULL,NULL,'$2y$12$R.FeKc7iTlQw3KKlK/0AEeHpaK1t/NEueWxlbFfJ.mZLUvVS6YwQi','Mikaella','F.','Torre','','MAR671586691118',NULL,NULL,'student',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:24','2025-10-28 09:06:24'),(16,'kookyarabia896@gmail.com',NULL,NULL,NULL,'$2y$12$x6UjmM856iLgc6UE04zJVey/Mj/eKmBBXm4fUEvVQIU/pK/13S7j6','Kooky','X.','Arabia','','MAR577763461115',NULL,NULL,'instructor',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:24','2025-10-28 09:06:24'),(17,'angelpoblete602@gmail.com',NULL,NULL,NULL,'$2y$12$aupTcUwjGdv5PSjdHMrBmuK3302IzkQ6XDHCQY0m5IfR1Bq3LkyrS','Angel','E.','Poblete','','MAR647679035246',NULL,NULL,'student',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:24','2025-10-28 09:06:24'),(18,'angelopascual445@gmail.com',NULL,NULL,NULL,'$2y$12$CwYfLjcL7R7zKSHmxuL6NeG3dHXRqoxCEWwzx/HS9C8bO1mJTlUUq','Angelo','K.','Pascual','','MAR862147051970',NULL,NULL,'student',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:24','2025-10-28 09:06:24'),(19,'kevinsy706@gmail.com',NULL,NULL,NULL,'$2y$12$0Qnn0VakgKLmznzIq9DQdeUsHX0/MHB3XgFBzMKJXBuXmochaSkQq','Kevin','X.','Sy','','MAR917270236475',NULL,NULL,'instructor',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:24','2025-10-28 09:06:24'),(20,'trishalopez346@gmail.com',NULL,NULL,NULL,'$2y$12$28WJzTq0Nm.4Uq6F5m9mquHrWIZXUrMkUgN7zquo8o37BQweQmk36','Trisha','C.','Lopez','','MAR639055713331',NULL,NULL,'student',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:25','2025-10-28 09:06:25'),(21,'nathangarcia120@gmail.com',NULL,NULL,NULL,'$2y$12$NrM98zfoVI51iork5AEFVuG1DzhXi06gyICzzr5zXmpFvs4PPov2e','Nathan','L.','Garcia','','MAR378123624637',NULL,NULL,'instructor',NULL,NULL,'1',1,'C1','C1',NULL,'2025-10-28 09:06:25','2025-10-28 09:40:06'),(22,'lancecruz136@gmail.com',NULL,NULL,NULL,'$2y$12$T4DBGOofGHFZQg8nFs/4IeoJUkDt/Y20OWWeC4Gj6gDYa0PPppwha','Lance','V.','Cruz','','MAR685991293930',NULL,NULL,'instructor',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:25','2025-10-28 09:06:25'),(23,'erikadelacruz546@gmail.com',NULL,NULL,NULL,'$2y$12$K8ulxCPTDSmD4HDTb2Rcz.zA6VTPprC9LAEgKJsJE9FfgM4s1kp16','Erika','E.','DelaCruz','','MAR907004772983',NULL,NULL,'instructor',NULL,NULL,'1',1,'A1','B1',NULL,'2025-10-28 09:06:25','2025-10-28 09:39:59'),(24,'hannahsantos431@gmail.com',NULL,NULL,NULL,'$2y$12$pbHVpWilgLc4qvhakNGWouDauMlQ1TbENkJ3NMeIZSh398sJkmL1m','Hannah','Q.','Santos','','MAR212210170309',NULL,NULL,'student',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:25','2025-10-28 09:06:25'),(25,'joshuatorres989@gmail.com',NULL,NULL,NULL,'$2y$12$WavGWQ/VM08q5x1FnNUUM.CSQcw67ZStPvhMGiCvnLXQ0DwUFA1wG','Joshua','O.','Torres','','MAR347772774818',NULL,NULL,'student',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:26','2025-10-28 09:06:26'),(26,'nicolevillanueva606@gmail.com',NULL,NULL,NULL,'$2y$12$3yRmiMrR/2eSJvborctBhuKyXulxzM0Ormu5LLKhs7tDgYe.Z.lbS','Nicole','L.','Villanueva','','MAR998446780113',NULL,NULL,'instructor',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:26','2025-10-28 09:06:26'),(27,'jessanavarro316@gmail.com',NULL,NULL,NULL,'$2y$12$q6bszwSQZDyKvGv7PgFrgeBxp3/z0TFwW/uQbnGN2jD.vu8qHPMeO','Jessa','G.','Navarro','','MAR756097779487',NULL,NULL,'student',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:26','2025-10-28 09:06:26'),(28,'elijahflores966@gmail.com',NULL,NULL,NULL,'$2y$12$b3BJKsJZj7MlBwp6r16WR.uWvdMr6f99GP1h9GXU1rqFoKj3K1.ai','Elijah','G.','Flores','','MAR770763327039',NULL,NULL,'student',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:26','2025-10-28 09:06:26'),(29,'christianreyes948@gmail.com',NULL,NULL,NULL,'$2y$12$5aHhcgOpTV8ZygHQQ2z3kOpvU.C8iIDL9Ne71SK0vSnp5ljzJSz..','Christian','U.','Reyes','','MAR636929388599',NULL,NULL,'instructor',NULL,NULL,'1',1,'D1',NULL,NULL,'2025-10-28 09:06:26','2025-10-28 09:06:26'),(30,'samantharamos824@gmail.com',NULL,NULL,NULL,'$2y$12$oAbnCXEIWyZWdVADRkgrXuwWRwNgZZDXY7v0GO1npHZHM.S6FU50G','Samantha','T.','Ramos','','MAR185143624641',NULL,NULL,'student',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:26','2025-10-28 09:06:26'),(31,'miguelcastro503@gmail.com',NULL,NULL,NULL,'$2y$12$cN04IXdXXKQjOionKk.51.JgERtbUA9nYobkkaJ4BEtnppumhPM46','Miguel','D.','Castro','','MAR901599767512',NULL,NULL,'student',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:27','2025-10-28 09:06:27'),(32,'alyssaaquino125@gmail.com',NULL,NULL,NULL,'$2y$12$mZ2abOslamw.pOa7XOXb8OBWE2zG78u.H1ekgm/p/qgKrNdh8a4My','Alyssa','D.','Aquino','','MAR195612121422',NULL,NULL,'instructor',NULL,NULL,'1',1,'E1','A1',NULL,'2025-10-28 09:06:27','2025-10-28 09:39:39'),(33,'jareddomingo476@gmail.com',NULL,NULL,NULL,'$2y$12$TeUuyRbCE1Iuj.zBT0t5V.Yn8O8VNcsRfezVAy3ZD8JpLV7C.Eoci','Jared','L.','Domingo','','MAR318242644183',NULL,NULL,'student',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:27','2025-10-28 09:06:27'),(34,'franzdiaz292@gmail.com',NULL,NULL,NULL,'$2y$12$0biXrHN4x3JB810jw2SECuPaDw1BOr2YFewx/.225gwW26NuAoioq','Franz','U.','Diaz','','MAR397504670379',NULL,NULL,'instructor',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:27','2025-10-28 09:06:27'),(35,'rheafernandez728@gmail.com',NULL,NULL,NULL,'$2y$12$oxrPS9pDloBBwORe87EW8u38wDUJCpa8GXDCb/g1cfJ6ppueXCXoy','Rhea','Z.','Fernandez','','MAR207331861658',NULL,NULL,'student',NULL,NULL,'1',1,'D1',NULL,NULL,'2025-10-28 09:06:27','2025-10-28 09:06:27'),(36,'andreamarquez418@gmail.com',NULL,NULL,NULL,'$2y$12$DRkCnTEfIXRfP9MrkX4KjeAhkhwetAU/lAeTgdbeybVaivQnaduVC','Andrea','U.','Marquez','','MAR812637825258',NULL,NULL,'instructor',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:28','2025-10-28 09:06:28'),(37,'dominicrodriguez753@gmail.com',NULL,NULL,NULL,'$2y$12$elI6NlLes1EzlBUIQNgxSeuFy3f9i6GQJl7kgOKGU27f2G2EUIeCy','Dominic','P.','Rodriguez','','MAR648257867087',NULL,NULL,'student',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:28','2025-10-28 09:06:28'),(38,'shanemendoza106@gmail.com',NULL,NULL,NULL,'$2y$12$DmFwfYVo68nU8WGsKBKrGeOA1M4d2vuaq2Iw7M.k9VzRcD6b1eZc6','Shane','F.','Mendoza','','MAR860272554669',NULL,NULL,'instructor',NULL,NULL,'1',1,'D1',NULL,NULL,'2025-10-28 09:06:28','2025-10-28 09:06:28'),(39,'carlogonzales701@gmail.com',NULL,NULL,NULL,'$2y$12$DwUX9AWdKGtaFwLyfkP4nunyKZTfIDgOPpYp5d.sMjHomywoKQk6e','Carlo','U.','Gonzales','','MAR084929522658',NULL,NULL,'instructor',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:28','2025-10-28 09:06:28'),(40,'biancavergara240@gmail.com',NULL,NULL,NULL,'$2y$12$uUchn9LTB36xqy800KPNWO36BYJRHyCX99SJKeZrZG.Xe/p4b2VtS','Bianca','S.','Vergara','','MAR319277691995',NULL,NULL,'instructor',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:28','2025-10-28 09:06:28'),(41,'paolovelasquez935@gmail.com',NULL,NULL,NULL,'$2y$12$pJ7vWEGO7YyjroVysG5c2Olj2wPdzkdG5KfaMXfQhzTVZBEQfJBaS','Paolo','L.','Velasquez','','MAR436805569477',NULL,NULL,'student',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:28','2025-10-28 09:06:28'),(42,'denisesalazar614@gmail.com',NULL,NULL,NULL,'$2y$12$KAE9PXblyQ1U5CXbTrXtVuRjeiAyv0VXdAVPBhB.rg/lfrzFEMhT.','Denise','L.','Salazar','','MAR915608710209',NULL,NULL,'instructor',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:29','2025-10-28 09:06:29'),(43,'lorenzolim121@gmail.com',NULL,NULL,NULL,'$2y$12$ytznkCGxwh2tM76zKTcAv.KqeCnFkJIMhJz75a6eusW0JweGjY/oC','Lorenzo','M.','Lim','','MAR827076275046',NULL,NULL,'student',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:29','2025-10-28 09:06:29'),(44,'fayechan110@gmail.com',NULL,NULL,NULL,'$2y$12$Om59jcCMyldYyqn8V6Q2BuO7dKVhBephdoYOGT9gJ7fIkVpDUnR7C','Faye','V.','Chan','','MAR124735287541',NULL,NULL,'student',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:29','2025-10-28 09:06:29'),(45,'marachua262@gmail.com',NULL,NULL,NULL,'$2y$12$22AC.2N.O/8xMLxB5yIbAuJL789LkVhyQ8/NvAJesKF2LTrKc2wa.','Mara','O.','Chua','','MAR886804871921',NULL,NULL,'instructor',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:29','2025-10-28 09:06:29'),(46,'renzgo104@gmail.com',NULL,NULL,NULL,'$2y$12$MSTEgtig8f.Zma3yGwmzmeeHbUz3ee7JcxF7Gskqgc6zPkykRtLxK','Renz','C.','Go','','MAR380984074093',NULL,NULL,'student',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:29','2025-10-28 09:06:29'),(47,'ellatan493@gmail.com',NULL,NULL,NULL,'$2y$12$JQhHUwgJiPeNhEwBAm1ELO25DaHW6z16CxRVc1j2EXaQDPqj7QScG','Ella','Y.','Tan','','MAR098882729051',NULL,NULL,'instructor',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:29','2025-10-28 09:06:29'),(48,'dalecortez358@gmail.com',NULL,NULL,NULL,'$2y$12$3O4OntJLpNvWZLDafelEueOAdyccbUTI2wNAr/73jGLZjvhzVu12S','Dale','Q.','Cortez','','MAR838417252287',NULL,NULL,'instructor',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:30','2025-10-28 09:06:30'),(49,'arvinmanalo709@gmail.com',NULL,NULL,NULL,'$2y$12$CQ9oH1/9FHl51Bw1OVX4suJ7w429dQNACOr/UhAwHT3JuPvHKwLhG','Arvin','D.','Manalo','','MAR130687038459',NULL,NULL,'student',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:30','2025-10-28 09:06:30'),(50,'beaocampo898@gmail.com',NULL,NULL,NULL,'$2y$12$amz9AAE5uZftQzgW9aXYiu3RAOtqZ9L27JG74QE7kvg6JzltS/UJq','Bea','D.','Ocampo','','MAR741259811161',NULL,NULL,'instructor',NULL,NULL,'1',1,'F1','E1',NULL,'2025-10-28 09:06:30','2025-10-28 09:40:15'),(51,'harveyrivera560@gmail.com',NULL,NULL,NULL,'$2y$12$OTT8gDGEv0VQPB/uMJRuY.v7oxD9YzE1RSFZL9KN04zUO.sOwo9Ay','Harvey','Q.','Rivera','','MAR583821475266',NULL,NULL,'instructor',NULL,NULL,'1',1,'C1',NULL,NULL,'2025-10-28 09:06:30','2025-10-28 09:06:30'),(52,'gracesoriano576@gmail.com',NULL,NULL,NULL,'$2y$12$cA5aV7PbI47tF00xzq.bcO9Ga6pi0G64xVuCQh31dwFRmSn5Bx.3i','Grace','G.','Soriano','','MAR133554382384',NULL,NULL,'student',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:30','2025-10-28 09:06:30'),(53,'liamvillamor990@gmail.com',NULL,NULL,NULL,'$2y$12$E0KvGsHWdvgsry21a5ptU.BrePYWU/MCgy1Q..3HsHAArTKhP0A4i','Liam','E.','Villamor','','MAR753251073269',NULL,NULL,'student',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:31','2025-10-28 09:06:31'),(54,'macyyap201@gmail.com',NULL,NULL,NULL,'$2y$12$lZGqtXFOFJxfnGFkkeAkm.oP94Is3z7y1fKPh9tzbFwTFyfKW.itW','Macy','V.','Yap','','MAR257847807590',NULL,NULL,'instructor',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:31','2025-10-28 09:06:31'),(55,'jaydencabrera272@gmail.com',NULL,NULL,NULL,'$2y$12$aGo/63vkNGTpVCiieywAS.bBk3ygs9yKo0A3vl6yCye794PDpN.Ym','Jayden','N.','Cabrera','','MAR673418203915',NULL,NULL,'instructor',NULL,NULL,'1',1,'A1',NULL,NULL,'2025-10-28 09:06:31','2025-10-28 09:06:31'),(56,'claravergara733@gmail.com',NULL,NULL,NULL,'$2y$12$/klMfoz3lCHNJ3UM0G04V.S2Yb90jte9C6EjoPPXeDVA7kpD7xMZu','Clara','T.','Vergara','','MAR656815115977',NULL,NULL,'student',NULL,NULL,'1',1,'E1',NULL,NULL,'2025-10-28 09:06:31','2025-10-28 09:06:31'),(57,'reinareyes965@gmail.com',NULL,NULL,NULL,'$2y$12$t87XGY8Z54DjXDogyD8IuuXmh8BR4d2lIf5xCmi/dwvKiY8jUa4m6','Reina','A.','Reyes','','MAR708112258337',NULL,NULL,'instructor',NULL,NULL,'1',1,'F1',NULL,NULL,'2025-10-28 09:06:31','2025-10-28 09:06:31'),(58,'evansantiago355@gmail.com',NULL,NULL,NULL,'$2y$12$72eIBioh2k/oithBuBg/vO48bWDNQxu2PBgpE4b/pNGlsmY29Gf46','Evan','A.','Santiago','','MAR792374800684',NULL,NULL,'instructor',NULL,NULL,'1',1,'B1',NULL,NULL,'2025-10-28 09:06:31','2025-10-28 09:06:31'),(59,'kylegutierrez302@gmail.com',NULL,NULL,NULL,'$2y$12$8HMN7hSEsS4n01En/opxh.3sJ2WKmNa9TcNwRM2cL9OqYGXZRZNC2','Kyle','M.','Gutierrez','','MAR957170847065',NULL,NULL,'student',NULL,NULL,'1',1,'D1',NULL,NULL,'2025-10-28 09:06:32','2025-10-28 09:06:32'),(60,'ninafrancisco137@gmail.com',NULL,NULL,NULL,'$2y$12$sYi9EoNhY4GXlhkA02QJkOYE8xke4D1.Rcauf7QVfsK6JgZ6jI.ge','Nina','N.','Francisco','','MAR218339527661',NULL,NULL,'student',NULL,NULL,'1',1,'D1',NULL,NULL,'2025-10-28 09:06:32','2025-10-28 09:06:32');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'epas_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-10-29  2:33:11
