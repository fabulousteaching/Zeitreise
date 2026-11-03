<?php
/**
 * ============================================================
 *  UNTERRICHTSMATERIALIEN – hier pflegst du alle Einträge.
 * ============================================================
 *
 * Neues Material hinzufügen: einen Block in 'materialien' kopieren
 * und anpassen. Felder:
 *
 *   'titel'          Name des Materials
 *   'fach'           Schlüssel aus 'faecher' (z. B. 'deutsch')
 *   'zyklen'         [1], [2], [3] oder mehrere, z. B. [1, 2]; [] = für alle
 *   'beschreibung'   ein bis zwei Sätze
 *   'typ'            z. B. 'PDF', 'Word', 'Werkstatt', 'Online'
 *   'umfang'         optional, z. B. '12 Seiten'
 *   'bild'           optional, Vorschaubild, z. B. 'assets/bilder/materialien/wortarten.webp'
 *   'datei'          Dateiname. Öffentliche Dateien liegen im Ordner downloads/,
 *                    geschützte (siehe nur_angemeldet) im Ordner daten/dateien/
 *   'link'           statt 'datei': eine Webadresse
 *   'nur_angemeldet' true = Download nur nach dem Login
 *   'neu'            true = zeigt das Abzeichen «Neu»
 *   'beispiel'       true = Platzhalter-Eintrag (zeigt «Beispiel»). Löschen, sobald echt.
 */

return [

    // ---------- Fächer: Name, Symbol, Farbe ----------
    // Symbole: book-open, calculator, earth, flask-conical, languages, palette,
    //          laptop, puzzle, music, volleyball, atom, microscope, scissors, pencil
    'faecher' => [
        'deutsch'     => ['name' => 'Deutsch',               'icon' => 'book-open',     'farbe' => '#e5484d'],
        'mathematik'  => ['name' => 'Mathematik',            'icon' => 'calculator',    'farbe' => '#3d8fd6'],
        'nmg'         => ['name' => 'NMG',                   'icon' => 'earth',         'farbe' => '#3aa856'],
        'nt'          => ['name' => 'Natur & Technik',       'icon' => 'flask-conical', 'farbe' => '#1f9e8f'],
        'sprachen'    => ['name' => 'Fremdsprachen',         'icon' => 'languages',     'farbe' => '#8a63d2'],
        'gestalten'   => ['name' => 'Gestalten',             'icon' => 'palette',       'farbe' => '#e0a516'],
        'mi'          => ['name' => 'Medien & Informatik',   'icon' => 'laptop',        'farbe' => '#5b6b7f'],
        'raetsel'     => ['name' => 'Rätsel & Knobeleien',   'icon' => 'puzzle',        'farbe' => '#f07a2e'],
    ],

    // ---------- Stufen ----------
    'zyklen' => [
        1 => 'Zyklus 1',
        2 => 'Zyklus 2',
        3 => 'Zyklus 3',
    ],

    // ---------- Materialien ----------
    'materialien' => [
        [
            'titel'        => 'Wortarten-Detektive',
            'fach'         => 'deutsch',
            'zyklen'       => [2],
            'beschreibung' => 'Spurensuche im Satz: Nomen, Verben und Adjektive bestimmen und sortieren.',
            'typ'          => 'PDF',
            'umfang'       => '14 Seiten',
            'datei'        => '',
            'neu'          => true,
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Einmaleins-Domino',
            'fach'         => 'mathematik',
            'zyklen'       => [1, 2],
            'beschreibung' => 'Legespiel zum Üben der Einmaleins-Reihen, alleine oder in der Gruppe.',
            'typ'          => 'PDF',
            'umfang'       => '6 Seiten',
            'datei'        => '',
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Der Wasserkreislauf',
            'fach'         => 'nmg',
            'zyklen'       => [2],
            'beschreibung' => 'Werkstatt mit Postenblättern, Versuchen und Lernkontrolle.',
            'typ'          => 'Werkstatt',
            'umfang'       => '22 Seiten',
            'datei'        => '',
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Experimentierkarten Säuren & Basen',
            'fach'         => 'nt',
            'zyklen'       => [3],
            'beschreibung' => 'Versuchsanleitungen mit Sicherheitshinweisen und Auswertungsblatt.',
            'typ'          => 'PDF',
            'umfang'       => '10 Karten',
            'datei'        => '',
            'nur_angemeldet' => true,
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Vocabulary Bingo',
            'fach'         => 'sprachen',
            'zyklen'       => [2, 3],
            'beschreibung' => 'Bingo-Karten zum Wortschatz – schnell vorbereitet, beliebt als Einstieg.',
            'typ'          => 'PDF',
            'datei'        => '',
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Farbkreis-Werkstatt',
            'fach'         => 'gestalten',
            'zyklen'       => [1, 2],
            'beschreibung' => 'Grundfarben mischen, Farbkreis gestalten, warme und kalte Farben entdecken.',
            'typ'          => 'Werkstatt',
            'datei'        => '',
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Codieren ohne Computer',
            'fach'         => 'mi',
            'zyklen'       => [2],
            'beschreibung' => 'Unplugged-Aufträge zu Befehlen, Schleifen und Geheimschriften.',
            'typ'          => 'PDF',
            'datei'        => '',
            'nur_angemeldet' => true,
            'beispiel'     => true,
        ],
        [
            'titel'        => 'Rätsellabor',
            'fach'         => 'raetsel',
            'zyklen'       => [],
            'beschreibung' => 'Knobeln, kombinieren, Codes knacken – direkt online im Rätsellabor.',
            'typ'          => 'Online',
            'link'         => 'https://raetsellabor.fabulousteaching.ch',
        ],
    ],
];
