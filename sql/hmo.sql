-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 15, 2023 at 07:12 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hmo`
--

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE `applicants` (
  `app_id` int(30) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `civil_status` varchar(15) NOT NULL,
  `spouse` varchar(80) NOT NULL,
  `date_of_birth` date NOT NULL,
  `home_address` varchar(50) NOT NULL,
  `city_address` varchar(50) NOT NULL,
  `contact_no` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `position_level` int(2) NOT NULL,
  `emp_type` varchar(20) NOT NULL,
  `current_status` varchar(20) NOT NULL,
  `business_unit` varchar(40) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `blood_type` varchar(3) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `allergies` varchar(200) NOT NULL,
  `philhealth_no` varchar(20) NOT NULL,
  `contact_person` varchar(80) NOT NULL,
  `contact_person_addr` varchar(50) NOT NULL,
  `contact_person_no` varchar(25) NOT NULL,
  `date_regularized` date DEFAULT NULL,
  `company` varchar(34) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`app_id`, `emp_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `gender`, `civil_status`, `spouse`, `date_of_birth`, `home_address`, `city_address`, `contact_no`, `email`, `position`, `position_level`, `emp_type`, `current_status`, `business_unit`, `dept_name`, `blood_type`, `height`, `weight`, `allergies`, `philhealth_no`, `contact_person`, `contact_person_addr`, `contact_person_no`, `date_regularized`, `company`, `photo`, `created`, `updated`) VALUES
(5, '23849-2022', 'Renciomar', 'Blaya', 'Dano', '', 'Male', 'Single', '', '1998-07-18', 'Bilar, Bohol', 'Tagbilaran City', '09237423232', 'rencio@dano.com', 'System Programmer II', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '168cm', '58kg', 'Seafoods', '8321-47271-121', 'Mama Dano', 'Bilar, Bohol', '09123711341', '2022-01-18', 'Alturas Supermarket Corporation', 'd41763fdb0b96d32c8db5df9c2332143.jpg', '2022-11-14 11:38:59', '2022-11-14 11:38:59'),
(7, '33754-2022', 'Jason', 'Danie', 'Baldesco', '', 'Male', 'Single', '', '1999-12-08', 'Catigbian, Bohol', 'Tagbilaran City, Bohol', '09078813452', 'jasonb@gmail.com', 'System Analyst I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '174cm', '58kg', 'Seafoods, Noodles', '235823-4852-45288', 'Mama Naho', 'Catigbi-an, Bohol', '09108743163', '2022-07-15', 'Alturas Supermarket Corporation', 'ba94fdf83fec6c852115faa73370daad.png', '2022-11-28 07:52:34', '2022-11-28 07:52:34'),
(8, '37453-2022', 'Marc Jayson', 'Budlat', 'Igcalinos', '', 'Male', 'Single', '', '1997-11-19', 'Dimiao, Bohol', 'Tagbilaran City, Bohol', '09672184627', 'jimboy@gmail.com', 'System Programmer II', 6, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B', '152cm', '58kg', 'Chicken Nuggets', '8943-3498-34589', 'Mama Naho', 'Dimiao, Bohol', '09320742638', '2022-08-18', 'Alturas Supermarket Corporation', '22392c7352e8481ed9173181f397b8be.jpg', '2022-11-28 09:08:19', '2022-11-28 09:08:19'),
(10, '23485-2022', 'Jan', 'Berting', 'Amodia', '', 'Male', 'Married', 'Eula Shin', '1996-09-12', 'Carmen, Bohol', 'Tagbilaran City, Bohol', '09846376563', 'janberting@gmail.com', 'System Programmer I', 6, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B', '172cm', '60kg', 'Chicken Nuggets', '84375-347845-34535', 'Mama Berting', 'Carmen, Bohol', '09347534634', '2022-09-14', 'Alturas Supermarket Corporation', '55bceedad64138e944dbd1377b0e52ae.jpg', '2022-11-29 06:42:28', '2022-11-29 06:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `applicants_backup`
--

CREATE TABLE `applicants_backup` (
  `app_id` int(30) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `civil_status` varchar(15) NOT NULL,
  `spouse` varchar(80) NOT NULL,
  `date_of_birth` date NOT NULL,
  `home_address` varchar(50) NOT NULL,
  `city_address` varchar(50) NOT NULL,
  `contact_no` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `position_level` int(2) NOT NULL,
  `emp_type` varchar(20) NOT NULL,
  `current_status` varchar(20) NOT NULL,
  `business_unit` varchar(40) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `blood_type` varchar(3) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `allergies` varchar(200) NOT NULL,
  `philhealth_no` varchar(20) NOT NULL,
  `contact_person` varchar(80) NOT NULL,
  `contact_person_addr` varchar(50) NOT NULL,
  `contact_person_no` varchar(25) NOT NULL,
  `date_regularized` date DEFAULT NULL,
  `company` varchar(34) NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applicants_backup`
--

INSERT INTO `applicants_backup` (`app_id`, `emp_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `gender`, `civil_status`, `spouse`, `date_of_birth`, `home_address`, `city_address`, `contact_no`, `email`, `position`, `position_level`, `emp_type`, `current_status`, `business_unit`, `dept_name`, `blood_type`, `height`, `weight`, `allergies`, `philhealth_no`, `contact_person`, `contact_person_addr`, `contact_person_no`, `date_regularized`, `company`, `photo`) VALUES
(1, '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', 'Male', 'Single', '', '1997-10-22', 'San Vicente, Pilar, Bohol', 'Tagbilaran City', '09275685011', 'marlgnirum32@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '168cm', '52kg', 'Crabs and Shrimps', '2934721', 'Marciana H. Muring', 'San Vicente, Pilar, Bohol', '09362764782', '2021-12-15', 'Alturas Supermarket Corporation', ''),
(2, '23278-2022', 'George', 'Ayuban', 'Curay', 'Jr.', 'Male', 'Single', 'Babe', '1999-04-17', 'Alicia, Bohol', 'Tagbilaran City', '09041784111', 'parenggeorge@gmail.com', 'System Programmer II', 6, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B', '176cm', '54kg', '', '3254236', 'Georgia Curay', 'Alicia, Bohol', '09023642232', '2021-09-15', 'Alturas Supermarket Corporation', ''),
(3, '32544-2022', 'Lorlie', 'Gwapo', 'Ochavillo', '', 'Male', 'Single', '', '1998-06-10', 'Garcia Hernandez, Bohol', 'Tagbilaran City', '09943727233', 'lorlieochavillo@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O+', '172cm', '56kg', '', '21723834', 'Mama Ochavillo', 'Garcia Hernandez', '09632462312', '2022-02-09', 'Alturas Supermarket Corporation', ''),
(4, '23764-2022', 'Ruel', 'Budoy', 'Tumale', 'Jr.', 'Male', 'Single', 'Teresita', '1997-08-23', 'Villa Aurora, Bilar, Bohol', 'Tagbilaran City', '09347228284', '', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '172cm', '58kg', '', '03485842', 'Budoy', 'Bilar, Bohol', '09012734164', '2022-03-03', 'Alturas Supermarket Corporation', ''),
(5, '23849-2022', 'Renciomar', 'Blaya', 'Dano', '', 'Male', 'Single', '', '1998-07-18', 'Bilar, Bohol', 'Tagbilaran City', '09237423232', 'rencio@dano.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '168cm', '58kg', 'Seafoods', '8321-47271-121', 'Rachel Dano', 'Bilar, Bohol', '09123711341', '2022-01-18', 'Alturas Supermarket Corporation', ''),
(6, '00281-2021', 'Ramon', 'Ocsin', 'Ortega', '', 'Male', 'Single', '', '1998-11-11', 'Bayong, Pilar, Bohol', 'Tagbilaran City', '09872384623', 'ramonortega@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '169cm', '57kg', '', '2321-47371-326', 'Raquel Ortega', 'Bayong, Pilar, Bohol', '09642143256', '2022-02-16', 'Alturas Group of Companies', '');

-- --------------------------------------------------------

--
-- Table structure for table `applicants_temp`
--

CREATE TABLE `applicants_temp` (
  `app_id` int(30) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `civil_status` varchar(15) NOT NULL,
  `spouse` varchar(80) NOT NULL,
  `date_of_birth` date NOT NULL,
  `home_address` varchar(50) NOT NULL,
  `city_address` varchar(50) NOT NULL,
  `contact_no` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `position_level` int(2) NOT NULL,
  `emp_type` varchar(20) NOT NULL,
  `current_status` varchar(20) NOT NULL,
  `business_unit` varchar(40) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `blood_type` varchar(3) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `allergies` varchar(200) NOT NULL,
  `philhealth_no` varchar(20) NOT NULL,
  `contact_person` varchar(80) NOT NULL,
  `contact_person_addr` varchar(50) NOT NULL,
  `contact_person_no` varchar(25) NOT NULL,
  `date_regularized` date DEFAULT NULL,
  `company` varchar(34) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `applicants_temp`
--

INSERT INTO `applicants_temp` (`app_id`, `emp_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `gender`, `civil_status`, `spouse`, `date_of_birth`, `home_address`, `city_address`, `contact_no`, `email`, `position`, `position_level`, `emp_type`, `current_status`, `business_unit`, `dept_name`, `blood_type`, `height`, `weight`, `allergies`, `philhealth_no`, `contact_person`, `contact_person_addr`, `contact_person_no`, `date_regularized`, `company`, `photo`, `created`, `updated`) VALUES
(1, '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', 'Male', 'Single', '', '1997-10-22', 'San Vicente, Pilar, Bohol', 'Tagbilaran City', '09275685011', 'marlgnirum32@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '168cm', '52kg', 'Crabs and Shrimps', '2934721', 'Marciana H. Muring', 'San Vicente, Pilar, Bohol', '09362764782', '2021-12-15', 'Alturas Supermarket Corporation', '', '2022-11-28 10:21:00', '2022-11-29 02:55:25'),
(2, '23278-2022', 'George', 'Ayuban', 'Curay', 'Jr.', 'Male', 'Single', 'Babe', '1999-04-17', 'Alicia, Bohol', 'Tagbilaran City', '09041784111', 'parenggeorge@gmail.com', 'System Programmer II', 6, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B', '176cm', '54kg', '', '3254236', 'Georgia Curay', 'Alicia, Bohol', '09023642232', '2021-09-15', 'Alturas Supermarket Corporation', '', '2022-11-28 10:21:00', '2022-11-29 02:55:26'),
(3, '32544-2022', 'Lorlie', 'Gwapo', 'Ochavillo', '', 'Male', 'Single', '', '1998-06-10', 'Garcia Hernandez, Bohol', 'Tagbilaran City', '09943727233', 'lorlieochavillo@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O+', '172cm', '56kg', '', '21723834', 'Mama Ochavillo', 'Garcia Hernandez', '09632462312', '2022-02-09', 'Alturas Supermarket Corporation', '', '2022-11-28 10:21:00', '2022-11-29 02:55:26'),
(4, '03764-2022', 'Ruel', 'Budoy', 'Tumale', 'Jr.', 'Male', 'Single', 'Teresita', '1997-08-23', 'Villa Aurora, Bilar, Bohol', 'Tagbilaran City', '09347228284', '', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '172cm', '58kg', '', '3485842', 'Budoy', 'Bilar, Bohol', '09012734164', '2022-03-03', 'Alturas Supermarket Corporation', '', '2022-11-28 10:21:00', '2022-11-29 02:55:26'),
(5, '23849-2022', 'Renciomar', 'Blaya', 'Dano', '', 'Male', 'Single', '', '1998-07-18', 'Bilar, Bohol', 'Tagbilaran City', '09237423232', 'rencio@dano.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '168cm', '58kg', 'Seafoods', '8321-47271-121', 'Rachel Dano', 'Bilar, Bohol', '09123711341', '2022-01-18', 'Alturas Supermarket Corporation', '', '2022-11-28 10:21:00', '2022-11-29 02:55:26'),
(6, '00281-2021', 'Ramon', 'Ocsin', 'Ortega', '', 'Male', 'Single', '', '1998-11-11', 'Bayong, Pilar, Bohol', 'Tagbilaran City', '09872384623', 'ramonortega@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '169cm', '57kg', '', '2321-47371-326', 'Raquel Ortega', 'Bayong, Pilar, Bohol', '09642143256', '2022-02-16', 'Alturas Supermarket Corporation', '', '2022-11-28 10:21:00', '2022-11-29 02:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `billing_id` int(255) NOT NULL,
  `billing_no` varchar(50) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `loa_id` varchar(50) NOT NULL,
  `noa_id` varchar(50) NOT NULL,
  `hp_id` varchar(20) NOT NULL,
  `total_bill` varchar(30) NOT NULL,
  `total_deduction` varchar(30) NOT NULL,
  `net_bill` varchar(30) NOT NULL,
  `personal_charge` varchar(30) NOT NULL,
  `mbr_remaining_bal` varchar(20) NOT NULL,
  `receipt_img` varchar(50) NOT NULL,
  `billed_by` varchar(80) NOT NULL,
  `billed_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`billing_id`, `billing_no`, `emp_id`, `loa_id`, `noa_id`, `hp_id`, `total_bill`, `total_deduction`, `net_bill`, `personal_charge`, `mbr_remaining_bal`, `receipt_img`, `billed_by`, `billed_on`) VALUES
(1, 'BLN-1676441439', '38343-2022', '31', '', '5', '5000.00', '2000.00', '3000.00', '0', '30000', '', 'George Curay', '2023-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `billing_deductions`
--

CREATE TABLE `billing_deductions` (
  `deduction_id` int(255) NOT NULL,
  `deduction_name` varchar(200) NOT NULL,
  `deduction_amount` varchar(50) NOT NULL,
  `billing_no` varchar(50) NOT NULL,
  `added_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billing_deductions`
--

INSERT INTO `billing_deductions` (`deduction_id`, `deduction_name`, `deduction_amount`, `billing_no`, `added_on`) VALUES
(1, 'Philhealth', '2000', 'BLN-1676441439', '2023-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `billing_services`
--

CREATE TABLE `billing_services` (
  `service_id` int(255) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_quantity` int(10) NOT NULL,
  `service_fee` varchar(30) NOT NULL,
  `billing_no` varchar(50) NOT NULL,
  `added_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `billing_services`
--

INSERT INTO `billing_services` (`service_id`, `service_name`, `service_quantity`, `service_fee`, `billing_no`, `added_on`) VALUES
(1, 'Consultation', 1, '5000', 'BLN-1676441439', '2023-02-15');

-- --------------------------------------------------------

--
-- Table structure for table `company_doctors`
--

CREATE TABLE `company_doctors` (
  `doctor_id` int(11) NOT NULL,
  `doctor_name` varchar(80) NOT NULL,
  `doctor_signature` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `date_updated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `company_doctors`
--

INSERT INTO `company_doctors` (`doctor_id`, `doctor_name`, `doctor_signature`, `date_added`, `date_updated`) VALUES
(1, 'Dr. Michael D. Uy', 'aa0699219809da71e92c0b4b8e98f661.png', '2022-12-02', '2022-12-02'),
(2, 'Dr. Nonaluz Pizarras', '685ef17e4d33ea6b385b9c2d4016f3c1.png', '2022-12-02', '2023-01-13');

-- --------------------------------------------------------

--
-- Table structure for table `cost_types`
--

CREATE TABLE `cost_types` (
  `ctype_id` int(11) NOT NULL,
  `cost_type` varchar(100) NOT NULL,
  `date_added` date NOT NULL,
  `date_updated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cost_types`
--

INSERT INTO `cost_types` (`ctype_id`, `cost_type`, `date_added`, `date_updated`) VALUES
(1, 'Urinalysis', '2022-09-21', '2022-09-21'),
(2, 'X-Ray', '2022-09-21', '2022-09-21'),
(3, 'Blood Test', '2022-09-21', '2022-09-21'),
(4, 'Stool Exam', '2022-09-21', '2022-09-21'),
(5, 'Anesthesia and Medications', '2022-09-21', '2022-09-21'),
(6, 'Dressing, Plaster Casts, Sutures', '2022-09-21', '2022-09-21'),
(7, 'Operating Room', '2022-09-21', '2022-09-21'),
(8, 'Standard Admission Kit', '2022-09-21', '2022-09-21'),
(9, 'Lithotripsy', '2022-09-21', '2022-09-21'),
(10, 'Blood Transfusion and Intravenous Fluids', '2022-09-21', '2022-09-21'),
(11, 'Room and Board', '2022-09-21', '2022-09-21'),
(12, 'ICU Confinement', '2022-09-21', '2022-09-21'),
(13, 'Ambulance Service', '2022-09-21', '2022-09-21'),
(14, 'Recovery Room', '2022-09-21', '2022-09-21'),
(15, 'CBC', '2022-09-21', '2022-09-21'),
(16, 'Minor Surgery', '2022-09-21', '2022-09-21'),
(17, 'Speech and Physical Therapy', '2022-09-21', '2022-09-21'),
(18, 'Pre-natal and Post-natal Consultation', '2022-09-21', '2022-09-21'),
(19, 'CT Scan', '2022-09-21', '2022-09-21'),
(20, 'Dialysis', '2022-09-21', '2023-01-13');

-- --------------------------------------------------------

--
-- Table structure for table `healthcare_providers`
--

CREATE TABLE `healthcare_providers` (
  `hp_id` int(20) NOT NULL,
  `hp_type` varchar(30) NOT NULL,
  `hp_name` varchar(200) NOT NULL,
  `hp_address` varchar(200) NOT NULL,
  `hp_contact` varchar(60) NOT NULL,
  `date_added` date NOT NULL,
  `date_updated` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `healthcare_providers`
--

INSERT INTO `healthcare_providers` (`hp_id`, `hp_type`, `hp_name`, `hp_address`, `hp_contact`, `date_added`, `date_updated`) VALUES
(1, 'Hospital', 'Ramiro Hospital', 'Celestino Gallares St., Tagbilaran City, Bohol', '(038) 411 3515', '2022-10-24', '2022-10-24'),
(2, 'Hospital', 'HNU Hospital (HNUMCI)', '462 J.A Clarin St., Tagbilaran City, Bohol', '(038) 501 9946', '2022-10-24', '2022-10-24'),
(3, 'Hospital', 'Tagbilaran Community Hospital Corporation', 'Miguel Paras St., Tagbilaran City, Bohol', '(038) 411 3324', '2022-10-24', '2022-10-24'),
(4, 'Hospital', 'Borja Family Hospital', '19 Celestino Gallares St., Tagbilaran City, Bohol', '(038) 411 3303', '2022-10-24', '2022-10-24'),
(5, 'Hospital', 'ACE Medical Center Bohol', '0368 Carlos P. Garcia East Avenue, Tagbilaran City, Bohol', '(038) 412 8888', '2022-10-24', '2022-10-24'),
(6, 'Hospital', 'Bohol Doctor\'s Medical', 'Belderol St. Tagbilaran City, Bohol', '09209526624', '2022-10-24', '2022-10-24'),
(7, 'Laboratory', 'St. John Diagnostic Lab', 'Tagbilaran City, Bohol', '09820937526', '2022-10-24', '2022-10-24'),
(9, 'Laboratory', 'AC Lab', 'Tagbilaran City, Bohol', '09166176417', '2022-10-24', '2022-10-24'),
(10, 'Laboratory', 'Medicus-BMOG Diagnostic Lab', 'Tagbilaran City, Bohol', '09176247861', '2022-10-24', '2022-10-24'),
(11, 'Laboratory', 'BMCI Laboratory', 'Tagbilaran City, Bohol', '09459524626', '2022-10-24', '2022-10-24'),
(12, 'Laboratory', '2SD Laboratory', 'Tagbilaran City, Bohol', '09762347536', '2022-10-24', '2023-01-13');

-- --------------------------------------------------------

--
-- Table structure for table `loa_requests`
--

CREATE TABLE `loa_requests` (
  `loa_id` int(20) NOT NULL,
  `loa_no` varchar(30) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `middle_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `hcare_provider` varchar(20) NOT NULL,
  `loa_request_type` varchar(32) NOT NULL,
  `med_services` varchar(100) NOT NULL,
  `health_card_no` varchar(20) NOT NULL,
  `requesting_company` varchar(40) NOT NULL,
  `request_date` date NOT NULL,
  `chief_complaint` varchar(1000) NOT NULL,
  `requesting_physician` varchar(80) NOT NULL,
  `attending_physician` varchar(70) NOT NULL,
  `rx_file` varchar(40) NOT NULL,
  `status` varchar(12) NOT NULL,
  `requested_by` varchar(30) NOT NULL,
  `approved_by` varchar(70) NOT NULL,
  `approved_on` date NOT NULL,
  `disapproved_by` varchar(70) NOT NULL,
  `disapprove_reason` varchar(500) NOT NULL,
  `disapproved_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loa_requests`
--

INSERT INTO `loa_requests` (`loa_id`, `loa_no`, `emp_id`, `first_name`, `middle_name`, `last_name`, `suffix`, `hcare_provider`, `loa_request_type`, `med_services`, `health_card_no`, `requesting_company`, `request_date`, `chief_complaint`, `requesting_physician`, `attending_physician`, `rx_file`, `status`, `requested_by`, `approved_by`, `approved_on`, `disapproved_by`, `disapprove_reason`, `disapproved_on`) VALUES
(1, 'LOA-00000001', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '5', 'Consultation', '', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-23', 'Hello World 2022', '1', 'Dr. No, Dr. Borja', '', 'Pending', '56313-2022', '', '0000-00-00', '', '', '0000-00-00'),
(2, 'LOA-00000002', '23278-2022', 'George', 'Ayuban', 'Curay', 'Jr.', '5', 'Diagnostic Test', '2;3', 'ACN-2022-23278', 'Alturas Supermarket Corporation', '2022-11-07', 'assafassa asfas asfasgvdagv', '2', 'Asasgaga', '9e32acacfc4f05f1fa2f57c77f9fdd60.jpg', 'Approved', '23278-2022', '2', '2022-11-07', '', '', '0000-00-00'),
(4, 'LOA-00000004', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '5', 'Diagnostic Test', '1;2', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-09', 'Diagnostic test requirement for Job application', '1', 'Dr. John Doe', '9b95cc0e5959129b136b21b79c64067d.jpg', 'Approved', '56313-2022', '1', '2022-11-08', '', '', '0000-00-00'),
(5, 'LOA-00000005', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '4', 'Consultation', '', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-23', 'Testing 123', '1', 'Joseph Santos', '', 'Approved', '56313-2022', '1', '2023-01-24', '', '', '0000-00-00'),
(8, 'LOA-00000007', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '3', 'Consultation', '', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-22', 'ahfjkahf asfhaskjhdksja hkj afskkj akjs', '2', 'Helloworld', '', 'Disapproved', '56313-2022', '', '0000-00-00', '1', 'Sample Disapproved\r\n', '2022-11-22'),
(22, 'LOA-00000021', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '5', 'Diagnostic Test', '3;5', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-24', 'helloooooooooooooooooooo woooooooooorld', '1', 'Asfafas', 'b24e1cd697484d169601d87f4299e96a.jpg', 'Approved', '56313-2022', '1', '2022-11-25', '', '', '0000-00-00'),
(23, 'LOA-00000023', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '4', 'Diagnostic Test', '1;5', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-25', 'myloa ijihh huih uhuhh piiop ipojkjk', '1', 'Jsdjjsd J', '34468c8e6abaefcb2b866b3452e71440.jpg', 'Approved', '56313-2022', '1', '2022-12-01', '', '', '0000-00-00'),
(24, 'LOA-00000024', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '11', 'Consultation', '', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-25', 'jdkjsakjfkdsj s ksjksjgkds dskjds', '1', 'Kjklkls Lklsdklkl', '', 'Disapproved', '56313-2022', '', '0000-00-00', '1', 'sdafafafaa', '2022-11-25'),
(25, 'LOA-00000025', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '2', 'Diagnostic Test', '2;3', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2022-11-25', 'hellloooooooooooo diagnostic', '2', 'Safjkjs Kgjsgkskgs', 'd9edb0e324808df7fbe6caee8543e6b4.jpg', 'Approved', '56313-2022', '1', '2022-11-25', '', '', '0000-00-00'),
(27, 'LOA-00000027', '23278-2022', 'George', 'Ayuban', 'Curay', 'Jr.', '5', 'Diagnostic Test', '6;8', 'ACN-2022-23278', 'Alturas Supermarket Corporation', '2022-12-05', 'asfaagasfgasfga', '2', 'Jaksfjkas Faaskfj', 'c5748afbcfc356fa4fa102c1bf104576.png', 'Pending', '23278-2022', '', '0000-00-00', '', '', '0000-00-00'),
(28, 'LOA-00000028', '56313-2022', 'Marlon', 'Hinampas', 'Muring', '', '5', 'Diagnostic Test', '6;7', 'ACN-2022-23412', 'Alturas Supermarket Corporation', '2023-01-24', 'Hey this is a sample request in 2023', '2', 'Asfha  Ffas, Jsafkjakf', '31cddcca2b2e011f38d9a26199e6b132.png', 'Pending', '56313-2022', '', '0000-00-00', '', '', '0000-00-00'),
(29, 'LOA-00000029', '38343-2022', 'Gedym', 'Mae', 'Sab', '', '5', 'Diagnostic Test', '3;6;7;10;11', 'ACN-2023-38343', 'Alturas Supermarket Corporation', '2023-02-01', 'this is a test loa', '2', 'Doctor No', '500e8437904e5c106d7147b6fb7ea58e.jpg', 'Approved', '38343-2022', '1', '2023-02-01', '', '', '0000-00-00'),
(30, 'LOA-00000030', '38343-2022', 'Gedym', 'Mae', 'Sab', '', '5', 'Consultation', '', 'ACN-2023-38343', 'Alturas Supermarket Corporation', '2023-02-02', 'Kidney UTI', '2', '', '', 'Pending', '38343-2022', '', '0000-00-00', '', '', '0000-00-00'),
(31, 'LOA-00000031', '38343-2022', 'Gedym', 'Mae', 'Sab', '', '5', 'Consultation', '', 'ACN-2023-38343', 'Alturas Supermarket Corporation', '2023-02-02', 'Kidney UTI', '2', 'Ahjfasjja Ksakfj', '', 'Approved', '38343-2022', '1', '2023-02-02', '', '', '0000-00-00'),
(32, 'LOA-00000032', '23764-2022', 'Ruel', 'Budoy', 'Tumale', 'Jr.', '5', 'Diagnostic Test', '2;3;10;11;14', 'ACN-2022-23764', 'Alturas Supermarket Corporation', '2023-02-02', 'Budix\'s Diagnostic Test in 2023', '1', '', 'ae46b6772030f11f873483ff848efa98.png', 'Pending', '23764-2022', '', '0000-00-00', '', '', '0000-00-00'),
(37, 'LOA-00000034', '23764-2022', 'Ruel', 'Budoy', 'Tumale', 'Jr.', '5', 'Consultation', '', 'ACN-2022-23764', 'Alturas Supermarket Corporation', '2023-02-02', 'asfgasfa ashfas faoh f aoif a', '2', 'Jasj Fajf, Jkasjf Asd', '', 'Pending', '23764-2022', '', '0000-00-00', '', '', '0000-00-00'),
(38, 'LOA-00000038', '23764-2022', 'Ruel', 'Budoy', 'Tumale', 'Jr.', '5', 'Consultation', '', 'ACN-2022-23764', 'Alturas Supermarket Corporation', '2023-02-02', 'asfgasf asfasfasfas', '1', '', '', 'Pending', '23764-2022', '', '0000-00-00', '', '', '0000-00-00'),
(39, 'LOA-00000039', '23764-2022', 'Ruel', 'Budoy', 'Tumale', 'Jr.', '1', 'Diagnostic Test', '2;3;4;5', 'ACN-2022-23764', 'Alturas Supermarket Corporation', '2023-02-02', 'sample diagnostic test of Budix', '2', '', 'b62fec98d0e87ad552cf7ad715b4708e.jpg', 'Pending', '23764-2022', '', '0000-00-00', '', '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `max_benefit_limits`
--

CREATE TABLE `max_benefit_limits` (
  `mbl_id` int(20) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `max_benefit_limit` varchar(10) NOT NULL,
  `used_mbl` int(10) NOT NULL,
  `remaining_balance` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `max_benefit_limits`
--

INSERT INTO `max_benefit_limits` (`mbl_id`, `emp_id`, `max_benefit_limit`, `used_mbl`, `remaining_balance`) VALUES
(6, '56313-2022', '30000', 0, '30000'),
(9, '23278-2022', '30000', 0, '30000'),
(10, '32544-2022', '25000', 0, '25000'),
(11, '00281-2021', '25000', 0, '25000'),
(12, '23764-2022', '22500', 0, '22500'),
(13, '38343-2022', '30000', 3000, '27000');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` int(50) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `health_card_no` varchar(20) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `civil_status` varchar(15) NOT NULL,
  `spouse` varchar(80) NOT NULL,
  `date_of_birth` date NOT NULL,
  `home_address` varchar(50) NOT NULL,
  `city_address` varchar(50) NOT NULL,
  `contact_no` varchar(32) NOT NULL,
  `email` varchar(30) NOT NULL,
  `position` varchar(30) NOT NULL,
  `position_level` int(2) NOT NULL,
  `emp_type` varchar(20) NOT NULL,
  `current_status` varchar(20) NOT NULL,
  `business_unit` varchar(40) NOT NULL,
  `dept_name` varchar(50) NOT NULL,
  `blood_type` varchar(3) NOT NULL,
  `height` varchar(10) NOT NULL,
  `weight` varchar(10) NOT NULL,
  `allergies` varchar(200) NOT NULL,
  `philhealth_no` varchar(20) NOT NULL,
  `contact_person` varchar(80) NOT NULL,
  `contact_person_addr` varchar(50) NOT NULL,
  `contact_person_no` varchar(25) NOT NULL,
  `date_regularized` date DEFAULT NULL,
  `company` varchar(34) NOT NULL,
  `date_approved` date NOT NULL,
  `approval_status` varchar(10) NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `emp_id`, `health_card_no`, `first_name`, `middle_name`, `last_name`, `suffix`, `gender`, `civil_status`, `spouse`, `date_of_birth`, `home_address`, `city_address`, `contact_no`, `email`, `position`, `position_level`, `emp_type`, `current_status`, `business_unit`, `dept_name`, `blood_type`, `height`, `weight`, `allergies`, `philhealth_no`, `contact_person`, `contact_person_addr`, `contact_person_no`, `date_regularized`, `company`, `date_approved`, `approval_status`, `photo`) VALUES
(1, '56313-2022', 'ACN-2022-23412', 'Marlon', 'Hinampas', 'Muring', '', 'Male', 'Single', '', '1997-10-22', 'San Vicente, Pilar, Bohol', 'Tagbilaran City', '09275685011', 'marlgnirum32@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '168cm', '52kg', 'Crabs and Shrimps', '2934721', 'Marciana H. Muring', 'San Vicente, Pilar, Bohol', '09362764782', '2021-12-15', 'Alturas Supermarket Corporation', '2022-09-07', 'Approved', '0ac1b8fbd5760719194a7ac2a34f2270.jpg'),
(2, '23278-2022', 'ACN-2022-23278', 'George', 'Ayuban', 'Curay', 'Jr.', 'Male', 'Single', 'Babe', '1998-04-17', 'Alicia, Bohol', 'Tagbilaran City', '09041784111', 'parenggeorge@gmail.com', 'System Programmer II', 6, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B', '176cm', '54kg', '', '3254236', 'Georgia Curay', 'Alicia, Bohol', '09023642232', '2021-09-15', 'Alturas Supermarket Corporation', '2022-09-07', 'Approved', 'ef5cc45a0b22713e7bdfd954988a4cd7.jpg'),
(3, '32544-2022', 'ACN-2022-32544', 'Lorlie', 'Gwapo', 'Ochavillo', '', 'Male', 'Single', '', '1998-06-10', 'Garcia Hernandez, Bohol', 'Tagbilaran City', '09943727233', 'lorlieochavillo@gmail.com', 'System Programmer II', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O+', '172cm', '56kg', '', '21723834', 'Mama Ochavillo', 'Garcia Hernandez', '09632462312', '2022-02-09', 'Alturas Supermarket Corporation', '2022-09-07', 'Approved', '3cc7da79209e2847e8545a4e8840c323.jpg'),
(7, '00281-2021', 'ACN-2022-00281', 'Ramon', 'Ocsin', 'Ortega', '', 'Male', 'Single', '', '1998-11-11', 'Bayong, Pilar, Bohol', 'Tagbilaran City', '09872384623', 'ramonortega@gmail.com', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '169cm', '57kg', '', '2321-47371-326', 'Raquel Ortega', 'Bayong, Pilar, Bohol', '09642143256', '2022-02-16', 'Alturas Group of Companies', '2022-11-03', 'Approved', 'fa1ded641a7e7704726bd37713a87dad.png'),
(8, '23764-2022', 'ACN-2022-23764', 'Ruel', 'Budoy', 'Tumale', 'Jr.', 'Male', 'Single', 'Teresita', '1997-08-23', 'Villa Aurora, Bilar, Bohol', 'Tagbilaran City', '09347228284', '', 'System Programmer I', 5, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'O', '172cm', '58kg', '', '03485842', 'Budoy', 'Bilar, Bohol', '09012734164', '2022-03-03', 'Alturas Supermarket Corporation', '2022-11-28', 'Approved', 'c1b0029211953d07bef7b48850961bde.jpg'),
(9, '38343-2022', 'ACN-2023-38343', 'Gedym', 'Mae', 'Sab', '', 'Female', 'Single', '', '1999-08-10', 'Balilihan, Bohol', 'Tagbilaran City, Bohol', '09423784627', 'gedymsab@gmail.com', 'System Programmer II', 6, 'Regular', 'Active', 'HEAD OFFICE', 'Information Technology', 'B+', '158cm', '56kg', 'Buwad', '8934743-453934-3553', 'Ahong Mama', 'Balilihan, Bohol', '09623727834', '2022-07-13', 'Alturas Supermarket Corporation', '2023-01-05', 'Approved', 'dd5a572b36a6fa46adf9045f1271088b.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `noa_requests`
--

CREATE TABLE `noa_requests` (
  `noa_id` int(20) NOT NULL,
  `noa_no` varchar(30) NOT NULL,
  `emp_id` varchar(15) NOT NULL,
  `health_card_no` varchar(30) NOT NULL,
  `requesting_company` varchar(40) NOT NULL,
  `first_name` varchar(40) NOT NULL,
  `middle_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `suffix` varchar(3) NOT NULL,
  `date_of_birth` date NOT NULL,
  `admission_date` date NOT NULL,
  `hospital_id` varchar(20) NOT NULL,
  `chief_complaint` varchar(1000) NOT NULL,
  `request_date` date NOT NULL,
  `work_related` varchar(5) NOT NULL,
  `status` varchar(15) NOT NULL,
  `requested_by` varchar(30) NOT NULL,
  `approved_by` varchar(70) NOT NULL,
  `approved_on` date NOT NULL,
  `disapproved_by` varchar(70) NOT NULL,
  `disapprove_reason` varchar(500) NOT NULL,
  `disapproved_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `noa_requests`
--

INSERT INTO `noa_requests` (`noa_id`, `noa_no`, `emp_id`, `health_card_no`, `requesting_company`, `first_name`, `middle_name`, `last_name`, `suffix`, `date_of_birth`, `admission_date`, `hospital_id`, `chief_complaint`, `request_date`, `work_related`, `status`, `requested_by`, `approved_by`, `approved_on`, `disapproved_by`, `disapprove_reason`, `disapproved_on`) VALUES
(1, 'NOA-00000001', '56313-2022', 'ACN-2022-23412', 'Alturas Supermarket Corporation', 'Marlon', 'Hinampas', 'Muring', '', '1997-10-22', '2022-10-20', '3', 'asfafafa', '2022-10-20', '', 'Disapproved', '56313-2022', '', '0000-00-00', '1', 'adfasfas', '2022-10-25'),
(2, 'NOA-00000002', '23278-2022', 'ACN-2022-23278', 'Alturas Supermarket Corporation', 'George', 'Ayuban', 'Curay', 'Jr.', '1999-04-17', '2022-10-20', '1', 'fasfasasasfa', '2022-10-20', 'Yes', 'Approved', '23278-2022', '1', '2022-10-20', '', '', '0000-00-00'),
(4, 'NOA-00000004', '23278-2022', 'ACN-2022-23278', 'Alturas Supermarket Corporation', 'George', 'Ayuban', 'Curay', 'Jr.', '1999-04-17', '2022-10-25', '5', 'asfaa', '2022-10-25', 'Yes', 'Approved', '23278-2022', '2', '2022-12-02', '', '', '0000-00-00'),
(6, 'NOA-00000006', '56313-2022', 'ACN-2022-23412', 'Alturas Supermarket Corporation', 'Marlon', 'Hinampas', 'Muring', '', '1997-10-22', '2022-10-27', '5', 'year 2023 :D\r\n', '2022-10-26', '', 'Pending', '56313-2022', '', '2022-10-26', '', '', '0000-00-00'),
(11, 'NOA-00000007', '32544-2022', 'ACN-2022-32544', 'Alturas Supermarket Corporation', 'Lorlie', 'Gwapo', 'Ochavillo', '', '1998-06-10', '2022-10-26', '5', 'asfasfasfasfsa', '2022-10-26', '', 'Disapproved', '56313-2022', '', '0000-00-00', '1', 'way lingaw', '2022-11-25'),
(13, 'NOA-00000013', '32544-2022', 'ACN-2022-32544', 'Alturas Supermarket Corporation', 'Lorlie', 'Gwapo', 'Ochavillo', '', '1998-06-10', '2022-10-28', '2', 'hello checkup from 2023 :D !!', '2022-10-28', '', 'Pending', '', '', '0000-00-00', '', '', '0000-00-00'),
(15, 'NOA-00000014', '56313-2022', 'ACN-2022-23412', 'Alturas Supermarket Corporation', 'Marlon', 'Hinampas', 'Muring', '', '1997-10-22', '2022-11-24', '2', 'wow yeeeah 3x', '2022-11-23', 'Yes', 'Closed', '56313-2022', '2', '2022-12-01', '', '', '0000-00-00'),
(16, 'NOA-00000016', '56313-2022', 'ACN-2022-23412', 'Alturas Supermarket Corporation', 'Marlon', 'Hinampas', 'Muring', '', '1997-10-22', '2022-11-24', '6', 'asfa faifafaiafsasaasfas', '2022-11-25', '', 'Disapproved', '56313-2022', '', '2022-11-25', '1', 'ilad ra', '2022-11-29'),
(17, 'NOA-00000017', '23278-2022', 'ACN-2022-23278', 'Alturas Supermarket Corporation', 'George', 'Ayuban', 'Curay', 'Jr.', '1998-04-17', '2022-11-28', '5', 'hello sdjfsdj sdj kgsdjsdk sdgjksdgsd', '2022-11-28', 'No', 'Approved', '56313-2022', '1', '2022-11-28', '', '', '0000-00-00'),
(18, 'NOA-00000018', '23278-2022', 'ACN-2022-23278', 'Alturas Supermarket Corporation', 'George', 'Ayuban', 'Curay', 'Jr.', '1998-04-17', '2022-11-11', '4', 'asdasfasfasas', '2022-11-29', 'No', 'Approved', '56313-2022', '1', '2022-11-29', '', '', '0000-00-00'),
(19, 'NOA-00000019', '56313-2022', 'ACN-2022-23412', 'Alturas Supermarket Corporation', 'Marlon', 'Hinampas', 'Muring', '', '1997-10-22', '2023-01-05', '5', 'hdioasio dashoif aohfoahiassafaafs asfdsads', '2023-01-05', 'Yes', 'Approved', '56313-2022', '2', '2023-01-05', '', '', '0000-00-00'),
(20, 'NOA-00000020', '23764-2022', 'ACN-2022-23764', 'Alturas Supermarket Corporation', 'Ruel', 'Budoy', 'Tumale', 'Jr.', '1997-08-23', '2023-01-29', '5', 'sakit ag ulo ug tiyan', '2023-01-30', '', 'Pending', '23764-2022', '', '0000-00-00', '', '', '0000-00-00'),
(21, 'NOA-00000021', '23764-2022', 'ACN-2022-23764', 'Alturas Supermarket Corporation', 'Ruel', 'Budoy', 'Tumale', 'Jr.', '1997-08-23', '2023-01-30', '5', 'Sakit gihapon', '2023-01-30', '', 'Disapproved', '23764-2022', '', '0000-00-00', '2', 'diagnosis is unclear', '2023-01-30');

-- --------------------------------------------------------

--
-- Table structure for table `personal_charges`
--

CREATE TABLE `personal_charges` (
  `pcharge_id` int(50) NOT NULL,
  `emp_id` varchar(30) NOT NULL,
  `loa_id` int(255) NOT NULL,
  `noa_id` int(255) NOT NULL,
  `amount` varchar(30) NOT NULL,
  `billing_no` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL,
  `added_on` date NOT NULL,
  `paid_on` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `temp`
--

CREATE TABLE `temp` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `city` varchar(80) NOT NULL,
  `post_code` varchar(30) NOT NULL,
  `job_title` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `temp`
--

INSERT INTO `temp` (`id`, `name`, `city`, `post_code`, `job_title`) VALUES
(1, 'Heber', 'Tagbilaran', '26728', 'Home Health Aide\r\n'),
(2, 'Modesto', 'West Janet', '15152-2683', 'Software Engineer\r\n'),
(3, 'Dante', 'East Chanel', '74689-6886', 'Entertainment Attendant\r\n'),
(4, 'Nolan', 'Murphyville', '32561-8079', 'Credit Authorizer\r\n'),
(5, 'Jovany', 'O\'Reillyton', '44371', 'Medical Assistant\r\n'),
(6, 'Jaeden', 'Greenfort', '06179-1759', 'School Social Worker\r\n'),
(7, 'Efrain', 'West Blairborough', '11282-0496', 'Electronic Drafter\r\n'),
(8, 'Travon', 'South Tatum', '76603-0822', 'Manufactured Building Installer\r\n'),
(9, 'Agustina', 'North Gertrudeland', '18950', 'Health Services Manager');

-- --------------------------------------------------------

--
-- Table structure for table `user_accounts`
--

CREATE TABLE `user_accounts` (
  `user_id` int(11) NOT NULL,
  `emp_id` varchar(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `user_role` varchar(30) NOT NULL,
  `dsg_hcare_prov` varchar(10) NOT NULL,
  `doctor_id` varchar(50) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `status` varchar(10) NOT NULL,
  `online` int(1) NOT NULL,
  `photo` varchar(100) NOT NULL,
  `created_on` date NOT NULL,
  `updated_on` date NOT NULL,
  `updated_by` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_accounts`
--

INSERT INTO `user_accounts` (`user_id`, `emp_id`, `full_name`, `user_role`, `dsg_hcare_prov`, `doctor_id`, `username`, `password`, `status`, `online`, `photo`, `created_on`, `updated_on`, `updated_by`) VALUES
(1, '', 'IT SysDev', 'super-admin', '', '', 'itsysdev', '$2y$10$XFb5b/ZPEfOKX.cozy410exlcMOh6z7SBq8P/XljjCrHEYi07sppy', 'Active', 0, '', '2022-09-29', '2023-01-13', 'IT SysDev'),
(2, '', 'Marlon H. Muring', 'healthcare-coordinator', '', '', 'coordinator', '$2y$10$uFMFd4RnlQFLQcGjie9vjuV6xbhb4urONDvcfM3vyjD7NZZcg57H.', 'Active', 0, '', '2022-09-29', '2022-10-21', 'Default HealthCare Coordinator '),
(9, '56313-2022', 'Marlon H. Muring', 'member', '', '', 'marlonm', '$2y$10$AZ7Px2zYdPm7SwKEvXRINeUGhnXmnh0DtMsOScF79PxwGyibUuvsm', 'Active', 0, '', '2022-09-29', '2022-10-28', 'Marlon H. Muring'),
(10, '23278-2022', 'George Curay', 'healthcare-provider', '5', '', 'Ayuban17', '$2y$10$oFmxPMZWyEIKkFz/T3BwyezXI3aVO7XviWqzhudhyPGVGj8CQfjJO', 'Active', 1, '', '2022-10-03', '2022-10-21', 'Default HealthCare Coordinator '),
(13, '23278-2022', 'George Curay', 'member', '', '', '23278-2022', '$2y$10$90/6gxZrfUZL7bEufvGHTuwhoyigRmk53HNyrr98iBFybiAlASpiq', 'Active', 0, '', '2022-10-05', '2022-10-14', 'Default HealthCare Coordinator '),
(17, '56313-2022', 'Ramiro Hospital Coordinator', 'healthcare-provider', '1', '', 'ramiro', '$2y$10$o8BBCELJf6LqSI4wRp0nMe0bf2B4t4cri8nDgLbNqGHE.QwjVpryK', 'Active', 0, '', '2022-10-25', '2023-02-02', 'IT SysDev'),
(18, '32544-2022', 'Lorlie Ochavillo', 'member', '', '', '32544-2022', '$2y$10$.uIaDmmPCIl2SugOYj0LPuPCXkLVKjGtflgB3J8fUu26jkJ5zkySi', 'Active', 0, '', '2022-10-26', '2023-02-10', 'Marlon H. Muring'),
(19, '56313-2022', 'Marlon H. Muring', 'healthcare-coordinator', '', '', 'hcoordinator', '$2y$10$Uy9qfSD2MgiYsik2PabkXugJ/w4EBLWwTu58YAyxE/ClXx1iUMu3i', 'Active', 0, '', '2022-10-26', '0000-00-00', ''),
(20, '23278-2022', 'George Curay', 'head-office-accounting', '', '', 'accounting', '$2y$10$tp4gJrN/U2YKQqkfWs0fWeB6J6B1CrkX2ALp3qIXscUgELOQs8eW.', 'Active', 0, '', '2022-10-27', '2022-12-07', 'Marlon H. Muring'),
(22, '32544-2022', 'Lorlie Gwapo Ochavillo ', 'healthcare-provider', '4', '', 'lorlie', '$2y$10$oo3b1O3nMEitemFxbaO1ZeemIXHvIoazBiQJIF.zEKaHa45Tow6N2', 'Active', 0, '', '2022-10-27', '2023-01-13', 'IT SysDev'),
(23, '00281-2021', 'Ramon Ocsin Ortega ', 'member', '', '', '00281-2021', '$2y$10$M6tdItwvtki8x1qu1gnaSuKcYohObptxUVinCwf1I40R150pVX9bC', 'Active', 0, '', '2022-11-03', '2022-12-07', 'Marlon H. Muring'),
(24, '23764-2022', 'Ruel Budoy Tumale Jr.', 'member', '', '', 'akobudoy', '$2y$10$1NxWGdi0gJFbVaMVssUHQOECqkJaiFW2CJ.BfUaTAqSTzFnq.e2tK', 'Active', 0, '', '2022-11-28', '2023-02-02', 'Ruel Budoy Tumale Jr.'),
(31, '', 'Dr. Michael D. Uy', 'company-doctor', '', '1', 'doctor', '$2y$10$ESojuDH6f8Eiz99eZtAeVeoHuZY8QI3nb7Wbc1.L5/WVRUEJkAn3K', 'Active', 0, '', '2022-12-02', '2023-01-27', 'Dr. Michael D. Uy'),
(32, '', 'Dr. Nonaluz Pizarras', 'company-doctor', '', '2', 'drnona', '$2y$10$pLzJ7lOD.bshnFnke3ff/exRajtOknhpR9mqddW4iH2XCx2p0q.Ji', 'Active', 0, '', '2022-12-02', '2022-12-02', ''),
(36, '38343-2022', 'Gedym Mae Sab ', 'member', '', '', 'gedymsab', '$2y$10$0G.RYXrRCklMg4eOMsOQ3OGl7FIRWaam8anyCyZEQFpsR3GHszJNq', 'Active', 0, '', '2023-01-05', '2023-02-14', 'IT SysDev');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applicants`
--
ALTER TABLE `applicants`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `primary_address` (`home_address`),
  ADD KEY `mobile_number` (`contact_no`),
  ADD KEY `email` (`email`),
  ADD KEY `gender` (`gender`),
  ADD KEY `birthday` (`date_of_birth`),
  ADD KEY `blood_type` (`blood_type`),
  ADD KEY `allergies` (`allergies`),
  ADD KEY `philhealth_no` (`philhealth_no`),
  ADD KEY `contact_person` (`contact_person`),
  ADD KEY `contact_person_addr` (`contact_person_addr`),
  ADD KEY `contact_person_no` (`contact_person_no`),
  ADD KEY `first_name_2` (`first_name`),
  ADD KEY `first_name_3` (`first_name`),
  ADD KEY `suffix` (`suffix`),
  ADD KEY `civil_status` (`civil_status`),
  ADD KEY `date_of_birth` (`date_of_birth`),
  ADD KEY `address` (`home_address`),
  ADD KEY `position` (`position`),
  ADD KEY `position_level` (`position_level`),
  ADD KEY `department` (`dept_name`),
  ADD KEY `homer_address` (`home_address`),
  ADD KEY `city_address` (`city_address`),
  ADD KEY `home_address` (`home_address`),
  ADD KEY `photo` (`photo`),
  ADD KEY `insurance_company` (`company`),
  ADD KEY `date_regular` (`date_regularized`),
  ADD KEY `date_regularized` (`date_regularized`),
  ADD KEY `dept_name` (`dept_name`),
  ADD KEY `business_unit` (`business_unit`),
  ADD KEY `emp_type` (`emp_type`),
  ADD KEY `current_status` (`current_status`),
  ADD KEY `contactno` (`contact_no`),
  ADD KEY `height` (`height`),
  ADD KEY `weight` (`weight`),
  ADD KEY `spouse` (`spouse`);

--
-- Indexes for table `applicants_backup`
--
ALTER TABLE `applicants_backup`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `primary_address` (`home_address`),
  ADD KEY `mobile_number` (`contact_no`),
  ADD KEY `email` (`email`),
  ADD KEY `gender` (`gender`),
  ADD KEY `birthday` (`date_of_birth`),
  ADD KEY `blood_type` (`blood_type`),
  ADD KEY `allergies` (`allergies`),
  ADD KEY `philhealth_no` (`philhealth_no`),
  ADD KEY `contact_person` (`contact_person`),
  ADD KEY `contact_person_addr` (`contact_person_addr`),
  ADD KEY `contact_person_no` (`contact_person_no`),
  ADD KEY `first_name_2` (`first_name`),
  ADD KEY `first_name_3` (`first_name`),
  ADD KEY `suffix` (`suffix`),
  ADD KEY `civil_status` (`civil_status`),
  ADD KEY `date_of_birth` (`date_of_birth`),
  ADD KEY `address` (`home_address`),
  ADD KEY `position` (`position`),
  ADD KEY `position_level` (`position_level`),
  ADD KEY `department` (`dept_name`),
  ADD KEY `homer_address` (`home_address`),
  ADD KEY `city_address` (`city_address`),
  ADD KEY `home_address` (`home_address`),
  ADD KEY `photo` (`photo`),
  ADD KEY `insurance_company` (`company`),
  ADD KEY `date_regular` (`date_regularized`),
  ADD KEY `date_regularized` (`date_regularized`),
  ADD KEY `dept_name` (`dept_name`),
  ADD KEY `business_unit` (`business_unit`),
  ADD KEY `emp_type` (`emp_type`),
  ADD KEY `current_status` (`current_status`),
  ADD KEY `contactno` (`contact_no`),
  ADD KEY `height` (`height`),
  ADD KEY `weight` (`weight`),
  ADD KEY `spouse` (`spouse`);

--
-- Indexes for table `applicants_temp`
--
ALTER TABLE `applicants_temp`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `primary_address` (`home_address`),
  ADD KEY `mobile_number` (`contact_no`),
  ADD KEY `email` (`email`),
  ADD KEY `gender` (`gender`),
  ADD KEY `birthday` (`date_of_birth`),
  ADD KEY `blood_type` (`blood_type`),
  ADD KEY `allergies` (`allergies`),
  ADD KEY `philhealth_no` (`philhealth_no`),
  ADD KEY `contact_person` (`contact_person`),
  ADD KEY `contact_person_addr` (`contact_person_addr`),
  ADD KEY `contact_person_no` (`contact_person_no`),
  ADD KEY `first_name_2` (`first_name`),
  ADD KEY `first_name_3` (`first_name`),
  ADD KEY `suffix` (`suffix`),
  ADD KEY `civil_status` (`civil_status`),
  ADD KEY `date_of_birth` (`date_of_birth`),
  ADD KEY `address` (`home_address`),
  ADD KEY `position` (`position`),
  ADD KEY `position_level` (`position_level`),
  ADD KEY `department` (`dept_name`),
  ADD KEY `homer_address` (`home_address`),
  ADD KEY `city_address` (`city_address`),
  ADD KEY `home_address` (`home_address`),
  ADD KEY `photo` (`photo`),
  ADD KEY `insurance_company` (`company`),
  ADD KEY `date_regular` (`date_regularized`),
  ADD KEY `date_regularized` (`date_regularized`),
  ADD KEY `dept_name` (`dept_name`),
  ADD KEY `business_unit` (`business_unit`),
  ADD KEY `emp_type` (`emp_type`),
  ADD KEY `current_status` (`current_status`),
  ADD KEY `contactno` (`contact_no`),
  ADD KEY `height` (`height`),
  ADD KEY `weight` (`weight`),
  ADD KEY `spouse` (`spouse`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`billing_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `hospital_id` (`hp_id`),
  ADD KEY `billing_no` (`billing_no`),
  ADD KEY `billing_date` (`billed_on`),
  ADD KEY `total_bill` (`total_bill`),
  ADD KEY `personal_charges` (`personal_charge`),
  ADD KEY `billing_img` (`receipt_img`),
  ADD KEY `billed_by` (`billed_by`),
  ADD KEY `loa_no` (`loa_id`),
  ADD KEY `noa_no` (`noa_id`),
  ADD KEY `net_bill` (`net_bill`),
  ADD KEY `total_deductions` (`total_deduction`),
  ADD KEY `mbr_remaining_bal` (`mbr_remaining_bal`),
  ADD KEY `billing_on` (`billed_on`),
  ADD KEY `billed_on` (`billed_on`),
  ADD KEY `personal_charge` (`personal_charge`),
  ADD KEY `receipt_img` (`receipt_img`);

--
-- Indexes for table `billing_deductions`
--
ALTER TABLE `billing_deductions`
  ADD PRIMARY KEY (`deduction_id`),
  ADD KEY `deduction_name` (`deduction_name`),
  ADD KEY `deduction_cost` (`deduction_amount`),
  ADD KEY `billing_no` (`billing_no`),
  ADD KEY `date_created` (`added_on`),
  ADD KEY `added_on` (`added_on`);

--
-- Indexes for table `billing_services`
--
ALTER TABLE `billing_services`
  ADD PRIMARY KEY (`service_id`),
  ADD KEY `bsv_cost_types` (`service_name`),
  ADD KEY `bsv_ct_fee` (`service_fee`),
  ADD KEY `emp_id` (`billing_no`),
  ADD KEY `billing_no` (`billing_no`),
  ADD KEY `date_created` (`added_on`),
  ADD KEY `service_quantity` (`service_quantity`),
  ADD KEY `added_on` (`added_on`);

--
-- Indexes for table `company_doctors`
--
ALTER TABLE `company_doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD KEY `doctor_name` (`doctor_name`),
  ADD KEY `added_on` (`date_added`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `date_updated` (`date_updated`),
  ADD KEY `signature` (`doctor_signature`),
  ADD KEY `doctor_signature` (`doctor_signature`);

--
-- Indexes for table `cost_types`
--
ALTER TABLE `cost_types`
  ADD PRIMARY KEY (`ctype_id`),
  ADD KEY `cost_type` (`cost_type`),
  ADD KEY `added_on` (`date_added`),
  ADD KEY `date_updated` (`date_updated`),
  ADD KEY `date_added` (`date_added`);

--
-- Indexes for table `healthcare_providers`
--
ALTER TABLE `healthcare_providers`
  ADD PRIMARY KEY (`hp_id`),
  ADD KEY `hc_prov_type` (`hp_type`),
  ADD KEY `hc_prov_name` (`hp_name`),
  ADD KEY `hc_prov_addr` (`hp_address`),
  ADD KEY `hc_prov_no` (`hp_contact`),
  ADD KEY `date_added` (`date_added`),
  ADD KEY `date_updated` (`date_updated`),
  ADD KEY `hp_contact` (`hp_contact`),
  ADD KEY `hp_address` (`hp_address`),
  ADD KEY `hp_name` (`hp_name`),
  ADD KEY `hp_type` (`hp_type`);

--
-- Indexes for table `loa_requests`
--
ALTER TABLE `loa_requests`
  ADD PRIMARY KEY (`loa_id`),
  ADD KEY `user_id` (`emp_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `name_of_hospital` (`hcare_provider`),
  ADD KEY `loa_request_type` (`loa_request_type`),
  ADD KEY `health_card_number` (`health_card_no`),
  ADD KEY `insurance_company` (`requesting_company`),
  ADD KEY `availment_request_date` (`request_date`),
  ADD KEY `chief_complaint` (`chief_complaint`),
  ADD KEY `attending_physician` (`attending_physician`),
  ADD KEY `rx_request_doc` (`rx_file`),
  ADD KEY `status` (`status`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `health_card_no` (`health_card_no`),
  ADD KEY `approved_by_2` (`approved_by`),
  ADD KEY `disapproved_by` (`disapproved_by`),
  ADD KEY `hospital_id` (`hcare_provider`),
  ADD KEY `suffix` (`suffix`),
  ADD KEY `disapproved_on` (`disapproved_on`),
  ADD KEY `approved_on` (`approved_on`),
  ADD KEY `healthcard_no` (`health_card_no`),
  ADD KEY `company_physician` (`requesting_physician`),
  ADD KEY `rx_file` (`rx_file`),
  ADD KEY `disapprove_reason` (`disapprove_reason`),
  ADD KEY `med_services` (`med_services`),
  ADD KEY `loa_no` (`loa_no`),
  ADD KEY `hcare_provider` (`hcare_provider`),
  ADD KEY `submitted_by` (`requested_by`);

--
-- Indexes for table `max_benefit_limits`
--
ALTER TABLE `max_benefit_limits`
  ADD PRIMARY KEY (`mbl_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `max_benefit_limit` (`max_benefit_limit`),
  ADD KEY `remaining_balance` (`remaining_balance`),
  ADD KEY `remaining_balance_2` (`remaining_balance`),
  ADD KEY `max_benefit_limit_2` (`max_benefit_limit`),
  ADD KEY `used_mbl` (`used_mbl`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `primary_address` (`home_address`),
  ADD KEY `mobile_number` (`contact_no`),
  ADD KEY `email` (`email`),
  ADD KEY `acn_no` (`health_card_no`),
  ADD KEY `date_approved` (`date_approved`),
  ADD KEY `status` (`approval_status`),
  ADD KEY `gender` (`gender`),
  ADD KEY `birthday` (`date_of_birth`),
  ADD KEY `blood_type` (`blood_type`),
  ADD KEY `allergies` (`allergies`),
  ADD KEY `philhealth_no` (`philhealth_no`),
  ADD KEY `contact_person` (`contact_person`),
  ADD KEY `contact_person_addr` (`contact_person_addr`),
  ADD KEY `contact_person_no` (`contact_person_no`),
  ADD KEY `first_name_2` (`first_name`),
  ADD KEY `first_name_3` (`first_name`),
  ADD KEY `suffix` (`suffix`),
  ADD KEY `civil_status` (`civil_status`),
  ADD KEY `date_of_birth` (`date_of_birth`),
  ADD KEY `address` (`home_address`),
  ADD KEY `position` (`position`),
  ADD KEY `position_level` (`position_level`),
  ADD KEY `department` (`dept_name`),
  ADD KEY `homer_address` (`home_address`),
  ADD KEY `city_address` (`city_address`),
  ADD KEY `home_address` (`home_address`),
  ADD KEY `photo` (`photo`),
  ADD KEY `approval_status` (`approval_status`),
  ADD KEY `health_card_no` (`health_card_no`),
  ADD KEY `insurance_company` (`company`),
  ADD KEY `date_regular` (`date_regularized`),
  ADD KEY `date_regularized` (`date_regularized`),
  ADD KEY `dept_name` (`dept_name`),
  ADD KEY `business_unit` (`business_unit`),
  ADD KEY `emp_type` (`emp_type`),
  ADD KEY `current_status` (`current_status`),
  ADD KEY `contactno` (`contact_no`),
  ADD KEY `height` (`height`),
  ADD KEY `weight` (`weight`),
  ADD KEY `spouse` (`spouse`);

--
-- Indexes for table `noa_requests`
--
ALTER TABLE `noa_requests`
  ADD PRIMARY KEY (`noa_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `admitting_hospital` (`date_of_birth`),
  ADD KEY `health_card_no` (`health_card_no`),
  ADD KEY `admission_date_2` (`admission_date`),
  ADD KEY `status` (`status`),
  ADD KEY `approved_by_2` (`approved_by`),
  ADD KEY `approved_on` (`approved_on`),
  ADD KEY `disapproved_by` (`disapproved_by`),
  ADD KEY `disapproved_on` (`disapproved_on`),
  ADD KEY `insurance_company_2` (`requesting_company`),
  ADD KEY `request_date` (`request_date`),
  ADD KEY `suffix` (`suffix`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `middle_name` (`middle_name`),
  ADD KEY `chief_complaint` (`chief_complaint`(768)),
  ADD KEY `hospital_id` (`hospital_id`),
  ADD KEY `disapprove_reason` (`disapprove_reason`),
  ADD KEY `noa_no` (`noa_no`),
  ADD KEY `hcare_provider` (`hospital_id`),
  ADD KEY `hospital_id_2` (`hospital_id`),
  ADD KEY `requested_by` (`requested_by`),
  ADD KEY `work_related` (`work_related`);

--
-- Indexes for table `personal_charges`
--
ALTER TABLE `personal_charges`
  ADD PRIMARY KEY (`pcharge_id`),
  ADD KEY `emp_id` (`emp_id`),
  ADD KEY `pcharge_amount` (`amount`),
  ADD KEY `status` (`status`),
  ADD KEY `loa_id` (`loa_id`),
  ADD KEY `noa_id` (`noa_id`),
  ADD KEY `billing_no` (`billing_no`),
  ADD KEY `paid_on` (`paid_on`),
  ADD KEY `date_created` (`added_on`),
  ADD KEY `added_on` (`added_on`);

--
-- Indexes for table `temp`
--
ALTER TABLE `temp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `post_code` (`post_code`),
  ADD KEY `job_title` (`job_title`),
  ADD KEY `city` (`city`);

--
-- Indexes for table `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `first_name` (`full_name`),
  ADD KEY `user_role` (`user_role`),
  ADD KEY `user_name` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `created` (`created_on`),
  ADD KEY `updated` (`updated_on`),
  ADD KEY `created_on` (`created_on`),
  ADD KEY `updated_on` (`updated_on`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `username` (`username`),
  ADD KEY `member_id` (`emp_id`),
  ADD KEY `status` (`status`),
  ADD KEY `designated_hospital` (`dsg_hcare_prov`),
  ADD KEY `user_role_2` (`user_role`),
  ADD KEY `dsg_hcare_prov` (`dsg_hcare_prov`),
  ADD KEY `full_name` (`full_name`),
  ADD KEY `photo` (`photo`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `online` (`online`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applicants`
--
ALTER TABLE `applicants`
  MODIFY `app_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `applicants_backup`
--
ALTER TABLE `applicants_backup`
  MODIFY `app_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `applicants_temp`
--
ALTER TABLE `applicants_temp`
  MODIFY `app_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `billing_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billing_deductions`
--
ALTER TABLE `billing_deductions`
  MODIFY `deduction_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billing_services`
--
ALTER TABLE `billing_services`
  MODIFY `service_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `company_doctors`
--
ALTER TABLE `company_doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cost_types`
--
ALTER TABLE `cost_types`
  MODIFY `ctype_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `healthcare_providers`
--
ALTER TABLE `healthcare_providers`
  MODIFY `hp_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `loa_requests`
--
ALTER TABLE `loa_requests`
  MODIFY `loa_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `max_benefit_limits`
--
ALTER TABLE `max_benefit_limits`
  MODIFY `mbl_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `member_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `noa_requests`
--
ALTER TABLE `noa_requests`
  MODIFY `noa_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `personal_charges`
--
ALTER TABLE `personal_charges`
  MODIFY `pcharge_id` int(50) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp`
--
ALTER TABLE `temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
