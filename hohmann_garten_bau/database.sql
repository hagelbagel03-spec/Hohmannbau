-- Stadtwache MySQL Database Schema
-- Created: 2025

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `stadtwache` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `stadtwache`;

-- Admin users table
CREATE TABLE `admins` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `username` varchar(100) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Applications table
CREATE TABLE `applications` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `position` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `cv_filename` varchar(255) DEFAULT NULL,
  `status` enum('pending','reviewed','accepted','rejected') NOT NULL DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Feedback table
CREATE TABLE `feedback` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `rating` int NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `status` enum('new','reviewed') NOT NULL DEFAULT 'new',
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- News table
CREATE TABLE `news` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` enum('normal','high','urgent') NOT NULL DEFAULT 'normal',
  `published` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reports table
CREATE TABLE `reports` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `incident_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time NOT NULL,
  `reporter_name` varchar(255) NOT NULL,
  `reporter_email` varchar(255) NOT NULL,
  `reporter_phone` varchar(50) NOT NULL,
  `is_witness` boolean NOT NULL DEFAULT false,
  `witnesses_present` boolean NOT NULL DEFAULT false,
  `witness_details` text DEFAULT NULL,
  `evidence_available` boolean NOT NULL DEFAULT false,
  `evidence_description` text DEFAULT NULL,
  `additional_info` text DEFAULT NULL,
  `status` enum('new','under_review','completed','closed') NOT NULL DEFAULT 'new',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `assigned_officer` varchar(255) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat messages table
CREATE TABLE `chat_messages` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `visitor_name` varchar(255) NOT NULL,
  `visitor_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `admin_response` text DEFAULT NULL,
  `status` enum('new','responded','closed') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `responded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat buttons table
CREATE TABLE `chat_buttons` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `label` varchar(255) NOT NULL,
  `action` enum('email','phone','link','message') NOT NULL,
  `value` varchar(500) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Chat widget settings table
CREATE TABLE `chat_widget` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `enabled` boolean NOT NULL DEFAULT true,
  `title` varchar(255) NOT NULL DEFAULT 'Hilfe & Support',
  `welcome_message` text NOT NULL DEFAULT 'Hallo! Wie können wir Ihnen helfen?',
  `offline_message` text NOT NULL DEFAULT 'Wir sind derzeit nicht verfügbar. Hinterlassen Sie uns eine Nachricht.',
  `position` enum('bottom-left','bottom-right') NOT NULL DEFAULT 'bottom-left',
  `color` enum('blue','green','gray') NOT NULL DEFAULT 'blue',
  `contact_email` varchar(255) NOT NULL DEFAULT 'support@stadtwache.de',
  `phone_number` varchar(50) DEFAULT NULL,
  `operating_hours` varchar(255) NOT NULL DEFAULT 'Mo-Fr: 8:00-18:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Services table
CREATE TABLE `services` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'Shield',
  `image` varchar(255) DEFAULT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Team members table
CREATE TABLE `team` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Statistics table
CREATE TABLE `statistics` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `title` varchar(255) NOT NULL,
  `value` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'TrendingUp',
  `color` varchar(20) NOT NULL DEFAULT 'blue',
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Navigation table
CREATE TABLE `navigation` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `label` varchar(255) NOT NULL,
  `section` varchar(100) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Homepage content table
CREATE TABLE `homepage` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `hero_title` varchar(255) NOT NULL DEFAULT 'Stadtwache',
  `hero_subtitle` text NOT NULL DEFAULT 'Sicherheit und Schutz für unsere Gemeinschaft. Moderne Polizeiarbeit im Dienste der Bürger.',
  `hero_image` varchar(255) DEFAULT NULL,
  `emergency_number` varchar(20) NOT NULL DEFAULT '110',
  `phone_number` varchar(50) NOT NULL DEFAULT '+49 123 456-789',
  `email` varchar(255) NOT NULL DEFAULT 'info@stadtwache.de',
  `address` text NOT NULL DEFAULT 'Stadtwache Hauptrevier\nHauptstraße 123\n12345 Musterstadt',
  `opening_hours` text NOT NULL DEFAULT 'Mo-Fr: 8:00-20:00\nSa: 9:00-16:00\nSo: 10:00-14:00',
  `show_latest_news` boolean NOT NULL DEFAULT true,
  `show_services` boolean NOT NULL DEFAULT true,
  `show_team` boolean NOT NULL DEFAULT true,
  `show_statistics` boolean NOT NULL DEFAULT true,
  `footer_text` varchar(255) NOT NULL DEFAULT '© 2024 Stadtwache. Alle Rechte vorbehalten.',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- About page table
CREATE TABLE `about` (
  `id` varchar(36) NOT NULL DEFAULT (UUID()),
  `title` varchar(255) NOT NULL DEFAULT 'Über uns',
  `subtitle` varchar(255) NOT NULL DEFAULT 'Erfahren Sie mehr über die Stadtwache',
  `content` text NOT NULL DEFAULT 'Hier steht der Inhalt über das Unternehmen...',
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `values` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (username: admin, password: admin123)
INSERT INTO `admins` (`id`, `username`, `email`, `hashed_password`) VALUES 
('admin-1', 'admin', 'admin@stadtwache.de', '$2y$10$lPV7O/keOXYvm4BMAsgUQOLtFurEB7b2R7GoE2/myMWxtjtEiAVZ.');

-- Insert default homepage content
INSERT INTO `homepage` (`id`) VALUES ('1');

-- Insert default about content
INSERT INTO `about` (`id`) VALUES ('1');

-- Insert default chat widget
INSERT INTO `chat_widget` (`id`) VALUES ('1');

-- Insert default services
INSERT INTO `services` (`title`, `description`, `icon`, `order`) VALUES
('Streifendienst', 'Regelmäßige Patrouillen für die Sicherheit unserer Bürger', 'Shield', 1),
('Ermittlungen', 'Professionelle Ermittlungsarbeit bei Straftaten', 'Search', 2),
('Bürgerdienst', 'Beratung und Unterstützung für alle Bürgeranliegen', 'Users', 3),
('Verkehrssicherheit', 'Überwachung und Kontrolle des Straßenverkehrs', 'Car', 4);

-- Insert default team members
INSERT INTO `team` (`name`, `position`, `description`, `order`) VALUES
('Kommissar Schmidt', 'Leiter der Stadtwache', 'Über 20 Jahre Erfahrung im Polizeidienst', 1),
('Hauptmeister Müller', 'Stellvertretender Leiter', 'Spezialist für Ermittlungsverfahren', 2),
('Meisterin Weber', 'Leiterin Bürgerdienst', 'Expertin für Bürgerberatung und Prävention', 3);

-- Insert default statistics
INSERT INTO `statistics` (`title`, `value`, `description`, `icon`, `color`, `order`) VALUES
('Aufgelöste Fälle', '95%', 'Erfolgsquote bei Ermittlungen', 'TrendingUp', 'green', 1),
('Einsätze pro Monat', '250+', 'Durchschnittliche monatliche Einsätze', 'Activity', 'blue', 2),
('Bürgerzufriedenheit', '4.8/5', 'Bewertung unserer Arbeit', 'Star', 'yellow', 3),
('Reaktionszeit', '< 10 Min', 'Durchschnittliche Ankunftszeit', 'Clock', 'red', 4);

-- Insert default navigation
INSERT INTO `navigation` (`label`, `section`, `order`) VALUES
('Startseite', 'home', 1),
('Über uns', 'about', 2),
('Dienste', 'services', 3),
('Team', 'team', 4),
('Feedback', 'feedback', 5),
('Vorfall melden', 'report', 6),
('Karriere', 'careers', 7),
('Kontakt', 'contact', 8);

-- Insert default chat buttons
INSERT INTO `chat_buttons` (`label`, `action`, `value`, `order`) VALUES
('E-Mail senden', 'email', 'support@stadtwache.de', 1),
('Anrufen', 'phone', '+49 123 456-789', 2),
('Notfall melden', 'message', 'Ich möchte einen Notfall melden', 3);

-- Create jobs table
CREATE TABLE `jobs` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `type` enum('full-time','part-time','contract','intern') NOT NULL DEFAULT 'full-time',
  `location` varchar(255) NOT NULL DEFAULT 'Musterstadt',
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `active` boolean NOT NULL DEFAULT true,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default jobs
INSERT INTO `jobs` (`id`, `title`, `department`, `type`, `description`, `requirements`) VALUES
('job-1', 'Polizeibeamter/in (m/w/d)', 'Streifendienst', 'full-time', 'Wir suchen motivierte Polizeibeamte für den Streifendienst und die Bürgernähe.', 'Abgeschlossene Polizeiausbildung und Teamfähigkeit erwünscht.'),
('job-2', 'Ermittler/in (m/w/d)', 'Ermittlungsabteilung', 'full-time', 'Für unsere Ermittlungsabteilung suchen wir erfahrene Kriminalbeamte.', 'Spezialisierung in der Tatort- und Spurensicherung.'),
('job-3', 'Sachbearbeiter/in Bürgerdienst (m/w/d)', 'Bürgerdienst', 'part-time', 'Unterstützung bei administrativen Aufgaben und Bürgerkontakt.', 'Verwaltungsausbildung oder vergleichbare Qualifikation erwünscht.');

COMMIT;