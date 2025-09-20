-- Hohmann Bau MySQL Database Setup
-- Datenbank erstellen
CREATE DATABASE IF NOT EXISTS hohmann_bau CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hohmann_bau;

-- Tabelle für Seiteninhalte
CREATE TABLE page_contents (
    id VARCHAR(255) PRIMARY KEY,
    page_name VARCHAR(100) NOT NULL,
    content JSON NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle für Services
CREATE TABLE services (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    features JSON NOT NULL,
    icon VARCHAR(255) NOT NULL,
    image VARCHAR(500) NOT NULL,
    order_num INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für Features
CREATE TABLE features (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(255) NOT NULL,
    order_num INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für Kontaktinformationen
CREATE TABLE contact_info (
    id VARCHAR(255) PRIMARY KEY,
    address VARCHAR(500) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    email VARCHAR(255) NOT NULL,
    opening_hours VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle für Projekte
CREATE TABLE projects (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle für Team-Mitglieder
CREATE TABLE team_members (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(255) NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    bio TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für Job-Postings
CREATE TABLE job_postings (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    requirements TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    employment_type VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für Bewerbungen
CREATE TABLE applications (
    id VARCHAR(255) PRIMARY KEY,
    job_id VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    cover_letter TEXT NOT NULL,
    cv_filename VARCHAR(500) NULL,
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES job_postings(id) ON DELETE CASCADE
);

-- Tabelle für Angebots-Anfragen
CREATE TABLE quote_requests (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NULL,
    project_type VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    budget_range VARCHAR(100) NULL,
    timeline VARCHAR(100) NULL,
    file_path VARCHAR(500) NULL,
    status VARCHAR(50) DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für Kontaktnachrichten
CREATE TABLE contact_messages (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabelle für News/Blog-Posts
CREATE TABLE news_posts (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    excerpt TEXT NULL,
    image_url VARCHAR(500) NULL,
    author VARCHAR(255) NOT NULL,
    is_published BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle für Support-Tickets
CREATE TABLE support_tickets (
    id VARCHAR(255) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(50) DEFAULT 'open',
    priority VARCHAR(50) DEFAULT 'normal',
    assigned_to VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle für Hilfe-Artikel
CREATE TABLE help_articles (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    category VARCHAR(100) NOT NULL,
    order_num INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabelle für Administratoren
CREATE TABLE admins (
    id VARCHAR(255) PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) NOT NULL,
    hashed_password VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Standard-Daten einfügen
INSERT INTO page_contents (id, page_name, content) VALUES
(UUID(), 'home', '{"hero_title": "Bauen mit Vertrauen", "hero_subtitle": "Ihr zuverlässiger Partner für Hochbau, Tiefbau und Sanierungen", "hero_image": "https://images.unsplash.com/photo-1599995903128-531fc7fb694b?crop=entropy&cs=srgb&fm=jpg&ixid=M3w3NDk1Nzl8MHwxfHNlYXJjaHwyfHxjb25zdHJ1Y3Rpb24lMjBzaXRlfGVufDB8fHx8MTc1ODM3ODEyMHww&ixlib=rb-4.1.0&q=85"}'),
(UUID(), 'services', '{"title": "Unsere Leistungen", "subtitle": "Umfassende Baulösungen aus einer Hand", "description": "Von der ersten Idee bis zur schlüsselfertigen Übergabe begleiten wir Sie durch Ihr gesamtes Bauvorhaben."}'),
(UUID(), 'projects', '{"title": "Unsere Projekte", "subtitle": "Referenzen aus verschiedenen Bereichen", "description": "Entdecken Sie unsere erfolgreich abgeschlossenen Bauprojekte."}'),
(UUID(), 'team', '{"title": "Unser Team", "subtitle": "Erfahrene Fachkräfte für Ihr Projekt", "description": "Lernen Sie die Menschen kennen, die hinter unseren erfolgreichen Bauprojekten stehen."}'),
(UUID(), 'contact', '{"title": "Kontakt", "subtitle": "Lassen Sie uns über Ihr Projekt sprechen", "description": "Haben Sie Fragen zu unseren Leistungen oder möchten Sie ein Projekt mit uns besprechen?"}');

INSERT INTO features (id, title, description, icon, order_num) VALUES
(UUID(), '25+ Jahre Erfahrung', 'Über zwei Jahrzehnte Expertise im Baugewerbe mit hunderten erfolgreich abgeschlossenen Projekten.', '⚒️', 1),
(UUID(), 'Qualität & Zuverlässigkeit', 'Höchste Qualitätsstandards und termingerechte Ausführung aller Bauvorhaben.', '✓', 2),
(UUID(), 'Rundum-Service', 'Von der Planung bis zur Übergabe - alles aus einer Hand für Ihr Bauprojekt.', '🔧', 3);

INSERT INTO contact_info (id, address, phone, email, opening_hours) VALUES
(UUID(), 'Bahnhofstraße 123, 12345 Musterstadt', '+49 123 456 789', 'info@hohmann-bau.de', 'Mo-Fr: 08:00-17:00 Uhr');

-- Standard-Admin (Benutzername: admin, Passwort: admin123)
INSERT INTO admins (id, username, email, hashed_password) VALUES
(UUID(), 'admin', 'admin@hohmann-bau.de', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');