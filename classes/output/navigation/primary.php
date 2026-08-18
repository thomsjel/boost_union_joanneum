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

namespace theme_boost_union_joanneum\output\navigation;

use renderer_base;
use theme_boost_union\output\navigation\primary as boost_union_primary;
use custom_menu;

/**
 * Primary navigation renderer for Boost Union Child theme with cohort filtering.
 *
 * @package   theme_boost_union_joanneum
 * @copyright 2026 Thomas Kautz <thomas.kautz@fh-joanneum.at>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class primary extends boost_union_primary {
    /**
     * Override to filter custom menu items by cohort.
     * This is called by export_for_template() in the parent class.
     */
    protected function get_custom_menu(renderer_base $output): array {
        global $CFG, $USER, $DB;

        // Require cohort library for cohort_get_user_cohorts().
        require_once($CFG->dirroot . '/cohort/lib.php');

        // Get the raw custom menu string from theme settings.
        $custommenustring = get_config('theme_boost_union_joanneum', 'custommenuitems');

        // If empty, fall back to parent (which uses $CFG->custommenuitems).
        if (empty($custommenustring)) {
            return parent::get_custom_menu($output);
        }

        $isadmin = is_siteadmin($USER->id);
        $currentlang = current_language();

        // Special handling for admin: show all items with cohort spans.
        if ($isadmin) {
            // For admin: parse the original menu string (with cohort markers),
            // then add cohort spans directly to node text before export.
            $custommenunodes = \custom_menu::convert_text_to_menu_nodes($custommenustring, $currentlang);

            // Process nodes recursively to add cohort spans.
            $nodes = $this->add_cohort_labels_to_nodes($custommenunodes, $custommenustring, $DB, $output);

            return $nodes;
        }

        // Non-admin: use standard filtering.
        $usercohortids = [];
        if (isloggedin() && !isguestuser()) {
            $usercohorts = cohort_get_user_cohorts($USER->id, 0); // 0 = system context
            $usercohortids = array_map(function($cohort) {
                return $cohort->id;
            }, $usercohorts);
        }

        // Filter the menu string by cohort.
        $filteredmenustring = $this->filter_custom_menu_by_cohort($custommenustring, $usercohortids);

        // If the filtered string is empty, return empty array.
        if (trim($filteredmenustring) === '') {
            return [];
        }

        // Use Moodle's standard parsing on the filtered string.
        $custommenunodes = \custom_menu::convert_text_to_menu_nodes($filteredmenustring, $currentlang);
        
        // Convert nodes to template format.
        $nodes = [];
        foreach ($custommenunodes as $node) {
            $nodes[] = $node->export_for_template($output);
        }

        return $nodes;
    }


    /**
     * Build HTML span for a cohort ID.
     */
    private function build_cohort_span(int $cohortid, $DB): string {
        $cohort = $DB->get_record('cohort', ['id' => $cohortid], 'id, name');
        if (!$cohort) {
            return '';
        }

        $escapedname = htmlspecialchars($cohort->name, ENT_QUOTES, 'UTF-8');

        return "<span class='cohort-tag cohort-$cohortid'>&#x2022; $escapedname</span>";
    }

    /**
     * Build HTML div container with multiple cohort spans.
     */
    private function build_cohort_spans_container($currenttext, array $cohortids, $DB): string {
        $spans = [];
        foreach ($cohortids as $cohortid) {
            $span = $this->build_cohort_span($cohortid, $DB);
            if ($span) {
                $spans[] = $span;
            }
        }
        
        if (empty($spans)) {
            return '';
        }

        $label = get_string('cohorttagscontainer_desc', 'theme_boost_union_joanneum');
        
        return '<div class="cohort-tags-container"><fieldset><legend>' . $label . '</legend>' . implode(' ', $spans) . '</fieldset></div>';
    }

    /**
     * Build toggle button which shows the cohort spans container on hover.
     */
    private function build_cohort_spans_container_toggle(): string {

        return '<div class="cohort-tags-container-toggle"><i class="fa-solid fa-eye"></i></div>';
    }

    /**
     * Recursively process nodes to add cohort spans.
     * Modifies exported node data AFTER export to ensure HTML is preserved.
     */
    private function add_cohort_labels_to_nodes(array $nodes, string $originalmenustring, $DB, renderer_base $output): array {
        $result = [];
        
        // Build a map of text to cohort IDs from original menu string.
        $lines = explode("\n", $originalmenustring);
        $texttocohorts = [];
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed) || $trimmed === '###') {
                continue;
            }
            
            $cohortids = [];
            if (preg_match('/\{([^}]+)\}/', $trimmed, $matches)) {
                $cohortids = array_map('trim', explode(',', $matches[1]));
            }
            
            // Get text part (before first |), strip - prefix for child items.
            $firstpipe = strpos($trimmed, '|');
            $textpart = $firstpipe === false ? $trimmed : substr($trimmed, 0, $firstpipe);
            $textpart = trim(ltrim($textpart, '- '));
            
            if (!empty($cohortids)) {
                $texttocohorts[$textpart] = $cohortids;
            }
        }

        foreach ($nodes as $node) {
            // Export node first.
            if (method_exists($node, 'export_for_template')) {
                $exported = $node->export_for_template($output);
            } else {
                $exported = (array) $node;
            }
            
            // Get the text from exported data.
            $currenttext = match (true) {
                is_array($exported) => $exported['text'] ?? '',
                is_object($exported) => $exported->text ?? '',
                default => '',
            };
            $currenttext = trim($currenttext);
            
            // Find matching cohort IDs.
            $cohortids = $texttocohorts[$currenttext] ?? [];
            
            // Add cohort spans container if any.
            if (!empty($cohortids)) {
                $toggle = $this->build_cohort_spans_container_toggle();
                $container = $this->build_cohort_spans_container($currenttext, $cohortids, $DB);
                if ($container) {
                    $newtext = $currenttext . ' ' . $toggle . $container;
                    if (is_array($exported)) {
                        $exported['text'] = $newtext;
                    } else {
                        $exported->text = $newtext;
                    }
                }
            }
            
            // Recursively process children.
            $children = [];
            if (is_array($exported) && isset($exported['children']) && is_array($exported['children'])) {
                $children = $exported['children'];
            } else if (is_object($exported) && isset($exported->children) && is_array($exported->children)) {
                $children = $exported->children;
            }
            
            if (!empty($children)) {
                if (is_array($exported)) {
                    $exported['children'] = $this->add_cohort_labels_to_nodes($children, $originalmenustring, $DB, $output);
                } else {
                    $exported->children = $this->add_cohort_labels_to_nodes($children, $originalmenustring, $DB, $output);
                }
            }

            $result[] = $exported;
        }

        return $result;
    }

    /**
     * Filter the custom menu string by cohort, removing items that the user doesn't have access to.
     * For admin users, all items are shown (cohort markers are removed but not replaced with spans here).
     * 
     * @param bool $foradmin If true, skip filtering and just remove cohort markers from all lines.
     */
    private function filter_custom_menu_by_cohort(string $custommenustring, array $usercohortids): string {
        $lines = explode("\n", $custommenustring);
        $filteredlines = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (empty($trimmed) || $trimmed === '###') {
                $filteredlines[] = $line;
                continue;
            }

            // Extract cohort IDs.
            $requiredcohortids = [];
            if (preg_match('/\{([^}]+)\}/', $trimmed, $matches)) {
                $requiredcohortids = array_map('trim', explode(',', $matches[1]));
                $trimmed = preg_replace('/\|\{[^}]+\}$/', '', $trimmed);
                $trimmed = trim(str_replace('  ', ' ', $trimmed));
            }

            // Skip if user lacks access.
            if (!empty($requiredcohortids) && !array_intersect($requiredcohortids, $usercohortids)) {
                continue;
            }

            $filteredlines[] = $trimmed;
        }

        return implode("\n", $filteredlines);
    }
}