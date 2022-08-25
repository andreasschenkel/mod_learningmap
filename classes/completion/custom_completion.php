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

namespace mod_learningmap\completion;

use stdClass;

/**
 * Custom completion rules for mod_learningmap
 *
 * @package     mod_learningmap
 * @copyright   2021-2022, ISB Bayern
 * @author      Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class custom_completion extends \core_completion\activity_custom_completion {
    /**
     * Returns completion state of the custom completion rules
     *
     * @param string $rule
     * @return integer
     */
    public function get_state(string $rule): int {
        global $DB;

        $this->validate_rule($rule);

        $map = $DB->get_record("learningmap", ["id" => $this->cm->instance], 'completiontype, placestore', MUST_EXIST);

        if ($map->completiontype > 0) {
            $placestore = json_decode($map->placestore);

            // Return COMPLETION_INCOMPLETE if there are no target places and condition requires to have one.
            if (
                ($map->completiontype < 3) &&
                count($placestore->targetplaces) == 0
            ) {
                return COMPLETION_INCOMPLETE;
            }

            $completion = new \completion_info($this->cm->get_course());

            $modinfo = get_fast_modinfo($this->cm->get_course(), $this->userid);
            $cms = $modinfo->get_cms();
            $allcms = array_keys($cms);

            foreach ($placestore->places as $place) {
                // Prevent infinite loop.
                if ($place->linkedActivity == $this->cm->id) {
                    continue;
                }
                // Skip non-target places when there is no condition to visit all places.
                if ($map->completiontype != 3 && !in_array($place->id, $placestore->targetplaces)) {
                    continue;
                }
                if ($place->linkedActivity != null) {
                    if (in_array($place->linkedActivity, $allcms)) {
                        $placecm = $modinfo->get_cm($place->linkedActivity);
                    } else {
                        // No way to fulfill condition.
                        if ($map->completiontype > 1) {
                            return COMPLETION_INCOMPLETE;
                        }
                        $placecm = false;
                    }

                    if (
                        !$placecm ||
                        !$this->is_completed($placecm)
                    ) {
                        // No way to fulfill condition.
                        if ($map->completiontype > 1) {
                            return COMPLETION_INCOMPLETE;
                        }
                    } else {
                        // We need only one.
                        if (
                            $map->completiontype == 1 &&
                            $this->is_completed($placecm)
                        ) {
                            return COMPLETION_COMPLETE;
                        }
                    }
                } else {
                    // No way to fulfill condition.
                    if ($map->completiontype > 1) {
                        return COMPLETION_INCOMPLETE;
                    }
                }
            }
            if ($map->completiontype == 1) {
                return COMPLETION_INCOMPLETE;
            } else {
                return COMPLETION_COMPLETE;
            }
        }
    }

    /**
     * Checks whether a given course module is completed (either by the user or at least one
     * of the users of the group, if groupmode is set for the activity).
     *
     * @param cm_info $cm course module to check
     */
    public function is_completed(\cm_info $cm) {
        if (!isset($this->cm)) {
            return false;
        }
        $completion = new \completion_info($cm->get_course());
        if ($this->cm->groupmode > 0) {
            $group = groups_get_activity_group($this->cm, false);
        }
        if (isset($group) && $group) {
            $members = groups_get_members($group);
        }
        if (!isset($members) || !$members) {
            $user = new stdClass;
            $user->id = $this->userid;
            $members = [$user];
        }
        foreach ($members as $member) {
            if ($completion->get_data($cm, true, $member->id)->completionstate > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Defines the names of custom completion rules.
     *
     * @return array
     */
    public static function get_defined_custom_rules(): array {
        return [
            'completion_with_one_target',
            'completion_with_all_targets',
            'completion_with_all_places'
        ];
    }

    /**
     * Returns the descriptions of the custom completion rules
     *
     * @return array
     */
    public function get_custom_rule_descriptions(): array {
        return [
            'completion_with_one_target' => get_string('completiondetail:one_target', 'learningmap'),
            'completion_with_all_targets' => get_string('completiondetail:all_targets', 'learningmap'),
            'completion_with_all_places' => get_string('completiondetail:all_places', 'learningmap')
        ];
    }

    /**
     * Returns the sort order of completion rules
     *
     * @return array
     */
    public function get_sort_order(): array {
        return [
            'completionview',
            'completion_with_one_target',
            'completion_with_all_targets',
            'completion_with_all_places'
        ];
    }
}
