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
 * Theme Boost Union Child - Settings file
 *
 * @package    theme_boost_union_child
 * @copyright  2023 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use theme_boost_union\admin_settingspage_tabs_with_tertiary;

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig || has_capability('theme/boost_union:configure', context_system::instance())) {
    // How this file works:
    // Boost Union's settings are divided into multiple settings pages which resides in its own settings category.
    // You will understand it as soon as you look at /theme/boost_union/settings.php.
    // This settings file here is built in a way that it adds another settings page to this existing settings
    // category. You can add all child-theme-specific settings to this settings page here.

    // However, there is still the $settings variable which is expected by Moodle core to be filled with the theme
    // settings and which is automatically linked from the theme selector page.
    // To avoid that there appears a broken "Boost Union Child" settings page, we redirect the user to a settings
    // overview page if he opens this page.
    $mainsettingspageurl = new \core\url('/admin/settings.php', ['section' => 'themesettingboost_union_child']);
    if ($ADMIN->fulltree && $PAGE->has_set_url() && $PAGE->url->compare($mainsettingspageurl)) {
        redirect(new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_child']));
    }

    // Create empty settings page structure to make the site administration work on non-admin pages.
    if (!$ADMIN->fulltree) {
        // Create Boost Union Child settings page
        // (and allow users with the theme/boost_union:configure capability to access it).
        $tab = new admin_settingpage(
            'theme_boost_union_child',
            get_string('configtitle', 'theme_boost_union_child', null, true),
            'theme/boost_union:configure'
        );
        $ADMIN->add('theme_boost_union', $tab);

        // Create full settings page structure.
        // phpcs:disable moodle.ControlStructures.ControlSignature.Found
    } else if ($ADMIN->fulltree) {
        // Require the necessary libraries.
        require_once($CFG->dirroot . '/theme/boost_union/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
        require_once($CFG->dirroot . '/theme/boost_union_child/lib.php');
        require_once($CFG->dirroot . '/theme/boost_union_child/locallib.php');

        // Prepare options array for select settings.
        // Due to MDL-58376, we will use binary select settings instead of checkbox settings throughout this theme.
        $yesnooption = [THEME_BOOST_UNION_SETTING_SELECT_YES => get_string('yes'),
                THEME_BOOST_UNION_SETTING_SELECT_NO => get_string('no'), ];


        // Create Boost Union Child settings page with tabs and tertiary navigation
        // (and allow users with the theme/boost_union:configure capability to access it).
        $page = new admin_settingspage_tabs_with_tertiary(
            'theme_boost_union_child',
            get_string('configtitle', 'theme_boost_union_child', null, true),
            'theme/boost_union:configure'
        );


        // Create general settings tab.
        $tab = new admin_settingpage(
            'theme_boost_union_child_general',
            get_string('generalsettings', 'theme_boost', null, true)
        );

        // Create inheritance heading.
        $name = 'theme_boost_union_child/inheritanceheading';
        $title = get_string('inheritanceheading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Prepare inheritance options.
        $inheritanceoptions = [
                THEME_BOOST_UNION_CHILD_SETTING_INHERITANCE_INHERIT =>
                        get_string('inheritanceinherit', 'theme_boost_union_child'),
                THEME_BOOST_UNION_CHILD_SETTING_INHERITANCE_DUPLICATE =>
                        get_string('inheritanceduplicate', 'theme_boost_union_child'),
        ];

        // Setting: Pre SCSS inheritance setting.
        $name = 'theme_boost_union_child/prescssinheritance';
        $title = get_string('prescssinheritancesetting', 'theme_boost_union_child', null, true);
        $description = get_string('prescssinheritancesetting_desc', 'theme_boost_union_child', null, true) . '<br />' .
                get_string('inheritanceoptionsexplanation', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            THEME_BOOST_UNION_CHILD_SETTING_INHERITANCE_INHERIT,
            $inheritanceoptions
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Extra SCSS inheritance setting.
        $name = 'theme_boost_union_child/extrascssinheritance';
        $title = get_string('extrascssinheritancesetting', 'theme_boost_union_child', null, true);
        $description = get_string('extrascssinheritancesetting_desc', 'theme_boost_union_child', null, true) . '<br />' .
                get_string('inheritanceoptionsexplanation', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            THEME_BOOST_UNION_CHILD_SETTING_INHERITANCE_INHERIT,
            $inheritanceoptions
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Create cohort settings heading.
        $name = 'theme_boost_union_child/custommenuitemsheading';
        $title = get_string('custommenuitemsheading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Custom menu items with cohort support.
        $name = 'theme_boost_union_child/custommenuitems';
        $title = get_string('custommenuitems', 'theme_boost_union_child', null, true);
        $description = get_string('custommenuitems_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configtextarea(
            $name,
            $title,
            $description,
            '',
            PARAM_RAW,
            '60',
            '10'
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);

        /**********************************************************
         * EXTENSION POINT:
         * Add your Boost Union Child settings here.
         *********************************************************/

        // Tab: Slider.
        $tab = new admin_settingpage(
            'theme_boost_union_child_slider',
            get_string('slidertab', 'theme_boost_union_child', null, true)
        );

        // Add reference to Boost Union Slider section.
        $sliderurl = new \core\url(
            '/admin/settings.php',
            ['section' => 'theme_boost_union_content']
        );
        $sliderurl->set_anchor('theme_boost_union_slider');
        $reference = get_string('sliderreference', 'theme_boost_union_child', $sliderurl->out(), true);
        $setting = new admin_setting_heading(
            'theme_boost_union_child/sliderreference',
            '',
            $reference
        );
        $tab->add($setting);

        // Heading: Slider layout.
        $name = 'theme_boost_union_child/sliderlayoutheading';
        $title = get_string('sliderlayoutheading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Slider layout type.
        $sliderlayoutoptions = [
                THEME_BOOST_UNION_CHILD_SETTING_SLIDER_LAYOUT_DEFAULT =>
                        get_string('sliderlayoutsetting_default', 'theme_boost_union_child'),
                THEME_BOOST_UNION_CHILD_SETTING_SLIDER_LAYOUT_TEXTLEFT_SOLID =>
                        get_string('sliderlayoutsetting_textleft_solid', 'theme_boost_union_child'),
        ];
        $name = 'theme_boost_union_child/sliderlayout';
        $title = get_string('sliderlayoutsetting', 'theme_boost_union_child', null, true);
        $description = get_string('sliderlayoutsetting_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            THEME_BOOST_UNION_CHILD_SETTING_SLIDER_LAYOUT_DEFAULT,
            $sliderlayoutoptions
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Heading: Slider visibility.
        $name = 'theme_boost_union_child/slidervisibilityheading';
        $title = get_string('slidervisibilityheading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Show slider on frontpage.
        $name = 'theme_boost_union_child/showslideronfrontpage';
        $title = get_string('showslideronfrontpage', 'theme_boost_union_child', null, true);
        $description = get_string('showslideronfrontpage_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configcheckbox(
            $name,
            $title,
            $description,
            0
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Show slider on dashboard.
        $name = 'theme_boost_union_child/showsliderondashboard';
        $title = get_string('showsliderondashboard', 'theme_boost_union_child', null, true);
        $description = get_string('showsliderondashboard_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configcheckbox(
            $name,
            $title,
            $description,
            0
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);

        // Tab: Login page.
        $tab = new admin_settingpage(
            'theme_boost_union_child_login',
            get_string('loginpagetab', 'theme_boost_union_child', null, true)
        );

        // Heading: Login page layout.
        $name = 'theme_boost_union_child/loginlayoutheading';
        $title = get_string('loginlayoutheading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Login page layout type.
        $loginlayoutoptions = [
                THEME_BOOST_UNION_CHILD_SETTING_LOGIN_LAYOUT_DEFAULT =>
                        get_string('loginlayoutsetting_default', 'theme_boost_union_child'),
                THEME_BOOST_UNION_CHILD_SETTING_LOGIN_LAYOUT_SPLIT_SCREEN =>
                        get_string('loginlayoutsetting_splitscreen', 'theme_boost_union_child'),
        ];
        $name = 'theme_boost_union_child/loginlayout';
        $title = get_string('loginlayoutsetting', 'theme_boost_union_child', null, true);
        $description = get_string('loginlayoutsetting_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configselect(
            $name,
            $title,
            $description,
            THEME_BOOST_UNION_CHILD_SETTING_LOGIN_LAYOUT_DEFAULT,
            $loginlayoutoptions
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Show OPENIDC Login Button
        $name = 'theme_boost_union_child/enableoidclogin';
        $setting = new admin_setting_configcheckbox(
            $name,  
            get_string('loginoidc', 'theme_boost_union_child', null, true),
            '',
            0              
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        $page->add($tab);

        // Tab: Highlights.
        $tab = new admin_settingpage(
            'theme_boost_union_child_highlights',
            get_string('highlightstab', 'theme_boost_union_child', null, true)
        );

        // Heading: Highlights.
        $name = 'theme_boost_union_child/highlightsheading';
        $title = get_string('highlightsheading', 'theme_boost_union_child', null, true);
        $description = get_string('highlightsheading_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, $description);
        $tab->add($setting);

        // Setting: Enable highlights.
        $name = 'theme_boost_union_child/enablehighlights';
        $title = get_string('enablehighlights', 'theme_boost_union_child', null, true);
        $description = get_string('enablehighlights_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configcheckbox(
            $name,
            $title,
            $description,
            0
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Highlights dismissible.
        $name = 'theme_boost_union_child/highlightsdismissible';
        $title = get_string('highlightsdismissible', 'theme_boost_union_child', null, true);
        $description = get_string('highlightsdismissible_desc', 'theme_boost_union_child', null, true);
        // Add Reset button if the highlights section is already configured to be dismissible.
        if (get_config('theme_boost_union_child', 'highlightsdismissible') == true) {
            $reseturl = new \core\url(
                '/theme/boost_union_child/settings_highlights_resetdismissed.php',
                ['sesskey' => sesskey()]
            );
            $description .= \core\output\html_writer::empty_tag('br');
            $description .= \core\output\html_writer::link(
                $reseturl,
                get_string('highlightsdismissresetbutton', 'theme_boost_union_child'),
                ['class' => 'btn btn-secondary mt-3', 'role' => 'button']
            );
        }
        $setting = new admin_setting_configcheckbox(
            $name,
            $title,
            $description,
            0
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Highlight section title.
        $name = 'theme_boost_union_child/highlightsectiontitle';
        $title = get_string('highlightsectiontitle', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configtext(
            $name,
            $title,
            '',
            '',
            PARAM_TEXT
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Heading: Highlights visibility.
        $name = 'theme_boost_union_child/highlightsvisibilityheading';
        $title = get_string('highlightsvisibilityheading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Show highlights on frontpage.
        $name = 'theme_boost_union_child/showhighlightsonfrontpage';
        $title = get_string('showhighlightsonfrontpage', 'theme_boost_union_child', null, true);
        $description = get_string('showhighlightsonfrontpage_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configcheckbox(
            $name,
            $title,
            $description,
            0
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Show highlights on dashboard.
        $name = 'theme_boost_union_child/showhighlightsonDashboard';
        $title = get_string('showhighlightsonDashboard', 'theme_boost_union_child', null, true);
        $description = get_string('showhighlightsonDashboard_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configcheckbox(
            $name,
            $title,
            $description,
            0
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add settings for up to 6 highlights.
        for ($i = 1; $i <= 6; $i++) {
            // Heading for each highlight.
            $name = 'theme_boost_union_child/highlight' . $i . 'heading';
            $title = "Highlight $i";
            $setting = new admin_setting_heading($name, $title, null);
            $tab->add($setting);

            // Setting: Enable highlight.
            $name = 'theme_boost_union_child/highlight' . $i . 'enabled';
            $title = get_string('highlightenabled', 'theme_boost_union_child', null, true);
            $description = get_string('highlightenabled_desc', 'theme_boost_union_child', null, true);
            $setting = new admin_setting_configcheckbox(
                $name,
                $title,
                $description,
                0
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);

            // Setting: Highlight icon.
            $name = 'theme_boost_union_child/highlight' . $i . 'icon';
            $title = get_string('highlighticon', 'theme_boost_union_child', null, true);
            $description = get_string('highlighticon_desc', 'theme_boost_union_child', null, true);
            $setting = new admin_setting_configstoredfile(
                $name,
                $title,
                $description,
                'highlight' . $i . 'icon',
                0,
                ['maxfiles' => 1, 'accepted_types' => ['.svg', '.png', '.jpg', '.jpeg', '.gif']]
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);

            // Setting: Highlight title.
            $name = 'theme_boost_union_child/highlight' . $i . 'title';
            $title = get_string('highlighttitle', 'theme_boost_union_child', null, true);
            $description = get_string('highlighttitle_desc', 'theme_boost_union_child', null, true);
            $setting = new admin_setting_configtext(
                $name,
                $title,
                $description,
                '',
                PARAM_TEXT
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);

            // Setting: Highlight description.
            $name = 'theme_boost_union_child/highlight' . $i . 'description';
            $title = get_string('highlightdescription', 'theme_boost_union_child', null, true);
            $description = get_string('highlightdescription_desc', 'theme_boost_union_child', null, true);
            $setting = new admin_setting_configtextarea(
                $name,
                $title,
                $description,
                '',
                PARAM_TEXT,
                '60',
                '4'
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);

            // Setting: Highlight link.
            $name = 'theme_boost_union_child/highlight' . $i . 'link';
            $title = get_string('highlightlink', 'theme_boost_union_child', null, true);
            $description = get_string('highlightlink_desc', 'theme_boost_union_child', null, true);
            $setting = new admin_setting_configtext(
                $name,
                $title,
                $description,
                '',
                PARAM_URL
            );
            $setting->set_updatedcallback('theme_reset_all_caches');
            $tab->add($setting);
        }

        // Add tab to settings page.
        $page->add($tab);

        // Tab: Fonts.
        $tab = new admin_settingpage(
            'theme_boost_union_child_fonts',
            get_string('fontstab', 'theme_boost_union_child', null, true)
        );

        // Heading: Font 1.
        $name = 'theme_boost_union_child/font1heading';
        $title = get_string('font1heading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Font 1 file upload.
        $name = 'theme_boost_union_child/font1file';
        $title = get_string('font1file', 'theme_boost_union_child', null, true);
        $description = get_string('font1file_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configstoredfile(
            $name,
            $title,
            $description,
            'font1',
            0,
            ['maxfiles' => 1, 'accepted_types' => ['.woff', '.woff2', '.ttf', '.eot', '.svg', '.otf']]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Font 1 CSS classes/tags.
        $name = 'theme_boost_union_child/font1cssclasses';
        $title = get_string('font1cssclasses', 'theme_boost_union_child', null, true);
        $description = get_string('font1cssclasses_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configtext(
            $name,
            $title,
            $description,
            '',
            PARAM_TEXT
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Heading: Font 2.
        $name = 'theme_boost_union_child/font2heading';
        $title = get_string('font2heading', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_heading($name, $title, null);
        $tab->add($setting);

        // Setting: Font 2 file upload.
        $name = 'theme_boost_union_child/font2file';
        $title = get_string('font2file', 'theme_boost_union_child', null, true);
        $description = get_string('font2file_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configstoredfile(
            $name,
            $title,
            $description,
            'font2',
            0,
            ['maxfiles' => 1, 'accepted_types' => ['.woff', '.woff2', '.ttf', '.eot', '.svg', '.otf']]
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Setting: Font 2 CSS classes/tags.
        $name = 'theme_boost_union_child/font2cssclasses';
        $title = get_string('font2cssclasses', 'theme_boost_union_child', null, true);
        $description = get_string('font2cssclasses_desc', 'theme_boost_union_child', null, true);
        $setting = new admin_setting_configtext(
            $name,
            $title,
            $description,
            '',
            PARAM_TEXT
        );
        $setting->set_updatedcallback('theme_reset_all_caches');
        $tab->add($setting);

        // Add tab to settings page.
        $page->add($tab);

        // Add settings page to the admin settings category.
        $ADMIN->add('theme_boost_union', $page);
    }
}
