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
$string['choosereadme'] = 'This plugin is just a boilerplate template one can use to develop Boost Union child themes.';
$string['configtitle'] = 'Boost Union Child';
$string['settingsoverview_buc_desc'] = 'With Boost Union Child, you can customize Boost Union to your own local needs.';

// Settings: General settings tab.
// ... Section: Inheritance.
$string['inheritanceheading'] = 'Inheritance';
$string['inheritanceinherit'] = 'Inherit';
$string['inheritanceduplicate'] = 'Duplicate';
$string['inheritanceoptionsexplanation'] = 'Most of the time, inheriting will be perfectly fine. However, it may happen that imperfect code is integrated into Boost Union which prevents simple SCSS inheritance for particular Boost Union features. If you encounter any issues with Boost Union features which seem not to work in Boost Union Child as well, try to switch this setting to \'Dupliate\' and, if this solves the problem, report an issue on Github (see the README.md file for details how to report an issue).';
// ... ... Setting: Pre SCSS inheritance setting.
$string['prescssinheritancesetting'] = 'Pre SCSS inheritance';
$string['prescssinheritancesetting_desc'] = 'With this setting, you control if the pre SCSS code from Boost Union should be inherited or duplicated.';
// ... ... Setting: Extra SCSS inheritance setting.
$string['extrascssinheritancesetting'] = 'Extra SCSS inheritance';
$string['extrascssinheritancesetting_desc'] = 'With this setting, you control if the extra SCSS code from Boost Union should be inherited or duplicated.';

/**************************************************************
 * EXTENSION POINT:
 * Add your language strings for your settings here.
 *************************************************************/

// Privacy API.
$string['privacy:metadata'] = 'The Boost Union Child theme does not store any personal data about any user.';

// Settings: General settings tab.
// ... Section: Cohort-based navigation.
$string['custommenuitemsheading'] = 'Cohort-based navigation';
// ... ... Setting: Custom menu items.
$string['custommenuitems'] = 'Custom menu items';
$string['custommenuitems_desc'] = 'Enter each menu item on a new line with <strong>format</strong>:<br><br><strong>Menu item title | Link URL | Tooltip title (optional) | language code (optional) | {cohort IDs} (optional)</strong><br><br>Lines starting with a hyphen will appear as menu items in the previous top level menu and ### makes a divider.<br><br><div class="settings-example"><span class="settings-example-title">Example</span><div class="settings-example-code"><code>Teacher Resources <span>|</span> /teacher <span>|</span> Teacher Area <span>|</span> en <span>|</span> <strong>{1}</strong><br>Student Resources <span>|</span> /students <span>| | |</span> <strong>{2}</strong></code></div></div><br>This will only show "Teacher Resources" to users in cohorts with database id=1 and "Student Resources" to users in cohorts with database id=2.';
$string['cohorttagscontainer_desc'] = 'Cohort visibility';

// Settings: Slider tab.
$string['slidertab'] = 'Slider';
$string['sliderreference'] = 'For general slider settings, see the <a href="{$a}">Slider section in Boost Union</a>.';
// ... Section: Slider layout.
$string['sliderlayoutheading'] = 'Slider layout';
// ... ... Setting: Slider layout type.
$string['sliderlayoutsetting'] = 'Slider layout type';
$string['sliderlayoutsetting_desc'] = 'With this setting, you can choose the layout for the slider.';
$string['sliderlayoutsetting_default'] = 'Default (Full width image with text overlay)';
$string['sliderlayoutsetting_textleft_solid'] = 'Text left with solid background and image right';
// ... Section: Slider visibility.
$string['slidervisibilityheading'] = 'Slider visibility';
// ... ... Setting: Show slider on frontpage.
$string['showslideronfrontpage'] = 'Show slider on frontpage';
$string['showslideronfrontpage_desc'] = 'If checked, the slider will be displayed on the site frontpage.';
// ... ... Setting: Show slider on dashboard.
$string['showsliderondashboard'] = 'Show slider on dashboard';
$string['showsliderondashboard_desc'] = 'If checked, the slider will be displayed on the user dashboard (My Moodle).';

// Settings: Login page tab.
$string['loginpagetab'] = 'Login page';
// ... Section: Login page layout.
$string['loginlayoutheading'] = 'Login page layout';
// ... ... Setting: Login page layout type.
$string['loginlayoutsetting'] = 'Login page layout type';
$string['loginlayoutsetting_desc'] = 'With this setting, you can choose the layout for the login page.';
$string['loginlayoutsetting_default'] = 'Default';
$string['loginlayoutsetting_splitscreen'] = 'Split screen (BG image left, login right)';
$string['loginoidc'] = 'Enable OpenIDC Login Button.';

// Settings: Highlights tab.
$string['highlightstab'] = 'Highlights';
// ... Section: Highlights settings.
$string['highlightsheading'] = 'Highlights';
$string['highlightsheading_desc'] = 'Configure up to 6 highlights to be displayed as tiles in the frontend.';

// Settings: Fonts tab.
$string['fontstab'] = 'Fonts';
// ... Section: Font 1.
$string['font1heading'] = 'Font 1';
$string['font1file'] = 'Font 1 file';
$string['font1file_desc'] = 'Upload a font file (WOFF, WOFF2, TTF, EOT, or SVG).';
$string['font1cssclasses'] = 'CSS classes/tags for Font 1';
$string['font1cssclasses_desc'] = 'Enter the CSS classes or HTML tags (comma-separated) that should use this font, e.g., h1,h2,h3,.custom-class';
// ... Section: Font 2.
$string['font2heading'] = 'Font 2';
$string['font2file'] = 'Font 2 file';
$string['font2file_desc'] = 'Upload a font file (WOFF, WOFF2, TTF, EOT, or SVG).';
$string['font2cssclasses'] = 'CSS classes/tags for Font 2';
$string['font2cssclasses_desc'] = 'Enter the CSS classes or HTML tags (comma-separated) that should use this font, e.g., h4,h5,h6,.another-class';
// ... ... Setting: Enable highlights.
$string['enablehighlights'] = 'Enable highlights';
$string['enablehighlights_desc'] = 'If checked, highlights will be displayed in the frontend.';
// ... ... Setting: Highlights dismissible.
$string['highlightsdismissible'] = 'Make highlights section dismissible';
$string['highlightsdismissible_desc'] = 'If checked, the entire highlights section will be dismissible. If a user clicks on the close button, the section will be hidden for this user permanently. If you want to reset the visibility of the highlights section, click the \'Reset visibility\' button below.';
// ... ... Setting: Reset dismissed highlights.
$string['highlightsdismissreset'] = 'Reset visibility of dismissed highlights';
$string['highlightsdismissresetbutton'] = 'Reset visibility of highlights';
$string['highlightsdismissconfirm'] = 'Do you really want to reset the visibility of the highlights section and want to re-show it for all users who have dismissed it?';
$string['highlightsdismissresetsuccess'] = 'The visibility of the highlights section has been reset';
$string['highlightsdismissresetfail'] = 'The visibility reset of the highlights section has failed for at least one user';
// ... Section: Highlights visibility.
$string['highlightsvisibilityheading'] = 'Highlights visibility';
// ... ... Setting: Show highlights on frontpage.
$string['showhighlightsonfrontpage'] = 'Show highlights on frontpage';
$string['showhighlightsonfrontpage_desc'] = 'If checked, the highlights will be displayed on the site frontpage.';
// ... ... Setting: Show highlights on dashboard.
$string['showhighlightsonDashboard'] = 'Show highlights on dashboard';
$string['showhighlightsonDashboard_desc'] = 'If checked, the highlights will be displayed on the user dashboard (My Moodle).';
// ... ... Settings: Highlight items.
$string['highlightenabled'] = 'Enable highlight';
$string['highlightenabled_desc'] = 'If checked, this highlight will be displayed.';
$string['highlightsectiontitle'] = 'Section headline';
$string['highlighticon'] = 'Icon file';
$string['highlighticon_desc'] = 'Upload an icon image file (SVG, JPG, JPEG, or PNG) for this highlight.';
$string['highlighttitle'] = 'Title';
$string['highlighttitle_desc'] = 'Enter the title for this highlight.';
$string['highlightdescription'] = 'Description';
$string['highlightdescription_desc'] = 'Enter the description for this highlight.';
$string['highlightlink'] = 'Link';
$string['highlightlink_desc'] = 'Enter the URL for this highlight tile (e.g., /course or https://example.com).';
$string['highlightcohortvisibility'] = 'Cohort visibility';
$string['highlightcohortvisibility_desc'] = 'Enter cohort IDs (comma-separated) that should be able to see this highlight. Leave empty to show to all users.';

// Miscellaneous
$string['or'] = 'or';
