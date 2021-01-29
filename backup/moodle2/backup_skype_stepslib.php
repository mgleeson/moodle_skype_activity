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
 * Define all the backup steps that will be used by the backup_skype_activity_task.
 *
 * @package mod_skype
 * @copyright 2016 onwards AL Rachels (drachels@drachels.co9m)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Define the complete skype structure for backup, with file and id annotations.
 *
 * @package mod_skype
 * @copyright 2016 onwards AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class backup_skype_activity_structure_step extends backup_activity_structure_step {

    /**
     * Defines the structure of the 'skype' element inside the skype.xml file
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $skype = new backup_nested_element('skype', array('id'),
                                           array('name',
                                                 'intro',
                                                 'chattime',
                                                 'introformat',
                                                 'timecreated',
                                                 'timemodified',
                                                 'timeopen',
                                                 'timeclose'));

        // Define sources.
        $skype->set_source_table('skype', array('id' => backup::VAR_ACTIVITYID));

        // Return the root element (skype), wrapped into standard activity structure.
        return $this->prepare_activity_structure($skype);
    }
}
