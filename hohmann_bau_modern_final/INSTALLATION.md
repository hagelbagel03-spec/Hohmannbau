# 🚀 Hohmann Bau - Installation & Setup

## ✅ Was ist enthalten:

### 📄 Hauptdateien:
- `index.php` → Moderne Homepage (vollständig bearbeitbar)
- `BENUTZERHANDBUCH.md` → Vollständige Anleitung
- `INSTALLATION.md` → Diese Datei

### 🔧 Admin Panel:
- `admin/login.php` → Admin Login
- `admin/text_editor.php` → Alle Texte bearbeiten
- `admin/simple_upload.php` → Bilder hochladen
- `admin/index.php` → Admin Dashboard

### 📁 Upload-Verzeichnisse:
- `uploads/hero/` → Hero-Hintergründe
- `uploads/gallery/` → Galerie-Bilder  
- `uploads/services/` → Service-Bilder
- `uploads/team/` → Team-Fotos
- `uploads/news/` → News-Bilder

## 🛠️ Installation:

### 1. Server-Anforderungen:
- **PHP 7.4+** mit MySQL-Erweiterung
- **Apache/Nginx** Webserver
- **MySQL/MariaDB** Datenbank
- **mod_rewrite** aktiviert

### 2. Dateien hochladen:
```bash
# Alle Dateien in Ihr Web-Verzeichnis kopieren
# z.B. /var/www/html/ oder /public_html/
```

### 3. Berechtigungen setzen:
```bash
# Upload-Verzeichnisse beschreibbar machen
chmod 755 uploads/
chmod 755 uploads/*
```

### 4. Datenbank einrichten:
```bash
# MySQL/MariaDB Datenbank erstellen
mysql -u root -p
CREATE DATABASE hohmann_bau CHARACTER SET utf8mb4;

# SQL-Datei importieren
mysql -u root -p hohmann_bau < database_funktionsfaehig.sql
```

### 5. Datenbank-Konfiguration:
Bearbeiten Sie `config/database.php`:
```php
$pdo = new PDO(
    "mysql:host=localhost;dbname=IHR_DB_NAME;charset=utf8mb4",
    'IHR_DB_USER',
    'IHR_DB_PASSWORT'
);
```

## 🎯 Erste Schritte:

### Admin-Zugang:
- **URL**: `http://ihre-domain.de/admin/login.php`
- **Benutzername**: `admin`
- **Passwort**: `admin123`

### Sofort einsatzbereit:
1. ✅ **Texte bearbeiten** → `admin/text_editor.php`
2. ✅ **Bilder hochladen** → `admin/simple_upload.php`
3. ✅ **Website anpassen** → Alle Inhalte editierbar!

## 🔒 Sicherheit:

### Admin-Passwort ändern:
```sql
-- Neues Passwort setzen (Beispiel: "mein_sicheres_passwort")
UPDATE admins SET hashed_password = '$2y$10$LPV7O/keOXYvm4BMAsgUQOLtFurEB7b2R7GoE2/myMWxtjtEiAVZ.' WHERE username = 'admin';
```

### Empfehlungen:
- `.htaccess` für Admin-Verzeichnis
- SSL-Zertifikat installieren
- Regelmäßige Backups

## 📞 Features im Überblick:

### ✅ Vollständig bearbeitbar:
- 📝 Alle Texte (Navigation, Überschriften, Beschreibungen)
- 🖼️ Bilder (Hero, Galerie, Services, Team)
- 🎨 Farben und Design-Elemente
- 📱 Komplett responsive

### ✅ Moderne Gestaltung:
- 🌿 Gartenbau-optimiertes Design
- ⚡ Schnelle Ladezeiten
- 📱 Mobile-First Ansatz
- 🎯 SEO-optimiert

### ✅ Admin-Features:
- 🔐 Sicheres Login-System
- 📝 Intuitiver Text-Editor
- 📤 Drag & Drop Bild-Upload
- 📊 Übersichtliches Dashboard

## 🎉 Bereit!

Die Website ist sofort einsatzbereit und kann nach der Installation direkt verwendet werden. Alle Texte und Bilder können über das Admin Panel bearbeitet werden.

**Viel Erfolg mit Ihrer neuen Website! 🚀**