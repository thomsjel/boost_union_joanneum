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
 * Theme Boost Union Child - Library
 *
 * @package    theme_boost_union_joanneum
 * @copyright  2026 Thomas Kautz <thomas.kautz@fh-joanneum.at>,
 *             Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de>
 *             and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Constants which are use throughout this theme.
define('THEME_BOOST_UNION_JOANNEUM_SETTING_INHERITANCE_INHERIT', 0);
define('THEME_BOOST_UNION_JOANNEUM_SETTING_INHERITANCE_DUPLICATE', 1);

// Constants for slider layout options.
define('THEME_BOOST_UNION_JOANNEUM_SETTING_SLIDER_LAYOUT_DEFAULT', 0);
define('THEME_BOOST_UNION_JOANNEUM_SETTING_SLIDER_LAYOUT_TEXTLEFT_SOLID', 1);

// Constants for login page layout options.
define('THEME_BOOST_UNION_JOANNEUM_SETTING_LOGIN_LAYOUT_DEFAULT', 'default');
define('THEME_BOOST_UNION_JOANNEUM_SETTING_LOGIN_LAYOUT_SPLIT_SCREEN', 'splitscreen');

/**
 * Serves files from the theme_boost_union_joanneum file areas.
 *
 * @param stdClass $course
 * @param stdClass $cm
 * @param context $context
 * @param string $filearea
 * @param array $args
 * @param bool $forcedownload
 * @param array $options
 * @return bool|null False to stop file serving, or null to continue
 */
function theme_boost_union_joanneum_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = []) {
    global $CFG;

    // Define allowed fileareas - add new ones here as needed.
    $allowedfileareas = [
        'highlight1icon', 'highlight2icon', 'highlight3icon',
        'highlight4icon', 'highlight5icon', 'highlight6icon',
        'font1', 'font2',
        // Add future fileareas here, e.g.:
        // 'customfont1', 'customfont2', 'backgroundimage', etc.
    ];

    // Check if this filearea is allowed.
    if (!in_array($filearea, $allowedfileareas)) {
        return false;
    }

    // Only allow access to system context files.
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }

    // Anyone, including guests and non-logged in users, can view these files.
    $options = ['cacheability' => 'public'];

    // Find the original file.
    $fs = get_file_storage();
    $itemid = clean_param(array_shift($args), PARAM_INT);
    $filename = clean_param(array_shift($args), PARAM_FILE);

    // Get the file from the file storage.
    $file = $fs->get_file(
        $context->id,
        'theme_boost_union_joanneum',
        $filearea,
        $itemid,
        '/',
        $filename
    );

    if (!$file) {
        send_file_not_found();
    }

    // Send the file.
    send_stored_file($file, 0, 0, true, $options);
    return true;
}

/**
 * Returns the main SCSS content.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_joanneum_get_main_scss_content($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // As a start, get the compiled main SCSS from Boost Union.
    // This way, Boost Union Child will ship the same SCSS code as Boost Union itself.
    $scss = theme_boost_union_get_main_scss_content(\core\output\theme_config::load('boost_union'));

    // And add Boost Union Child's main SCSS file to the stack.
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union_joanneum/scss/post.scss');

    return $scss;
}

/**
 * Get SCSS to prepend.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_joanneum_get_pre_scss($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // As a start, initialize the Pre SCSS code with an empty string.
    $scss = '';

    // Then, if configured, get the compiled pre SCSS code from Boost Union.
    // This should not be necessary as Moodle core calls the *_get_pre_scss() functions from all parent themes as well.
    // However, as soon as Boost Union would use $theme->settings in this function, $theme would be this theme here and
    // not Boost Union. The Boost Union developers are aware of this topic, but faults can always happen.
    // If such a fault happens, the Boost Union Child administrator can switch the inheritance to 'Duplicate'.
    // This way, we will add the pre SCSS code with the explicit use of the Boost Union configuration to the stack.
    $inheritanceconfig = get_config('theme_boost_union_joanneum', 'prescssinheritance');
    if ($inheritanceconfig == THEME_BOOST_UNION_JOANNEUM_SETTING_INHERITANCE_DUPLICATE) {
        $scss .= theme_boost_union_get_pre_scss(\core\output\theme_config::load('boost_union'));
    }

    // And add Boost Union Child's pre SCSS file to the stack.
    $scss .= file_get_contents($CFG->dirroot . '/theme/boost_union_joanneum/scss/pre.scss');

    /**********************************************************
     * EXTENSION POINT:
     * Compose and add additional pre-SCSS code here.
     * It will be added on top of Boost Union's pre-SCSS code.
     *********************************************************/

    // Add custom font CSS.
    $scss .= theme_boost_union_joanneum_get_font_scss($theme);

    return $scss;
}

/**
 * Generate SCSS for custom fonts.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_joanneum_get_font_scss($theme) {
    $scss = '';

    $fs = get_file_storage();
    $context = context_system::instance();

    // Process Font 1.
    $font1file = get_config('theme_boost_union_joanneum', 'font1file');
    $font1cssclasses = get_config('theme_boost_union_joanneum', 'font1cssclasses');

    if (!empty($font1file) && !empty($font1cssclasses)) {
        // Get the font file URL.
        $files = $fs->get_area_files($context->id, 'theme_boost_union_joanneum', 'font1', 0, '', false);

        foreach ($files as $file) {
            if ($file->get_filename() !== '.') {
                $fonturl = moodle_url::make_pluginfile_url(
                    $context->id,
                    'theme_boost_union_joanneum',
                    'font1',
                    0,
                    '/',
                    $file->get_filename()
                );

                // Extract font family name from filename (without extension).
                $filename = $file->get_filename();
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                // Sanitize the basename to be a valid CSS font family name.
                $basename = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $basename);
                $fontfamily = 'font1-' . $basename;

                // Generate @font-face rule.
                $scss .= "@font-face {\n";
                $scss .= "    font-family: '$fontfamily';\n";
                $scss .= "    src: url('$fonturl');\n";
                $scss .= "}\n\n";

                // Apply to specified CSS classes/tags.
                $selectors = explode(',', $font1cssclasses);
                $selectors = array_map('trim', $selectors);
                $selectors = array_filter($selectors);

                if (!empty($selectors)) {
                    $selectorstring = implode(', ', $selectors);
                    $scss .= "$selectorstring {\n";
                    $scss .= "    font-family: '$fontfamily', sans-serif !important;\n";
                    $scss .= "}\n\n";
                }
                break; // Only process the first file.
            }
        }
    }

    // Process Font 2.
    $font2file = get_config('theme_boost_union_joanneum', 'font2file');
    $font2cssclasses = get_config('theme_boost_union_joanneum', 'font2cssclasses');

    if (!empty($font2file) && !empty($font2cssclasses)) {
        // Get the font file URL.
        $files = $fs->get_area_files($context->id, 'theme_boost_union_joanneum', 'font2', 0, '', false);

        foreach ($files as $file) {
            if ($file->get_filename() !== '.') {
                $fonturl = moodle_url::make_pluginfile_url(
                    $context->id,
                    'theme_boost_union_joanneum',
                    'font2',
                    0,
                    '/',
                    $file->get_filename()
                );

                // Extract font family name from filename (without extension).
                $filename = $file->get_filename();
                $basename = pathinfo($filename, PATHINFO_FILENAME);
                // Sanitize the basename to be a valid CSS font family name.
                $basename = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $basename);
                $fontfamily = 'font2-' . $basename;

                // Generate @font-face rule.
                $scss .= "@font-face {\n";
                $scss .= "    font-family: '$fontfamily';\n";
                $scss .= "    src: url('$fonturl');\n";
                $scss .= "}\n\n";

                // Apply to specified CSS classes/tags.
                $selectors = explode(',', $font2cssclasses);
                $selectors = array_map('trim', $selectors);
                $selectors = array_filter($selectors);

                if (!empty($selectors)) {
                    $selectorstring = implode(', ', $selectors);
                    $scss .= "$selectorstring {\n";
                    $scss .= "    font-family: '$fontfamily', sans-serif !important;\n";
                    $scss .= "}\n\n";
                }
                break; // Only process the first file.
            }
        }
    }

    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param \core\output\theme_config $theme The theme config object.
 * @return string
 */
function theme_boost_union_joanneum_get_extra_scss($theme) {
    global $CFG;

    // Require the necessary libraries.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // As a start, initialize the Extra SCSS code with an empty string.
    $scss = '';

    // Then, if configured, get the compiled extra SCSS code from Boost Union.
    // This should not be necessary as Moodle core calls the *_get_extra_scss() functions from all parent themes as well.
    // However, as soon as Boost Union would use $theme->settings in this function, $theme would be this theme here and
    // not Boost Union. The Boost Union developers are aware of this topic, but faults can always happen.
    // If such a fault happens, the Boost Union Child administrator can switch the inheritance to 'Duplicate'.
    // This way, we will add the extra SCSS code with the explicit use of the Boost Union configuration to the stack.
    $inheritanceconfig = get_config('theme_boost_union_joanneum', 'extrascssinheritance');
    if ($inheritanceconfig == THEME_BOOST_UNION_JOANNEUM_SETTING_INHERITANCE_DUPLICATE) {
        $scss .= theme_boost_union_get_extra_scss(\core\output\theme_config::load('boost_union'));
    }

    /**********************************************************
     * EXTENSION POINT:
     * Compose and add additional SCSS code here.
     * It will be added on top of Boost Union's SCSS code.
     *********************************************************/

    return $scss;
}

/**
 * Callback function for theme_boost_union to allow Boost Union Child to add cards to the Boost Union settings overview page.
 * This function is expected to return an array of arrays containing values with the keys 'label', 'desc', 'btn' and 'url'.
 *
 * @return array
 */
function theme_boost_union_joanneum_extend_busettingsoverview() {

    $cards[] = [
        'label' => get_string('pluginname', 'theme_boost_union_joanneum'),
        'desc' => get_string('settingsoverview_buc_desc', 'theme_boost_union_joanneum'),
        'btn' => 'primary',
        'url' => new \core\url('/admin/settings.php', ['section' => 'theme_boost_union_joanneum']),
    ];

    return $cards;
}

/**
 * Define preferences which may be set via the core_user_set_user_preferences external function.
 *
 * @uses \core\user::is_current_user
 *
 * @return array[]
 */
function theme_boost_union_joanneum_user_preferences(): array {
    return [
        'theme_boost_union_joanneum_highlights_dismissed' => [
            'type' => PARAM_INT,
            'null' => NULL_NOT_ALLOWED,
            'default' => 0,
            'choices' => [0, 1],
            'permissioncallback' => [\core\user::class, 'is_current_user'],
        ],
    ];
}

/**
 * Callback function which allows themes to alter the CSS URLs.
 * We use this function to change the CSS URL to the flavour CSS URL if a flavour applies to the current page.
 *
 * @copyright 2024 Alexander Bias <bias@alexanderbias.de>
 *
 * @param mixed $urls The CSS URLs (passed as reference).
 */
function theme_boost_union_joanneum_alter_css_urls(&$urls) {
    global $CFG;

    // Require Boost Union library.
    require_once($CFG->dirroot . '/theme/boost_union/lib.php');

    // Call Boost Union's theme_boost_union_alter_css_urls() function which implements the logic to change the CSS URL for flavours.
    theme_boost_union_alter_css_urls($urls);
}
