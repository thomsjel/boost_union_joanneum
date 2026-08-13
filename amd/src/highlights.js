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
 * Theme Boost Union Child - JS code for highlights dismiss functionality
 *
 * @module     theme_boost_union_child/highlights
 * @copyright  2026 Thomas Kautz, <thomas.kautz@fh-joanneum.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('theme_boost_union_child/highlights', ['jquery'], function($) {
    "use strict";

    /**
     * Highlights management class.
     */
    class HighlightsManager {
        constructor() {
            this.$wrapper = $('#themeboostunionchildhighlights-wrapper');
            this.$closebutton = $('#themeboostunionchildhighlightsclose');
        }

        /**
         * Check if highlights were dismissed and hide if so.
         */
        checkDismissedState() {
            require(['core_user/repository'], (UserRepository) => {
                UserRepository.getUserPreference('theme_boost_union_child_highlights_dismissed')
                    .then((value) => {
                        if (value === true || value === '1') {
                            this.$wrapper.hide();
                        }
                    })
                    .catch(() => {
                        // Silently ignore errors.
                    });
            });
        }

        /**
         * Set up the close button handler.
         */
        setupCloseHandler() {
            this.$closebutton.on('click', (e) => {
                e.preventDefault();
                this.$wrapper.hide();

                require(['core_user/repository'], (UserRepository) => {
                    UserRepository.setUserPreference('theme_boost_union_child_highlights_dismissed', 1)
                        .catch(() => {
                            // Silently ignore errors.
                        });
                });
            });
        }

        /**
         * Initialize the highlights functionality.
         */
        init() {
            this.checkDismissedState();
            this.setupCloseHandler();
        }
    }

    // Return a singleton instance.
    return new HighlightsManager();
});