# 🪟 Windows Apache Installation Guide

## Problem: 404-Fehler auf Windows Apache

Die PHP-Anwendung läuft nicht auf Windows Apache, weil sie für Linux entwickelt wurde. Hier ist die komplette Lösung:

## 🛠️ Schritt-für-Schritt Lösung:

### 1. **Apache mod_rewrite aktivieren**

Öffnen Sie die Apache-Konfigurationsdatei `httpd.conf` (meist in `C:\xampp\apache\conf\` oder `C:\Apache24\conf\`):

```apache
# Diese Zeile auskommentieren (# entfernen):
LoadModule rewrite_module modules/mod_rewrite.so
```

### 2. **AllowOverride aktivieren**

In der gleichen `httpd.conf`-Datei suchen Sie den `<Directory>` Abschnitt für Ihr DocumentRoot:

```apache
<Directory "C:/xampp/htdocs">  # oder Ihr DocumentRoot
    Options Indexes FollowSymLinks
    AllowOverride All              # WICHTIG: Muss "All" sein, nicht "None"
    Require all granted
</Directory>
```

### 3. **Dateien korrekt platzieren**

Kopieren Sie ALLE PHP-Dateien in Ihr Apache DocumentRoot-Verzeichnis:
- Bei XAMPP: `C:\xampp\htdocs\`
- Bei Standard Apache: `C:\Apache24\htdocs\`

### 4. **Datenbank einrichten**

**MySQL/MariaDB starten:**
- Bei XAMPP: MySQL im Control Panel starten
- Bei WAMP: MySQL-Service starten

**Datenbank importieren:**
```sql
-- In phpMyAdmin oder MySQL-Konsole:
CREATE DATABASE stadtwache;
USE stadtwache;
-- Dann database.sql importieren
```

### 5. **Datenbank-Konfiguration anpassen**

Bearbeiten Sie `config/database.php`:

```php
<?php
class Database {
    private $host = 'localhost';        # oder '127.0.0.1'
    private $db_name = 'stadtwache';    # Datenbankname
    private $username = 'root';         # MySQL-Benutzername
    private $password = '';             # MySQL-Passwort (bei XAMPP meist leer)
    // ... rest bleibt gleich
}
?>
```

### 6. **Apache neustarten**

Starten Sie Apache neu:
- XAMPP: Apache Stop → Start
- Windows Service: `net stop Apache2.4` → `net start Apache2.4`

## 🧪 Testen:

1. **Basis-Test:**
   ```
   http://localhost/index.php
   ```
   ✅ Sollte die Homepage zeigen

2. **URL-Rewriting-Test:**
   ```
   http://localhost/
   ```
   ✅ Sollte auch die Homepage zeigen (ohne .php)

3. **Weitere Seiten:**
   ```
   http://localhost/report.php
   http://localhost/careers.php
   http://localhost/admin/login.php
   ```

## 🚨 Häufige Windows-Probleme:

### Problem 1: "Forbidden - You don't have permission"
**Lösung:** 
```apache
# In httpd.conf
<Directory "C:/xampp/htdocs">
    Require all granted  # Statt "Deny from all"
</Directory>
```

### Problem 2: ".htaccess wird ignoriert"
**Lösung:**
- `AllowOverride All` setzen
- mod_rewrite aktivieren
- Apache neustarten

### Problem 3: "Database connection failed"
**Lösung:**
- MySQL läuft? (XAMPP Control Panel)
- Richtige Zugangsdaten in `config/database.php`
- PHP PDO MySQL Extension aktiviert?

### Problem 4: "File not found" für uploads/
**Lösung:**
```bash
# Ordner erstellen und Schreibrechte geben
mkdir uploads
# Bei Windows: Rechtsklick → Eigenschaften → Sicherheit → Vollzugriff
```

## 🎯 Alternative für sofortigen Test:

Falls .htaccess Probleme macht, verwenden Sie direkt die .php-Endungen:

1. `http://localhost/index.php` - Homepage
2. `http://localhost/report.php` - Vorfall melden
3. `http://localhost/careers.php` - Bewerbungen
4. `http://localhost/feedback.php` - Feedback
5. `http://localhost/admin/login.php` - Admin-Login

## 📋 Checkliste für Windows:

- [ ] mod_rewrite aktiviert in httpd.conf
- [ ] AllowOverride All gesetzt
- [ ] Alle PHP-Dateien im DocumentRoot
- [ ] MySQL läuft
- [ ] Datenbank "stadtwache" erstellt
- [ ] database.sql importiert
- [ ] config/database.php angepasst
- [ ] Apache neugestartet
- [ ] PHP Version 7.4+ mit PDO MySQL

## 🎊 Nach der Installation:

**Demo-Zugangsdaten:**
- Admin-Login: `admin` / `admin123`
- URL: `http://localhost/admin/login.php`

Die Anwendung sollte dann vollständig funktionieren!