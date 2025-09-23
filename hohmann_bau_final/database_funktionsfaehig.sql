-- Hohmann Bau - Saubere SQL-Datei für Import
-- Kompatibel mit allen MySQL/MariaDB Versionen

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Datenbank erstellen (falls nicht vorhanden)
CREATE DATABASE IF NOT EXISTS `hohmann_ewsdfa` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hohmann_ewsdfa`;

-- Tabellen löschen falls vorhanden (für sauberen Import)
DROP TABLE IF EXISTS `chat_buttons`;
DROP TABLE IF EXISTS `chat_messages`;
DROP TABLE IF EXISTS `chat_widget`;
DROP TABLE IF EXISTS `applications`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `feedback`;
DROP TABLE IF EXISTS `reports`;
DROP TABLE IF EXISTS `news`;
DROP TABLE IF EXISTS `statistics`;
DROP TABLE IF EXISTS `navigation`;
DROP TABLE IF EXISTS `team`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `about`;
DROP TABLE IF EXISTS `homepage`;
DROP TABLE IF EXISTS `admins`;

-- 1. Admins Tabelle
CREATE TABLE `admins` (
  `id` varchar(36) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Homepage Tabelle
CREATE TABLE `homepage` (
  `id` varchar(36) NOT NULL,
  `hero_title` varchar(255) NOT NULL DEFAULT 'Hohmann Bau',
  `hero_subtitle` text NOT NULL DEFAULT 'Ihr Experte für Garten- und Landschaftsbau. Professionelle Gartengestaltung seit über 20 Jahren.',
  `hero_image` varchar(255) DEFAULT NULL,
  `emergency_number` varchar(20) NOT NULL DEFAULT '+49 123 456-999',
  `phone_number` varchar(50) NOT NULL DEFAULT '+49 123 456-789',
  `email` varchar(255) NOT NULL DEFAULT 'info@hohmann-bau.de',
  `address` text NOT NULL DEFAULT 'Hohmann Garten & Landschaftsbau\nMeisterstraße 45\n12345 Gartenstadt',
  `opening_hours` text NOT NULL DEFAULT 'Mo-Fr: 7:00-17:00\nSa: 8:00-14:00\nSo: Notdienst verfügbar',
  `color_theme` varchar(20) DEFAULT 'green',
  `show_latest_news` boolean NOT NULL DEFAULT true,
  `show_services` boolean NOT NULL DEFAULT true,
  `show_team` boolean NOT NULL DEFAULT true,
  `show_statistics` boolean NOT NULL DEFAULT true,
  `footer_text` varchar(255) NOT NULL DEFAULT '© 2024 Hohmann Bau. Alle Rechte vorbehalten.',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Services Tabelle
CREATE TABLE `services` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'Leaf',
  `image` varchar(255) DEFAULT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Team Tabelle
CREATE TABLE `team` (
  `id` varchar(36) NOT NULL,
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

-- 5. Statistics Tabelle
CREATE TABLE `statistics` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'TrendingUp',
  `color` varchar(20) NOT NULL DEFAULT 'green',
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. News Tabelle
CREATE TABLE `news` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `priority` enum('normal','high','urgent') NOT NULL DEFAULT 'normal',
  `published` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Reports Tabelle (Schadensmeldungen)
CREATE TABLE `reports` (
  `id` varchar(36) NOT NULL,
  `incident_type` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `incident_date` date NOT NULL,
  `incident_time` time DEFAULT NULL,
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

-- 8. Applications Tabelle
CREATE TABLE `applications` (
  `id` varchar(36) NOT NULL,
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

-- 9. Jobs Tabelle
CREATE TABLE `jobs` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `type` enum('full-time','part-time','contract','intern') NOT NULL DEFAULT 'full-time',
  `location` varchar(255) NOT NULL DEFAULT 'Gartenstadt',
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `salary_range` varchar(100) DEFAULT NULL,
  `active` boolean NOT NULL DEFAULT true,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Feedback Tabelle
CREATE TABLE `feedback` (
  `id` varchar(36) NOT NULL,
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

-- 11. Chat Messages Tabelle
CREATE TABLE `chat_messages` (
  `id` varchar(36) NOT NULL,
  `visitor_name` varchar(255) NOT NULL,
  `visitor_email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `admin_response` text DEFAULT NULL,
  `status` enum('new','responded','closed') NOT NULL DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `responded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Chat Buttons Tabelle
CREATE TABLE `chat_buttons` (
  `id` varchar(36) NOT NULL,
  `label` varchar(255) NOT NULL,
  `action` enum('email','phone','link','message') NOT NULL,
  `value` varchar(500) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Chat Widget Tabelle
CREATE TABLE `chat_widget` (
  `id` varchar(36) NOT NULL,
  `enabled` boolean NOT NULL DEFAULT true,
  `title` varchar(255) NOT NULL DEFAULT 'Garten-Beratung',
  `welcome_message` text NOT NULL DEFAULT 'Hallo! Wie können wir Ihnen bei Ihrem Gartenprojekt helfen?',
  `offline_message` text NOT NULL DEFAULT 'Wir sind derzeit nicht verfügbar. Hinterlassen Sie uns eine Nachricht.',
  `position` enum('bottom-left','bottom-right') NOT NULL DEFAULT 'bottom-left',
  `color` enum('blue','green','gray') NOT NULL DEFAULT 'green',
  `contact_email` varchar(255) NOT NULL DEFAULT 'support@hohmann-bau.de',
  `phone_number` varchar(50) DEFAULT NULL,
  `operating_hours` varchar(255) NOT NULL DEFAULT 'Mo-Fr: 7:00-17:00',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. About Tabelle
CREATE TABLE `about` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT 'Über Hohmann Bau',
  `subtitle` varchar(255) NOT NULL DEFAULT 'Ihr vertrauensvoller Partner für Garten- und Landschaftsbau',
  `content` text NOT NULL DEFAULT 'Hohmann Bau ist ein traditionsreiches Familienunternehmen.',
  `mission` text DEFAULT NULL,
  `vision` text DEFAULT NULL,
  `values` text DEFAULT NULL,
  `history` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Navigation Tabelle
CREATE TABLE `navigation` (
  `id` varchar(36) NOT NULL,
  `label` varchar(255) NOT NULL,
  `section` varchar(100) NOT NULL,
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standard-Daten einfügen
INSERT INTO `admins` (`id`, `username`, `email`, `hashed_password`) VALUES 
('admin-1', 'admin', 'admin@hohmann-bau.de', '$2y$10$lPV7O/keOXYvm4BMAsgUQOLtFurEB7b2R7GoE2/myMWxtjtEiAVZ.');

INSERT INTO `homepage` (`id`, `hero_title`, `hero_subtitle`, `emergency_number`, `phone_number`, `email`, `address`, `opening_hours`, `color_theme`) VALUES 
('1', 'Hohmann Bau', 'Ihr Experte für Garten- und Landschaftsbau. Professionelle Gartengestaltung seit über 20 Jahren.', '+49 123 456-999', '+49 123 456-789', 'info@hohmann-bau.de', 'Hohmann Garten & Landschaftsbau\nMeisterstraße 45\n12345 Gartenstadt', 'Mo-Fr: 7:00-17:00\nSa: 8:00-14:00\nSo: Notdienst verfügbar', 'green');

INSERT INTO `about` (`id`) VALUES ('1');
INSERT INTO `chat_widget` (`id`) VALUES ('1');

INSERT INTO `services` (`id`, `title`, `description`, `icon`, `order`) VALUES
('serv-1', 'Gartenplanung', 'Professionelle Planung und Design für Ihren Traumgarten', 'Leaf', 1),
('serv-2', 'Landschaftsbau', 'Umfassende Gestaltung von Außenanlagen und Landschaften', 'Mountain', 2),
('serv-3', 'Pflanzarbeiten', 'Fachgerechte Bepflanzung mit hochwertigen Pflanzen', 'Seedling', 3),
('serv-4', 'Gartenpflege', 'Regelmäßige Pflege und Instandhaltung Ihrer Gartenanlage', 'Scissors', 4);

INSERT INTO `team` (`id`, `name`, `position`, `description`, `order`) VALUES
('team-1', 'Meister Klaus Hohmann', 'Geschäftsführer & Gartenbaumeister', 'Über 25 Jahre Erfahrung im Garten- und Landschaftsbau', 1),
('team-2', 'Sarah Müller', 'Gartendesignerin', 'Spezialistin für moderne Gartenplanung und -gestaltung', 2),
('team-3', 'Thomas Weber', 'Bauleiter Landschaftsbau', 'Experte für komplexe Landschaftsbauprojekte', 3);

INSERT INTO `statistics` (`id`, `title`, `value`, `description`, `icon`, `color`, `order`) VALUES
('stat-1', 'Zufriedene Kunden', '98%', 'Kundenzufriedenheitsrate bei Gartenprojekten', 'TrendingUp', 'green', 1),
('stat-2', 'Gartenprojekte pro Jahr', '150+', 'Erfolgreich abgeschlossene Gartenprojekte', 'Activity', 'blue', 2),
('stat-3', 'Jahre Erfahrung', '25+', 'Expertise im Garten- und Landschaftsbau', 'Star', 'yellow', 3),
('stat-4', 'Garantie-Jahre', '5 Jahre', 'Gewährleistung auf alle Gartenarbeiten', 'Clock', 'red', 4);

INSERT INTO `news` (`id`, `title`, `content`, `excerpt`, `priority`) VALUES
('news-1', 'Neue Gartensaison 2024', 'Die neue Gartensaison beginnt! Jetzt ist die perfekte Zeit für Ihre Gartenplanung. Unser Team berät Sie gerne kostenlos zu allen Fragen rund um Ihren Traumgarten.', 'Frühjahrsrabatt auf alle Planungsleistungen', 'high'),
('news-2', 'Auszeichnung für Hohmann Bau', 'Wir freuen uns sehr über die Auszeichnung als "Gartenbaubetrieb des Jahres 2024" durch die Handelskammer. Diese Auszeichnung bestätigt unsere hohen Qualitätsstandards.', 'Qualität wird belohnt', 'normal'),
('news-3', 'Erweiterte Öffnungszeiten', 'Ab sofort sind wir samstags bis 14:00 Uhr für Sie da. Mehr Service für unsere geschätzten Kunden - auch am Wochenende.', 'Mehr Service am Wochenende', 'normal');

INSERT INTO `jobs` (`id`, `title`, `department`, `type`, `description`, `requirements`) VALUES
('job-1', 'Gärtner/in (m/w/d)', 'Gartengestaltung', 'full-time', 'Wir suchen motivierte Gärtner für die Umsetzung kreativer Gartenprojekte.', 'Abgeschlossene Gärtnerausbildung und Leidenschaft für Pflanzen erwünscht.'),
('job-2', 'Landschaftsgärtner/in (m/w/d)', 'Landschaftsbau', 'full-time', 'Für komplexe Landschaftsbauprojekte suchen wir erfahrene Fachkräfte.', 'Mehrjährige Erfahrung im Landschaftsbau und Maschinenführerschein.'),
('job-3', 'Gartendesigner/in (m/w/d)', 'Planung', 'part-time', 'Unterstützung bei der Gartenplanung und Kundenberatung.', 'Studium Landschaftsarchitektur oder vergleichbare Qualifikation erwünscht.');

INSERT INTO `navigation` (`id`, `label`, `section`, `order`) VALUES
('nav-1', 'Startseite', 'home', 1),
('nav-2', 'Leistungen', 'services', 2),
('nav-3', 'Aktuelles', 'news', 3),
('nav-4', 'Kostenvoranschlag', 'contact', 4),
('nav-5', 'Karriere', 'careers', 5);

INSERT INTO `chat_buttons` (`id`, `label`, `action`, `value`, `order`) VALUES
('chat-1', 'Kostenvoranschlag', 'email', 'info@hohmann-bau.de', 1),
('chat-2', 'Beratung anrufen', 'phone', '+49 123 456-789', 2),
('chat-3', 'Garten-Notdienst', 'phone', '+49 123 456-999', 3);

COMMIT;