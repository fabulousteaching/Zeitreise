# fabulousteaching.ch – Lehrerwebseite

Grundlayout der Website mit Startseite («Über mich»), Unterrichtsmaterialien,
Rätsellabor und Login-Bereich. Farben und Stil kommen aus dem Logo
(Aquarell-Orange, Labortisch-Anthrazit, Holzbraun, bunte Kolben).

## Seiten

| Datei | Inhalt |
|---|---|
| `index.php` | Home: Begrüssung, **Über mich** (Foto + Text), Überblick, Materialvorschau |
| `unterrichtsmaterialien.php` | Materialien mit Fach-Kacheln, Zyklus-Filter und Suche |
| `raetsellabor.php` | Infoseite mit Knopf zu https://raetsellabor.fabulousteaching.ch |
| `login.php` | Anmeldung (beim allerersten Aufruf: eigenes Konto einrichten) |
| `bereich.php` | «Mein Bereich» nach dem Login: geschützte Materialien, Abmelden |
| `download.php` | liefert geschützte Dateien nur an angemeldete Personen aus |

Gemeinsame Teile liegen in `inc/` (Kopf mit Navigation, Fusszeile, Symbole),
die Gestaltung in `assets/css/stil.css`.

## Auf Infomaniak hochladen

1. Im Infomaniak-Manager die Website *fabulousteaching.ch* öffnen und
   PHP 8.1 oder neuer einstellen (Standard bei neuen Sites).
2. Den **Inhalt** dieses Ordners `fabulousteaching/` per FTP (z. B. FileZilla)
   oder mit dem Web-FTP in das Hauptverzeichnis der Site laden – also so, dass
   `index.php` direkt im Web-Ordner liegt. Die versteckten Dateien `.htaccess`
   unbedingt mitkopieren (sie sperren `inc/` und `daten/`).
3. Im Manager unter *SSL/HTTPS* das Zertifikat aktivieren und «HTTPS erzwingen» einschalten.
4. **Sofort** `https://fabulousteaching.ch/login.php` öffnen und dein Konto
   einrichten. Danach ist die Einrichtung gesperrt.

## Inhalte anpassen

- **Über-mich-Text:** in `index.php` beim Kommentar `TODO` den Platzhalter ersetzen.
- **Foto:** als `assets/bilder/ueber-mich.jpg` hochladen (Hochformat, etwa 800 × 1000 px).
  Es erscheint dann automatisch statt des Platzhalters.
- **Materialien:** alles in `daten/materialien.php` – oben in der Datei ist
  beschrieben, welche Felder es gibt. Die Einträge mit `'beispiel' => true`
  sind Platzhalter und können gelöscht werden.
  - Öffentliche Dateien in den Ordner `downloads/` legen.
  - Dateien nur für Angemeldete (`'nur_angemeldet' => true`) in `daten/dateien/` legen.
- **Fächer und Farben:** ebenfalls in `daten/materialien.php` unter `'faecher'`.
- **Adresse des Rätsellabors:** in `inc/start.php` (`RAETSELLABOR_URL`).
- **Menüpunkte:** in `inc/kopf.php` (Liste `$menue`).

## Konten

- Das erste Konto legst du über `login.php` selbst an. Gespeichert wird es in
  `daten/benutzer.php` (Passwort nur als sicherer Hash). Diese Datei kommt nicht ins Git.
- Weitere Konten: in `daten/benutzer.php` eine Zeile `'name' => 'hash',` ergänzen.
  Den Hash erzeugt `php -r 'echo password_hash("NeuesPasswort", PASSWORD_DEFAULT);'`.
- Passwort vergessen: `daten/benutzer.php` per FTP löschen und das Konto neu einrichten.
- Nach 8 Fehlversuchen innert 15 Minuten wird der Login für dieses Gerät kurz gesperrt.

## Lokal ansehen

Mit installiertem PHP im Ordner `fabulousteaching/`:

```bash
php -S localhost:8000
```

Dann http://localhost:8000 öffnen.

## Datenschutz

Schriften (Fredoka, Nunito – SIL Open Font License) und Symbole (Lucide, ISC)
liegen auf dem eigenen Server. Es werden keine Google-Fonts oder andere
externen Dienste geladen. Ein Sitzungs-Cookie entsteht nur auf der Login-Seite
und nach dem Anmelden – normale Besucherinnen und Besucher erhalten keines.
