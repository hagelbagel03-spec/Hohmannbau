-- EINFACHE Datenbank-Erweiterung für Farb-Funktionen
-- Kopieren und in phpMyAdmin einfügen

-- Neue Spalten zur homepage-Tabelle hinzufügen
ALTER TABLE homepage ADD footer_bg_color VARCHAR(20) DEFAULT '#1f2937';
ALTER TABLE homepage ADD footer_text_color VARCHAR(20) DEFAULT '#ffffff';
ALTER TABLE homepage ADD header_bg_color VARCHAR(20) DEFAULT '#ffffff';
ALTER TABLE homepage ADD header_text_color VARCHAR(20) DEFAULT '#1f2937';
ALTER TABLE homepage ADD button_primary_color VARCHAR(20) DEFAULT '#10b981';
ALTER TABLE homepage ADD button_secondary_color VARCHAR(20) DEFAULT '#6b7280';
ALTER TABLE homepage ADD accent_color VARCHAR(20) DEFAULT '#3b82f6';
ALTER TABLE homepage ADD body_text_color VARCHAR(20) DEFAULT '#374151';