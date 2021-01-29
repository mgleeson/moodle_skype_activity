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
 * The main skype configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package   mod_skype
 * @copyright 2011 Amr Hourani a.hourani@gmail.com
 * @copyright 2020 onwards AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package    mod_skype
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class mod_skype_mod_form extends moodleform_mod {
    /**
     * Define the Skype mod_form.
     *
     * 20200507 added missing visibility, public.
     */
    public function definition() {

        global $COURSE;
        $mform =& $this->_form;

        // Adding the general fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard name field.
        $mform->addElement('text', 'name', get_string('skypename', 'skype'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'skypename', 'skype');

        // Adding the standard intro and introformat fields.
        $this->standard_intro_elements();

        // Availability.
        $mform->addElement('header', 'availabilityhdr', get_string('availability'));

        $mform->addElement('date_time_selector', 'chattime', get_string('chattime', 'chat'));

        $mform->addElement('date_time_selector', 'timeopen',
                           get_string('skypeopentime', 'skype'),
                           array('optional' => true, 'step' => 1));
        $mform->addElement('date_time_selector', 'timeclose',
                           get_string('skypeclosetime', 'skype'),
                           array('optional' => true, 'step' => 1));

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();
        // Add standard buttons, common to all modules.
        $this->add_action_buttons();

    }
}
