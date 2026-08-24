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
 * Local library for the Boost Union Joanneum theme.
 *
 * This file contains utility functions for theme-specific features including
 * highlight icon management and cohort-based visibility controls.
 *
 * @package    theme_boost_union_joanneum
 * @copyright  2026 Thomas Kautz <thomas.kautz@fh-joanneum.at> and Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/***************************************************************
 * EXTENSION POINT:
 * This section is reserved for adding custom local functions specific to
 * the Boost Union Child theme or its derivatives.
 **************************************************************/

/**
 * Get the highlight icon file URL from the file area.
 *
 * This function retrieves the URL for a highlight icon stored in the theme's file area.
 * It validates the highlight number, checks the configuration, and constructs the
 * pluginfile URL for the stored icon file.
 *
 * @param int $highlightno The highlight number (1-6).
 * @return string|null The URL of the icon file, or null if not found.
 */
function theme_boost_union_joanneum_get_highlight_icon_url($highlightno) {
    // If highlight number is invalid (not in range 1-6), return null.
    if ($highlightno < 1 || $highlightno > 6) {
        return null;
    }

    // Retrieve the icon configuration setting for this highlight.
    $iconconfig = get_config('theme_boost_union_joanneum', 'highlight' . $highlightno . 'icon');

    // If an icon is configured for this highlight, process it.
    if (!empty($iconconfig)) {
        // Get the system context instance.
        $systemcontext = context_system::instance();

        // Get the Moodle file storage instance.
        $fs = get_file_storage();

        // Retrieve all files from the highlight icon file area.
        $files = $fs->get_area_files(
            $systemcontext->id,
            'theme_boost_union_joanneum',
            'highlight' . $highlightno . 'icon',
            false,
            'itemid',
            false
        );

        // Use the first file from the file area (only one file is expected per highlight).
        $file = reset($files);

        if ($file) {
            // Construct and return the pluginfile URL for the icon.
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

    // Return null if no icon file was found.
    return null;
}

/**
 * Reset the visibility of the dismissed highlights section for all users.
 *
 * This function clears the user preference that tracks whether users have dismissed
 * the highlights section, effectively making it visible again for all users.
 *
 * @return bool True if all users were reset successfully, false if any error occurred.
 */
function theme_boost_union_joanneum_highlights_reset_visibility() {
    global $DB;

    // Retrieve all users who have dismissed the highlights section (preference is set to 1).
    $whereclause = 'name = :name AND value = :value';
    $params = ['name' => 'theme_boost_union_joanneum_highlights_dismissed', 'value' => '1'];
    $users = $DB->get_records_select('user_preferences', $whereclause, $params, '', 'userid');

    // Track whether any errors occurred during reset.
    $somethingwentwrong = false;

    // For each user, attempt to remove their dismissal preference.
    foreach ($users as $user) {
        try {
            unset_user_preference('theme_boost_union_joanneum_highlights_dismissed', $user->userid);
        } catch (coding_exception $e) {
            $somethingwentwrong = true;
        }
    }

    // Return true if all users were reset successfully, false otherwise.
    if (!$somethingwentwrong) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if the current user can see a highlight based on cohort visibility settings.
 *
 * This function determines whether the current user should see a specific highlight
 * by checking if they belong to any of the cohorts configured for that highlight.
 *
 * @param int $highlightno The highlight number (1-6).
 * @return bool True if the user can see the highlight, false otherwise.
 */
function theme_boost_union_joanneum_highlight_is_visible_for_user($highlightno) {
    global $CFG, $USER;

    // If highlight number is invalid (not in range 1-6), show by default.
    if ($highlightno < 1 || $highlightno > 6) {
        return true;
    }

    // Retrieve the cohort visibility setting for this highlight.
    $cohortvisibility = get_config('theme_boost_union_joanneum', 'highlight' . $highlightno . 'cohortvisibility');

    // If no cohort visibility is configured (empty string), show to all users.
    if (empty(trim($cohortvisibility))) {
        return true;
    }

    // If the user is not logged in or is a guest, they cannot belong to any cohort, so hide the highlight.
    if (!isloggedin() || isguestuser()) {
        return false;
    }

    // Load the cohort library for cohort operations.
    require_once($CFG->dirroot . '/cohort/lib.php');

    // Retrieve all cohorts the current user belongs to.
    $usercohorts = cohort_get_user_cohorts($USER->id);

    // If the user has no cohorts and cohort visibility is configured, hide the highlight.
    if (empty($usercohorts)) {
        return false;
    }

    // Extract cohort IDs from the user's cohort memberships.
    $usercohortids = array_keys($usercohorts);

    // Parse the comma-separated list of allowed cohort IDs from the configuration.
    $allowedcohortids = explode(',', $cohortvisibility);
    $allowedcohortids = array_map('trim', $allowedcohortids);
    $allowedcohortids = array_filter($allowedcohortids);

    // If no valid cohort IDs are configured, show to all users.
    if (empty($allowedcohortids)) {
        return true;
    }

    // Check if the user belongs to any of the allowed cohorts.
    foreach ($allowedcohortids as $allowedid) {
        // Normalize the cohort ID by converting it to an integer for comparison.
        $normalizedid = (int) $allowedid;
        if (in_array($normalizedid, $usercohortids)) {
            return true;
        }
    }

    // User does not belong to any of the allowed cohorts, so hide the highlight.
    return false;
}
