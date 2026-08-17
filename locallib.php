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
 * Theme Boost Union Child - Local library
 *
 * @package    theme_boost_union_child
 * @copyright  2023 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/***************************************************************
 * EXTENSION POINT:
 * Add whatever Boost Union Child local functions you need here.
 **************************************************************/

/**
 * Get the highlight icon file URL from the filearea.
 *
 * @param int $highlightno The highlight number (1-6).
 * @return string|null The URL of the icon file, or null if not found.
 */
function theme_boost_union_child_get_highlight_icon_url($highlightno) {
    // If the highlight number is apparently not valid, return.
    if ($highlightno < 1 || $highlightno > 6) {
        return null;
    }

    // Get the icon config for this highlight.
    $iconconfig = get_config('theme_boost_union_child', 'highlight' . $highlightno . 'icon');

    // If an icon is configured.
    if (!empty($iconconfig)) {
        // Get the system context.
        $systemcontext = context_system::instance();

        // Get filearea.
        $fs = get_file_storage();

        // Get all files from filearea.
        $files = $fs->get_area_files(
            $systemcontext->id,
            'theme_boost_union_child',
            'highlight' . $highlightno . 'icon',
            false,
            'itemid',
            false
        );

        // Just pick the first file - we are sure that there is just one file.
        $file = reset($files);

        if ($file) {
            // Build and return the image URL.
            return \core\url::make_pluginfile_url(
                $file->get_contextid(),
                $file->get_component(),
                $file->get_filearea(),
                $file->get_itemid(),
                $file->get_filepath(),
                $file->get_filename()
            );
        }
    }

    // As no file was found, return null.
    return null;
}

/**
 * Reset the visibility of the dismissed highlights section.
 *
 * @return bool True if everything went fine, false if at least one user couldn't be resetted.
 */
function theme_boost_union_child_highlights_reset_visibility() {
    global $DB;

    // Get all users that have dismissed the highlights section once and therefore the user preference.
    $whereclause = 'name = :name AND value = :value';
    $params = ['name' => 'theme_boost_union_child_highlights_dismissed', 'value' => '1'];
    $users = $DB->get_records_select('user_preferences', $whereclause, $params, '', 'userid');

    // Initialize variable for feedback messages.
    $somethingwentwrong = false;

    foreach ($users as $user) {
        try {
            unset_user_preference('theme_boost_union_child_highlights_dismissed', $user->userid);
        } catch (coding_exception $e) {
            $somethingwentwrong = true;
        }
    }

    if (!$somethingwentwrong) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if the current user can see a highlight based on cohort visibility settings.
 *
 * @param int $highlightno The highlight number (1-6).
 * @return bool True if the user can see the highlight, false otherwise.
 */
function theme_boost_union_child_highlight_is_visible_for_user($highlightno) {
    global $CFG, $USER;

    // If the highlight number is apparently not valid, return true (show by default).
    if ($highlightno < 1 || $highlightno > 6) {
        return true;
    }

    // Get the cohort visibility configuration for this highlight.
    $cohortvisibility = get_config('theme_boost_union_child', 'highlight' . $highlightno . 'cohortvisibility');

    // If no cohort visibility is configured (empty string), show to all users.
    if (empty(trim($cohortvisibility))) {
        return true;
    }

    // If the user is not logged in, they can't be in any cohort, so don't show.
    if (!isloggedin() || isguestuser()) {
        return false;
    }

    // Require cohort library.
    require_once($CFG->dirroot . '/cohort/lib.php');

    // Get the user's cohorts.
    $usercohorts = cohort_get_user_cohorts($USER->id);

    // If the user has no cohorts and cohort visibility is configured, don't show.
    if (empty($usercohorts)) {
        return false;
    }

    // Extract cohort IDs from the user's cohorts.
    $usercohortids = array_keys($usercohorts);

    // Parse the configured cohort IDs (comma-separated).
    $allowedcohortids = explode(',', $cohortvisibility);
    $allowedcohortids = array_map('trim', $allowedcohortids);
    $allowedcohortids = array_filter($allowedcohortids);

    // If no valid cohort IDs are configured, show to all users.
    if (empty($allowedcohortids)) {
        return true;
    }

    // Check if the user is in any of the allowed cohorts.
    foreach ($allowedcohortids as $allowedid) {
        // Normalize the cohort ID (convert to int for comparison).
        $normalizedid = (int) $allowedid;
        if (in_array($normalizedid, $usercohortids)) {
            return true;
        }
    }

    // User is not in any of the allowed cohorts.
    return false;
}
