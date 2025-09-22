-- Datenbank-Update für erweiterte Farb-Funktionen
-- Diese SQL-Datei direkt in phpMyAdmin importieren

-- Erweiterte Farb-Spalten zur homepage-Tabelle hinzufügen
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS footer_bg_color VARCHAR(20) DEFAULT '#1f2937';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS footer_text_color VARCHAR(20) DEFAULT '#ffffff';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS header_bg_color VARCHAR(20) DEFAULT '#ffffff';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS header_text_color VARCHAR(20) DEFAULT '#1f2937';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS button_primary_color VARCHAR(20) DEFAULT '#10b981';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS button_secondary_color VARCHAR(20) DEFAULT '#6b7280';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS accent_color VARCHAR(20) DEFAULT '#3b82f6';
ALTER TABLE homepage ADD COLUMN IF NOT EXISTS body_text_color VARCHAR(20) DEFAULT '#374151';

-- Sicherstellen, dass ein homepage-Eintrag existiert
INSERT IGNORE INTO homepage (id) VALUES ('1');

-- Standard-Werte setzen falls noch nicht vorhanden
UPDATE homepage SET 
    footer_bg_color = COALESCE(footer_bg_color, '#1f2937'),
    footer_text_color = COALESCE(footer_text_color, '#ffffff'),
    header_bg_color = COALESCE(header_bg_color, '#ffffff'),
    header_text_color = COALESCE(header_text_color, '#1f2937'),
    button_primary_color = COALESCE(button_primary_color, '#10b981'),
    button_secondary_color = COALESCE(button_secondary_color, '#6b7280'),
    accent_color = COALESCE(accent_color, '#3b82f6'),
    body_text_color = COALESCE(body_text_color, '#374151')
WHERE id = '1';