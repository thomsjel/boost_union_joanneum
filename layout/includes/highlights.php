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
 * Theme Boost Union Child - highlights layout include.
 *
 * This file prepares the template context for the highlights tiles.
 *
 * Architecture Overview:
 *
 * This file serves as a layout INCLUDE and is not a standalone renderer.
 * Its purpose is to populate the `$templatecontext` variable with highlight-related data,
 * such as `showhighlights` and the `highlights` array.
 *
 * The populated `$templatecontext` is then consumed by `layout/drawers.php`.
 * That file calls `render_from_template()` using either:
 *   - The template `'theme_boost/drawers'`, or
 *   - The template `'local_boost_union_mwp/drawers'`.
 *
 * These templates include the `{{> theme_boost_union_joanneum/highlights }}` partial,
 * which renders the actual HTML using the context prepared in this file.
 *
 * Note: The rendering process occurs in `drawers.php` (lines 259 or 264), not in this file.
 * This design pattern centralizes template context preparation in this file while delegating
 * the rendering call to the main layout file (`drawers.php`).
 *
 * @package   theme_boost_union_joanneum
 * @copyright 2026 Thomas Kautz <thomas.kautz@fh-joanneum.at>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE;

// Require Boost Union Child locallib for helper functions.
require_once($CFG->dirroot . '/theme/boost_union_joanneum/locallib.php');

// Get Boost Union Child theme config.
$childconfig = get_config('theme_boost_union_joanneum');

// Initialize templatecontext if it doesn't exist in the current scope.
if (!isset($templatecontext)) {
    $templatecontext = [];
}

// Check if highlights are enabled.
$enablehighlights = isset($childconfig->enablehighlights) ? $childconfig->enablehighlights : 0;

// Check if highlights container is dismissible (but not on the login page as the user preference can't be stored there).
if ($PAGE->pagelayout != 'login') {
    $highlightsdismissible = isset($childconfig->highlightsdismissible) ? $childconfig->highlightsdismissible : 0;
    // If highlights are dismissible and the user has dismissed them, don't show them.
    // Note: get_user_preferences returns a string "1" or "0", not boolean true/false.
    if ($highlightsdismissible && get_user_preferences('theme_boost_union_joanneum_highlights_dismissed') != false) {
        $enablehighlights = false;
    }
} else {
    $highlightsdismissible = 0;
}

// Initialize the highlights array.
$highlights = [];

$isadmin = is_siteadmin($USER->id);

// Get all configured highlights (up to 6).
for ($i = 1; $i <= 6; $i++) {
    // Check if this highlight is enabled.
    $enabled = isset($childconfig->{'highlight' . $i . 'enabled'}) ? $childconfig->{'highlight' . $i . 'enabled'} : 0;

    if (!$isadmin) {
        // Skip if highlight is disabled.
        if (!$enabled) {
            continue;
        }

        // Check cohort visibility for this highlight.
        if (!theme_boost_union_joanneum_highlight_is_visible_for_user($i)) {
            continue;
        }
    }

    $title = isset($childconfig->{'highlight' . $i . 'title'}) ? $childconfig->{'highlight' . $i . 'title'} : '';
    $description = isset($childconfig->{'highlight' . $i . 'description'}) ? $childconfig->{'highlight' . $i . 'description'} : '';
    $link = isset($childconfig->{'highlight' . $i . 'link'}) ? $childconfig->{'highlight' . $i . 'link'} : '';

    // Only add highlights that have at least a title or description.
    if (!empty($title) || !empty($description)) {
        // Get the icon file URL.
        $iconurl = theme_boost_union_joanneum_get_highlight_icon_url($i);

        // Check if link should open in new tab (for external links).
        $linktargetnewtab = false;
        if (!empty($link) && (strpos($link, 'http://') === 0 || strpos($link, 'https://') === 0)) {
            $linktargetnewtab = true;
        }

        $highlights[] = [
            'iconurl' => $iconurl,
            'title' => $title,
            'description' => $description,
            'link' => $link,
            'linktargetnewtab' => $linktargetnewtab,
        ];
    }
}

// Prepare the template context - always set showhighlights for the template.
$templatecontext['showhighlights'] = $enablehighlights && !empty($highlights);
if ($templatecontext['showhighlights']) {
    $templatecontext['highlights'] = $highlights;
    $templatecontext['highlightsectiontitle'] = $childconfig->highlightsectiontitle;
    $templatecontext['issinglehighlight'] = (count($highlights) === 1);
    $templatecontext['highlightsdismissible'] = $highlightsdismissible;
    // Set the position flag to render highlights after the slider.
    $templatecontext['highlightspositionafter'] = true;
    $templatecontext['highlights_wrapper_id'] = 'theme_boost_union_joanneum_highlights_wrapper';
    $templatecontext['highlights_close_id'] = 'theme_boost_union_joanneum_highlights_close';

    // Add the dismissible AMD module to the page if highlights are dismissible.
    if ($highlightsdismissible == true) {
        $PAGE->requires->js_call_amd('theme_boost_union_joanneum/highlights', 'init');
    }
}
