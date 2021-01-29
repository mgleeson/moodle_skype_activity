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
 * Prints a particular instance of skype
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package   mod_skype
 * @copyright 2011 Amr Hourani a.hourani@gmail.com
 * @copyright 2020 onwards AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID.
$n  = optional_param('n', 0, PARAM_INT);  // Skype instance ID - it should be named as the first character of the module.
$groupid  = optional_param('group', 0, PARAM_INT); // All users.

if ($id) {
    $cm         = get_coursemodule_from_id('skype', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $skype  = $DB->get_record('skype', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $skype  = $DB->get_record('skype', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $skype->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('skype', $skype->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}
$context = context_module::instance($cm->id);
require_login($course, true, $cm);


$modulecontext = context_module::instance($cm->id);
require_capability('mod/skype:view', $modulecontext);

// Trigger module viewed event.
$event = \mod_skype\event\course_module_viewed::create(array(
   'objectid' => $skype->id,
   'context' => $context
));
$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('skype', $skype);
$event->trigger();

// Print the page header.
$skypeoutput = $PAGE->get_renderer('mod_skype');

$PAGE->set_url('/mod/skype/view.php', array('id' => $cm->id));
$PAGE->set_title($skype->name);
$PAGE->set_heading($course->shortname);

// 20200507 If Moodle less than version 3.2 show the button.
if ($CFG->branch < 32) {
    $PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename', 'skype')));
}

// Output starts here.
echo $OUTPUT->header();

// Replace the following lines with you own code.
echo $OUTPUT->heading($skype->name);

// Availability restrictions applied to students only.
if ((!(is_available($skype))) && (!(has_capability('mod/skype:manageentries', $context)))) {
    if ($skype->timeclose != 0 && time() > $skype->timeclose) {
        echo $skypeoutput->skype_inaccessible(get_string('skypeclosed', 'skype', userdate($skype->timeclose)));
    } else {
        echo $skypeoutput->skype_inaccessible(get_string('skypeopen', 'skype', userdate($skype->timeopen)));
    }
    echo $OUTPUT->footer();
    exit();
} else {
    echo $OUTPUT->box(get_string('timetoskype', 'skype', userdate($skype->chattime)), 'generalbox boxaligncenter');
    echo $OUTPUT->box($skype->intro, 'generalbox boxaligncenter');

    if (empty($USER->skype)) {
        $updateskypeidlink = '<a href="'.$CFG->wwwroot.'/user/edit.php?id='.$USER->id
            .'&course=1">'.get_string('updateskypeid', 'skype').'</a>';
        echo $OUTPUT->box(get_string('updateskypeidnote', 'skype', $updateskypeidlink), 'error');
    } else {
        // Check to see if groups are being used here.
        $groupmode = groups_get_activity_groupmode($cm);
        $currentgroup = groups_get_activity_group($cm, true);
        groups_print_activity_menu($cm, $CFG->wwwroot . "/mod/skype/view.php?id=$cm->id");

        $coursecontext = context_course::instance($skype->course);
        $skypeusers = get_enrolled_users($modulecontext, '', $currentgroup);

        if (empty($skypeusers)) {
            echo $OUTPUT->box(get_string('nobody', 'skype'), 'error');
        } else {
            echo $OUTPUT->box(printskypeuserslist($skypeusers), 'generalbox boxaligncenter');
        }
    }
}

// Finish the page.
echo $OUTPUT->footer();
