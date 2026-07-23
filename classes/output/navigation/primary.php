<?php
namespace theme_boost_union_child\output\navigation;

use renderer_base;
use theme_boost_union\output\navigation\primary as boost_union_primary;
use custom_menu;

class primary extends boost_union_primary {
    public function __construct($page) {
        parent::__construct($page);
    }

    /**
     * Override to filter custom menu items by cohort.
     * This is called by export_for_template() in the parent class.
     */
    protected function get_custom_menu(renderer_base $output): array {
        global $CFG, $USER;

        // Require cohort library for cohort_get_user_cohorts().
        require_once($CFG->dirroot . '/cohort/lib.php');

        // Get the raw custom menu string from theme settings.
        $custommenustring = get_config('theme_boost_union_child', 'custommenuitems');

        // If empty, fall back to parent (which uses $CFG->custommenuitems).
        if (empty($custommenustring)) {
            return parent::get_custom_menu($output);
        }

        // Get user's system-level cohorts.
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
        if (empty(trim($filteredmenustring))) {
            return [];
        }

        // Use Moodle's standard parsing on the filtered string.
        $currentlang = current_language();
        
        // Parse using Moodle core's method.
        // Note: custom_menu::convert_text_to_menu_nodes takes the text directly as a parameter.
        $custommenunodes = \custom_menu::convert_text_to_menu_nodes($filteredmenustring, $currentlang);
        
        // Convert nodes to template format.
        $nodes = [];
        foreach ($custommenunodes as $node) {
            $nodes[] = $node->export_for_template($output);
        }

        return $nodes;
    }

    /**
     * Filter the custom menu string by cohort, removing items that the user doesn't have access to.
     */
    private function filter_custom_menu_by_cohort(string $custommenustring, array $usercohortids): string {
        $lines = explode("\n", $custommenustring);
        $filteredlines = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            
            // Keep empty lines and dividers as-is.
            if (empty($trimmed) || $trimmed === '###') {
                $filteredlines[] = $line;
                continue;
            }

            // Extract cohort IDs from the line.
            $requiredcohortids = [];
            if (preg_match('/\{([^}]+)\}/', $trimmed, $matches)) {
                $requiredcohortids = array_map('trim', explode(',', $matches[1]));
            }

            // Check access: if no cohort restriction, allow.
            $hasaccess = empty($requiredcohortids);
            if (!$hasaccess) {
                foreach ($requiredcohortids as $requiredid) {
                    if (in_array($requiredid, $usercohortids)) {
                        $hasaccess = true;
                        break;
                    }
                }
            }

            // Skip if no access.
            if (!$hasaccess) {
                continue;
            }

            // Remove the cohort restriction from the line.
            // We need to handle the format: text|url|title|lang|{cohorts}
            // After removing {cohorts}, we might have a trailing | which needs to be removed.
            $cleanedline = preg_replace('/\|\{[^}]+\}$/', '', $trimmed);
            $cleanedline = trim(str_replace('  ', ' ', $cleanedline));
            
            // Preserve the original line structure (including - prefix for submenu items).
            $filteredlines[] = $cleanedline;
        }

        return implode("\n", $filteredlines);
    }
}