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
 * Theme Boost Union Child - slider layout include.
 *
 * This file extends the Boost Union slider functionality to support additional layouts.
 * 
 * Architecture Overview:
 *
 * This file serves as a layout INCLUDE and is not a standalone renderer.
 * Its purpose is to populate the `$templatecontext` variable with slider-related data,
 * such as `slidergeneralsettings` with layout configuration.
 *
 * The populated `$templatecontext` is then consumed by `layout/drawers.php`.
 * That file calls `render_from_template()` using either:
 *   - The template `'theme_boost/drawers'`, or
 *   - The template `'local_boost_union_mwp/drawers'`.
 *
 * These templates include the `{{> theme_boost_union/slider }}` partial,
 * which renders the actual HTML using the context prepared in this file.
 *
 * Note: The rendering process occurs in `drawers.php` (lines 259 or 264), not in this file.
 * This design pattern centralizes template context preparation in this file while delegating
 * the rendering call to the main layout file (`drawers.php`).
 *
 * @package   theme_boost_union_joanneum
 * @copyright 2026 Daniel Poggenpohl <daniel.poggenpohl@fernuni-hagen.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

// First, include the parent theme's slider functionality
require_once($CFG->dirroot . '/theme/boost_union/layout/includes/slider.php');

// Now extend the templatecontext with Boost Union Child specific slider settings
// Get Boost Union Child theme config
$childconfig = get_config('theme_boost_union_joanneum');

// Initialize layout settings
$templatecontext['slidergeneralsettings']->layout = 0;
$templatecontext['slidergeneralsettings']->islayouttextleft = false;

// Check if we have a slider layout setting from Boost Union Child
if (isset($childconfig->sliderlayout)) {
    // Set the layout flag for the template
    switch ($childconfig->sliderlayout) {
        case THEME_BOOST_union_joanneum_SETTING_SLIDER_LAYOUT_DEFAULT:
            // Default layout (no special handling needed)
            $templatecontext['slidergeneralsettings']->layout = 0;
            $templatecontext['slidergeneralsettings']->islayouttextleft = false;
            break;
        case THEME_BOOST_union_joanneum_SETTING_SLIDER_LAYOUT_TEXTLEFT_SOLID:
            // Text left with solid background and image right layout
            $templatecontext['slidergeneralsettings']->layout = 1;
            $templatecontext['slidergeneralsettings']->islayouttextleft = true;
            break;
        default:
            // Default to standard layout
            $templatecontext['slidergeneralsettings']->layout = 0;
            $templatecontext['slidergeneralsettings']->islayouttextleft = false;
    }
}

// Make sure the template uses the Boost Union Child slider template
// The template will be automatically selected based on the theme in use