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
 * Theme Boost Union Child Login - Login page layout.
 *
 * This layout extends theme/boost_union/layout/login.php
 *
 * @package   theme_boost_union_child
 * @copyright  2026 Thomas Kautz <thomas.kautz@fh-joanneum.at>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

// Get the login layout setting from theme_boost_union_child.
$loginlayout = get_config('theme_boost_union_child', 'loginlayout');

// Handle different login layouts.
if ($loginlayout === THEME_BOOST_UNION_CHILD_SETTING_LOGIN_LAYOUT_SPLIT_SCREEN) {
    // Split screen layout.
    $bodyattributes = $OUTPUT->body_attributes();
    [$loginbackgroundimagetext, $loginbackgroundimagetextcolor] = theme_boost_union_get_loginbackgroundimage_text();

    // Prepare template context.
    $templatecontext = [
        'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
        'output' => $OUTPUT,
        'bodyattributes' => $bodyattributes,
        'loginbackgroundimagetext' => $loginbackgroundimagetext,
        'loginbackgroundimagetextcolor' => $loginbackgroundimagetextcolor,
    ];

    // Add login container classes based on parent theme settings.
    $templatecontext['loginwrapperclass'] = 'login-wrapper-' . get_config('theme_boost_union', 'loginformposition');
    $templatecontext['logincontainerclass'] = (
        get_config('theme_boost_union', 'loginformtransparency') == THEME_BOOST_UNION_SETTING_SELECT_YES
    ) ? 'login-container-80t' : '';

    // For split screen layout, we need to get the background image URL directly.
    // We'll use Boost Union's functions to get the files and generate the URL.
    require_once($CFG->dirroot . '/theme/boost_union/locallib.php');
    
    $loginbackgroundimageurl = '';
    $files = theme_boost_union_get_loginbackgroundimage_files();
    
    // If files exist, get the URL for the first one (or random if available).
    if (!empty($files)) {
        $file = reset($files); // Get the first file as fallback.
        $loginbackgroundimageurl = core\url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_itemid(),
            $file->get_filepath(),
            $file->get_filename()
        )->out();
    }
    
    $templatecontext['loginbackgroundimageurl'] = $loginbackgroundimageurl;

    // Include the template content for the footnote.
    require_once($CFG->dirroot . '/theme/boost_union/layout/includes/footnote.php');

    // Include the template content for the static pages.
    require_once($CFG->dirroot . '/theme/boost_union/layout/includes/staticpages.php');

    // Include the template content for the accessibility pages.
    require_once($CFG->dirroot . '/theme/boost_union/layout/includes/accessibilitypages.php');

    // Include the template content for the footer button.
    require_once($CFG->dirroot . '/theme/boost_union/layout/includes/footer.php');

    // Include the template content for the info banners.
    require_once($CFG->dirroot . '/theme/boost_union/layout/includes/infobanners.php');
    
    // Render the split screen template.
    echo $OUTPUT->render_from_template('theme_boost_union_child/login_splitscreen', $templatecontext);
} else {
    // Default layout - delegate to parent theme's login.php to ensure all functionality is preserved.
    require_once($CFG->dirroot . '/theme/boost_union/layout/login.php');
}