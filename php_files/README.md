# Hohmann Bau - PHP Version

Eine vollständige PHP-Konvertierung der ursprünglichen React/FastAPI Hohmann Bau Website für die Verwendung mit XAMPP.

## 🚀 Installation in XAMPP

### 1. Dateien kopieren
```bash
# Kopieren Sie den gesamten 'hohmann_bau_php' Ordner in Ihr XAMPP htdocs Verzeichnis
C:\xampp\htdocs\hohmann_bau_php\
```

### 2. Datenbank einrichten
1. Starten Sie XAMPP (Apache + MySQL)
2. Öffnen Sie phpMyAdmin: `http://localhost/phpmyadmin`
3. Importieren Sie die Datei: `database/setup.sql`
   - Erstellt automatisch die Datenbank "hohmann_bau"
   - Erstellt alle benötigten Tabellen
   - Fügt Beispieldaten ein

### 3. Konfiguration anpassen
Falls nötig, passen Sie die Datenbankverbindung in `config/database.php` an:
```php
private $host = 'localhost';
private $dbname = 'hohmann_bau';
private $username = 'root';
private $password = '';  // Standard XAMPP MySQL Passwort
```

### 4. Website aufrufen
Öffnen Sie Ihren Browser und gehen Sie zu:
```
http://localhost/hohmann_bau_php/
```

## 📁 Projektstruktur

```
hohmann_bau_php/
├── api/                    # API Endpoints
│   └── index.php          # API Router
├── classes/               # PHP Klassen
│   ├── ApiHandler.php     # API Request Handler
│   └── PageContent.php    # Content Management
├── config/                # Konfigurationsdateien
│   ├── config.php         # Hauptkonfiguration
│   └── database.php       # Datenbankverbindung
├── database/              # Datenbank Setup
│   └── setup.sql          # MySQL Schema & Daten
├── includes/              # Template Includes
│   ├── header.php         # Header Template
│   └── footer.php         # Footer Template
├── uploads/               # Upload Verzeichnis
│   ├── cv/               # CV Uploads
│   └── blueprints/       # Blueprint Uploads
├── admin/                 # Admin Panel (geplant)
├── index.php             # Homepage
├── kontakt.php           # Kontaktseite
├── angebot.php           # Angebot anfordern
├── leistungen.php        # Leistungen
├── projekte.php          # Projekte
├── team.php              # Team (geplant)
├── karriere.php          # Karriere (geplant)
└── README.md             # Diese Datei
```

## 🎨 Features

### ✅ Implementiert
- **Homepage** mit dynamischem Content
- **Kontaktformular** mit E-Mail Funktionalität
- **Angebots-Anfrage** mit Datei-Upload
- **Leistungsübersicht** mit Service-Details
- **Projektgalerie** mit Filterung
- **Responsive Design** mit Tailwind CSS
- **REST API** für dynamische Inhalte
- **MySQL Datenbank** mit vollständigem Schema

### 🚧 In Entwicklung
- Team-Seite
- Karriere-Seite mit Bewerbungsformular
- Admin-Panel für Content-Management
- Erweiterte Projektdetails
- News/Blog System

## 🔧 Technische Details

### Backend
- **PHP 7.4+** mit PDO für Datenbankzugriff
- **MySQL** Datenbank
- **RESTful API** Struktur
- **File Upload** Funktionalität
- **UUID** basierte IDs (MongoDB kompatibel)

### Frontend
- **Tailwind CSS** für Styling
- **Vanilla JavaScript** für Interaktivität
- **Lucide Icons** für moderne Icons
- **Responsive Design** für alle Geräte
- **Progressive Enhancement**

### Datenbank Schema
- `page_contents` - CMS Inhalte
- `services` - Leistungen
- `features` - Homepage Features
- `projects` - Projekt Portfolio
- `team_members` - Team Mitglieder
- `job_postings` - Stellenausschreibungen
- `applications` - Bewerbungen
- `quote_requests` - Angebots-Anfragen
- `contact_messages` - Kontakt Nachrichten
- `contact_info` - Kontaktinformationen
- `news_posts` - News/Blog Artikel
- `support_tickets` - Support Tickets
- `help_articles` - Hilfe Artikel
- `admins` - Administrator Accounts

## 🔐 Admin Zugang

Standard Admin-Login (nach Installation):
- **Benutzername:** admin
- **Passwort:** admin123

**⚠️ Wichtig:** Ändern Sie das Admin-Passwort nach der Installation!

## 📧 E-Mail Konfiguration

Für den Versand von Kontaktformularen können Sie die E-Mail Konfiguration in der jeweiligen PHP-Datei anpassen. Standardmäßig werden die Nachrichten in der Datenbank gespeichert.

## 🔄 Migration von MongoDB

Die ursprünglichen MongoDB Datenstrukturen wurden vollständig zu MySQL migriert:
- JSON Felder für komplexe Datenstrukturen
- UUID Felder für Kompatibilität
- Alle Original API Endpoints beibehalten

## 📱 Browser Kompatibilität

- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Edge 90+
- ✅ Mobile Browser

## 🤝 Support

Bei Fragen oder Problemen:
1. Überprüfen Sie die XAMPP Logs
2. Stellen Sie sicher, dass Apache + MySQL laufen
3. Prüfen Sie die Datenbankverbindung
4. Kontrollieren Sie die Dateiberechtigungen

## 📄 Lizenz

Dieses Projekt ist eine PHP-Konvertierung der ursprünglichen Hohmann Bau Website.