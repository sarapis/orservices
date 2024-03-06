-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2024 at 09:50 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ors-dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessibilities`
--

CREATE TABLE `accessibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accessibility_recordid` varchar(191) DEFAULT NULL,
  `accessibility_location` varchar(191) DEFAULT NULL,
  `accessibility` varchar(191) DEFAULT NULL,
  `accessibility_details` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `accessibility_url` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_pages`
--

CREATE TABLE `account_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `top_content` longtext DEFAULT NULL,
  `sidebar_widget` longtext DEFAULT NULL,
  `appear_for` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `additional_taxonomies`
--

CREATE TABLE `additional_taxonomies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxonomy_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_type_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address_recordid` bigint(20) DEFAULT NULL,
  `address_1` longtext DEFAULT NULL,
  `address_2` varchar(45) DEFAULT NULL,
  `address_city` varchar(45) DEFAULT NULL,
  `address_state_province` varchar(45) DEFAULT NULL,
  `address_postal_code` varchar(45) DEFAULT NULL,
  `address_region` varchar(45) DEFAULT NULL,
  `address_country` varchar(45) DEFAULT NULL,
  `address_attention` varchar(45) DEFAULT NULL,
  `address_type` varchar(191) DEFAULT NULL,
  `address_locations` text DEFAULT NULL,
  `address_services` text DEFAULT NULL,
  `address_organization` text DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address_types`
--

CREATE TABLE `address_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recordid` varchar(255) NOT NULL,
  `agency_code` varchar(255) DEFAULT NULL,
  `agency_name` varchar(255) DEFAULT NULL,
  `projects` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `flag` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airtablekeyinfos`
--

CREATE TABLE `airtablekeyinfos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_key` varchar(191) DEFAULT NULL,
  `base_url` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `access_token` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airtables`
--

CREATE TABLE `airtables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `name_v2` varchar(191) DEFAULT NULL,
  `records` varchar(255) DEFAULT NULL,
  `syncdate` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airtable_v2s`
--

CREATE TABLE `airtable_v2s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `records` varchar(191) DEFAULT NULL,
  `syncdate` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airtable_v2s`
--

INSERT INTO `airtable_v2s` (`id`, `name`, `records`, `syncdate`, `created_at`, `updated_at`) VALUES
(1, 'Services', '1099', '2021/02/03 09:22:45', NULL, '2021-02-03 09:22:45'),
(2, 'Locations', '567', '2021/02/09 11:24:14', NULL, '2021-02-09 11:24:14'),
(3, 'Organizations', '224', '2021/02/03 09:22:56', NULL, '2021-02-03 09:22:56'),
(4, 'Contacts', '93', '2021/02/03 09:22:58', NULL, '2021-02-03 09:22:58'),
(5, 'Phones', '754', '2021/02/03 09:23:14', NULL, '2021-02-03 09:23:14'),
(6, 'Physical_Address', '490', '2021/02/03 09:23:17', NULL, '2021-02-03 09:23:17'),
(7, 'Schedule', '395', '2021/02/03 09:23:20', NULL, '2021-02-03 09:23:20'),
(8, 'Taxonomy_Term', '419', '2021/02/12 08:29:10', NULL, '2021-02-12 08:29:10'),
(9, 'X_Details', '32', '2021/02/03 09:23:25', NULL, '2021-02-03 09:23:25'),
(10, 'Programs', '25', '2021/02/03 09:23:27', NULL, '2021-02-03 09:23:28'),
(11, 'x_Taxonomy', '4', '2021/02/12 08:29:00', NULL, '2021-02-12 08:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `all_languages`
--

CREATE TABLE `all_languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_name` varchar(191) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `flag` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alt_taxonomies`
--

CREATE TABLE `alt_taxonomies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `alt_taxonomy_name` varchar(255) DEFAULT NULL,
  `alt_taxonomy_vocabulary` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE `analytics` (
  `id` int(10) UNSIGNED NOT NULL,
  `search_term` varchar(255) DEFAULT NULL,
  `search_results` int(10) DEFAULT NULL,
  `times_searched` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area_recordid` varchar(191) DEFAULT NULL,
  `area_service` varchar(191) DEFAULT NULL,
  `area_description` varchar(191) DEFAULT NULL,
  `area_date_added` varchar(191) DEFAULT NULL,
  `area_multiple_counties` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `area_services`
--

CREATE TABLE `area_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `service_area_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(191) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(191) NOT NULL,
  `auditable_type` varchar(191) NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` text DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `url` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(1023) DEFAULT NULL,
  `tags` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_sync_airtables`
--

CREATE TABLE `auto_sync_airtables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option` varchar(191) DEFAULT NULL,
  `days` varchar(191) DEFAULT NULL,
  `working_status` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_sync_records`
--

CREATE TABLE `auto_sync_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sync_date` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `city` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE `codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) DEFAULT NULL,
  `code_system` varchar(191) DEFAULT NULL,
  `resource` varchar(191) DEFAULT NULL,
  `resource_element` varchar(191) DEFAULT NULL,
  `category` varchar(191) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `grouping` longtext DEFAULT NULL,
  `definition` longtext DEFAULT NULL,
  `code_id` int(11) DEFAULT NULL,
  `uid` varchar(191) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `is_panel_code` varchar(191) DEFAULT NULL,
  `is_multiselect` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `code_categories`
--

CREATE TABLE `code_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `code_ledgers`
--

CREATE TABLE `code_ledgers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `SDOH_code` varchar(191) DEFAULT NULL,
  `resource` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `code_type` varchar(191) DEFAULT NULL,
  `code` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `code_systems`
--

CREATE TABLE `code_systems` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `versions` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `oid` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comments_recordid` bigint(20) DEFAULT NULL,
  `comments_content` longtext DEFAULT NULL,
  `comments_user` varchar(191) DEFAULT NULL,
  `comments_organization` varchar(191) DEFAULT NULL,
  `comments_service` varchar(191) DEFAULT NULL,
  `comments_contact` varchar(191) DEFAULT NULL,
  `comments_location` varchar(191) DEFAULT NULL,
  `comments_user_firstname` varchar(191) DEFAULT NULL,
  `comments_user_lastname` varchar(191) DEFAULT NULL,
  `comments_datetime` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_recordid` bigint(20) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_organizations` varchar(255) DEFAULT NULL,
  `contact_services` varchar(255) DEFAULT NULL,
  `contact_title` varchar(255) DEFAULT NULL,
  `contact_department` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_phones` varchar(255) DEFAULT NULL,
  `visibility` enum('public','private') DEFAULT 'public',
  `flag` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `locations` text DEFAULT NULL,
  `service_at_locations` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_phones`
--

CREATE TABLE `contact_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_recordid` varchar(191) DEFAULT NULL,
  `phone_recordid` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE `contact_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_type` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cost_options`
--

CREATE TABLE `cost_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cost_recordid` bigint(20) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `option` text DEFAULT NULL,
  `currency` varchar(191) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `amount_description` longtext DEFAULT NULL,
  `attributes` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dial_code` varchar(10) NOT NULL COMMENT 'Country code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `csv`
--

CREATE TABLE `csv` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `c_s_v__sources`
--

CREATE TABLE `c_s_v__sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `source` varchar(191) DEFAULT NULL,
  `records` varchar(191) DEFAULT NULL,
  `syncdate` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_recordid` bigint(20) DEFAULT NULL,
  `detail_value` text DEFAULT NULL,
  `detail_type` text DEFAULT NULL,
  `parent` varchar(191) DEFAULT NULL,
  `language` varchar(191) DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `detail_description` text DEFAULT NULL,
  `detail_organizations` text DEFAULT NULL,
  `detail_services` text DEFAULT NULL,
  `detail_locations` text DEFAULT NULL,
  `phones` text DEFAULT NULL,
  `contacts` text DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_types`
--

CREATE TABLE `detail_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dispositions`
--

CREATE TABLE `dispositions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_recordid` bigint(20) DEFAULT NULL,
  `email_info` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `export_configurations`
--

CREATE TABLE `export_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `filter` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `endpoint` varchar(191) DEFAULT NULL,
  `organization_tags` varchar(191) DEFAULT NULL,
  `service_tags` longtext DEFAULT NULL,
  `key` varchar(191) DEFAULT NULL,
  `file_path` varchar(191) DEFAULT NULL,
  `file_name` varchar(191) DEFAULT NULL,
  `full_path_name` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `export_histories`
--

CREATE TABLE `export_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `auto_sync` varchar(191) DEFAULT NULL,
  `configuration_id` varchar(191) DEFAULT NULL,
  `status` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facility_types`
--

CREATE TABLE `facility_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_type` varchar(191) DEFAULT NULL,
  `notes` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `deleted_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(191) DEFAULT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_options`
--

CREATE TABLE `fee_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fees` varchar(191) DEFAULT NULL,
  `services` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fundings`
--

CREATE TABLE `fundings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `funding_recordid` bigint(20) DEFAULT NULL,
  `source` varchar(191) DEFAULT NULL,
  `organizations` longtext DEFAULT NULL,
  `services` longtext DEFAULT NULL,
  `attributes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `helptexts`
--

CREATE TABLE `helptexts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_classification` longtext DEFAULT NULL,
  `service_conditions` longtext DEFAULT NULL,
  `service_goals` longtext DEFAULT NULL,
  `service_activities` longtext DEFAULT NULL,
  `code_category` longtext DEFAULT NULL,
  `sdoh_code_helptext` longtext DEFAULT NULL,
  `registration_message` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hsds_api_keys`
--

CREATE TABLE `hsds_api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hsds_api_key` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `identifiers`
--

CREATE TABLE `identifiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identifier_recordid` bigint(20) DEFAULT NULL,
  `identifier` varchar(191) DEFAULT NULL,
  `identifier_scheme` varchar(191) DEFAULT NULL,
  `identifier_type` varchar(191) DEFAULT NULL,
  `organizations` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_data_sources`
--

CREATE TABLE `import_data_sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `format` varchar(191) DEFAULT NULL,
  `import_type` varchar(191) DEFAULT NULL,
  `source_file` varchar(191) DEFAULT NULL,
  `airtable_api_key` varchar(191) DEFAULT NULL,
  `airtable_base_id` varchar(191) DEFAULT NULL,
  `endpoint` varchar(191) DEFAULT NULL,
  `key` varchar(191) DEFAULT NULL,
  `mode` varchar(191) DEFAULT NULL,
  `auto_sync` enum('0','1') NOT NULL DEFAULT '0',
  `sync_hours` int(11) DEFAULT NULL,
  `last_imports` datetime DEFAULT NULL,
  `organization_tags` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_histories`
--

CREATE TABLE `import_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_name` varchar(191) DEFAULT NULL,
  `import_type` varchar(191) DEFAULT NULL,
  `auto_sync` enum('0','1') NOT NULL DEFAULT '0',
  `status` enum('In-progress','Completed','Error') NOT NULL DEFAULT 'In-progress',
  `error_message` longtext DEFAULT NULL,
  `sync_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interaction_methods`
--

CREATE TABLE `interaction_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interpretation_services`
--

CREATE TABLE `interpretation_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_recordid` varchar(45) DEFAULT NULL,
  `language_location` varchar(45) DEFAULT NULL,
  `language_service` varchar(45) DEFAULT NULL,
  `language` varchar(45) DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `code` varchar(191) DEFAULT NULL,
  `note` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `layouts`
--

CREATE TABLE `layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `sidebar_content` longtext DEFAULT NULL,
  `sidebar_content_part_1` longtext DEFAULT NULL,
  `part_1_image` varchar(191) DEFAULT NULL,
  `sidebar_content_part_2` longtext DEFAULT NULL,
  `part_2_image` varchar(191) DEFAULT NULL,
  `sidebar_content_part_3` longtext DEFAULT NULL,
  `part_3_image` varchar(191) DEFAULT NULL,
  `contact_text` varchar(255) DEFAULT NULL,
  `banner_text1` varchar(191) DEFAULT NULL,
  `banner_text2` varchar(191) DEFAULT NULL,
  `contact_btn_label` varchar(255) DEFAULT NULL,
  `contact_btn_link` varchar(255) DEFAULT NULL,
  `footer` text DEFAULT NULL,
  `homepage_background` varchar(191) DEFAULT NULL,
  `header_pdf` varchar(255) DEFAULT NULL,
  `footer_pdf` varchar(255) DEFAULT NULL,
  `footer_csv` varchar(255) DEFAULT NULL,
  `logo_active` varchar(255) DEFAULT NULL,
  `title_active` varchar(255) DEFAULT NULL,
  `about_active` varchar(255) DEFAULT NULL,
  `primary_color` varchar(255) DEFAULT NULL,
  `secondary_color` varchar(255) DEFAULT NULL,
  `button_color` varchar(255) DEFAULT NULL,
  `button_hover_color` varchar(255) DEFAULT NULL,
  `title_link_color` varchar(191) DEFAULT NULL,
  `top_menu_color` varchar(45) DEFAULT NULL,
  `top_menu_link_color` varchar(45) DEFAULT NULL,
  `menu_title_color` varchar(45) DEFAULT NULL,
  `top_menu_link_hover_color` varchar(45) DEFAULT NULL,
  `meta_filter_activate` int(11) NOT NULL DEFAULT 0,
  `meta_filter_on_label` varchar(255) DEFAULT NULL,
  `meta_filter_off_label` varchar(255) DEFAULT NULL,
  `top_background` longtext DEFAULT NULL,
  `bottom_background` longtext DEFAULT NULL,
  `bottom_section_active` varchar(255) DEFAULT NULL,
  `login_content` longtext DEFAULT NULL,
  `register_content` longtext DEFAULT NULL,
  `activate_login_home` int(11) NOT NULL DEFAULT 0,
  `activate_about_home` int(11) DEFAULT NULL,
  `home_page_style` varchar(255) DEFAULT NULL,
  `activate_religions` int(11) NOT NULL DEFAULT 0,
  `activate_languages` int(11) NOT NULL DEFAULT 0,
  `activate_organization_types` int(11) NOT NULL DEFAULT 0,
  `activate_contact_types` int(11) NOT NULL DEFAULT 0,
  `activate_facility_types` int(11) NOT NULL DEFAULT 0,
  `exclude_vocabulary` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `activate_login_button` int(11) NOT NULL DEFAULT 0,
  `organization_share_button` int(11) NOT NULL DEFAULT 0,
  `service_share_button` int(11) NOT NULL DEFAULT 0,
  `show_classification` enum('yes','no') DEFAULT 'no',
  `display_download_menu` varchar(191) DEFAULT NULL,
  `display_download_pdf` varchar(191) DEFAULT NULL,
  `display_download_csv` varchar(191) DEFAULT NULL,
  `show_suggest_menu` varchar(191) DEFAULT NULL,
  `show_registration_message` varchar(191) DEFAULT NULL,
  `user_metafilter_option` varchar(191) DEFAULT NULL,
  `default_label` varchar(191) DEFAULT NULL,
  `hide_organizations_with_no_filtered_services` varchar(191) DEFAULT NULL,
  `top_menu_link_hover_background_color` varchar(191) DEFAULT NULL,
  `site_title_active` varchar(191) DEFAULT NULL,
  `submenu_highlight_color` varchar(191) DEFAULT NULL,
  `default_service_status` varchar(191) DEFAULT NULL,
  `default_organization_status` varchar(191) DEFAULT NULL,
  `taxonomy_icon_hover` varchar(191) DEFAULT NULL,
  `hide_service_category_with_no_filter_service` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layouts`
--

INSERT INTO `layouts` (`id`, `logo`, `site_name`, `tagline`, `sidebar_content`, `sidebar_content_part_1`, `part_1_image`, `sidebar_content_part_2`, `part_2_image`, `sidebar_content_part_3`, `part_3_image`, `contact_text`, `banner_text1`, `banner_text2`, `contact_btn_label`, `contact_btn_link`, `footer`, `homepage_background`, `header_pdf`, `footer_pdf`, `footer_csv`, `logo_active`, `title_active`, `about_active`, `primary_color`, `secondary_color`, `button_color`, `button_hover_color`, `title_link_color`, `top_menu_color`, `top_menu_link_color`, `menu_title_color`, `top_menu_link_hover_color`, `meta_filter_activate`, `meta_filter_on_label`, `meta_filter_off_label`, `top_background`, `bottom_background`, `bottom_section_active`, `login_content`, `register_content`, `activate_login_home`, `activate_about_home`, `home_page_style`, `activate_religions`, `activate_languages`, `activate_organization_types`, `activate_contact_types`, `activate_facility_types`, `exclude_vocabulary`, `created_at`, `updated_at`, `activate_login_button`, `organization_share_button`, `service_share_button`, `show_classification`, `display_download_menu`, `display_download_pdf`, `display_download_csv`, `show_suggest_menu`, `show_registration_message`, `user_metafilter_option`, `default_label`, `hide_organizations_with_no_filtered_services`, `top_menu_link_hover_background_color`, `site_title_active`, `submenu_highlight_color`, `default_service_status`, `default_organization_status`, `taxonomy_icon_hover`, `hide_service_category_with_no_filter_service`) VALUES
(1, '1665615645.png', 'Community Resource Inventory', NULL, '<p dir=\"ltr\" style=\"line-height:1.38;margin-top:0pt;margin-bottom:0pt;\"><span id=\"docs-internal-guid-57d17976-7fff-a35b-8680-b0195bbe172b\"></span></p><p dir=\"ltr\" style=\"line-height:1.38;margin-top:0pt;margin-bottom:0pt;\"><span style=\"font-size:11pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">The DC Community Resource Inventory (CRI) is a District-wide, publicly available directory that provides information about regional health, human, and social programs and organizations in the community that are available to District residents. As part of the DC Community Resource Information Exchange Initiative (DC CoRIE), the CRI is public information infrastructure operated in the interests of the residents of the District of Columbia and represents directories contributed by District organizations and services offered by members of the DC PACT coalition. Data available in the CRI include information such as organization and program description, location, contact information, service category, and service eligibility, and more.&nbsp;</span></p><p dir=\"ltr\" style=\"line-height:1.38;margin-top:0pt;margin-bottom:0pt;\"><span style=\"font-size:11pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:700;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">Learn More</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><b style=\"font-weight:normal;\"><br></b></p><p dir=\"ltr\" style=\"line-height:1.38;margin-top:0pt;margin-bottom:0pt;\"><span style=\"font-size:11pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">If you have questions or would like to contribute to and/or otherwise get involved with the DC CRI initiative, please contact David Poms at </span><a href=\"mailto:dpoms@dcpca.org\" style=\"text-decoration:none;\"><span style=\"font-size:11pt;font-family:Arial;color:#1155cc;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:underline;-webkit-text-decoration-skip:none;text-decoration-skip-ink:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">dpoms@dcpca.org</span></a><span style=\"font-size:11pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">&nbsp;</span></p><p dir=\"ltr\" style=\"line-height:1.38;margin-top:0pt;margin-bottom:0pt;\"><span style=\"font-size:11pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">Additional Information: learn more about the DC CRI initiative </span><a href=\"https://openreferral.org/evolving-the-dc-community-resource-information-exchange/\" style=\"text-decoration:none;\"><span style=\"font-size:11pt;font-family:Arial;color:#1155cc;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:underline;-webkit-text-decoration-skip:none;text-decoration-skip-ink:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">in this blog post on OpenReferral.org</span></a><span style=\"font-size:11pt;font-family:Arial;color:#000000;background-color:transparent;font-weight:400;font-style:normal;font-variant:normal;text-decoration:none;vertical-align:baseline;white-space:pre;white-space:pre-wrap;\">.&nbsp;</span></p>', '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">The DC Community Resource Inventory is a project of the DC Community Resource Information Exchange (DC CoRIE) Initiative.&nbsp;</span><span style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: Arial; font-size: 11pt; white-space: pre-wrap;\">Produced by the DCPCA, in partnership with the Open Referral Initiative, with technology development by Sarapis, this is a \'proof of concept\' of a functional Community Resource Inventory containing information about health, human, and social services available to DC residents in need.&nbsp;</span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span id=\"docs-internal-guid-74dba194-7fff-5e13-40e1-13b1ea2fdb29\"><br></span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p>', '/uploads/images/1612796962_part2.png', '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"color: rgb(0, 0, 0); font-family: Arial; font-size: 14.6667px; text-align: center; white-space: pre-wrap;\">This website is a reference implementation for the underlying database. This database is also available via an Application Programming Interface, which can be accessed at our Developer Portal here.</span><br></p>', '/uploads/images/1593798060_part3.png', '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To suggest edits or additions to this directory, reach out to David Poms at the DCPCA.</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To learn more about the Open Referral Initiative, reach out to <a href=\"mailto:Greg@openreferral.org\" target=\"_blank\">Greg Bloom</a>.</span></p>', '/uploads/images/1593798060.png', 'Help us improve by reporting software bugs.', NULL, 'Find local resources that are right for you.', 'Report a Bug', 'https://airtable.com/shrpdMJWSOyZucr9H', '<p style=\"text-align: center; \"> Community Resource Exchange</p>', '1613685113_part1.jpg', 'dc-resources.sarapis.org', 'dc-resources.sarapis.org', 'dc-resources.sarapis.org', '0', '1', '0', '#e29612', '#50d195', '#5052d9', '#ff4000', '#eb0008', '#50aed1', '#ffffff', '#ffffff', '#e09412', 1, 'South Dade Only', 'All Services', '1577102338.png', '1577102281.jpg', '0', '<p>Welcome! This is the login page.</p>', '<p>Welcome! This is the register page. </p>', 0, 1, 'Services (ex. larable-dev.sarapisorg)', 0, 0, 0, 0, 0, 'Health', NULL, '2024-01-31 23:34:56', 1, 1, 1, 'yes', '1', '1', '1', '0', NULL, NULL, NULL, NULL, '#1ec9eb', '1', '#24dde3', NULL, '11', 'no', 0);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `location_name` varchar(255) DEFAULT NULL,
  `location_organization` varchar(255) DEFAULT NULL,
  `location_alternate_name` varchar(255) DEFAULT NULL,
  `location_transportation` varchar(45) DEFAULT NULL,
  `location_latitude` double DEFAULT NULL,
  `location_longitude` double DEFAULT NULL,
  `location_description` text DEFAULT NULL,
  `location_services` text DEFAULT NULL,
  `location_phones` text DEFAULT NULL,
  `location_details` longtext DEFAULT NULL,
  `location_schedule` longtext DEFAULT NULL,
  `location_address` text DEFAULT NULL,
  `location_tag` varchar(191) DEFAULT NULL,
  `enrich_flag` varchar(191) DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `accessibility_recordid` bigint(20) DEFAULT NULL,
  `accessibility_details` longtext DEFAULT NULL,
  `location_type` varchar(191) DEFAULT NULL,
  `location_url` varchar(191) DEFAULT NULL,
  `external_identifier` varchar(191) DEFAULT NULL,
  `external_identifier_type` varchar(191) DEFAULT NULL,
  `location_languages` varchar(191) DEFAULT NULL,
  `accessesibility_url` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_accessibilities`
--

CREATE TABLE `location_accessibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `accessibility_recordid` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_addresses`
--

CREATE TABLE `location_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `address_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_languages`
--

CREATE TABLE `location_languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_recordid` bigint(20) DEFAULT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_phones`
--

CREATE TABLE `location_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` varchar(45) DEFAULT NULL,
  `phone_recordid` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_regions`
--

CREATE TABLE `location_regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `region_id` bigint(20) DEFAULT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location_schedules`
--

CREATE TABLE `location_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `schedule_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `maps`
--

CREATE TABLE `maps` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `long` varchar(255) DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 0,
  `zoom` int(11) DEFAULT NULL,
  `zoom_profile` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meta_filters`
--

CREATE TABLE `meta_filters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operations` varchar(191) DEFAULT NULL,
  `facet` varchar(191) DEFAULT NULL,
  `method` varchar(191) DEFAULT NULL,
  `values` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(10, '2014_10_12_000000_create_users_table', 1),
(11, '2014_10_12_100000_create_password_resets_table', 1),
(12, '2019_08_19_000000_create_failed_jobs_table', 1),
(13, '2020_06_10_130011_create_pages_table', 2),
(14, '2020_06_10_130208_create_contacts_table', 2),
(15, '2020_06_10_130612_create_agencies_table', 3),
(16, '2020_06_10_130839_create_airtables_table', 4),
(17, '2020_06_10_131039_create_services_table', 4),
(18, '2020_06_10_131605_create_locations_table', 5),
(19, '2020_06_11_051826_create_layouts_table', 6),
(20, '2020_06_11_054319_create_maps_table', 7),
(21, '2020_06_11_055446_create_alt_taxonomies_table', 8),
(22, '2020_06_11_055630_create_taxonomies_table', 9),
(23, '2020_06_11_110250_create_organizations_table', 10),
(24, '2020_06_11_114436_create_roles_table', 11),
(25, '2020_06_12_095525_create_airtablekeyinfos_table', 11),
(26, '2020_06_12_095827_create_c_s_v__sources_table', 11),
(27, '2020_06_12_095843_create_source_datas_table', 11),
(28, '2020_06_12_095855_create_auto_sync_airtables_table', 11),
(30, '2020_06_13_045440_create_service_locations_table', 12),
(31, '2020_06_13_045634_create_service_addresses_table', 12),
(32, '2020_06_13_045751_create_service_phones_table', 12),
(33, '2020_06_13_045906_create_service_details_table', 12),
(34, '2020_06_13_050151_create_service_organizations_table', 12),
(35, '2020_06_13_050302_create_service_contacts_table', 12),
(36, '2020_06_13_050427_create_service_taxonomies_table', 12),
(37, '2020_06_13_050450_create_service_schedules_table', 12),
(38, '2020_06_13_061511_create_meta_filters_table', 13),
(39, '2020_06_13_064046_create_phones_table', 14),
(41, '2020_06_13_070324_create_addresses_table', 15),
(42, '2020_06_13_071701_create_location_addresses_table', 16),
(43, '2020_06_13_071912_create_location_phones_table', 17),
(44, '2020_06_13_072019_create_location_schedules_table', 18),
(45, '2020_06_13_092504_create_organization_details_table', 19),
(47, '2020_06_13_094604_create_schedules_table', 20),
(48, '2020_06_13_101330_create_details_table', 21),
(49, '2020_06_13_114413_create_languages_table', 22),
(50, '2020_06_13_132816_create_analytics_table', 23),
(51, '2020_06_17_092557_create_comments_table', 24),
(52, '2020_06_17_095028_create_sessions_table', 25),
(53, '2020_06_17_101459_create_organization_phones_table', 26),
(54, '2020_06_17_101531_create_organization_contacts_table', 26),
(55, '2020_06_17_101545_create_organization_users_table', 26),
(56, '2020_06_17_101557_create_organization_types_table', 26),
(57, '2020_06_17_132421_create_contact_phones_table', 27),
(58, '2020_06_19_123039_create_suggests_table', 28),
(59, '2020_06_19_124212_create_emails_table', 29),
(60, '2020_06_20_085319_create_csvs_table', 30),
(61, '2020_06_20_161845_create_session_data_table', 31),
(62, '2020_06_23_114015_create_session_interactions_table', 32),
(63, '2020_06_25_154843_create_accessibilities_table', 33),
(64, '2020_06_26_093801_create_religions_table', 34),
(66, '2020_06_26_095320_create_all_languages_table', 35),
(67, '2020_06_26_100437_create_contact_types_table', 36),
(68, '2020_06_26_113335_create_facility_types_table', 37),
(69, '2020_06_26_135349_create_areas_table', 38),
(70, '2020_06_27_143015_create_hsds_api_keys_table', 39),
(71, '2020_11_23_123212_create_service_attributes_table', 40),
(72, '2020_11_24_115221_create_other_attributes_table', 40),
(73, '2020_11_24_131058_create_x_details_table', 40),
(74, '2020_11_26_085215_create_airtable_v2s_table', 41),
(75, '2020_11_26_125441_create_programs_table', 42),
(80, '2020_11_28_061354_create_schedules_table', 43),
(81, '2020_12_12_093558_create_phone_types_table', 44),
(82, '2020_12_24_101610_add_badge_color_to_taxonomies_table', 45),
(83, '2020_12_28_063300_create_account_pages_table', 46),
(84, '2021_01_05_112420_add_message_to_users_table', 47),
(85, '2021_01_07_125021_create_detail_types_table', 48),
(86, '2021_01_18_102404_add_order_to_taxonomies_table', 49),
(87, '2021_01_18_112952_create_email_templates_table', 49),
(88, '2021_01_20_055834_create_service_categories_table', 50),
(89, '2021_01_20_060120_create_service_eligibilities_table', 50),
(90, '2021_01_28_062036_create_service_programs_table', 51),
(91, '2021_01_28_062241_create_organization_programs_table', 51),
(92, '2021_02_08_110939_create_auto_sync_records_table', 52),
(93, '2021_02_09_055001_create_taxonomy_types_table', 53),
(96, '2021_02_11_055709_add_taxonomy_to_taxonomies_table', 54),
(97, '2021_02_11_084831_add_parent_to_details_table', 54),
(98, '2021_02_12_104206_add_taxonomies_recordid_to_taxonomy_types', 54),
(99, '2021_02_12_104956_create_taxonomy_terms_table', 54),
(100, '2021_02_12_105031_create_additional_taxonomies_table', 54),
(101, '2013_04_09_062329_create_revisions_table', 55),
(102, '2021_03_01_150201_create_organization_histories_table', 55),
(103, '2021_03_01_182835_create_audits_table', 55),
(104, '2021_03_18_160935_add_program_service_relations_to_programs_table', 55),
(105, '2021_03_18_161213_add_service_code_to_services_table', 55),
(106, '2021_03_18_161246_add_organization_code_to_organizations_table', 55),
(107, '2021_03_19_150405_create_import_data_sources_table', 55),
(108, '2021_03_22_113905_create_import_histories_table', 55),
(109, '2021_04_05_074356_create_organization_tags_table', 55),
(110, '2021_04_20_155851_create_organization_statuses_table', 55),
(111, '2021_04_20_173743_add_active_login_button_to_layouts_table', 55),
(112, '2021_04_23_150013_add_access_requirement_to_services_table', 55),
(113, '2021_04_23_161951_add_main_priority_to_phones_table', 55),
(114, '2021_04_24_105611_add_social_links_to_organizations_table', 55),
(115, '2021_04_27_122320_add_status_to_taxonomies_table', 55),
(116, '2021_04_28_143046_add_temp_service_to_taxonomies_table', 55),
(117, '2021_04_28_145312_create_taxonomy_emails_table', 55),
(118, '2021_04_29_102707_add_added_term_to_taxonomies_table', 55),
(119, '2021_05_03_182049_add_order_to_languages_table', 55),
(120, '2021_05_03_184214_add_order_to_organization_tags_table', 55),
(121, '2021_05_05_151658_add_order_to_phone_types_table', 55),
(122, '2021_05_05_152547_add_order_to_detail_types_table', 55),
(123, '2021_05_05_154154_add_order_to_taxonomy_types_table', 55),
(124, '2021_05_05_164832_add_order_to_organization_statuses_table', 55),
(125, '2021_06_23_111641_create_cities_table', 56),
(126, '2021_06_23_111928_create_states_table', 56),
(127, '2021_08_04_112815_create_codes_table', 57),
(128, '2021_08_09_145447_create_service_codes_table', 57),
(129, '2021_08_10_110542_create_helptexts_table', 57),
(130, '2021_08_10_185957_create_code_ledgers_table', 57),
(131, '2021_08_12_114902_add__s_d_o_h_code_to_services_table', 57),
(132, '2021_08_25_003627_add_show_classification_to_layouts_table', 58),
(133, '2021_09_07_022848_create_export_configurations_table', 59),
(134, '2021_09_07_023138_create_export_histories_table', 59),
(135, '2021_09_17_024230_add_visibility_to_contacts_table', 59),
(136, '2021_09_20_011508_create_service_areas_table', 59),
(137, '2021_09_20_042754_create_fee_options_table', 59),
(138, '2021_09_20_052747_create_regions_table', 59),
(139, '2021_09_20_073554_create_location_regions_table', 59),
(140, '2021_09_21_021206_create_area_services_table', 59),
(141, '2021_09_21_030808_create_service_fees_table', 59),
(142, '2021_09_22_063837_add_grouping_table_to_codes_table', 59),
(143, '2021_09_22_082618_add_code_category_table_to_helptexts_table', 59),
(144, '2021_09_23_050904_add_code_category_ids_to_services_table', 59),
(145, '2021_10_13_070516_add_code_id_to_codes_table', 59),
(146, '2021_10_13_080544_add_endpoint_to_import_data_sources_table', 59),
(147, '2021_10_15_054359_add_deleted_at_to_code_ledgers_table', 59),
(148, '2021_10_20_032418_add_procedure_grouping_to_services_table', 59),
(149, '2022_02_11_015844_add_download_settings_to_layouts_table', 60),
(150, '2022_02_15_011121_create_dispositions_table', 60),
(151, '2022_02_15_023014_add_services_to_session_interactions_table', 60),
(152, '2022_02_15_090453_add_services_to_session_data_table', 60),
(153, '2022_02_16_015637_create_service_statuses_table', 60),
(154, '2022_02_16_045443_create_service_tags_table', 60),
(155, '2022_02_16_071348_add_service_comment_to_comments_table', 60),
(156, '2022_02_16_074859_add_service_tag_to_services_table', 60),
(157, '2022_02_16_075646_add_service_status_to_session_data_table', 60),
(158, '2022_02_17_014035_add_organization_tags_to_users_table', 60),
(159, '2022_02_17_060302_add_suggest_menu_to_layouts_table', 60),
(160, '2022_02_19_155525_add_service_tags_to_export_configurations_table', 60),
(161, '2022_03_04_072235_add_registration_message_to_layouts_table', 60),
(162, '2022_03_04_073404_add_registration_message_helptexts_table', 60),
(163, '2022_03_16_011339_add_user_metafilter_to_layouts_table', 60),
(164, '2022_06_08_044148_create_interaction_methods_table', 60),
(165, '2022_06_10_085506_add_default_status_to_layouts_table', 60),
(166, '2022_06_17_091804_add_service_tags_to_users_table', 60),
(167, '2022_06_17_114506_add_bookmark_to_organizations_table', 60),
(168, '2022_06_17_132121_add_bookmark_to_services_table', 60),
(169, '2022_08_03_064854_create_organization_table_filters_table', 61),
(170, '2022_08_05_052226_add_appear_for_to_account_pages', 61),
(171, '2022_08_16_045555_add_last_verified_by_to_organizations_table', 61),
(172, '2022_09_27_132357_add_fields_to_organization_table_filters_table', 62),
(173, '2022_10_19_112540_add_created_by_to_organizations_table', 63),
(174, '2022_10_19_112724_add_created_by_to_services_table', 63),
(175, '2022_11_12_084901_add_updated_to_organization_table_filters_table', 64),
(176, '2022_12_03_060530_add_taxonomy_icon_hover_to_layouts_table', 65),
(177, '2022_12_26_092036_add_updated_by_to_contacts_table', 66),
(178, '2022_12_26_092400_add_updated_by_to_locations_table', 66),
(179, '2023_01_02_062248_add_latest_updated_date_to_organizations_table', 66),
(180, '2023_02_14_095458_add_verifier_to_users_table', 67),
(181, '2023_03_09_054104_add_open_24_hours_to_schedules_table', 68),
(182, '2023_04_05_091642_create_code_categories_table', 69),
(183, '2023_05_04_164732_create_interpretation_services_table', 70),
(184, '2023_05_05_162824_add_language_to_services_table', 70),
(185, '2023_05_05_173520_rename_altername_name_to_programs_table', 70),
(186, '2023_05_05_174518_add_altername_to_programs_table', 70),
(187, '2023_05_06_061043_create_required_documents_table', 70),
(188, '2023_05_12_065558_add_parent_organization_to_organizations_table', 70),
(189, '2023_05_24_095231_add_hide_service_category_no_with_filter_service_to_layuots_table', 70),
(190, '2023_06_16_131105_create_location_accessibilities_table', 70),
(191, '2023_06_19_061708_add_accessibility_to_locations_table', 70),
(192, '2023_06_28_061138_create_code_systems_table', 70),
(193, '2023_06_28_085652_add_source_to_codes_table', 70),
(194, '2023_07_05_114446_add_code_to_languages_table', 70),
(195, '2023_07_05_115711_add_extent_to_service_areas_table', 70),
(196, '2023_07_11_050936_add_oid_to_code_systems', 70),
(197, '2024_01_11_165940_add_alert_funding_to_services_table', 71),
(198, '2024_01_11_170418_add_funding_to_organizations_table', 71),
(199, '2024_01_16_160830_add_new_fields_to_locations_table', 72),
(200, '2024_01_16_163336_add_accessibilities_url_to_accessibilities_table', 72),
(201, '2024_01_17_173922_add_main_address_to_addresses_table', 72),
(202, '2024_01_19_043025_create_address_types_table', 73),
(203, '2024_01_31_170227_add_version_to_taxonomy_types', 74),
(204, '2024_01_31_174244_add_tag_to_taxonomies_table', 74),
(205, '2024_02_06_172110_create_cost_options_table', 75),
(206, '2024_02_06_173357_create_service_costs_table', 75),
(207, '2024_02_07_033024_create_fundings_table', 75),
(208, '2024_02_07_033514_create_service_fundings_table', 75),
(209, '2024_02_07_034219_add_attribute_to_services_table', 75),
(210, '2024_02_07_095904_add_location_to_contacts_table', 75),
(211, '2024_02_07_111959_create_location_languages_table', 75),
(212, '2024_02_08_035152_create_organization_fundings_table', 75),
(213, '2024_02_08_105250_create_identifiers_table', 75),
(214, '2024_02_09_105436_create_organization_identifiers_table', 75),
(215, '2024_02_12_101436_add_notes_to_schedules_table', 75),
(216, '2024_02_13_041917_add_access_token_to_airtablekeyinfos_table', 75),
(217, '2024_02_13_115317_add_uuid_to_failed_jobs_table', 75);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `organization_name` varchar(191) DEFAULT NULL,
  `parent_organization` bigint(20) DEFAULT NULL,
  `organization_alternate_name` varchar(191) DEFAULT NULL,
  `organization_logo_x` varchar(191) DEFAULT NULL,
  `organization_x_uid` varchar(191) DEFAULT NULL,
  `organization_description` longtext DEFAULT NULL,
  `organization_email` text DEFAULT NULL,
  `organization_forms_x_filename` varchar(191) DEFAULT NULL,
  `organization_forms_x_url` varchar(191) DEFAULT NULL,
  `organization_url` longtext DEFAULT NULL,
  `organization_status_x` varchar(191) DEFAULT NULL,
  `organization_status_sort` varchar(191) DEFAULT NULL,
  `organization_legal_status` varchar(191) DEFAULT NULL,
  `organization_tax_status` varchar(191) DEFAULT NULL,
  `organization_tax_id` varchar(191) DEFAULT NULL,
  `organization_year_incorporated` varchar(191) DEFAULT NULL,
  `organization_services` text DEFAULT NULL,
  `organization_phones` text DEFAULT NULL,
  `organization_locations` text DEFAULT NULL,
  `organization_contact` text DEFAULT NULL,
  `organization_details` longtext DEFAULT NULL,
  `organization_tag` varchar(191) DEFAULT NULL,
  `organization_code` varchar(191) DEFAULT NULL,
  `organization_airs_taxonomy_x` text DEFAULT NULL,
  `flag` varchar(191) DEFAULT NULL,
  `organization_website_rating` varchar(191) DEFAULT NULL,
  `facebook_url` varchar(191) DEFAULT NULL,
  `twitter_url` varchar(191) DEFAULT NULL,
  `instagram_url` varchar(191) DEFAULT NULL,
  `bookmark` int(11) DEFAULT NULL,
  `last_verified_at` timestamp NULL DEFAULT NULL,
  `last_verified_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `latest_updated_date` timestamp NULL DEFAULT NULL,
  `logo` longtext DEFAULT NULL,
  `funding` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_contacts`
--

CREATE TABLE `organization_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) DEFAULT NULL,
  `contact_recordid` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_details`
--

CREATE TABLE `organization_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `detail_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_fundings`
--

CREATE TABLE `organization_fundings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `funding_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_histories`
--

CREATE TABLE `organization_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) DEFAULT NULL,
  `changed_fieldname` varchar(191) DEFAULT NULL,
  `old_value` varchar(191) DEFAULT NULL,
  `new_value` varchar(191) DEFAULT NULL,
  `changed_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_identifiers`
--

CREATE TABLE `organization_identifiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `identifier_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_phones`
--

CREATE TABLE `organization_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) DEFAULT NULL,
  `phone_recordid` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_programs`
--

CREATE TABLE `organization_programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `program_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_statuses`
--

CREATE TABLE `organization_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `organization_statuses`
--

INSERT INTO `organization_statuses` (`id`, `status`, `order`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 'Unverified', 3, 1, NULL, '2021-05-17 11:30:32', '2021-05-17 11:30:32'),
(6, 'Verified', 1, 1, 25, '2021-05-17 11:30:38', '2022-07-23 17:15:35'),
(7, 'No previous attempts', 5, 1, NULL, '2021-05-17 11:30:54', '2021-05-17 11:30:54'),
(8, 'Unverifiable', 6, 1, NULL, '2021-05-17 11:31:03', '2021-05-17 11:31:03'),
(9, 'Out of Business/Inactive', 7, 1, 25, '2021-05-17 11:31:12', '2022-07-23 17:18:32'),
(10, 'In Queue', 2, 1, 25, '2021-05-17 11:31:24', '2022-07-23 17:16:20'),
(11, 'No Status', 11, 95, NULL, '2022-07-25 12:27:21', '2022-07-25 12:27:21'),
(12, 'Verification in Progress', 4, 25, NULL, '2022-07-25 12:49:59', '2022-07-25 12:49:59');

-- --------------------------------------------------------

--
-- Table structure for table `organization_table_filters`
--

CREATE TABLE `organization_table_filters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `filter_name` varchar(191) DEFAULT NULL,
  `organization_tags` text DEFAULT NULL,
  `service_tags` text DEFAULT NULL,
  `status` text DEFAULT NULL,
  `bookmark_only` varchar(191) DEFAULT NULL,
  `start_date` varchar(191) DEFAULT NULL,
  `end_date` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `start_verified` timestamp NULL DEFAULT NULL,
  `end_verified` timestamp NULL DEFAULT NULL,
  `start_updated` timestamp NULL DEFAULT NULL,
  `end_updated` timestamp NULL DEFAULT NULL,
  `last_verified_by` int(11) DEFAULT NULL,
  `last_updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_tags`
--

CREATE TABLE `organization_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(191) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_types`
--

CREATE TABLE `organization_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_type` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_users`
--

CREATE TABLE `organization_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) DEFAULT NULL,
  `user_id` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_attributes`
--

CREATE TABLE `other_attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `link_id` varchar(191) DEFAULT NULL,
  `link_type` varchar(191) DEFAULT NULL,
  `taxonomy_term_id` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `title` varchar(191) NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `files` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone_recordid` bigint(20) DEFAULT NULL,
  `phone_number` text DEFAULT NULL,
  `phone_locations` text DEFAULT NULL,
  `phone_services` text DEFAULT NULL,
  `phone_organizations` varchar(191) DEFAULT NULL,
  `phone_contacts` varchar(191) DEFAULT NULL,
  `phone_extension` varchar(191) DEFAULT NULL,
  `phone_type` varchar(191) DEFAULT NULL,
  `phone_language` varchar(191) DEFAULT NULL,
  `phone_description` longtext DEFAULT NULL,
  `phone_schedule` longtext DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `main_priority` enum('0','1') DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_types`
--

CREATE TABLE `phone_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `program_recordid` bigint(20) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `program_service_relationship` varchar(191) DEFAULT NULL,
  `organizations` longtext DEFAULT NULL,
  `services` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `alternate_name` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `region` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `religions`
--

CREATE TABLE `religions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `parent` varchar(191) DEFAULT NULL,
  `organizations` varchar(191) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `icon` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `deleted_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `required_documents`
--

CREATE TABLE `required_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `detail_id` int(11) DEFAULT NULL,
  `document_type` varchar(191) DEFAULT NULL,
  `document_link` longtext DEFAULT NULL,
  `document_title` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revisions`
--

CREATE TABLE `revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `revisionable_type` varchar(191) NOT NULL,
  `revisionable_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `key` varchar(191) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `permissions` longtext DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'System Admin', '[\"log-viewer::logs.list\",\"log-viewer::logs.delete\",\"log-viewer::logs.show\",\"log-viewer::logs.download\",\"log-viewer::logs.filter\",\"log-viewer::logs.search\",\"ignition.healthCheck\",\"ignition.executeSolution\",\"ignition.shareReport\",\"ignition.scripts\",\"ignition.styles\",\"password.request\",\"password.email\",\"password.reset\",\"password.confirm\",\"services.fetch\",\"services.index\",\"services.create\",\"services.store\",\"services.show\",\"services.edit\",\"services.update\",\"services.destroy\",\"services.add_code_category_ids\",\"services.add_code_category_ids_create\",\"services.code_conditions_save\",\"organizations.fetch\",\"organizations.index\",\"organizations.create\",\"organizations.store\",\"organizations.show\",\"organizations.edit\",\"organizations.update\",\"organizations.destroy\",\"account.fetch_organization\",\"account.index\",\"account.create\",\"account.store\",\"account.show\",\"account.edit\",\"account.update\",\"account.destroy\",\"account.fetch_account_service\",\"contacts.index\",\"contacts.create\",\"contacts.store\",\"contacts.show\",\"contacts.edit\",\"contacts.update\",\"contacts.destroy\",\"facilities.export\",\"facilities.index\",\"facilities.create\",\"facilities.store\",\"facilities.show\",\"facilities.edit\",\"facilities.update\",\"facilities.destroy\",\"sessions.index\",\"sessions.create\",\"sessions.store\",\"sessions.show\",\"sessions.edit\",\"sessions.update\",\"sessions.destroy\",\"suggest.index\",\"suggest.create\",\"suggest.store\",\"suggest.show\",\"suggest.edit\",\"suggest.update\",\"suggest.destroy\",\"users_lists.index\",\"users_lists.create\",\"users_lists.store\",\"users_lists.show\",\"users_lists.edit\",\"users_lists.update\",\"users_lists.destroy\",\"tracking.index\",\"tracking.create\",\"tracking.store\",\"tracking.show\",\"tracking.edit\",\"tracking.update\",\"tracking.destroy\",\"tracking.export\",\"terminology.index\",\"terminology.create\",\"terminology.store\",\"terminology.show\",\"terminology.edit\",\"terminology.update\",\"terminology.destroy\",\"user.invite_user\",\"user.index\",\"user.create\",\"user.store\",\"user.show\",\"user.edit\",\"user.update\",\"user.destroy\",\"user.permissions\",\"user.save\",\"user.activate\",\"user.deactivate\",\"user.ajax_all\",\"user.profile\",\"user.changelog\",\"user.saveProfile\",\"user.send_activation\",\"home.dashboard\",\"messagesSetting.index\",\"dashboard_setting.index\",\"dashboard_setting.create\",\"dashboard_setting.store\",\"dashboard_setting.show\",\"dashboard_setting.edit\",\"dashboard_setting.update\",\"dashboard_setting.destroy\",\"pages.index\",\"pages.create\",\"pages.store\",\"pages.show\",\"pages.edit\",\"pages.update\",\"pages.destroy\",\"parties.index\",\"parties.create\",\"parties.store\",\"parties.show\",\"parties.edit\",\"parties.update\",\"parties.destroy\",\"All_Sessions.index\",\"All_Sessions.create\",\"All_Sessions.store\",\"All_Sessions.show\",\"All_Sessions.edit\",\"All_Sessions.update\",\"All_Sessions.destroy\",\"All_Sessions.getSessions\",\"All_Sessions.all_session_export\",\"All_Sessions.all_interaction_export\",\"All_Sessions.getInteraction\",\"organization_tags.index\",\"organization_tags.create\",\"organization_tags.store\",\"organization_tags.show\",\"organization_tags.edit\",\"organization_tags.update\",\"organization_tags.destroy\",\"organization_tags.changeTag\",\"organization_status.index\",\"organization_status.create\",\"organization_status.store\",\"organization_status.show\",\"organization_status.edit\",\"organization_status.update\",\"organization_status.destroy\",\"organization_status.save_default_organization_status\",\"role.index\",\"role.create\",\"role.store\",\"role.show\",\"role.edit\",\"role.update\",\"role.destroy\",\"role.permissions\",\"role.save\",\"add_country.add_country\",\"add_country.save_country\",\"registrations.index\",\"registrations.create\",\"registrations.store\",\"registrations.show\",\"registrations.edit\",\"registrations.update\",\"registrations.destroy\",\"contact_form.index\",\"contact_form.create\",\"contact_form.store\",\"contact_form.show\",\"contact_form.edit\",\"contact_form.update\",\"contact_form.destroy\",\"contact_form.delete_email\",\"contact_form.create_email\",\"tables.tb_locations\",\"tables.tb_organizations\",\"tables.tb_contact\",\"tables.tb_phones\",\"tables.tb_address\",\"tables.tb_schedule\",\"tables.tb_service_area\",\"tables.saveOrganizationTags\",\"tables.saveOrganizationStatus\",\"tables.createNewOrganizationTag\",\"tables.saveOrganizationBookmark\",\"tables.saveOrganizationFilter\",\"tables.manage_filters\",\"tables.delete_manage_filters\",\"tables.saveServiceBookmark\",\"tables.saveServiceTags\",\"tables.createNewServiceTag\",\"tb_taxonomy.index\",\"tb_taxonomy.create\",\"tb_taxonomy.store\",\"tb_taxonomy.show\",\"tb_taxonomy.edit\",\"tb_taxonomy.update\",\"tb_taxonomy.destroy\",\"tb_taxonomy.taxonommyUpdate\",\"tb_taxonomy.taxonomy_export_csv\",\"tb_taxonomy.getAllTaxonomy\",\"tb_taxonomy.show_added_taxonomy\",\"tb_taxonomy.getParentTerm\",\"tb_taxonomy.edit_taxonomy_added\",\"tb_taxonomy.add_taxonomy_email\",\"tb_taxonomy.delete_taxonomy_email\",\"tb_taxonomy.saveLanguage\",\"tb_taxonomy.save_vocabulary\",\"tb_taxonomy_term.index\",\"tb_taxonomy_term.create\",\"tb_taxonomy_term.store\",\"tb_taxonomy_term.show\",\"tb_taxonomy_term.edit\",\"tb_taxonomy_term.update\",\"tb_taxonomy_term.destroy\",\"service_attributes.index\",\"service_attributes.create\",\"service_attributes.store\",\"service_attributes.show\",\"service_attributes.edit\",\"service_attributes.update\",\"service_attributes.destroy\",\"other_attributes.index\",\"other_attributes.create\",\"other_attributes.store\",\"other_attributes.show\",\"other_attributes.edit\",\"other_attributes.update\",\"other_attributes.destroy\",\"XDetails.index\",\"XDetails.create\",\"XDetails.store\",\"XDetails.show\",\"XDetails.edit\",\"XDetails.update\",\"XDetails.destroy\",\"notes.index\",\"notes.create\",\"notes.store\",\"notes.show\",\"notes.edit\",\"notes.update\",\"notes.destroy\",\"notes.get_session_record\",\"notes.userNotes\",\"notes.organization_notes\",\"cities.index\",\"cities.create\",\"cities.store\",\"cities.show\",\"cities.edit\",\"cities.update\",\"cities.destroy\",\"cities.add_city\",\"address_types.index\",\"address_types.create\",\"address_types.store\",\"address_types.show\",\"address_types.edit\",\"address_types.update\",\"address_types.destroy\",\"states.index\",\"states.create\",\"states.store\",\"states.show\",\"states.edit\",\"states.update\",\"states.destroy\",\"states.add_state\",\"code_categories.index\",\"code_categories.create\",\"code_categories.store\",\"code_categories.show\",\"code_categories.edit\",\"code_categories.update\",\"code_categories.destroy\",\"codes.index\",\"codes.create\",\"codes.store\",\"codes.show\",\"codes.edit\",\"codes.update\",\"codes.destroy\",\"codes.import\",\"codes.export\",\"codes.ImportCodesExcel\",\"code_systems.index\",\"code_systems.create\",\"code_systems.store\",\"code_systems.show\",\"code_systems.edit\",\"code_systems.update\",\"code_systems.destroy\",\"service_areas.index\",\"service_areas.create\",\"service_areas.store\",\"service_areas.show\",\"service_areas.edit\",\"service_areas.update\",\"service_areas.destroy\",\"fees_options.index\",\"fees_options.create\",\"fees_options.store\",\"fees_options.show\",\"fees_options.edit\",\"fees_options.update\",\"fees_options.destroy\",\"regions.index\",\"regions.create\",\"regions.store\",\"regions.show\",\"regions.edit\",\"regions.update\",\"regions.destroy\",\"edits.index\",\"edits.create\",\"edits.store\",\"edits.show\",\"edits.edit\",\"edits.update\",\"edits.destroy\",\"edits.userEdits\",\"edits.organization_edits\",\"edits.edits_export_csv\",\"tb_details.index\",\"tb_details.create\",\"tb_details.store\",\"tb_details.show\",\"tb_details.edit\",\"tb_details.update\",\"tb_details.destroy\",\"programs.index\",\"programs.create\",\"programs.store\",\"programs.show\",\"programs.edit\",\"programs.update\",\"programs.destroy\",\"tb_x_details.index\",\"tb_x_details.create\",\"tb_x_details.store\",\"tb_x_details.show\",\"tb_x_details.edit\",\"tb_x_details.update\",\"tb_x_details.destroy\",\"tb_languages.index\",\"tb_languages.create\",\"tb_languages.store\",\"tb_languages.show\",\"tb_languages.edit\",\"tb_languages.update\",\"tb_languages.destroy\",\"tb_accessibility.index\",\"tb_accessibility.create\",\"tb_accessibility.store\",\"tb_accessibility.show\",\"tb_accessibility.edit\",\"tb_accessibility.update\",\"tb_accessibility.destroy\",\"system_emails.index\",\"system_emails.create\",\"system_emails.store\",\"system_emails.show\",\"system_emails.edit\",\"system_emails.update\",\"system_emails.destroy\",\"tb_service.index\",\"tb_service.create\",\"tb_service.store\",\"tb_service.show\",\"tb_service.edit\",\"tb_service.update\",\"tb_service.destroy\",\"tb_service.get_service_data\",\"tb_service.export\",\"code_ledgers.index\",\"code_ledgers.create\",\"code_ledgers.store\",\"code_ledgers.show\",\"code_ledgers.edit\",\"code_ledgers.update\",\"code_ledgers.destroy\",\"code_ledgers.export\",\"helptexts.helptexts\",\"helptexts.save_helptexts\",\"export.services\",\"export.index\",\"export.create\",\"export.store\",\"export.show\",\"export.edit\",\"export.update\",\"export.destroy\",\"export.getExportConfiguration\",\"export.getExportHistory\",\"export.changeAutoExport\",\"export.exportData\",\"export.export_csv\",\"export.data_for_api\",\"export.data_for_api_v2\",\"import.services\",\"import.location\",\"import.organizations\",\"import.contacts\",\"import.phones\",\"import.addresses\",\"import.languages\",\"import.taxonomy\",\"import.services_taxonomy\",\"import.services_location\",\"import.accessibility\",\"import.schedule\",\"import.service_areas\",\"import.zip\",\"import.index\",\"import.create\",\"import.store\",\"import.show\",\"import.edit\",\"import.update\",\"import.destroy\",\"import.getDataSource\",\"import.getImportHistory\",\"import.importData\",\"import.changeAutoImport\",\"layout_edit.dowload_settings\",\"layout_edit.save_dowload_settings\",\"layout_edit.index\",\"layout_edit.create\",\"layout_edit.store\",\"layout_edit.show\",\"layout_edit.edit\",\"layout_edit.update\",\"layout_edit.destroy\",\"taxonomy_types.export\",\"taxonomy_types.index\",\"taxonomy_types.create\",\"taxonomy_types.store\",\"taxonomy_types.show\",\"taxonomy_types.edit\",\"taxonomy_types.update\",\"taxonomy_types.destroy\",\"home_edit.index\",\"home_edit.create\",\"home_edit.store\",\"home_edit.show\",\"home_edit.edit\",\"home_edit.update\",\"home_edit.destroy\",\"about_edit.index\",\"about_edit.create\",\"about_edit.store\",\"about_edit.show\",\"about_edit.edit\",\"about_edit.update\",\"about_edit.destroy\",\"login_register_edit.index\",\"login_register_edit.create\",\"login_register_edit.store\",\"login_register_edit.show\",\"login_register_edit.edit\",\"login_register_edit.update\",\"login_register_edit.destroy\",\"map.index\",\"map.create\",\"map.store\",\"map.show\",\"map.edit\",\"map.update\",\"map.destroy\",\"map.scan_ungeocoded_location\",\"map.scan_enrichable_location\",\"map.apply_geocode\",\"map.apply_geocode_again\",\"map.apply_enrich\",\"dataSync.export_hsds_zip_file\",\"dataSync.datapackages\",\"dataSync.ImportContactExcel\",\"meta_filter.showMeta\",\"meta_filter.metafilter_save\",\"meta_filter.meta_additional_setting\",\"meta_filter.taxonomy_filter\",\"meta_filter.postal_filter\",\"meta_filter.organization_status_filter\",\"meta_filter.service_status_filter\",\"meta_filter.operation\",\"meta_filter.delete_operation\",\"meta_filter.metafilter_edit\",\"data.index\",\"data.create\",\"data.store\",\"data.show\",\"data.edit\",\"data.update\",\"data.destroy\",\"data.save_source_data\",\"analytics.download_analytic_csv\",\"analytics.download_search_analytic_csv\",\"analytics.index\",\"analytics.create\",\"analytics.store\",\"analytics.show\",\"analytics.edit\",\"analytics.update\",\"analytics.destroy\",\"cron_datasync.cron_datasync\",\"service_tags.index\",\"service_tags.create\",\"service_tags.store\",\"service_tags.show\",\"service_tags.edit\",\"service_tags.update\",\"service_tags.destroy\",\"service_status.index\",\"service_status.create\",\"service_status.store\",\"service_status.show\",\"service_status.edit\",\"service_status.update\",\"service_status.destroy\",\"service_status.save_default_service_status\",\"dispositions.index\",\"dispositions.create\",\"dispositions.store\",\"dispositions.show\",\"dispositions.edit\",\"dispositions.update\",\"dispositions.destroy\",\"interaction_methods.index\",\"interaction_methods.create\",\"interaction_methods.store\",\"interaction_methods.show\",\"interaction_methods.edit\",\"interaction_methods.update\",\"interaction_methods.destroy\",\"phone_types.index\",\"phone_types.create\",\"phone_types.store\",\"phone_types.show\",\"phone_types.edit\",\"phone_types.update\",\"phone_types.destroy\",\"detail_types.index\",\"detail_types.create\",\"detail_types.store\",\"detail_types.show\",\"detail_types.edit\",\"detail_types.update\",\"detail_types.destroy\",\"religions.index\",\"religions.create\",\"religions.store\",\"religions.show\",\"religions.edit\",\"religions.update\",\"religions.destroy\",\"organizationTypes.index\",\"organizationTypes.create\",\"organizationTypes.store\",\"organizationTypes.show\",\"organizationTypes.edit\",\"organizationTypes.update\",\"organizationTypes.destroy\",\"ContactTypes.index\",\"ContactTypes.create\",\"ContactTypes.store\",\"ContactTypes.show\",\"ContactTypes.edit\",\"ContactTypes.update\",\"ContactTypes.destroy\",\"FacilityTypes.index\",\"FacilityTypes.create\",\"FacilityTypes.store\",\"FacilityTypes.show\",\"FacilityTypes.edit\",\"FacilityTypes.update\",\"FacilityTypes.destroy\",\"languages.index\",\"languages.create\",\"languages.store\",\"languages.show\",\"languages.edit\",\"languages.update\",\"languages.destroy\",\"service_categories.index\",\"service_categories.create\",\"service_categories.store\",\"service_categories.show\",\"service_categories.edit\",\"service_categories.update\",\"service_categories.destroy\",\"service_eligibilities.index\",\"service_eligibilities.create\",\"service_eligibilities.store\",\"service_eligibilities.show\",\"service_eligibilities.edit\",\"service_eligibilities.update\",\"service_eligibilities.destroy\"]', '1', NULL, NULL, '2024-01-31 10:33:06'),
(2, 'client', 'client', NULL, '1', NULL, '2020-06-26 06:55:45', '2020-06-26 06:55:45'),
(3, 'Organization Admin', 'Organization Admin', NULL, '1', NULL, '2020-07-09 17:16:14', '2020-07-09 17:16:14'),
(4, 'Section Admin', 'Section Admin', NULL, '3', NULL, '2022-08-25 06:09:05', '2022-08-25 06:09:05');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_recordid` bigint(20) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `services` longtext DEFAULT NULL,
  `phones` varchar(191) DEFAULT NULL,
  `locations` longtext DEFAULT NULL,
  `weekday` varchar(191) DEFAULT NULL,
  `byday` varchar(191) DEFAULT NULL,
  `opens_at` varchar(191) DEFAULT NULL,
  `opens` varchar(191) DEFAULT NULL,
  `closes_at` varchar(191) DEFAULT NULL,
  `closes` varchar(191) DEFAULT NULL,
  `dtstart` varchar(191) DEFAULT NULL,
  `until` varchar(191) DEFAULT NULL,
  `special` varchar(191) DEFAULT NULL,
  `closed` varchar(191) DEFAULT NULL,
  `service_at_location` varchar(191) DEFAULT NULL,
  `freq` varchar(191) DEFAULT NULL,
  `valid_from` varchar(191) DEFAULT NULL,
  `valid_to` varchar(191) DEFAULT NULL,
  `wkst` varchar(191) DEFAULT NULL,
  `interval` varchar(191) DEFAULT NULL,
  `count` varchar(191) DEFAULT NULL,
  `byweekno` varchar(191) DEFAULT NULL,
  `bymonthday` varchar(191) DEFAULT NULL,
  `byyearday` varchar(191) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `timezone` varchar(191) DEFAULT NULL,
  `schedule_services` varchar(191) DEFAULT NULL,
  `schedule_locations` varchar(191) DEFAULT NULL,
  `schedule_holiday` varchar(191) DEFAULT NULL,
  `schedule_closed` varchar(191) DEFAULT NULL,
  `open_24_hours` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `attending_type` varchar(191) DEFAULT NULL,
  `schedule_link` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules_1`
--

CREATE TABLE `schedules_1` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_recordid` bigint(20) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `services` longtext DEFAULT NULL,
  `phones` varchar(191) DEFAULT NULL,
  `locations` longtext DEFAULT NULL,
  `weekday` varchar(191) DEFAULT NULL,
  `byday` varchar(191) DEFAULT NULL,
  `opens_at` varchar(191) DEFAULT NULL,
  `opens` varchar(191) DEFAULT NULL,
  `closes_at` varchar(191) DEFAULT NULL,
  `closes` varchar(191) DEFAULT NULL,
  `dtstart` varchar(191) DEFAULT NULL,
  `until` varchar(191) DEFAULT NULL,
  `special` varchar(191) DEFAULT NULL,
  `closed` varchar(191) DEFAULT NULL,
  `service_at_location` varchar(191) DEFAULT NULL,
  `freq` varchar(191) DEFAULT NULL,
  `valid_from` varchar(191) DEFAULT NULL,
  `valid_to` varchar(191) DEFAULT NULL,
  `wkst` varchar(191) DEFAULT NULL,
  `interval` varchar(191) DEFAULT NULL,
  `count` varchar(191) DEFAULT NULL,
  `byweekno` varchar(191) DEFAULT NULL,
  `bymonthday` varchar(191) DEFAULT NULL,
  `byyearday` varchar(191) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `timezone` varchar(191) DEFAULT NULL,
  `schedule_services` varchar(191) DEFAULT NULL,
  `schedule_locations` varchar(191) DEFAULT NULL,
  `schedule_holiday` varchar(191) DEFAULT NULL,
  `schedule_closed` varchar(191) DEFAULT NULL,
  `open_24_hours` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `service_name` varchar(255) DEFAULT NULL,
  `service_alternate_name` varchar(255) DEFAULT NULL,
  `service_organization` varchar(191) DEFAULT NULL,
  `service_description` text DEFAULT NULL,
  `service_locations` text DEFAULT NULL,
  `service_url` text DEFAULT NULL,
  `service_email` text DEFAULT NULL,
  `service_status` varchar(45) DEFAULT NULL,
  `access_requirement` varchar(191) NOT NULL DEFAULT 'none',
  `service_taxonomy` longtext DEFAULT NULL,
  `service_application_process` text DEFAULT NULL,
  `service_wait_time` longtext DEFAULT NULL,
  `service_fees` longtext DEFAULT NULL,
  `service_accreditations` longtext DEFAULT NULL,
  `service_licenses` varchar(45) DEFAULT NULL,
  `service_phones` text DEFAULT NULL,
  `service_schedule` text DEFAULT NULL,
  `service_contacts` text DEFAULT NULL,
  `service_details` text DEFAULT NULL,
  `service_address` text DEFAULT NULL,
  `service_metadata` varchar(45) DEFAULT NULL,
  `service_program` text DEFAULT NULL,
  `service_code` varchar(191) DEFAULT NULL,
  `SDOH_code` longtext DEFAULT NULL,
  `code_category_ids` longtext DEFAULT NULL,
  `procedure_grouping` longtext DEFAULT NULL,
  `service_tag` varchar(191) DEFAULT NULL,
  `service_airs_taxonomy_x` text DEFAULT NULL,
  `flag` varchar(45) DEFAULT NULL,
  `bookmark` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `service_language` longtext DEFAULT NULL,
  `service_interpretation` longtext DEFAULT NULL,
  `eligibility_description` longtext DEFAULT NULL,
  `minimum_age` int(11) DEFAULT NULL,
  `maximum_age` int(11) DEFAULT NULL,
  `service_alert` longtext DEFAULT NULL,
  `alert` longtext DEFAULT NULL,
  `funding` longtext DEFAULT NULL,
  `attribute` longtext DEFAULT NULL,
  `assured_date` date DEFAULT NULL,
  `assured_email` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_addresses`
--

CREATE TABLE `service_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `address_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_areas`
--

CREATE TABLE `service_areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `services` varchar(191) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `extent` varchar(191) DEFAULT NULL,
  `extent_type` varchar(191) DEFAULT NULL,
  `uri` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `term` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_codes`
--

CREATE TABLE `service_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) DEFAULT NULL,
  `code_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_contacts`
--

CREATE TABLE `service_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` varchar(191) DEFAULT NULL,
  `contact_recordid` bigint(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_costs`
--

CREATE TABLE `service_costs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `cost_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_details`
--

CREATE TABLE `service_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `detail_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_eligibilities`
--

CREATE TABLE `service_eligibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `term` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_fees`
--

CREATE TABLE `service_fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `fees_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_fundings`
--

CREATE TABLE `service_fundings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `funding_recordid` bigint(20) DEFAULT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_locations`
--

CREATE TABLE `service_locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_organizations`
--

CREATE TABLE `service_organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_phones`
--

CREATE TABLE `service_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` varchar(191) DEFAULT NULL,
  `phone_recordid` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_programs`
--

CREATE TABLE `service_programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `program_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_schedules`
--

CREATE TABLE `service_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `schedule_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_statuses`
--

CREATE TABLE `service_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_tags`
--

CREATE TABLE `service_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(191) DEFAULT NULL,
  `created_by` varchar(191) DEFAULT NULL,
  `updated_by` varchar(191) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_taxonomies`
--

CREATE TABLE `service_taxonomies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_id` varchar(191) DEFAULT NULL,
  `taxonomy_detail` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_recordid` varchar(191) DEFAULT NULL,
  `session_name` varchar(191) DEFAULT NULL,
  `session_organization` varchar(191) DEFAULT NULL,
  `session_method` varchar(191) DEFAULT NULL,
  `session_disposition` varchar(191) DEFAULT NULL,
  `session_records_edited` varchar(191) DEFAULT NULL,
  `session_notes` varchar(191) DEFAULT NULL,
  `session_status` varchar(191) DEFAULT NULL,
  `session_verification_status` varchar(191) DEFAULT NULL,
  `session_edits` varchar(191) DEFAULT NULL,
  `session_performed_by` varchar(191) DEFAULT NULL,
  `session_performed_at` timestamp NULL DEFAULT NULL,
  `session_verify` timestamp NULL DEFAULT NULL,
  `session_start` varchar(191) DEFAULT NULL,
  `session_end` varchar(191) DEFAULT NULL,
  `session_duration` varchar(191) DEFAULT NULL,
  `session_start_datetime` timestamp NULL DEFAULT NULL,
  `session_end_datetime` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_data`
--

CREATE TABLE `session_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_recordid` bigint(45) DEFAULT NULL,
  `session_name` varchar(191) DEFAULT NULL,
  `session_organization` bigint(45) DEFAULT NULL,
  `session_service` varchar(191) DEFAULT NULL,
  `service_status` varchar(191) DEFAULT NULL,
  `session_method` varchar(191) DEFAULT NULL,
  `session_disposition` varchar(191) DEFAULT NULL,
  `session_records_edited` varchar(191) DEFAULT NULL,
  `session_notes` longtext DEFAULT NULL,
  `session_status` varchar(191) DEFAULT NULL,
  `session_verification_status` varchar(191) DEFAULT NULL,
  `session_edits` varchar(191) DEFAULT NULL,
  `session_performed_by` varchar(191) DEFAULT NULL,
  `session_performed_at` timestamp NULL DEFAULT NULL,
  `session_verify` timestamp NULL DEFAULT NULL,
  `session_start` varchar(191) DEFAULT NULL,
  `session_end` varchar(191) DEFAULT NULL,
  `session_duration` varchar(191) DEFAULT NULL,
  `session_start_datetime` timestamp NULL DEFAULT NULL,
  `session_end_datetime` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_services` longtext DEFAULT NULL,
  `organization_status` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_interactions`
--

CREATE TABLE `session_interactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `interaction_recordid` varchar(191) DEFAULT NULL,
  `interaction_session` varchar(191) DEFAULT NULL,
  `interaction_method` varchar(191) DEFAULT NULL,
  `interaction_disposition` varchar(191) DEFAULT NULL,
  `interaction_notes` longtext DEFAULT NULL,
  `interaction_records_edited` varchar(191) DEFAULT NULL,
  `interaction_timestamp` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `organization_services` longtext DEFAULT NULL,
  `organization_status` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `source_datas`
--

CREATE TABLE `source_datas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_name` varchar(191) DEFAULT NULL,
  `active` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suggests`
--

CREATE TABLE `suggests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `suggest_recordid` bigint(20) DEFAULT NULL,
  `suggest_organization` varchar(191) DEFAULT NULL,
  `suggest_content` text DEFAULT NULL,
  `suggest_username` varchar(191) DEFAULT NULL,
  `suggest_user_email` varchar(191) DEFAULT NULL,
  `suggest_user_phone` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomies`
--

CREATE TABLE `taxonomies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxonomy_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_name` varchar(191) DEFAULT NULL,
  `taxonomy_parent_name` varchar(191) DEFAULT NULL,
  `exclude_vocabulary` varchar(191) DEFAULT NULL,
  `taxonomy` varchar(191) DEFAULT NULL,
  `x_taxonomies` varchar(191) DEFAULT NULL,
  `category_logo` varchar(191) DEFAULT NULL,
  `category_logo_white` varchar(191) DEFAULT NULL,
  `taxonomy_grandparent_name` varchar(191) DEFAULT NULL,
  `taxonomy_vocabulary` varchar(191) DEFAULT NULL,
  `taxonomy_x_description` longtext DEFAULT NULL,
  `taxonomy_x_notes` varchar(191) DEFAULT NULL,
  `language` varchar(191) DEFAULT NULL,
  `taxonomy_services` text DEFAULT NULL,
  `taxonomy_parent_recordid` varchar(191) DEFAULT NULL,
  `taxonomy_facet` varchar(191) DEFAULT NULL,
  `category_id` varchar(191) DEFAULT NULL,
  `taxonomy_id` varchar(191) DEFAULT NULL,
  `order` tinyint(4) DEFAULT NULL,
  `badge_color` varchar(191) NOT NULL DEFAULT '#000',
  `flag` varchar(191) DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'Published',
  `temp_service_recordid` bigint(20) DEFAULT NULL,
  `temp_organization_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_term` enum('0','1') NOT NULL DEFAULT '0',
  `code` varchar(191) DEFAULT NULL,
  `term_uri` varchar(191) DEFAULT NULL,
  `taxonomy_tag` text DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy_emails`
--

CREATE TABLE `taxonomy_emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_recordid` bigint(20) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy_terms`
--

CREATE TABLE `taxonomy_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxonomy_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_type_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy_types`
--

CREATE TABLE `taxonomy_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `taxonomy_type_recordid` bigint(20) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `type` varchar(191) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `reference_url` varchar(191) DEFAULT NULL,
  `notes` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `version` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) DEFAULT NULL,
  `last_name` varchar(191) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `user_organization` varchar(255) DEFAULT NULL,
  `organization_tags` varchar(191) DEFAULT NULL,
  `service_tags` longtext DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `permissions` longtext DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_by` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verifier` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `email_verified_at`, `password`, `role_id`, `user_organization`, `organization_tags`, `service_tags`, `message`, `phone_number`, `permissions`, `last_login`, `remember_token`, `status`, `created_by`, `deleted_at`, `created_at`, `updated_at`, `verifier`) VALUES
(1, 'admin', NULL, 'admin@admin.com', NULL, '$2y$10$Qmh7UPQYtq0W/kEYM8m4k.ULA1a1IPNP.yWiz.PeiqXTo8Sds075u', 1, NULL, NULL, NULL, NULL, NULL, '[\"password.request\",\"password.email\",\"password.reset\",\"password.confirm\",\"contacts.index\",\"contacts.store\",\"contacts.show\",\"contacts.update\",\"contacts.destroy\",\"groups.index\",\"groups.store\",\"groups.show\",\"groups.update\",\"groups.destroy\",\"campaigns.index\",\"campaigns.store\",\"campaigns.show\",\"campaigns.update\",\"campaigns.destroy\",\"messages.index\",\"messages.store\",\"messages.show\",\"messages.update\",\"messages.destroy\",\"home.dashboard\",\"user.index\",\"user.store\",\"user.show\",\"user.update\",\"user.destroy\",\"user.permissions\",\"user.save\",\"user.activate\",\"user.deactivate\",\"role.index\",\"role.store\",\"role.show\",\"role.update\",\"role.destroy\",\"role.permissions\",\"role.save\",\"tb_services.index\",\"tb_services.store\",\"tb_services.show\",\"tb_services.update\",\"tb_services.destroy\",\"tb_locations.index\",\"tb_locations.store\",\"tb_locations.show\",\"tb_locations.update\",\"tb_locations.destroy\",\"contacts.create\",\"contacts.store\",\"contacts.edit\",\"contacts.update\",\"groups.create\",\"groups.store\",\"groups.edit\",\"groups.update\",\"campaigns.create\",\"campaigns.store\",\"campaigns.edit\",\"campaigns.update\",\"messages.create\",\"messages.store\",\"messages.edit\",\"messages.update\",\"pages.index\",\"pages.create\",\"pages.store\",\"pages.show\",\"pages.edit\",\"pages.update\",\"pages.destroy\",\"user.create\",\"user.store\",\"user.edit\",\"user.update\",\"role.create\",\"role.store\",\"role.edit\",\"role.update\",\"tb_services.create\",\"tb_services.store\",\"tb_services.edit\",\"tb_services.update\",\"tb_locations.create\",\"tb_locations.store\",\"tb_locations.edit\",\"tb_locations.update\",\"tb_organizations.index\",\"tb_organizations.create\",\"tb_organizations.store\",\"tb_organizations.show\",\"tb_organizations.edit\",\"tb_organizations.update\",\"tb_organizations.destroy\",\"tb_contact.index\",\"tb_contact.create\",\"tb_contact.store\",\"tb_contact.show\",\"tb_contact.edit\",\"tb_contact.update\",\"tb_contact.destroy\",\"tb_phones.index\",\"tb_phones.create\",\"tb_phones.store\",\"tb_phones.show\",\"tb_phones.edit\",\"tb_phones.update\",\"tb_phones.destroy\",\"tb_address.index\",\"tb_address.create\",\"tb_address.store\",\"tb_address.show\",\"tb_address.edit\",\"tb_address.update\",\"tb_address.destroy\",\"tb_schedule.index\",\"tb_schedule.create\",\"tb_schedule.store\",\"tb_schedule.show\",\"tb_schedule.edit\",\"tb_schedule.update\",\"tb_schedule.destroy\",\"tb_service_area.index\",\"tb_service_area.create\",\"tb_service_area.store\",\"tb_service_area.show\",\"tb_service_area.edit\",\"tb_service_area.update\",\"tb_service_area.destroy\",\"tb_taxonomy.index\",\"tb_taxonomy.create\",\"tb_taxonomy.store\",\"tb_taxonomy.show\",\"tb_taxonomy.edit\",\"tb_taxonomy.update\",\"tb_taxonomy.destroy\",\"tb_details.index\",\"tb_details.create\",\"tb_details.store\",\"tb_details.show\",\"tb_details.edit\",\"tb_details.update\",\"tb_details.destroy\",\"tb_languages.index\",\"tb_languages.create\",\"tb_languages.store\",\"tb_languages.show\",\"tb_languages.edit\",\"tb_languages.update\",\"tb_languages.destroy\",\"tb_accessibility.index\",\"tb_accessibility.create\",\"tb_accessibility.store\",\"tb_accessibility.show\",\"tb_accessibility.edit\",\"tb_accessibility.update\",\"tb_accessibility.destroy\",\"layout_edit.index\",\"layout_edit.create\",\"layout_edit.store\",\"layout_edit.show\",\"layout_edit.edit\",\"layout_edit.update\",\"layout_edit.destroy\",\"home_edit.index\",\"home_edit.create\",\"home_edit.store\",\"home_edit.show\",\"home_edit.edit\",\"home_edit.update\",\"home_edit.destroy\",\"about_edit.index\",\"about_edit.create\",\"about_edit.store\",\"about_edit.show\",\"about_edit.edit\",\"about_edit.update\",\"about_edit.destroy\",\"login_register_edit.index\",\"login_register_edit.create\",\"login_register_edit.store\",\"login_register_edit.show\",\"login_register_edit.edit\",\"login_register_edit.update\",\"login_register_edit.destroy\",\"map.index\",\"map.create\",\"map.store\",\"map.show\",\"map.edit\",\"map.update\",\"map.destroy\",\"data.index\",\"data.create\",\"data.store\",\"data.show\",\"data.edit\",\"data.update\",\"data.destroy\",\"analytics.index\",\"analytics.create\",\"analytics.store\",\"analytics.show\",\"analytics.edit\",\"analytics.update\",\"analytics.destroy\",\"religions.index\",\"religions.create\",\"religions.store\",\"religions.show\",\"religions.edit\",\"religions.update\",\"religions.destroy\",\"organizationTypes.index\",\"organizationTypes.create\",\"organizationTypes.store\",\"organizationTypes.show\",\"organizationTypes.edit\",\"organizationTypes.update\",\"organizationTypes.destroy\",\"ContactTypes.index\",\"ContactTypes.create\",\"ContactTypes.store\",\"ContactTypes.show\",\"ContactTypes.edit\",\"ContactTypes.update\",\"ContactTypes.destroy\",\"FacilityTypes.index\",\"FacilityTypes.create\",\"FacilityTypes.store\",\"FacilityTypes.show\",\"FacilityTypes.edit\",\"FacilityTypes.update\",\"FacilityTypes.destroy\",\"languages.index\",\"languages.create\",\"languages.store\",\"languages.show\",\"languages.edit\",\"languages.update\",\"languages.destroy\",\"ignition.healthCheck\",\"ignition.executeSolution\",\"ignition.shareReport\",\"ignition.scripts\",\"ignition.styles\",\"dataSync.export\",\"dataSync.import\",\"dataSync.ImportContactExcel\",\"log-viewer::logs.list\",\"log-viewer::logs.delete\",\"log-viewer::logs.show\",\"log-viewer::logs.download\",\"log-viewer::logs.filter\",\"log-viewer::logs.search\"]', '2021-11-09 09:34:30', NULL, '1', NULL, NULL, NULL, '2021-11-09 09:34:30', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessibilities`
--
ALTER TABLE `accessibilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_pages`
--
ALTER TABLE `account_pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `additional_taxonomies`
--
ALTER TABLE `additional_taxonomies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `address_types`
--
ALTER TABLE `address_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agencies`
--
ALTER TABLE `agencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airtablekeyinfos`
--
ALTER TABLE `airtablekeyinfos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airtables`
--
ALTER TABLE `airtables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airtable_v2s`
--
ALTER TABLE `airtable_v2s`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `all_languages`
--
ALTER TABLE `all_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `alt_taxonomies`
--
ALTER TABLE `alt_taxonomies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `analytics`
--
ALTER TABLE `analytics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `area_services`
--
ALTER TABLE `area_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audits`
--
ALTER TABLE `audits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audits_auditable_type_auditable_id_index` (`auditable_type`,`auditable_id`),
  ADD KEY `audits_user_id_user_type_index` (`user_id`,`user_type`);

--
-- Indexes for table `auto_sync_airtables`
--
ALTER TABLE `auto_sync_airtables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auto_sync_records`
--
ALTER TABLE `auto_sync_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cities_city_unique` (`city`);

--
-- Indexes for table `codes`
--
ALTER TABLE `codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_categories`
--
ALTER TABLE `code_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_ledgers`
--
ALTER TABLE `code_ledgers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `code_systems`
--
ALTER TABLE `code_systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_phones`
--
ALTER TABLE `contact_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_types`
--
ALTER TABLE `contact_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cost_options`
--
ALTER TABLE `cost_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `csv`
--
ALTER TABLE `csv`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `c_s_v__sources`
--
ALTER TABLE `c_s_v__sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `details`
--
ALTER TABLE `details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_types`
--
ALTER TABLE `detail_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dispositions`
--
ALTER TABLE `dispositions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `export_configurations`
--
ALTER TABLE `export_configurations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `export_histories`
--
ALTER TABLE `export_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility_types`
--
ALTER TABLE `facility_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fee_options`
--
ALTER TABLE `fee_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fundings`
--
ALTER TABLE `fundings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `helptexts`
--
ALTER TABLE `helptexts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hsds_api_keys`
--
ALTER TABLE `hsds_api_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identifiers`
--
ALTER TABLE `identifiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import_data_sources`
--
ALTER TABLE `import_data_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `import_histories`
--
ALTER TABLE `import_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interaction_methods`
--
ALTER TABLE `interaction_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interpretation_services`
--
ALTER TABLE `interpretation_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `layouts`
--
ALTER TABLE `layouts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_accessibilities`
--
ALTER TABLE `location_accessibilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_addresses`
--
ALTER TABLE `location_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_languages`
--
ALTER TABLE `location_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_phones`
--
ALTER TABLE `location_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_regions`
--
ALTER TABLE `location_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_schedules`
--
ALTER TABLE `location_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maps`
--
ALTER TABLE `maps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meta_filters`
--
ALTER TABLE `meta_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organizations`
--
ALTER TABLE `organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_contacts`
--
ALTER TABLE `organization_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_details`
--
ALTER TABLE `organization_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_fundings`
--
ALTER TABLE `organization_fundings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_histories`
--
ALTER TABLE `organization_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_identifiers`
--
ALTER TABLE `organization_identifiers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_phones`
--
ALTER TABLE `organization_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_programs`
--
ALTER TABLE `organization_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_statuses`
--
ALTER TABLE `organization_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_table_filters`
--
ALTER TABLE `organization_table_filters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_tags`
--
ALTER TABLE `organization_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_types`
--
ALTER TABLE `organization_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization_users`
--
ALTER TABLE `organization_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_attributes`
--
ALTER TABLE `other_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`(191));

--
-- Indexes for table `phones`
--
ALTER TABLE `phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `phone_types`
--
ALTER TABLE `phone_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `religions`
--
ALTER TABLE `religions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `required_documents`
--
ALTER TABLE `required_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revisions`
--
ALTER TABLE `revisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revisions_revisionable_id_revisionable_type_index` (`revisionable_id`,`revisionable_type`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schedules_1`
--
ALTER TABLE `schedules_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_addresses`
--
ALTER TABLE `service_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_areas`
--
ALTER TABLE `service_areas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_codes`
--
ALTER TABLE `service_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_contacts`
--
ALTER TABLE `service_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_costs`
--
ALTER TABLE `service_costs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_details`
--
ALTER TABLE `service_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_eligibilities`
--
ALTER TABLE `service_eligibilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_fees`
--
ALTER TABLE `service_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_fundings`
--
ALTER TABLE `service_fundings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_locations`
--
ALTER TABLE `service_locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_organizations`
--
ALTER TABLE `service_organizations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_phones`
--
ALTER TABLE `service_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_programs`
--
ALTER TABLE `service_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_schedules`
--
ALTER TABLE `service_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_statuses`
--
ALTER TABLE `service_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_tags`
--
ALTER TABLE `service_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_taxonomies`
--
ALTER TABLE `service_taxonomies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session_data`
--
ALTER TABLE `session_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session_interactions`
--
ALTER TABLE `session_interactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `source_datas`
--
ALTER TABLE `source_datas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `states_state_unique` (`state`);

--
-- Indexes for table `suggests`
--
ALTER TABLE `suggests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxonomies`
--
ALTER TABLE `taxonomies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxonomy_emails`
--
ALTER TABLE `taxonomy_emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxonomy_terms`
--
ALTER TABLE `taxonomy_terms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxonomy_types`
--
ALTER TABLE `taxonomy_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accessibilities`
--
ALTER TABLE `accessibilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_pages`
--
ALTER TABLE `account_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `additional_taxonomies`
--
ALTER TABLE `additional_taxonomies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_types`
--
ALTER TABLE `address_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agencies`
--
ALTER TABLE `agencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `airtablekeyinfos`
--
ALTER TABLE `airtablekeyinfos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `airtables`
--
ALTER TABLE `airtables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `airtable_v2s`
--
ALTER TABLE `airtable_v2s`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `all_languages`
--
ALTER TABLE `all_languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alt_taxonomies`
--
ALTER TABLE `alt_taxonomies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `analytics`
--
ALTER TABLE `analytics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `area_services`
--
ALTER TABLE `area_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audits`
--
ALTER TABLE `audits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_sync_airtables`
--
ALTER TABLE `auto_sync_airtables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auto_sync_records`
--
ALTER TABLE `auto_sync_records`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `codes`
--
ALTER TABLE `codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `code_categories`
--
ALTER TABLE `code_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `code_ledgers`
--
ALTER TABLE `code_ledgers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `code_systems`
--
ALTER TABLE `code_systems`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_phones`
--
ALTER TABLE `contact_phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_types`
--
ALTER TABLE `contact_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cost_options`
--
ALTER TABLE `cost_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `csv`
--
ALTER TABLE `csv`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `c_s_v__sources`
--
ALTER TABLE `c_s_v__sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `details`
--
ALTER TABLE `details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_types`
--
ALTER TABLE `detail_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dispositions`
--
ALTER TABLE `dispositions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `export_configurations`
--
ALTER TABLE `export_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `export_histories`
--
ALTER TABLE `export_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facility_types`
--
ALTER TABLE `facility_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_options`
--
ALTER TABLE `fee_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fundings`
--
ALTER TABLE `fundings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `helptexts`
--
ALTER TABLE `helptexts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hsds_api_keys`
--
ALTER TABLE `hsds_api_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `identifiers`
--
ALTER TABLE `identifiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_data_sources`
--
ALTER TABLE `import_data_sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `import_histories`
--
ALTER TABLE `import_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interaction_methods`
--
ALTER TABLE `interaction_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interpretation_services`
--
ALTER TABLE `interpretation_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layouts`
--
ALTER TABLE `layouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_accessibilities`
--
ALTER TABLE `location_accessibilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_addresses`
--
ALTER TABLE `location_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_languages`
--
ALTER TABLE `location_languages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_phones`
--
ALTER TABLE `location_phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_regions`
--
ALTER TABLE `location_regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_schedules`
--
ALTER TABLE `location_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `maps`
--
ALTER TABLE `maps`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meta_filters`
--
ALTER TABLE `meta_filters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `organizations`
--
ALTER TABLE `organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_contacts`
--
ALTER TABLE `organization_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_details`
--
ALTER TABLE `organization_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_fundings`
--
ALTER TABLE `organization_fundings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_histories`
--
ALTER TABLE `organization_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_identifiers`
--
ALTER TABLE `organization_identifiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_phones`
--
ALTER TABLE `organization_phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_programs`
--
ALTER TABLE `organization_programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_statuses`
--
ALTER TABLE `organization_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `organization_table_filters`
--
ALTER TABLE `organization_table_filters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_tags`
--
ALTER TABLE `organization_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_types`
--
ALTER TABLE `organization_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organization_users`
--
ALTER TABLE `organization_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_attributes`
--
ALTER TABLE `other_attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phones`
--
ALTER TABLE `phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `phone_types`
--
ALTER TABLE `phone_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `religions`
--
ALTER TABLE `religions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `required_documents`
--
ALTER TABLE `required_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_costs`
--
ALTER TABLE `service_costs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_fundings`
--
ALTER TABLE `service_fundings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
