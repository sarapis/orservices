-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2021 at 02:25 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sarapis-orservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `accessibilities`
--

CREATE TABLE `accessibilities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `accessibility_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessibility_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessibility` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `accessibility_details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_pages`
--

CREATE TABLE `account_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `top_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_widget` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_pages`
--

INSERT INTO `account_pages` (`id`, `top_content`, `sidebar_widget`, `created_at`, `updated_at`) VALUES
(1, '<p class=\"p1\" style=\"margin-bottom: 0px; font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 14.7px; line-height: normal; font-family: Arial; -webkit-text-stroke-color: rgb(0, 0, 0);\"><span class=\"s1\" style=\"font-kerning: none;\">The DC Community Resource Inventory is a project of the DC Community Resource Information Exchange (DC CoRIE) Initiative.&nbsp;</span></p><p class=\"p2\" style=\"margin-bottom: 0px; font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 14px; line-height: normal; font-family: Helvetica; color: rgb(117, 117, 117); -webkit-text-stroke-color: rgb(117, 117, 117);\"><span class=\"s1\" style=\"font-kerning: none;\"><br></span></p><p class=\"p1\" style=\"margin-bottom: 0px; font-variant-numeric: normal; font-variant-east-asian: normal; font-stretch: normal; font-size: 14.7px; line-height: normal; font-family: Arial; -webkit-text-stroke-color: rgb(0, 0, 0);\"><span class=\"s1\" style=\"font-kerning: none;\">Produced by the DC Primary Care Association, in partnership with the Open Referral Initiative, with technology development by Sarapis, this is a \'proof of concept\' of a functional Community Resource Inventory containing information about health, human, and social services available to DC residents in need.&nbsp;</span></p>', '<iframe src=\"https://calendar.google.com/calendar/embed?src=fcrjsfqi6p3jpc3urerk77caig%40group.calendar.google.com&ctz=America%2FNew_York\" style=\"border: 0\" width=\"800\" height=\"600\" frameborder=\"0\" scrolling=\"no\"></iframe>', '2020-12-28 14:56:10', '2020-12-31 01:51:21');

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
  `address_1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_city` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_state_province` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_postal_code` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_region` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_country` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_attention` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_locations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_services` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_organization` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agencies`
--

CREATE TABLE `agencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `recordid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agency_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `projects` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacts` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airtablekeyinfos`
--

CREATE TABLE `airtablekeyinfos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `api_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `airtables`
--

CREATE TABLE `airtables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_v2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `records` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `syncdate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airtables`
--

INSERT INTO `airtables` (`id`, `name`, `name_v2`, `records`, `syncdate`, `created_at`, `updated_at`) VALUES
(1, 'Services', 'Services', '1014', '2020/11/26 06:09:50', NULL, '2020-11-26 00:39:50'),
(2, 'Locations', 'Locations', '434', '2020/07/01 05:27:01', NULL, '2020-07-01 05:27:01'),
(3, 'Organizations', 'Organizations', '233', '2020/07/01 05:27:02', NULL, '2020-07-01 05:27:02'),
(4, 'Contact', 'Contact', '58', '2020/07/01 05:27:03', NULL, '2020-07-01 05:27:03'),
(5, 'Phones', 'Phones', '615', '2020/07/01 05:27:05', NULL, '2020-07-01 05:27:05'),
(6, 'Address', 'Physical_Address', '390', '2020/07/01 05:27:07', NULL, '2020-07-01 05:27:07'),
(7, 'Schedule', 'Schedule', '393', '2020/07/01 05:27:09', NULL, '2020-07-01 05:27:09'),
(8, 'Taxonomy', 'Taxonomy_Term', '386', '2020/07/01 05:27:11', NULL, '2020-07-01 05:27:11'),
(9, 'Details', 'X_Details', '161', '2020/07/01 05:27:12', NULL, '2020-07-01 05:27:12');

-- --------------------------------------------------------

--
-- Table structure for table `airtable_v2s`
--

CREATE TABLE `airtable_v2s` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `records` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `syncdate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airtable_v2s`
--

INSERT INTO `airtable_v2s` (`id`, `name`, `records`, `syncdate`, `created_at`, `updated_at`) VALUES
(1, 'Services', '1099', '2021/02/04 10:29:49', NULL, '2021-02-04 04:59:49'),
(2, 'Locations', '567', '2021/02/04 10:31:20', NULL, '2021-02-04 05:01:20'),
(3, 'Organizations', '224', '2021/02/04 10:31:36', NULL, '2021-02-04 05:01:36'),
(4, 'Contacts', '93', '2021/02/04 10:31:42', NULL, '2021-02-04 05:01:42'),
(5, 'Phones', '754', '2021/02/04 10:34:31', NULL, '2021-02-04 05:04:31'),
(6, 'Physical_Address', '490', '2021/02/04 10:34:58', NULL, '2021-02-04 05:04:58'),
(7, 'Schedule', '395', '2021/02/04 10:35:20', NULL, '2021-02-04 05:05:20'),
(8, 'Taxonomy_Term', '419', '2021/02/12 15:02:24', NULL, '2021-02-12 09:32:24'),
(9, 'X_Details', '32', '2021/02/04 10:35:47', NULL, '2021-02-04 05:05:47'),
(10, 'Programs', '25', '2021/02/04 10:35:59', NULL, '2021-02-04 05:05:59'),
(11, 'x_Taxonomy', '4', '2021/02/12 16:54:30', NULL, '2021-02-12 11:24:30');

-- --------------------------------------------------------

--
-- Table structure for table `all_languages`
--

CREATE TABLE `all_languages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `language_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `alt_taxonomy_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alt_taxonomy_vocabulary` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `area_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_service` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_date_added` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `area_multiple_counties` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audits`
--

CREATE TABLE `audits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint(20) UNSIGNED NOT NULL,
  `old_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(1023) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_sync_airtables`
--

CREATE TABLE `auto_sync_airtables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `option` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `working_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auto_sync_records`
--

CREATE TABLE `auto_sync_records` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sync_date` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_system` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_element` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_panel_code` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `is_multiselect` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'no',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
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
  `SDOH_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comments_recordid` bigint(20) DEFAULT NULL,
  `comments_content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_user` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_organization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_contact` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_user_firstname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_user_lastname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comments_datetime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_organizations` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_services` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_department` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_phones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_phones`
--

CREATE TABLE `contact_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_types`
--

CREATE TABLE `contact_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `sortname` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `dial_code` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Country code'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `csv`
--

CREATE TABLE `csv` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `csv`
--

INSERT INTO `csv` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Sources:', 'Community Resource Exchange - orservices.sarapis.org', NULL, '2020-10-15 14:23:28'),
(2, 'Filtered by: ', '', NULL, '2020-06-20 03:32:44'),
(3, 'Downloaded: ', '11/16/2020 23:33:28', NULL, '2020-11-16 23:33:28');

-- --------------------------------------------------------

--
-- Table structure for table `c_s_v__sources`
--

CREATE TABLE `c_s_v__sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `records` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `syncdate` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `c_s_v__sources`
--

INSERT INTO `c_s_v__sources` (`id`, `name`, `source`, `records`, `syncdate`, `created_at`, `updated_at`) VALUES
(1, 'Services', 'services.csv', '957', '2020-07-01 05:19:32', NULL, '2020-07-01 05:19:32'),
(2, 'Locations', 'locations.csv', '4089', '2020-07-01 05:20:21', NULL, '2020-07-01 05:20:21'),
(3, 'Organizations', 'organizations.csv', '1074', '2020-07-01 05:20:40', NULL, '2020-07-01 05:20:40'),
(4, 'Contacts', 'contacts.csv', '4089', '2020-07-01 05:25:59', NULL, '2020-07-01 05:25:59'),
(5, 'Phones', 'phones.csv', '4089', '2019/07/30 17:27:03', NULL, NULL),
(6, 'Address', 'physical_addresses.csv', '4089', '2019/07/30 17:27:43', NULL, NULL),
(7, 'Languages', 'languages.csv', '9364', '2019/07/30 17:28:19', NULL, NULL),
(8, 'Taxonomy', 'taxonomy.csv', '1994', '2019/08/12 12:31:30', NULL, NULL),
(9, 'Services_taxonomy', 'services_taxonomy.csv', '26259', '2019/07/30 17:29:51', NULL, NULL),
(10, 'Services_location', 'services_at_location.csv', '4089', '2019/07/30 17:30:16', NULL, NULL),
(11, 'Accessibility_for_disabilites', 'accessibility_for_disabilities.csv', '4089', '2019/07/30 17:30:30', NULL, NULL),
(12, 'Regular_schedules', 'regular_schedules.csv', '11350', '2019/07/30 17:31:47', NULL, NULL),
(13, 'Service_areas', 'service_areas.csv', '6404', '2019/08/23 02:25:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `details`
--

CREATE TABLE `details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_recordid` bigint(20) DEFAULT NULL,
  `detail_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_type` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_organizations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_services` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail_locations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacts` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `detail_types`
--

CREATE TABLE `detail_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `email_info` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facility_types`
--

CREATE TABLE `facility_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `facility_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `helptexts`
--

CREATE TABLE `helptexts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_classification` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_conditions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_goals` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_activities` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `hsds_api_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_data_sources`
--

CREATE TABLE `import_data_sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `import_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `airtable_api_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `airtable_base_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_sync` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `sync_hours` int(11) DEFAULT NULL,
  `last_imports` datetime DEFAULT NULL,
  `organization_tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `import_histories`
--

CREATE TABLE `import_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `import_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `auto_sync` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `status` enum('In-progress','Completed','Error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'In-progress',
  `error_message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sync_by` int(11) DEFAULT NULL,
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
  `order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `layouts`
--

CREATE TABLE `layouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_content_part_1` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_1_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_content_part_2` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_2_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sidebar_content_part_3` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_3_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_text1` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_text2` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_btn_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_btn_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `homepage_background` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_csv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_active` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_active` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_active` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secondary_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_hover_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title_link_color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_menu_color` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_menu_link_color` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_title_color` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_menu_link_hover_color` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_filter_activate` int(11) NOT NULL DEFAULT 0,
  `meta_filter_on_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_filter_off_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `top_background` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bottom_background` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bottom_section_active` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `register_content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activate_login_home` int(11) NOT NULL DEFAULT 0,
  `activate_about_home` int(11) DEFAULT NULL,
  `home_page_style` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activate_religions` int(11) NOT NULL DEFAULT 0,
  `activate_languages` int(11) NOT NULL DEFAULT 0,
  `activate_organization_types` int(11) NOT NULL DEFAULT 0,
  `activate_contact_types` int(11) NOT NULL DEFAULT 0,
  `activate_facility_types` int(11) NOT NULL DEFAULT 0,
  `exclude_vocabulary` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `activate_login_button` int(11) NOT NULL DEFAULT 0,
  `organization_share_button` int(11) NOT NULL DEFAULT 0,
  `service_share_button` int(11) NOT NULL DEFAULT 0,
  `show_classification` enum('yes','no') COLLATE utf8mb4_unicode_ci DEFAULT 'no'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `layouts`
--

INSERT INTO `layouts` (`id`, `logo`, `site_name`, `tagline`, `sidebar_content`, `sidebar_content_part_1`, `part_1_image`, `sidebar_content_part_2`, `part_2_image`, `sidebar_content_part_3`, `part_3_image`, `contact_text`, `banner_text1`, `banner_text2`, `contact_btn_label`, `contact_btn_link`, `footer`, `homepage_background`, `header_pdf`, `footer_pdf`, `footer_csv`, `logo_active`, `title_active`, `about_active`, `primary_color`, `secondary_color`, `button_color`, `button_hover_color`, `title_link_color`, `top_menu_color`, `top_menu_link_color`, `menu_title_color`, `top_menu_link_hover_color`, `meta_filter_activate`, `meta_filter_on_label`, `meta_filter_off_label`, `top_background`, `bottom_background`, `bottom_section_active`, `login_content`, `register_content`, `activate_login_home`, `activate_about_home`, `home_page_style`, `activate_religions`, `activate_languages`, `activate_organization_types`, `activate_contact_types`, `activate_facility_types`, `exclude_vocabulary`, `created_at`, `updated_at`, `activate_login_button`, `organization_share_button`, `service_share_button`, `show_classification`) VALUES
(1, '1594328932.png', 'Community Resource Exchange', NULL, '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">The DC Community Resource Inventory is a project of the DC Community Resource Information Exchange (DC CoRIE) Initiative.&nbsp;</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span id=\"docs-internal-guid-74dba194-7fff-5e13-40e1-13b1ea2fdb29\"><br></span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">Produced by the DC Primary Care Association, in partnership with the Open Referral Initiative, with technology development by Sarapis, this is a \'proof of concept\' of a functional Community Resource Inventory containing information about health, human, and social services available to DC residents in need.&nbsp;</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">This website is a reference implementation for the underlying database. This database is also available via an Application Programming Interface, which can be accessed at our Developer Portal here.</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To suggest edits or additions to this directory, reach out to David Poms at the DCPCA.</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To learn more about the Open Referral Initiative, reach out to <a href=\"mailto:Greg@openreferral.org\" target=\"_blank\">Greg Bloom</a>.</span></p>', '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">The DC Community Resource Inventory is a project of the DC Community Resource Information Exchange (DC CoRIE) Initiative.&nbsp;</span><span style=\"background-color: transparent; color: rgb(0, 0, 0); font-family: Arial; font-size: 11pt; white-space: pre-wrap;\">Produced by the DCPCA, in partnership with the Open Referral Initiative, with technology development by Sarapis, this is a \'proof of concept\' of a functional Community Resource Inventory containing information about health, human, and social services available to DC residents in need.&nbsp;</span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span id=\"docs-internal-guid-74dba194-7fff-5e13-40e1-13b1ea2fdb29\"><br></span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p>', '/uploads/images/1595338162_part2.png', '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"color: rgb(0, 0, 0); font-family: Arial; font-size: 14.6667px; text-align: center; white-space: pre-wrap;\">This website is a reference implementation for the underlying database. This database is also available via an Application Programming Interface, which can be accessed at our Developer Portal here.</span><br></p>', '/uploads/images/1593798060_part3.png', '<p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To suggest edits or additions to this directory, reach out to David Poms at the DCPCA.</span></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"text-align: left; margin-top: 0pt; margin-bottom: 0pt; color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To learn more about the Open Referral Initiative, reach out to <a href=\"mailto:Greg@openreferral.org\" target=\"_blank\">Greg Bloom</a>.</span></p>', '/uploads/images/1593798060.png', 'See information that is incorrect or needs to be updated?', NULL, 'Find local resources that are right for you.', 'Suggest a Change', 'http://ors.sarapis.org/suggest/create', '<p style=\"text-align: center; \"> Community Resource Exchange</p>', '1599793837_part1.png', NULL, 'orservices.sarapis.org', 'Community Resource Exchange - orservices.sarapis.org', '0', '1', '0', '#17cbeb', '#50aed3', '#1973c8', '#ee2e31', '#003db8', '#1973c8', '#ffffff', '#ffffff', '#50c9ce', 1, 'South Dade Only', 'All Services', '1577102338.png', '1577102281.jpg', '0', '<p>Welcome! This is the login page.</p>', '<p>Welcome! This is the register page. </p>', 0, 1, 'Services (ex. larable-dev.sarapisorg)', 0, 0, 0, 0, 0, 'Health', NULL, '2021-02-07 12:41:20', 0, 0, 0, 'no');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` bigint(20) DEFAULT NULL,
  `location_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_organization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_alternate_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_transportation` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_latitude` double DEFAULT NULL,
  `location_longitude` double DEFAULT NULL,
  `location_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_services` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_phones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_details` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_schedule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_tag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enrich_flag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `location_phones`
--

CREATE TABLE `location_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location_recordid` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_recordid` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `long` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `operations` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facet` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `values` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `meta_filters`
--

INSERT INTO `meta_filters` (`id`, `operations`, `facet`, `method`, `values`, `created_at`, `updated_at`) VALUES
(3, 'Include', 'Taxonomy', 'Checklist', NULL, '2020-07-03 13:50:15', '2020-07-03 13:50:15');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
(86, '2021_01_07_125021_create_detail_types_table', 48),
(87, '2021_01_18_102404_add_order_to_taxonomies_table', 49),
(88, '2021_01_18_112952_create_email_templates_table', 50),
(89, '2021_01_20_055834_create_service_categories_table', 51),
(90, '2021_01_20_060120_create_service_eligibilities_table', 51),
(91, '2021_01_28_062036_create_service_programs_table', 52),
(92, '2021_01_28_062241_create_organization_programs_table', 52),
(93, '2021_02_08_110939_create_auto_sync_records_table', 53),
(94, '2021_02_09_055001_create_taxonomy_types_table', 54),
(99, '2021_02_11_055709_add_taxonomy_to_taxonomies_table', 55),
(100, '2021_02_11_084831_add_parent_to_details_table', 55),
(101, '2021_02_12_104206_add_taxonomies_recordid_to_taxonomy_types', 56),
(102, '2021_02_12_104956_create_taxonomy_terms_table', 56),
(103, '2021_02_12_105031_create_additional_taxonomies_table', 56),
(104, '2013_04_09_062329_create_revisions_table', 57),
(105, '2021_03_01_150201_create_organization_histories_table', 57),
(106, '2021_03_01_182835_create_audits_table', 57),
(107, '2021_03_18_160935_add_program_service_relations_to_programs_table', 57),
(108, '2021_03_18_161213_add_service_code_to_services_table', 57),
(109, '2021_03_18_161246_add_organization_code_to_organizations_table', 57),
(110, '2021_03_19_150405_create_import_data_sources_table', 57),
(111, '2021_03_22_113905_create_import_histories_table', 57),
(112, '2021_04_05_074356_create_organization_tags_table', 57),
(113, '2021_04_20_155851_create_organization_statuses_table', 57),
(114, '2021_04_20_173743_add_active_login_button_to_layouts_table', 57),
(115, '2021_04_23_150013_add_access_requirement_to_services_table', 57),
(116, '2021_04_23_161951_add_main_priority_to_phones_table', 57),
(117, '2021_04_24_105611_add_social_links_to_organizations_table', 57),
(118, '2021_04_27_122320_add_status_to_taxonomies_table', 57),
(119, '2021_04_28_143046_add_temp_service_to_taxonomies_table', 57),
(120, '2021_04_28_145312_create_taxonomy_emails_table', 57),
(121, '2021_04_29_102707_add_added_term_to_taxonomies_table', 57),
(122, '2021_05_03_182049_add_order_to_languages_table', 57),
(123, '2021_05_03_184214_add_order_to_organization_tags_table', 57),
(124, '2021_05_05_151658_add_order_to_phone_types_table', 57),
(125, '2021_05_05_152547_add_order_to_detail_types_table', 57),
(126, '2021_05_05_154154_add_order_to_taxonomy_types_table', 57),
(127, '2021_05_05_164832_add_order_to_organization_statuses_table', 57),
(128, '2021_06_23_111641_create_cities_table', 57),
(129, '2021_06_23_111928_create_states_table', 57),
(130, '2021_08_04_112815_create_codes_table', 57),
(131, '2021_08_09_145447_create_service_codes_table', 57),
(132, '2021_08_10_110542_create_helptexts_table', 57),
(133, '2021_08_10_185957_create_code_ledgers_table', 57),
(134, '2021_08_12_114902_add__s_d_o_h_code_to_services_table', 57),
(135, '2021_08_25_003627_add_show_classification_to_layouts_table', 57);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE `organizations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` bigint(20) DEFAULT NULL,
  `organization_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_alternate_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_logo_x` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_x_uid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_email` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_forms_x_filename` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_forms_x_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_status_x` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_status_sort` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_legal_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_tax_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_tax_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_year_incorporated` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_services` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_phones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_locations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_contact` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_details` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_tag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_airs_taxonomy_x` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organization_website_rating` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_contacts`
--

CREATE TABLE `organization_contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `organization_histories`
--

CREATE TABLE `organization_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_fieldname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changed_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_phones`
--

CREATE TABLE `organization_phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `organization_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organization_tags`
--

CREATE TABLE `organization_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `organization_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `organization_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_attributes`
--

CREATE TABLE `other_attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `link_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_term_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext CHARACTER SET utf8mb4 DEFAULT NULL,
  `files` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `title`, `body`, `files`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Home', 'This is Home page', '<p><br></p><p><br></p>', '', '0000-00-00 00:00:00', '2019-12-23 15:20:00', NULL),
(2, 'About', 'About the South Dade Cares Resource Directory', '<div class=\"m_-1977661481779162877gmail-text-center\" style=\"color: rgb(117, 117, 117); font-family: Roboto, sans-serif; font-size: 14px; text-align: center;\"><span id=\"docs-internal-guid-45367cd5-7fff-6623-79dd-1574b65bf19c\"><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">The DC Community Resource Inventory is a project of the DC Community Resource Information Exchange (DC CoRIE) Initiative. </span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><span id=\"docs-internal-guid-74dba194-7fff-5e13-40e1-13b1ea2fdb29\"><br></span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">Produced by the DCPCA, in partnership with the Open Referral Initiative, with technology development by Sarapis, this is a \'proof of concept\' of a functional Community Resource Inventory containing information about health, human, and social services available to DC residents in need. </span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">This website is a reference implementation for the underlying database. This database is also available via an Application Programming Interface, which can be accessed at our Developer Portal here.</span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To suggest edits or additions to this directory, reach out to David Poms at the DCPCA.</span></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><br></p><p dir=\"ltr\" style=\"margin-top: 0pt; margin-bottom: 0pt; line-height: 1.38;\"><span style=\"font-size: 11pt; font-family: Arial; color: rgb(0, 0, 0); background-color: transparent; font-variant-numeric: normal; font-variant-east-asian: normal; vertical-align: baseline; white-space: pre-wrap;\">To learn more about the Open Referral Initiative, reach out to <a href=\"mailto:Greg@openreferral.org\" target=\"_blank\">Greg Bloom</a>.</span></p></span></div>', NULL, '0000-00-00 00:00:00', '2020-06-26 00:27:10', NULL),
(3, 'Feedback', 'This is Feedback page', '<script src=\"https://static.airtable.com/js/embed/embed_snippet_v1.js\"></script><iframe class=\"airtable-embed airtable-dynamic-height\" src=\"https://airtable.com/embed/shrGardkjNsxKA6sO?backgroundColor=teal\" frameborder=\"0\" onmousewheel=\"\" width=\"100%\" height=\"1431\" style=\"background: transparent; border: 1px solid #ccc;\"></iframe>', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', NULL),
(4, 'Google Analytics', 'This is Google Analytics Page', 'final', '', NULL, '2020-07-15 17:29:30', NULL),
(5, 'Login/Register Page', 'This is Login/Register Page', '<p style=\"text-align: center; \"><br class=\"Apple-interchange-newline\"></p>', '', '2019-10-15 19:35:22', '2019-10-15 19:35:22', NULL),
(6, 'Dashboard', 'What is Lorem Ipsum?', '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </span></p><p><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</span></p><p><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\"> It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', NULL, '2021-02-08 06:58:21', '2021-02-09 04:31:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phones`
--

CREATE TABLE `phones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `phone_recordid` bigint(20) DEFAULT NULL,
  `phone_number` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_locations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_services` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_organizations` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_contacts` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_extension` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_language` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_schedule` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `main_priority` enum('0','1') COLLATE utf8mb4_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `phone_types`
--

CREATE TABLE `phone_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alternate_name` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `program_service_relationship` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizations` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `religions`
--

CREATE TABLE `religions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `organizations` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revisions`
--

CREATE TABLE `revisions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `revisionable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisionable_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_value` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `slug`, `name`, `permissions`, `created_by`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'System Admin', '[\"log-viewer::logs.list\",\"log-viewer::logs.delete\",\"log-viewer::logs.show\",\"log-viewer::logs.download\",\"log-viewer::logs.filter\",\"log-viewer::logs.search\",\"ignition.healthCheck\",\"ignition.executeSolution\",\"ignition.shareReport\",\"ignition.scripts\",\"ignition.styles\",\"password.request\",\"password.email\",\"password.reset\",\"password.confirm\",\"account.index\",\"account.create\",\"account.store\",\"account.show\",\"account.edit\",\"account.update\",\"account.destroy\",\"services.index\",\"services.create\",\"services.store\",\"services.show\",\"services.edit\",\"services.update\",\"services.destroy\",\"organizations.index\",\"organizations.create\",\"organizations.store\",\"organizations.show\",\"organizations.edit\",\"organizations.update\",\"organizations.destroy\",\"contacts.index\",\"contacts.create\",\"contacts.store\",\"contacts.show\",\"contacts.edit\",\"contacts.update\",\"contacts.destroy\",\"facilities.index\",\"facilities.create\",\"facilities.store\",\"facilities.show\",\"facilities.edit\",\"facilities.update\",\"facilities.destroy\",\"sessions.index\",\"sessions.create\",\"sessions.store\",\"sessions.show\",\"sessions.edit\",\"sessions.update\",\"sessions.destroy\",\"suggest.index\",\"suggest.create\",\"suggest.store\",\"suggest.show\",\"suggest.edit\",\"suggest.update\",\"suggest.destroy\",\"home.dashboard\",\"dashboard_setting.index\",\"dashboard_setting.create\",\"dashboard_setting.store\",\"dashboard_setting.show\",\"dashboard_setting.edit\",\"dashboard_setting.update\",\"dashboard_setting.destroy\",\"pages.index\",\"pages.create\",\"pages.store\",\"pages.show\",\"pages.edit\",\"pages.update\",\"pages.destroy\",\"parties.index\",\"parties.create\",\"parties.store\",\"parties.show\",\"parties.edit\",\"parties.update\",\"parties.destroy\",\"All_Sessions.index\",\"All_Sessions.create\",\"All_Sessions.store\",\"All_Sessions.show\",\"All_Sessions.edit\",\"All_Sessions.update\",\"All_Sessions.destroy\",\"All_Sessions.getSessions\",\"All_Sessions.all_session_export\",\"All_Sessions.all_interaction_export\",\"All_Sessions.getInteraction\",\"user.index\",\"user.create\",\"user.store\",\"user.show\",\"user.edit\",\"user.update\",\"user.destroy\",\"user.permissions\",\"user.save\",\"user.activate\",\"user.deactivate\",\"user.ajax_all\",\"user.profile\",\"user.saveProfile\",\"role.index\",\"role.create\",\"role.store\",\"role.show\",\"role.edit\",\"role.update\",\"role.destroy\",\"role.permissions\",\"role.save\",\"add_country.add_country\",\"add_country.save_country\",\"contact_form.index\",\"contact_form.delete_email\",\"contact_form.create_email\",\"registrations.index\",\"tables.tb_services\",\"tables.tb_locations\",\"tables.tb_organizations\",\"tables.tb_contact\",\"tables.tb_phones\",\"tables.tb_address\",\"tables.tb_schedule\",\"tables.tb_service_area\",\"tb_taxonomy.index\",\"tb_taxonomy.create\",\"tb_taxonomy.store\",\"tb_taxonomy.show\",\"tb_taxonomy.edit\",\"tb_taxonomy.update\",\"tb_taxonomy.destroy\",\"tb_taxonomy.taxonommyUpdate\",\"tb_taxonomy.saveLanguage\",\"tb_taxonomy.save_vocabulary\",\"tb_taxonomy_term.index\",\"tb_taxonomy_term.create\",\"tb_taxonomy_term.store\",\"tb_taxonomy_term.show\",\"tb_taxonomy_term.edit\",\"tb_taxonomy_term.update\",\"tb_taxonomy_term.destroy\",\"service_attributes.index\",\"service_attributes.create\",\"service_attributes.store\",\"service_attributes.show\",\"service_attributes.edit\",\"service_attributes.update\",\"service_attributes.destroy\",\"other_attributes.index\",\"other_attributes.create\",\"other_attributes.store\",\"other_attributes.show\",\"other_attributes.edit\",\"other_attributes.update\",\"other_attributes.destroy\",\"XDetails.index\",\"XDetails.create\",\"XDetails.store\",\"XDetails.show\",\"XDetails.edit\",\"XDetails.update\",\"XDetails.destroy\",\"tb_details.index\",\"tb_details.create\",\"tb_details.store\",\"tb_details.show\",\"tb_details.edit\",\"tb_details.update\",\"tb_details.destroy\",\"programs.index\",\"programs.create\",\"programs.store\",\"programs.show\",\"programs.edit\",\"programs.update\",\"programs.destroy\",\"tb_x_details.index\",\"tb_x_details.create\",\"tb_x_details.store\",\"tb_x_details.show\",\"tb_x_details.edit\",\"tb_x_details.update\",\"tb_x_details.destroy\",\"tb_languages.index\",\"tb_languages.create\",\"tb_languages.store\",\"tb_languages.show\",\"tb_languages.edit\",\"tb_languages.update\",\"tb_languages.destroy\",\"tb_accessibility.index\",\"tb_accessibility.create\",\"tb_accessibility.store\",\"tb_accessibility.show\",\"tb_accessibility.edit\",\"tb_accessibility.update\",\"tb_accessibility.destroy\",\"system_emails.index\",\"system_emails.create\",\"system_emails.store\",\"system_emails.show\",\"system_emails.edit\",\"system_emails.update\",\"system_emails.destroy\",\"export.services\",\"import.services\",\"import.location\",\"import.organizations\",\"import.contacts\",\"import.phones\",\"import.addresses\",\"import.languages\",\"import.taxonomy\",\"import.services_taxonomy\",\"import.services_location\",\"import.accessibility\",\"import.schedule\",\"import.service_areas\",\"import.zip\",\"taxonomy_types.index\",\"taxonomy_types.create\",\"taxonomy_types.store\",\"taxonomy_types.show\",\"taxonomy_types.edit\",\"taxonomy_types.update\",\"taxonomy_types.destroy\",\"layout_edit.index\",\"layout_edit.create\",\"layout_edit.store\",\"layout_edit.show\",\"layout_edit.edit\",\"layout_edit.update\",\"layout_edit.destroy\",\"home_edit.index\",\"home_edit.create\",\"home_edit.store\",\"home_edit.show\",\"home_edit.edit\",\"home_edit.update\",\"home_edit.destroy\",\"about_edit.index\",\"about_edit.create\",\"about_edit.store\",\"about_edit.show\",\"about_edit.edit\",\"about_edit.update\",\"about_edit.destroy\",\"login_register_edit.index\",\"login_register_edit.create\",\"login_register_edit.store\",\"login_register_edit.show\",\"login_register_edit.edit\",\"login_register_edit.update\",\"login_register_edit.destroy\",\"map.index\",\"map.create\",\"map.store\",\"map.show\",\"map.edit\",\"map.update\",\"map.destroy\",\"map.scan_ungeocoded_location\",\"map.scan_enrichable_location\",\"map.apply_geocode\",\"map.apply_enrich\",\"dataSync.import\",\"dataSync.export\",\"dataSync.export_hsds_zip_file\",\"dataSync.datapackages\",\"dataSync.ImportContactExcel\",\"meta_filter.showMeta\",\"meta_filter.metafilter_save\",\"meta_filter.taxonomy_filter\",\"meta_filter.postal_filter\",\"meta_filter.operation\",\"meta_filter.delete_operation\",\"meta_filter.metafilter_edit\",\"data.index\",\"data.create\",\"data.store\",\"data.show\",\"data.edit\",\"data.update\",\"data.destroy\",\"data.save_source_data\",\"analytics.index\",\"analytics.create\",\"analytics.store\",\"analytics.show\",\"analytics.edit\",\"analytics.update\",\"analytics.destroy\",\"cron_datasync.cron_datasync\",\"phone_types.index\",\"phone_types.create\",\"phone_types.store\",\"phone_types.show\",\"phone_types.edit\",\"phone_types.update\",\"phone_types.destroy\",\"detail_types.index\",\"detail_types.create\",\"detail_types.store\",\"detail_types.show\",\"detail_types.edit\",\"detail_types.update\",\"detail_types.destroy\",\"religions.index\",\"religions.create\",\"religions.store\",\"religions.show\",\"religions.edit\",\"religions.update\",\"religions.destroy\",\"organizationTypes.index\",\"organizationTypes.create\",\"organizationTypes.store\",\"organizationTypes.show\",\"organizationTypes.edit\",\"organizationTypes.update\",\"organizationTypes.destroy\",\"ContactTypes.index\",\"ContactTypes.create\",\"ContactTypes.store\",\"ContactTypes.show\",\"ContactTypes.edit\",\"ContactTypes.update\",\"ContactTypes.destroy\",\"FacilityTypes.index\",\"FacilityTypes.create\",\"FacilityTypes.store\",\"FacilityTypes.show\",\"FacilityTypes.edit\",\"FacilityTypes.update\",\"FacilityTypes.destroy\",\"languages.index\",\"languages.create\",\"languages.store\",\"languages.show\",\"languages.edit\",\"languages.update\",\"languages.destroy\",\"service_categories.index\",\"service_categories.create\",\"service_categories.store\",\"service_categories.show\",\"service_categories.edit\",\"service_categories.update\",\"service_categories.destroy\",\"service_eligibilities.index\",\"service_eligibilities.create\",\"service_eligibilities.store\",\"service_eligibilities.show\",\"service_eligibilities.edit\",\"service_eligibilities.update\",\"service_eligibilities.destroy\"]', '1', NULL, NULL, '2021-02-09 11:01:05'),
(2, 'client', 'client', NULL, '1', NULL, '2020-06-26 06:55:45', '2020-06-26 06:55:45'),
(3, 'Organization Admin', 'Organization Admin', NULL, '1', NULL, '2020-07-09 17:16:14', '2020-07-09 17:16:14');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `schedule_recordid` bigint(20) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phones` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `locations` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weekday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `byday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opens_at` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `opens` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closes_at` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dtstart` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `until` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `closed` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_at_location` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `freq` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_from` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_to` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wkst` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `count` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `byweekno` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bymonthday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `byyearday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_services` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_locations` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_holiday` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schedule_closed` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_alternate_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_organization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_locations` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_email` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_status` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `access_requirement` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `service_taxonomy` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_application_process` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_wait_time` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_fees` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_accreditations` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_licenses` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_phones` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_schedule` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_contacts` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_details` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_address` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_metadata` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_program` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `SDOH_code` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_airs_taxonomy_x` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `term` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `service_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_recordid` bigint(100) DEFAULT NULL,
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
  `term` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `service_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
-- Table structure for table `service_taxonomies`
--

CREATE TABLE `service_taxonomies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_recordid` bigint(20) DEFAULT NULL,
  `taxonomy_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_detail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `session_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_organization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_disposition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_records_edited` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_verification_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_edits` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_performed_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_performed_at` timestamp NULL DEFAULT NULL,
  `session_verify` timestamp NULL DEFAULT NULL,
  `session_start` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_end` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_duration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_start_datetime` timestamp NULL DEFAULT NULL,
  `session_end_datetime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `session_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_organization` bigint(45) DEFAULT NULL,
  `session_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_disposition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_records_edited` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_verification_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_edits` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_performed_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_performed_at` timestamp NULL DEFAULT NULL,
  `session_verify` timestamp NULL DEFAULT NULL,
  `session_start` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_end` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_duration` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_start_datetime` timestamp NULL DEFAULT NULL,
  `session_end_datetime` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_interactions`
--

CREATE TABLE `session_interactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `interaction_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interaction_session` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interaction_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interaction_disposition` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interaction_notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interaction_records_edited` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interaction_timestamp` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `source_datas`
--

CREATE TABLE `source_datas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `source_datas`
--

INSERT INTO `source_datas` (`id`, `source_name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'source', '3', NULL, '2021-02-02 09:33:53');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `suggest_organization` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suggest_content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suggest_username` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suggest_user_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `suggest_user_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `taxonomy_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_parent_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exclude_vocabulary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_taxonomies` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_logo_white` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_grandparent_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_vocabulary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_x_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_x_notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `language` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_services` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_parent_recordid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_facet` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `taxonomy_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` tinyint(4) DEFAULT NULL,
  `badge_color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000',
  `flag` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Published',
  `temp_service_recordid` bigint(20) DEFAULT NULL,
  `temp_organization_recordid` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `added_term` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxonomy_emails`
--

CREATE TABLE `taxonomy_emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_recordid` bigint(20) DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `reference_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `user_organization` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Indexes for table `code_ledgers`
--
ALTER TABLE `code_ledgers`
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
-- Indexes for table `facility_types`
--
ALTER TABLE `facility_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
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
-- Indexes for table `location_addresses`
--
ALTER TABLE `location_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location_phones`
--
ALTER TABLE `location_phones`
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
-- Indexes for table `organization_histories`
--
ALTER TABLE `organization_histories`
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
-- Indexes for table `religions`
--
ALTER TABLE `religions`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT for table `code_ledgers`
--
ALTER TABLE `code_ledgers`
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
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `csv`
--
ALTER TABLE `csv`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `c_s_v__sources`
--
ALTER TABLE `c_s_v__sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- AUTO_INCREMENT for table `location_addresses`
--
ALTER TABLE `location_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location_phones`
--
ALTER TABLE `location_phones`
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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

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
-- AUTO_INCREMENT for table `organization_histories`
--
ALTER TABLE `organization_histories`
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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
-- AUTO_INCREMENT for table `religions`
--
ALTER TABLE `religions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revisions`
--
ALTER TABLE `revisions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_addresses`
--
ALTER TABLE `service_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_codes`
--
ALTER TABLE `service_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_contacts`
--
ALTER TABLE `service_contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_details`
--
ALTER TABLE `service_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_eligibilities`
--
ALTER TABLE `service_eligibilities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_locations`
--
ALTER TABLE `service_locations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_organizations`
--
ALTER TABLE `service_organizations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_phones`
--
ALTER TABLE `service_phones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_programs`
--
ALTER TABLE `service_programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_schedules`
--
ALTER TABLE `service_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_taxonomies`
--
ALTER TABLE `service_taxonomies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session_data`
--
ALTER TABLE `session_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `session_interactions`
--
ALTER TABLE `session_interactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `source_datas`
--
ALTER TABLE `source_datas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suggests`
--
ALTER TABLE `suggests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxonomies`
--
ALTER TABLE `taxonomies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxonomy_emails`
--
ALTER TABLE `taxonomy_emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxonomy_terms`
--
ALTER TABLE `taxonomy_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxonomy_types`
--
ALTER TABLE `taxonomy_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
