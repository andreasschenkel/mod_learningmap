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
 * Unit test for mod_learningmap
 *
 * @package mod_learningmap
 * @copyright  2021 Stefan Hanauska <stefan.hanauska@altmuehlnet.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @group mod_learningmap
 * @group mebis
 */

class mod_learningmap_completion_testcase extends advanced_testcase {

    public function test_completiontype1() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course(['enablecompletion' => 1]);
        $learningmap = $this->getDataGenerator()->create_module('learningmap',
            ['course' => $course, 'completion' => 2, 'completiontype' => 1]);

        $activities = [];
        for ($i = 0; $i < 9; $i++) {
            array_push($activities, $this->getDataGenerator()->create_module(
                'page',
                ['name' => 'A', 'content' => 'B', 'course' => $course, 'completion' => 2, 'completionview' => 1]
            ));
            $learningmap->placestore = str_replace(99990 + $i, $activities[$i]->cmid, $learningmap->placestore);
        }
        $DB->set_field('learningmap', 'placestore', $learningmap->placestore, array('id' => $learningmap->id));

        $user1 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user1@example.com',
            'username' => 'user1')
        );

        $modinfo = get_fast_modinfo($course, $user1->id);
        $completion = new \completion_info($modinfo->get_course());
        $cm = $modinfo->get_cm($learningmap->cmid);
        $this->assertEquals(
            COMPLETION_INCOMPLETE,
            $completion->get_data($cm, true, $user1->id)->completionstate
        );

        for ($i = 0; $i < 9; $i++) {
            $acm = $modinfo->get_cm($activities[$i]->cmid);
            $completion->set_module_viewed($acm, $user1->id);
            $completion->update_state($cm, COMPLETION_UNKNOWN, $user1->id);
            if ($i < 7) {
                $this->assertEquals(
                    COMPLETION_INCOMPLETE,
                    $completion->get_data($cm, true, $user1->id)->completionstate
                );
            } else {
                $this->assertEquals(
                    COMPLETION_COMPLETE,
                    $completion->get_data($cm, true, $user1->id)->completionstate
                );
            }
        }
    }

    public function test_completiontype2() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course(['enablecompletion' => 1]);
        $learningmap = $this->getDataGenerator()->create_module('learningmap',
            ['course' => $course, 'completion' => 2, 'completiontype' => 2]);

        $activities = [];
        for ($i = 0; $i < 9; $i++) {
            array_push($activities, $this->getDataGenerator()->create_module(
                'page',
                ['name' => 'A', 'content' => 'B', 'course' => $course, 'completion' => 2, 'completionview' => 1]
            ));
            $learningmap->placestore = str_replace(99990 + $i, $activities[$i]->cmid, $learningmap->placestore);
        }
        $DB->set_field('learningmap', 'placestore', $learningmap->placestore, array('id' => $learningmap->id));

        $user1 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user1@example.com',
            'username' => 'user1')
        );

        $modinfo = get_fast_modinfo($course, $user1->id);
        $completion = new \completion_info($modinfo->get_course());
        $cm = $modinfo->get_cm($learningmap->cmid);
        $this->assertEquals(
            COMPLETION_INCOMPLETE,
            $completion->get_data($cm, true, $user1->id)->completionstate
        );

        for ($i = 0; $i < 9; $i++) {
            $acm = $modinfo->get_cm($activities[$i]->cmid);
            $completion->set_module_viewed($acm, $user1->id);
            $completion->update_state($cm, COMPLETION_UNKNOWN, $user1->id);
            if ($i < 8) {
                $this->assertEquals(
                    COMPLETION_INCOMPLETE,
                    $completion->get_data($cm, true, $user1->id)->completionstate
                );
            } else {
                $this->assertEquals(
                    COMPLETION_COMPLETE,
                    $completion->get_data($cm, true, $user1->id)->completionstate
                );
            }
        }
    }

    public function test_completiontype3() {
        global $DB;
        $this->resetAfterTest();
        $this->setAdminUser();

        $course = $this->getDataGenerator()->create_course(['enablecompletion' => 1]);
        $learningmap = $this->getDataGenerator()->create_module('learningmap',
            ['course' => $course, 'completion' => 2, 'completiontype' => 3]);

        $activities = [];
        for ($i = 0; $i < 9; $i++) {
            array_push($activities, $this->getDataGenerator()->create_module(
                'page',
                ['name' => 'A', 'content' => 'B', 'course' => $course, 'completion' => 2, 'completionview' => 1]
            ));
            $learningmap->placestore = str_replace(99990 + $i, $activities[$i]->cmid, $learningmap->placestore);
        }
        $DB->set_field('learningmap', 'placestore', $learningmap->placestore, array('id' => $learningmap->id));

        $user1 = $this->getDataGenerator()->create_user(
            array(
            'email' => 'user1@example.com',
            'username' => 'user1')
        );

        $modinfo = get_fast_modinfo($course, $user1->id);
        $completion = new \completion_info($modinfo->get_course());
        $cm = $modinfo->get_cm($learningmap->cmid);
        $this->assertEquals(
            COMPLETION_INCOMPLETE,
            $completion->get_data($cm, true, $user1->id)->completionstate
        );

        for ($i = 0; $i < 9; $i++) {
            $acm = $modinfo->get_cm($activities[$i]->cmid);
            $completion->set_module_viewed($acm, $user1->id);
            $completion->update_state($cm, COMPLETION_UNKNOWN, $user1->id);
            if ($i < 8) {
                $this->assertEquals(
                    COMPLETION_INCOMPLETE,
                    $completion->get_data($cm, true, $user1->id)->completionstate
                );
            } else {
                $this->assertEquals(
                    COMPLETION_COMPLETE,
                    $completion->get_data($cm, true, $user1->id)->completionstate
                );
            }
        }
    }


}
