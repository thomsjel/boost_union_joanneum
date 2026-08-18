<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// //
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Theme Boost Union Joanneum - Sprachpaket
 *
 * @package    theme_boost_union_joanneum
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> und Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 oder neuer
 */

defined('MOODLE_INTERNAL') || die();

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

// Allgemein.
$string['pluginname'] = 'Boost Union Joanneum';
$string['choosereadme'] = 'Dieses Plugin ist nur eine Vorlage, die Sie verwenden können, um Boost Union Joanneum-Themes zu entwickeln.';
$string['configtitle'] = 'Boost Union Joanneum';
$string['settingsoverview_buc_desc'] = 'Mit Boost Union Joanneum können Sie Boost Union an Ihre lokalen Bedürfnisse anpassen.';

// Einstellungen: Registerkarte Allgemeine Einstellungen.
// ... Abschnitt: Vererbung.
$string['inheritanceheading'] = 'Vererbung';
$string['inheritanceinherit'] = 'Vererben';
$string['inheritanceduplicate'] = 'Duplizieren';
$string['inheritanceoptionsexplanation'] = 'In den meisten Fällen ist die Vererbung völlig ausreichend. Es kann jedoch vorkommen, dass fehlerhafter Code in Boost Union integriert ist, der die einfache SCSS-Vererbung für bestimmte Boost Union-Funktionen verhindert. Wenn Sie Probleme mit Boost Union-Funktionen haben, die in Boost Union Joanneum nicht zu funktionieren scheinen, versuchen Sie, diese Einstellung auf "Duplizieren" zu ändern. Wenn dies das Problem löst, melden Sie bitte ein Problem auf GitHub (siehe README.md-Datei für Details, wie man ein Problem meldet).';
// ... ... Einstellung: Pre-SCSS-Vererbungseinstellung.
$string['prescssinheritancesetting'] = 'Pre-SCSS-Vererbung';
$string['prescssinheritancesetting_desc'] = 'Mit dieser Einstellung steuern Sie, ob der Pre-SCSS-Code von Boost Union vererbt oder dupliziert werden soll.';
// ... ... Einstellung: Extra-SCSS-Vererbungseinstellung.
$string['extrascssinheritancesetting'] = 'Extra-SCSS-Vererbung';
$string['extrascssinheritancesetting_desc'] = 'Mit dieser Einstellung steuern Sie, ob der Extra-SCSS-Code von Boost Union vererbt oder dupliziert werden soll.';

/***************************************************************
 * ERWEITERUNGSPUNKT:
 * Fügen Sie hier Ihre Sprachstrings für Ihre Einstellungen ein.
 *************************************************************/

// Datenschutz-API.
$string['privacy:metadata'] = 'Das Boost Union Joanneum-Theme speichert keine persönlichen Daten über Benutzer.';

// Einstellungen: Registerkarte Allgemeine Einstellungen.
// ... Abschnitt: Kohortenbasierte Navigation.
$string['custommenuitemsheading'] = 'Kohortenbasierte Navigation';
// ... ... Einstellung: Benutzerdefinierte Menüelemente.
$string['custommenuitems'] = 'Benutzerdefinierte Menüelemente';
$string['custommenuitems_desc'] = 'Geben Sie die Menüelemente zeilenweise ein: Titel | URL | Tooltip | Sprache | {Kohorten-IDs}. Verwenden Sie - für Unterelemente, ### für Trennlinien. Beispiel: Lehrerressourcen | /teacher | Lehrerbereich | de | {1}';
$string['cohorttagscontainer_desc'] = 'Kohortensichtbarkeit';

// Einstellungen: Registerkarte Slider.
$string['slidertab'] = 'Slider';
$string['sliderreference'] = 'Für allgemeine Slider-Einstellungen siehe den <a href="{$a}">Slider-Abschnitt in Boost Union</a>.';
// ... Abschnitt: Slider-Layout.
$string['sliderlayoutheading'] = 'Slider-Layout';
// ... ... Einstellung: Slider-Layout-Typ.
$string['sliderlayoutsetting'] = 'Slider-Layout-Typ';
$string['sliderlayoutsetting_desc'] = 'Mit dieser Einstellung können Sie das Layout für den Slider auswählen.';
$string['sliderlayoutsetting_default'] = 'Standard (Vollbild mit Textüberlagerung)';
$string['sliderlayoutsetting_textleft_solid'] = 'Text links mit einfarbigem Hintergrund und Bild rechts';
// ... Abschnitt: Slider-Sichtbarkeit.
$string['slidervisibilityheading'] = 'Slider-Sichtbarkeit';
// ... ... Einstellung: Slider auf der Startseite anzeigen.
$string['showslideronfrontpage'] = 'Slider auf der Startseite anzeigen';
$string['showslideronfrontpage_desc'] = 'Wenn aktiviert, wird der Slider auf der Startseite der Website angezeigt.';
// ... ... Einstellung: Slider auf dem Dashboard anzeigen.
$string['showsliderondashboard'] = 'Slider auf dem Dashboard anzeigen';
$string['showsliderondashboard_desc'] = 'Wenn aktiviert, wird der Slider auf dem Benutzer-Dashboard (Mein Moodle) angezeigt.';

// Einstellungen: Registerkarte Anmeldeseite.
$string['loginpagetab'] = 'Anmeldeseite';
// ... Abschnitt: Anmeldeseite-Layout.
$string['loginlayoutheading'] = 'Anmeldeseite-Layout';
// ... ... Einstellung: Anmeldeseite-Layout-Typ.
$string['loginlayoutsetting'] = 'Anmeldeseite-Layout-Typ';
$string['loginlayoutsetting_desc'] = 'Mit dieser Einstellung können Sie das Layout für die Anmeldeseite auswählen.';
$string['loginlayoutsetting_default'] = 'Standard';
$string['loginlayoutsetting_splitscreen'] = 'Geteilter Bildschirm (Hintergrundbild links, Anmeldung rechts)';
$string['loginoidc'] = 'OpenIDC-Anmeldebutton aktivieren.';

// Einstellungen: Registerkarte Highlights.
$string['highlightstab'] = 'Highlights';
// ... Abschnitt: Highlights-Einstellungen.
$string['highlightsheading'] = 'Highlights';
$string['highlightsheading_desc'] = 'Konfigurieren Sie bis zu 6 Highlights, die als Kacheln im Frontend angezeigt werden.';

// Einstellungen: Registerkarte Schriftarten.
$string['fontstab'] = 'Schriftarten';
// ... Abschnitt: Schriftart 1.
$string['font1heading'] = 'Schriftart 1';
$string['font1file'] = 'Schriftart 1-Datei';
$string['font1file_desc'] = 'Laden Sie eine Schriftartdatei (WOFF, WOFF2, TTF, EOT oder SVG) hoch.';
$string['font1cssclasses'] = 'CSS-Klassen/Tags für Schriftart 1';
$string['font1cssclasses_desc'] = 'Geben Sie die CSS-Klassen oder HTML-Tags (kommagetrennt) ein, die diese Schriftart verwenden sollen, z. B. h1,h2,h3,.benutzerdefinierte-klasse';
// ... Abschnitt: Schriftart 2.
$string['font2heading'] = 'Schriftart 2';
$string['font2file'] = 'Schriftart 2-Datei';
$string['font2file_desc'] = 'Laden Sie eine Schriftartdatei (WOFF, WOFF2, TTF, EOT oder SVG) hoch.';
$string['font2cssclasses'] = 'CSS-Klassen/Tags für Schriftart 2';
$string['font2cssclasses_desc'] = 'Geben Sie die CSS-Klassen oder HTML-Tags (kommagetrennt) ein, die diese Schriftart verwenden sollen, z. B. h4,h5,h6,.andere-klasse';
// ... ... Einstellung: Highlights aktivieren.
$string['enablehighlights'] = 'Highlights aktivieren';
$string['enablehighlights_desc'] = 'Wenn aktiviert, werden die Highlights im Frontend angezeigt.';
// ... ... Einstellung: Highlights abweisbar.
$string['highlightsdismissible'] = 'Highlights-Bereich abweisbar machen';
$string['highlightsdismissible_desc'] = 'Wenn aktiviert, wird der Highlights-Bereich abweisbar. Durch Klicken auf die Schaltfläche "Schließen" wird er für den Benutzer dauerhaft ausgeblendet. Verwenden Sie die Schaltfläche "Sichtbarkeit zurücksetzen", um ihn wiederherzustellen.';
// ... ... Einstellung: Zurückgesetzte Highlights zurücksetzen.
$string['highlightsdismissreset'] = 'Sichtbarkeit der abgelehnten Highlights zurücksetzen';
$string['highlightsdismissresetbutton'] = 'Sichtbarkeit der Highlights zurücksetzen';
$string['highlightsdismissconfirm'] = 'Möchten Sie die Sichtbarkeit des Highlights-Bereichs wirklich zurücksetzen und ihn für alle Benutzer, die ihn abgelehnt haben, erneut anzeigen?';
$string['highlightsdismissresetsuccess'] = 'Die Sichtbarkeit des Highlights-Bereichs wurde zurückgesetzt.';
$string['highlightsdismissresetfail'] = 'Das Zurücksetzen der Sichtbarkeit des Highlights-Bereichs ist für mindestens einen Benutzer fehlgeschlagen';
// ... Abschnitt: Highlights-Sichtbarkeit.
$string['highlightsvisibilityheading'] = 'Highlights-Sichtbarkeit';
// ... ... Einstellung: Highlights auf der Startseite anzeigen.
$string['showhighlightsonfrontpage'] = 'Highlights auf der Startseite anzeigen';
$string['showhighlightsonfrontpage_desc'] = 'Wenn aktiviert, werden die Highlights auf der Startseite der Website angezeigt.';
// ... ... Einstellung: Highlights auf dem Dashboard anzeigen.
$string['showhighlightsonDashboard'] = 'Highlights auf dem Dashboard anzeigen';
$string['showhighlightsonDashboard_desc'] = 'Wenn aktiviert, werden die Highlights auf dem Benutzer-Dashboard (Mein Moodle) angezeigt.';
// ... ... Einstellungen: Highlight-Elemente.
$string['highlightenabled'] = 'Highlight aktivieren';
$string['highlightenabled_desc'] = 'Wenn aktiviert, wird dieses Highlight angezeigt.';
$string['highlightsectiontitle'] = 'Abschnittsüberschrift';
$string['highlighticon'] = 'Symbol-Datei';
$string['highlighticon_desc'] = 'Laden Sie eine Symbol-Bilddatei (SVG, JPG, JPEG oder PNG) für dieses Highlight hoch.';
$string['highlighttitle'] = 'Titel';
$string['highlighttitle_desc'] = 'Geben Sie den Titel für dieses Highlight ein.';
$string['highlightdescription'] = 'Beschreibung';
$string['highlightdescription_desc'] = 'Geben Sie die Beschreibung für dieses Highlight ein.';
$string['highlightlink'] = 'Link';
$string['highlightlink_desc'] = 'Geben Sie die URL für diese Highlight-Kachel ein (z. B. /course oder https://example.com).';
$string['highlightcohortvisibility'] = 'Kohortensichtbarkeit';
$string['highlightcohortvisibility_desc'] = 'Geben Sie die Kohorten-IDs (kommagetrennt) ein, die dieses Highlight sehen können sollen. Lassen Sie das Feld leer, um es allen Benutzern anzuzeigen.';

// Sonstiges
$string['or'] = 'oder';