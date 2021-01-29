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
 * This file contains the backup activity for the skype module.
 *
 * @package mod_skype
 * @copyright 2016 onwards AL Rachels (drachels@drachels.com).
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mod/skype/backup/moodle2/backup_skype_stepslib.php'); // Because it exists (must).

/**
 * Provides the steps to perform one complete backup of the Skype instance.
 *
 * @package mod_skype
 * @copyright 2016 onwards AL Rachels (drachels@drachels.com).
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class backup_skype_activity_task extends backup_activity_task {

    /**
     * Define (add) particular settings this activity can have.
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Defines a backup step to store the instance data in the skype.xml file.
     */
    protected function define_my_steps() {
        // Choice only has one structure step.
        $this->add_step(new backup_skype_activity_structure_step('skype_structure', 'skype.xml'));
    }

    /**
     * Encodes URLs to the index.php and view.php scripts.
     *
     * @param string $content some HTML text that eventually contains URLs to the activity instance scripts.
     * @return string $content The content with the URLs encoded.
     */
    static public function encode_content_links($content) {
        global $CFG;

        $base = preg_quote($CFG->wwwroot, "/");

        // Link to the list of skypes.
        $search = "/(".$base."\/mod\/skype\/index.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@SKYPEINDEX*$2@$', $content);

        // Link to skype view by moduleid.
        $search = "/(".$base."\/mod\/skype\/view.php\?id\=)([0-9]+)/";
        $content = preg_replace($search, '$@SKYPEVIEWBYID*$2@$', $content);

        return $content;
    }
}
