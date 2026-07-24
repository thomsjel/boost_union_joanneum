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
        global $CFG, $USER, $DB;

        // Require cohort library for cohort_get_user_cohorts().
        require_once($CFG->dirroot . '/cohort/lib.php');

        // Get the raw custom menu string from theme settings.
        $custommenustring = get_config('theme_boost_union_child', 'custommenuitems');

        // If empty, fall back to parent (which uses $CFG->custommenuitems).
        if (empty($custommenustring)) {
            return parent::get_custom_menu($output);
        }

        $isadmin = is_siteadmin($USER->id);
        $currentlang = current_language();

        // Special handling for admin: show all items with cohort spans.
        if ($isadmin) {
            // For admin: parse the original menu string (with cohort markers),
            // then use reflection to add cohort spans directly to node text before export.
            $custommenunodes = \custom_menu::convert_text_to_menu_nodes($custommenustring, $currentlang);

            // Process nodes recursively to add cohort spans.
            $nodes = $this->add_cohort_spans_to_nodes($custommenunodes, $custommenustring, $DB, $output);

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
        if (empty(trim($filteredmenustring))) {
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
    private function build_cohort_span($cohortid, $DB): string {
        $cohortname = $DB->get_field('cohort', 'name', ['id' => (int)$cohortid]);
        $cohortdesc = $DB->get_field('cohort', 'description', ['id' => (int)$cohortid]);
        $cohorttagcolor = '';
        if ($cohortdesc !== false) {
            $cohorttagcolor = strip_tags($cohortdesc ?? '');
        }
        if ($cohortname !== false) {
            $escapedname = htmlspecialchars($cohortname, ENT_QUOTES, 'UTF-8');
            $bgcolor = $cohorttagcolor . '25';
            return "<span class='cohort-tag cohort-$cohortid' style='background-color:$bgcolor; color:$cohorttagcolor; border:1px solid $cohorttagcolor;'>$escapedname</span>";
        }
        return '';
    }

    /**
     * Recursively process nodes to add cohort spans.
     * Modifies exported node data AFTER export to ensure HTML is preserved.
     */
    private function add_cohort_spans_to_nodes(array $nodes, string $originalmenustring, $DB, renderer_base $output): array {
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
            $currenttext = is_array($exported) ? ($exported['text'] ?? '') : ($exported->text ?? '');
            $currenttext = trim($currenttext);
            
            // Find matching cohort IDs.
            $cohortids = $texttocohorts[$currenttext] ?? [];
            
            // Add cohort spans if any.
            if (!empty($cohortids)) {
                $spans = [];
                foreach ($cohortids as $cid) {
                    $span = $this->build_cohort_span($cid, $DB);
                    if ($span) {
                        $spans[] = $span;
                    }
                }
                if (!empty($spans)) {
                    $newtext = $currenttext . ' ' . implode(' ', $spans);
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
                    $exported['children'] = $this->add_cohort_spans_to_nodes($children, $originalmenustring, $DB, $output);
                } else {
                    $exported->children = $this->add_cohort_spans_to_nodes($children, $originalmenustring, $DB, $output);
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
    private function filter_custom_menu_by_cohort(string $custommenustring, array $usercohortids, bool $foradmin = false): string {
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

            // If admin mode, don't filter - just remove cohort markers.
            if ($foradmin) {
                $cleanedline = preg_replace('/\|\{[^}]+\}$/', '', $trimmed);
                $cleanedline = trim(str_replace('  ', ' ', $cleanedline));
                $filteredlines[] = $cleanedline;
                continue;
            }

            // Original filtering logic for non-admin.
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
            $cleanedline = preg_replace('/\|\{[^}]+\}$/', '', $trimmed);
            $cleanedline = trim(str_replace('  ', ' ', $cleanedline));
            
            $filteredlines[] = $cleanedline;
        }

        return implode("\n", $filteredlines);
    }
}