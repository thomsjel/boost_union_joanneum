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
 * @copyright  2026 Thomas Kautz <thomas.kautz@fh-joanneum.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['core_user/repository'], function(UserRepository) {
    "use strict";

    /**
     * Hide element with fade out animation.
     *
     * @param {HTMLElement} element The element to hide
     * @param {number} duration The duration in milliseconds
     * @returns {Promise} Promise that resolves when the animation completes
     */
    const fadeOut = (element, duration = 200) => {
        return new Promise((resolve) => {
            element.style.transition = `opacity ${duration}ms ease-in-out`;
            element.style.opacity = '0';

            const onTransitionEnd = () => {
                element.style.display = 'none';
                element.removeEventListener('transitionend', onTransitionEnd);
                resolve();
            };

            element.addEventListener('transitionend', onTransitionEnd);

            // Fallback in case transition doesn't fire
            setTimeout(() => {
                if (element.style.opacity === '0') {
                    element.style.display = 'none';
                    resolve();
                }
            }, duration);
        });
    };

    /**
     * Initialise the highlights dismissal functionality.
     */
    const initHighlights = () => {
        const closeButton = document.getElementById('themeboostunionchildhighlightsclose');
        const wrapper = document.getElementById('themeboostunionchildhighlights-wrapper');

        if (!closeButton || !wrapper) {
            return;
        }

        // Register click handler for the highlights close button.
        closeButton.addEventListener('click', (e) => {
            // Prevent Bootstrap's default alert dismissal to handle it ourselves.
            e.preventDefault();
            e.stopPropagation();

            // Store the dismissal as a user preference to persist this decision.
            UserRepository.setUserPreference('theme_boost_union_child_highlights_dismissed', 1)
                .then(() => fadeOut(wrapper))
                .catch(() => fadeOut(wrapper));
        });
    };

    return {
        init: () => {
            initHighlights();
        }
    };
});
