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
 * A circle shape.
 *
 * @module     mod_learningmap/circle
 * @copyright  2024 ISB Bayern
 * @author     Stefan Hanauska <stefan.hanauska@csg-in.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Returns a circle tag with the given dimensions.
 * @param {*} mapsvg
 * @param {*} x x coordinate of the center
 * @param {*} y y coordinate of the center
 * @param {*} r radius
 * @param {*} classes classes to add
 * @param {*} id id of the circle
 * @returns {any}
 */
export default function circle(mapsvg, x, y, r, classes, id) {
    return mapsvg.circle(r * 2).cx(x).cy(y).attr({'class': classes}).id(id);
}