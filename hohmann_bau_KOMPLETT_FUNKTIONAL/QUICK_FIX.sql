-- Quick Fix für fehlende Datenbank-Felder
-- Führen Sie diese Befehle aus, um die Warnungen zu beheben

USE hohmann_ewsdfa;

-- Neue Spalten zur homepage Tabelle hinzufügen (falls sie nicht existieren)
ALTER TABLE homepage 
ADD COLUMN IF NOT EXISTS company_name VARCHAR(255) DEFAULT 'Hohmann Bau',
ADD COLUMN IF NOT EXISTS company_tagline VARCHAR(255) DEFAULT 'Garten & Landschaftsbau',
ADD COLUMN IF NOT EXISTS nav_home_text VARCHAR(100) DEFAULT 'Home',
ADD COLUMN IF NOT EXISTS nav_about_text VARCHAR(100) DEFAULT 'Über uns',
ADD COLUMN IF NOT EXISTS nav_services_text VARCHAR(100) DEFAULT 'Leistungen',
ADD COLUMN IF NOT EXISTS nav_team_text VARCHAR(100) DEFAULT 'Team',
ADD COLUMN IF NOT EXISTS nav_careers_text VARCHAR(100) DEFAULT 'Karriere',
ADD COLUMN IF NOT EXISTS nav_news_text VARCHAR(100) DEFAULT 'Aktuelles',
ADD COLUMN IF NOT EXISTS nav_contact_text VARCHAR(100) DEFAULT 'Kontakt',
ADD COLUMN IF NOT EXISTS services_section_title VARCHAR(255) DEFAULT 'Unsere Leistungen',
ADD COLUMN IF NOT EXISTS services_section_description TEXT DEFAULT 'Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten',
ADD COLUMN IF NOT EXISTS team_section_title VARCHAR(255) DEFAULT 'Unser Expertenteam',
ADD COLUMN IF NOT EXISTS team_section_description TEXT DEFAULT 'Erfahrene Fachkräfte mit Leidenschaft für Garten und Landschaft',
ADD COLUMN IF NOT EXISTS news_section_title VARCHAR(255) DEFAULT 'Aktuelles',
ADD COLUMN IF NOT EXISTS news_section_description TEXT DEFAULT 'Neuigkeiten und Informationen rund um Garten und Landschaftsbau',
ADD COLUMN IF NOT EXISTS cta_section_title VARCHAR(255) DEFAULT 'Bereit für Ihren Traumgarten?',
ADD COLUMN IF NOT EXISTS cta_section_description TEXT DEFAULT 'Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.',
ADD COLUMN IF NOT EXISTS cta_button_text VARCHAR(100) DEFAULT 'Beratungstermin vereinbaren',
ADD COLUMN IF NOT EXISTS cta_phone_button_text VARCHAR(100) DEFAULT 'Jetzt anrufen',
ADD COLUMN IF NOT EXISTS trust_indicator_1_value VARCHAR(50) DEFAULT '25+',
ADD COLUMN IF NOT EXISTS trust_indicator_1_label VARCHAR(100) DEFAULT 'Jahre Erfahrung',
ADD COLUMN IF NOT EXISTS trust_indicator_2_value VARCHAR(50) DEFAULT '150+',
ADD COLUMN IF NOT EXISTS trust_indicator_2_label VARCHAR(100) DEFAULT 'Projekte/Jahr',
ADD COLUMN IF NOT EXISTS trust_indicator_3_value VARCHAR(50) DEFAULT '98%',
ADD COLUMN IF NOT EXISTS trust_indicator_3_label VARCHAR(100) DEFAULT 'Zufriedenheit',
ADD COLUMN IF NOT EXISTS hero_background_image VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS gallery_image_1 VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS gallery_image_2 VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS gallery_image_3 VARCHAR(255) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS gallery_image_4 VARCHAR(255) DEFAULT NULL;

-- Daten für existierenden Datensatz aktualisieren
UPDATE homepage SET 
    company_name = COALESCE(company_name, 'Hohmann Bau'),
    company_tagline = COALESCE(company_tagline, 'Garten & Landschaftsbau'),
    nav_home_text = COALESCE(nav_home_text, 'Home'),
    nav_about_text = COALESCE(nav_about_text, 'Über uns'),
    nav_services_text = COALESCE(nav_services_text, 'Leistungen'),
    nav_team_text = COALESCE(nav_team_text, 'Team'),
    nav_careers_text = COALESCE(nav_careers_text, 'Karriere'),
    nav_news_text = COALESCE(nav_news_text, 'Aktuelles'),
    nav_contact_text = COALESCE(nav_contact_text, 'Kontakt'),
    services_section_title = COALESCE(services_section_title, 'Unsere Leistungen'),
    services_section_description = COALESCE(services_section_description, 'Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten'),
    team_section_title = COALESCE(team_section_title, 'Unser Expertenteam'),
    team_section_description = COALESCE(team_section_description, 'Erfahrene Fachkräfte mit Leidenschaft für Garten und Landschaft'),
    news_section_title = COALESCE(news_section_title, 'Aktuelles'),
    news_section_description = COALESCE(news_section_description, 'Neuigkeiten und Informationen rund um Garten und Landschaftsbau'),
    cta_section_title = COALESCE(cta_section_title, 'Bereit für Ihren Traumgarten?'),
    cta_section_description = COALESCE(cta_section_description, 'Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.'),
    cta_button_text = COALESCE(cta_button_text, 'Beratungstermin vereinbaren'),
    cta_phone_button_text = COALESCE(cta_phone_button_text, 'Jetzt anrufen'),
    trust_indicator_1_value = COALESCE(trust_indicator_1_value, '25+'),
    trust_indicator_1_label = COALESCE(trust_indicator_1_label, 'Jahre Erfahrung'),
    trust_indicator_2_value = COALESCE(trust_indicator_2_value, '150+'),
    trust_indicator_2_label = COALESCE(trust_indicator_2_label, 'Projekte/Jahr'),
    trust_indicator_3_value = COALESCE(trust_indicator_3_value, '98%'),
    trust_indicator_3_label = COALESCE(trust_indicator_3_label, 'Zufriedenheit')
WHERE id = '1';

-- Falls kein Datensatz existiert, einen erstellen
INSERT IGNORE INTO homepage (
    id, hero_title, hero_subtitle, phone_number, email,
    company_name, company_tagline, nav_home_text, nav_about_text, nav_services_text,
    nav_team_text, nav_careers_text, nav_news_text, nav_contact_text,
    services_section_title, services_section_description,
    team_section_title, team_section_description,
    news_section_title, news_section_description,
    cta_section_title, cta_section_description,
    cta_button_text, cta_phone_button_text,
    trust_indicator_1_value, trust_indicator_1_label,
    trust_indicator_2_value, trust_indicator_2_label,
    trust_indicator_3_value, trust_indicator_3_label
) VALUES (
    '1', 
    'Ihr Experte für Garten- und Landschaftsbau',
    'Professionelle Gartengestaltung seit über 20 Jahren mit Leidenschaft für die Natur',
    '+49 123 456-789',
    'info@hohmann-bau.de',
    'Hohmann Bau',
    'Garten & Landschaftsbau',
    'Home', 'Über uns', 'Leistungen', 'Team', 'Karriere', 'Aktuelles', 'Kontakt',
    'Unsere Leistungen',
    'Von der Planung bis zur Pflege - wir bieten Ihnen den kompletten Service für Ihren Traumgarten',
    'Unser Expertenteam',
    'Erfahrene Fachkräfte mit Leidenschaft für Garten und Landschaft',
    'Aktuelles',
    'Neuigkeiten und Informationen rund um Garten und Landschaftsbau',
    'Bereit für Ihren Traumgarten?',
    'Lassen Sie uns gemeinsam Ihre Vorstellungen in die Realität umsetzen. Kontaktieren Sie uns für ein unverbindliches Beratungsgespräch.',
    'Beratungstermin vereinbaren',
    'Jetzt anrufen',
    '25+', 'Jahre Erfahrung',
    '150+', 'Projekte/Jahr',
    '98%', 'Zufriedenheit'
);