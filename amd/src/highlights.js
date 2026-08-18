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
 * Theme Boost Union Child - JS code for dismissible highlights section
 *
 * @module     theme_boost_union_child/highlights
 * @copyright  2026 Alexander Bias <bias@alexanderbias.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core_user/repository'], function($, UserRepository) {
    "use strict";

    /**
     * Initialising.
     */
    function initHighlights() {
        // Register click handler for the highlights close button.
        $('#themeboostunionchildhighlightsclose').on('click', function(e) {
            // Prevent Bootstrap's default alert dismissal to handle it ourselves.
            e.preventDefault();
            e.stopPropagation();

            // Store the dismissal as a user preference to persist this decision.
            UserRepository.setUserPreference('theme_boost_union_child_highlights_dismissed', 1)
                .then(function() {
                    // Hide the highlights wrapper immediately for better UX.
                    $('#themeboostunionchildhighlights-wrapper').fadeOut(200);
                })
                .catch(function() {
                    // If the preference could not be saved, just hide it anyway for this session.
                    $('#themeboostunionchildhighlights-wrapper').fadeOut(200);
                });
        });
    }

    return {
        init: function() {
            initHighlights();
        }
    };
});
