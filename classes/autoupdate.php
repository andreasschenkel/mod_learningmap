<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Autoupdate class for mod_learningmap
 *
 * @package mod_learningmap
 * @copyright  2021 Stefan Hanauska <stefan.hanauska@altmuehlnet.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_learningmap;

defined('MOODLE_INTERNAL') || die();

class autoupdate {
    public static function update_from_event(\core\event\base $event) {
        $data = $event->get_data();
        if (isset($data['courseid']) && $data['courseid'] > 0) {
            $modinfo = get_fast_modinfo($data['courseid']);
            $instances = $modinfo->get_instances_of('learningmap');
            if (count($instances) > 0) {
                $completion = new \completion_info($modinfo->get_course());
                foreach ($instances as $i) {
                    if($i->completion == COMPLETION_TRACKING_AUTOMATIC) {
                        $instance = $i->get_course_module_record();
                        if ($instance->id == $data['contextinstanceid']) {
                            continue;
                        }
                        $completion->update_state($i, COMPLETION_UNKNOWN, $data['userid']);
                    }
                }
            }
        }
    }
}
