-- Datenbank-Update für erweiterte Farb-Funktionen
-- Kompatibel mit älteren MySQL-Versionen

-- Spalten einzeln hinzufügen (ignoriert Fehler wenn bereits vorhanden)
ALTER TABLE homepage ADD footer_bg_color VARCHAR(20) DEFAULT '#1f2937';
ALTER TABLE homepage ADD footer_text_color VARCHAR(20) DEFAULT '#ffffff';
ALTER TABLE homepage ADD header_bg_color VARCHAR(20) DEFAULT '#ffffff';
ALTER TABLE homepage ADD header_text_color VARCHAR(20) DEFAULT '#1f2937';
ALTER TABLE homepage ADD button_primary_color VARCHAR(20) DEFAULT '#10b981';
ALTER TABLE homepage ADD button_secondary_color VARCHAR(20) DEFAULT '#6b7280';
ALTER TABLE homepage ADD accent_color VARCHAR(20) DEFAULT '#3b82f6';
ALTER TABLE homepage ADD body_text_color VARCHAR(20) DEFAULT '#374151';

-- Homepage-Eintrag sicherstellen
INSERT INTO homepage (id, footer_bg_color, footer_text_color, header_bg_color, header_text_color, button_primary_color, button_secondary_color, accent_color, body_text_color) 
VALUES ('1', '#1f2937', '#ffffff', '#ffffff', '#1f2937', '#10b981', '#6b7280', '#3b82f6', '#374151')
ON DUPLICATE KEY UPDATE
    footer_bg_color = COALESCE(footer_bg_color, '#1f2937'),
    footer_text_color = COALESCE(footer_text_color, '#ffffff'),
    header_bg_color = COALESCE(header_bg_color, '#ffffff'),
    header_text_color = COALESCE(header_text_color, '#1f2937'),
    button_primary_color = COALESCE(button_primary_color, '#10b981'),
    button_secondary_color = COALESCE(button_secondary_color, '#6b7280'),
    accent_color = COALESCE(accent_color, '#3b82f6'),
    body_text_color = COALESCE(body_text_color, '#374151');