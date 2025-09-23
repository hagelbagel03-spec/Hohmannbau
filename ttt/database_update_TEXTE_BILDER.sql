-- Update für bearbeitbare Texte und Bilder
-- Hohmann Bau Admin Panel Erweiterung

USE `hohmann_ewsdfa`;

-- Homepage Tabelle um weitere Text-Felder erweitern
ALTER TABLE `homepage` 
ADD COLUMN `nav_home_text` VARCHAR(100) DEFAULT 'Home' AFTER `color_theme`,
ADD COLUMN `nav_about_text` VARCHAR(100) DEFAULT 'Über uns' AFTER `nav_home_text`,
ADD COLUMN `nav_services_text` VARCHAR(100) DEFAULT 'Leistungen' AFTER `nav_about_text`,
ADD COLUMN `nav_team_text` VARCHAR(100) DEFAULT 'Team' AFTER `nav_services_text`,
ADD COLUMN `nav_careers_text` VARCHAR(100) DEFAULT 'Karriere' AFTER `nav_team_text`,
ADD COLUMN `nav_news_text` VARCHAR(100) DEFAULT 'Aktuelles' AFTER `nav_careers_text`,
ADD COLUMN `nav_contact_text` VARCHAR(100) DEFAULT 'Kontakt' AFTER `nav_news_text`,

ADD COLUMN `services_section_title` VARCHAR(255) DEFAULT 'Unsere Leistungen' AFTER `nav_contact_text`,
ADD COLUMN `services_section_description` TEXT DEFAULT 'Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten' AFTER `services_section_title`,

ADD COLUMN `team_section_title` VARCHAR(255) DEFAULT 'Unser Team' AFTER `services_section_description`,
ADD COLUMN `team_section_description` TEXT DEFAULT 'Erfahrene Experten mit Leidenschaft für Garten und Landschaft' AFTER `team_section_title`,

ADD COLUMN `news_section_title` VARCHAR(255) DEFAULT 'Aktuelles' AFTER `team_section_description`,
ADD COLUMN `news_section_description` TEXT DEFAULT 'Neuigkeiten und Informationen rund um Garten und Landschaftsbau' AFTER `news_section_title`,

ADD COLUMN `cta_section_title` VARCHAR(255) DEFAULT 'Bereit für Ihren Traumgarten?' AFTER `news_section_description`,
ADD COLUMN `cta_section_description` TEXT DEFAULT 'Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.' AFTER `cta_section_title`,
ADD COLUMN `cta_button_text` VARCHAR(100) DEFAULT 'Beratungstermin vereinbaren' AFTER `cta_section_description`,
ADD COLUMN `cta_phone_button_text` VARCHAR(100) DEFAULT 'Jetzt anrufen' AFTER `cta_button_text`,

ADD COLUMN `hero_background_image` VARCHAR(255) DEFAULT NULL AFTER `cta_phone_button_text`,
ADD COLUMN `about_image` VARCHAR(255) DEFAULT NULL AFTER `hero_background_image`,
ADD COLUMN `gallery_image_1` VARCHAR(255) DEFAULT NULL AFTER `about_image`,
ADD COLUMN `gallery_image_2` VARCHAR(255) DEFAULT NULL AFTER `gallery_image_1`,
ADD COLUMN `gallery_image_3` VARCHAR(255) DEFAULT NULL AFTER `gallery_image_2`,
ADD COLUMN `gallery_image_4` VARCHAR(255) DEFAULT NULL AFTER `gallery_image_3`,

ADD COLUMN `company_name` VARCHAR(255) DEFAULT 'Hohmann Bau' AFTER `gallery_image_4`,
ADD COLUMN `company_tagline` VARCHAR(255) DEFAULT 'Garten & Landschaftsbau' AFTER `company_name`,
ADD COLUMN `trust_indicator_1_value` VARCHAR(50) DEFAULT '25+' AFTER `company_tagline`,
ADD COLUMN `trust_indicator_1_label` VARCHAR(100) DEFAULT 'Jahre Erfahrung' AFTER `trust_indicator_1_value`,
ADD COLUMN `trust_indicator_2_value` VARCHAR(50) DEFAULT '150+' AFTER `trust_indicator_1_label`,
ADD COLUMN `trust_indicator_2_label` VARCHAR(100) DEFAULT 'Projekte/Jahr' AFTER `trust_indicator_2_value`,
ADD COLUMN `trust_indicator_3_value` VARCHAR(50) DEFAULT '98%' AFTER `trust_indicator_2_label`,
ADD COLUMN `trust_indicator_3_label` VARCHAR(100) DEFAULT 'Zufriedenheit' AFTER `trust_indicator_3_value`;

-- Services Tabelle um Bildfeld erweitern
ALTER TABLE `services` 
ADD COLUMN `background_image` VARCHAR(255) DEFAULT NULL AFTER `image`;

-- Team Tabelle um zusätzliche Felder erweitern  
ALTER TABLE `team`
ADD COLUMN `background_image` VARCHAR(255) DEFAULT NULL AFTER `image`,
ADD COLUMN `social_facebook` VARCHAR(255) DEFAULT NULL AFTER `background_image`,
ADD COLUMN `social_linkedin` VARCHAR(255) DEFAULT NULL AFTER `social_facebook`,
ADD COLUMN `social_instagram` VARCHAR(255) DEFAULT NULL AFTER `social_linkedin`;

-- Neue Tabelle für Galerie-Bilder
CREATE TABLE `gallery` (
  `id` varchar(36) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `order` int NOT NULL DEFAULT 0,
  `active` boolean NOT NULL DEFAULT true,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standard Galerie-Bilder einfügen
INSERT INTO `gallery` (`id`, `title`, `description`, `image_path`, `category`, `order`) VALUES
('gal-1', 'Moderne Gartengestaltung', 'Zeitgemäße Gartenarchitektur mit natürlichen Elementen', '/uploads/gallery/garden-modern-1.jpg', 'gartengestaltung', 1),
('gal-2', 'Landschaftsbau Projekt', 'Professioneller Landschaftsbau für Wohnanlage', '/uploads/gallery/landscape-1.jpg', 'landschaftsbau', 2),
('gal-3', 'Pflanzarbeiten', 'Fachgerechte Bepflanzung mit heimischen Gewächsen', '/uploads/gallery/planting-1.jpg', 'pflanzarbeiten', 3),
('gal-4', 'Gartenpflege Service', 'Regelmäßige professionelle Gartenpflege', '/uploads/gallery/maintenance-1.jpg', 'gartenpflege', 4);

-- Uploads-Verzeichnis Struktur
-- /uploads/
-- /uploads/gallery/
-- /uploads/team/
-- /uploads/services/
-- /uploads/hero/

-- Update Homepage mit erweiterten Standardwerten
UPDATE `homepage` SET 
    `services_section_title` = 'Unsere Leistungen',
    `services_section_description` = 'Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten',
    `team_section_title` = 'Unser Expertenteam',
    `team_section_description` = 'Erfahrene Fachkräfte mit Leidenschaft für Garten und Landschaft',
    `news_section_title` = 'Aktuelles',
    `news_section_description` = 'Neuigkeiten und Informationen rund um Garten und Landschaftsbau',
    `cta_section_title` = 'Bereit für Ihren Traumgarten?',
    `cta_section_description` = 'Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.',
    `company_name` = 'Hohmann Bau',
    `company_tagline` = 'Garten & Landschaftsbau'
WHERE `id` = '1';

-- Neue Tabelle für Upload-Management
CREATE TABLE `uploads` (
  `id` varchar(36) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT 'general',
  `alt_text` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;