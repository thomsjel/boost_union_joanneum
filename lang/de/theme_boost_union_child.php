<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme Boost Union Child - Language pack
 *
 * @package    theme_boost_union_child
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

// General.
$string['pluginname'] = 'Boost Union Child';
$string['choosereadme'] = 'Dieses Plugin ist lediglich eine Standardvorlage, die zur Entwicklung von Boost Union-Child-Themes verwendet werden kann.';
$string['configtitle'] = 'Boost Union Child';
$string['settingsoverview_buc_desc'] = 'Mit Boost Union Child können Sie Boost Union an Ihre individuellen lokalen Anforderungen anpassen.';

// Settings: General settings tab.
// ... Section: Inheritance.
$string['inheritanceheading'] = 'Vererbung';
$string['inheritanceinherit'] = 'Vererben';
$string['inheritanceduplicate'] = 'Duplizieren';
$string['inheritanceoptionsexplanation'] = 'In den meisten Fällen funktioniert die Vererbung einwandfrei. Es kann jedoch vorkommen, dass fehlerhafter Code in Boost Union integriert wurde, der eine einfache SCSS-Vererbung für bestimmte Boost-Union-Funktionen verhindert. Sollten Sie auf Probleme mit Boost-Union-Funktionen stoßen, die offenbar auch in Boost Union Child nicht funktionieren, versuchen Sie, diese Einstellung auf Duplizieren umzustellen. Falls das Problem dadurch behoben wird, melden Sie bitte ein Issue auf GitHub (Einzelheiten zur Meldung eines Issues finden Sie in der Datei README.md).';
// ... ... Setting: Pre SCSS inheritance setting.
$string['prescssinheritancesetting'] = 'Pre SCSS Vererbung';
$string['prescssinheritancesetting_desc'] = 'Mit dieser Einstellung legen Sie fest, ob der Pre-SCSS-Code von Boost Union übernommen oder dupliziert werden soll.';
// ... ... Setting: Extra SCSS inheritance setting.
$string['extrascssinheritancesetting'] = 'Extra SCSS Vererbung';
$string['extrascssinheritancesetting_desc'] = 'Mit dieser Einstellung legen Sie fest, ob der zusätzliche SCSS-Code von Boost Union übernommen oder dupliziert werden soll.';

/**************************************************************
 * EXTENSION POINT:
 * Add your language strings for your settings here.
 *************************************************************/

// Privacy API.
$string['privacy:metadata'] = 'Das „Boost Union Child“-Theme speichert keinerlei personenbezogene Daten von Nutzern.';

// Settings: Slider tab.
$string['slidertab'] = 'Slider';
// ... Section: Slider layout.
$string['sliderlayoutheading'] = 'Slider-Layout';
// ... ... Setting: Slider layout type.
$string['sliderlayoutsetting'] = 'Slider-Layout-Typ';
$string['sliderlayoutsetting_desc'] = 'Mit dieser Einstellung können Sie das Layout für den Slider auswählen.';
$string['sliderlayoutsetting_default'] = 'Standard (Vollbreitenbild mit Textüberlagerung)';
$string['sliderlayoutsetting_textleft_solid'] = 'Text links mit durchgezogener Hintergrundfarbe und Bild rechts';

// Settings: General settings tab.
// ... Section: Cohort-based navigation.
$string['custommenuitemsheading'] = 'Kohortenbasierte Navigation';
// ... ... Setting: Custom menu items.
$string['custommenuitems'] = 'Eigene Menüpunkte';
$string['custommenuitems_desc'] = 'Geben Sie jeden Menüpunkt in einer neuen Zeile im folgenden Format ein: <strong>Format</strong>:<br><br><strong>Titel des Menüpunkts | Link-URL | Titel des Tooltips (optional) | Sprachcode (optional) | {Kohorten-IDs} (optional)</strong><br><br>Zeilen, die mit einem Bindestrich beginnen, werden als Menüpunkte im übergeordneten Menü angezeigt, und ### dient als Trennzeichen. <br><br><div class="settings-example"><span class="settings-example-title">Beispiel</span><div class="settings-example-code"><code>Ressourcen für Lehrkräfte <span>|</span> /teacher <span>|</span> Bereich für Lehrkräfte <span>|</span> en <span>|</span> <strong> {1}</strong><br>Ressourcen für Schüler <span>|</span> /students <span>| | |</span> <strong>{2}</strong></code></div></div><br>Dadurch werden nur „Ressourcen für Lehrkräfte“ für Benutzer in Kohorten mit der Datenbank-ID=1 und „Ressourcen für Schüler“ für Benutzer in Kohorten mit der Datenbank-ID=2 angezeigt.';
