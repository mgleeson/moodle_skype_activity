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
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/// (Replace skype with the name of your module and remove this line)

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // skype instance ID - it should be named as the first character of the module
$groupid  = optional_param('group', 0, PARAM_INT); // All users

if ($id) {
    $cm         = get_coursemodule_from_id('skype', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $skype  = $DB->get_record('skype', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $skype  = $DB->get_record('skype', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $skype->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('skype', $skype->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);


$module_context = get_context_instance(CONTEXT_MODULE,$cm->id);
require_capability('mod/skype:view', $module_context);
		

add_to_log($course->id, 'skype', 'view', "view.php?id=$cm->id", $skype->name, $cm->id);

/// Print the page header

$PAGE->set_url('/mod/skype/view.php', array('id' => $cm->id));
$PAGE->set_title($skype->name);
$PAGE->set_heading($course->shortname);
$PAGE->set_button(update_module_button($cm->id, $course->id, get_string('modulename', 'skype')));

// other things you may want to set - remove if not needed
//$PAGE->set_cacheable(false);
//$PAGE->set_focuscontrol('some-html-id');

// Output starts here
echo $OUTPUT->header();

// Replace the following lines with you own code
echo $OUTPUT->heading($skype->name);

echo $OUTPUT->box(get_string('timetoskype', 'skype', userdate($skype->chattime)), 'generalbox boxaligncenter');
echo $OUTPUT->box($skype->intro, 'generalbox boxaligncenter');

if(empty($USER->skype)){
	$update_skypeid_link = '<a href="'.$CFG->wwwroot.'/user/edit.php?id='.$USER->id.'&course=1">'.get_string('updateskypeid','skype').'</a>';
	echo $OUTPUT->box(get_string('updateskypeidnote', 'skype', $update_skypeid_link), 'error');
}else{
	/// Check to see if groups are being used here
	$groupmode = groups_get_activity_groupmode($cm);
	$currentgroup = groups_get_activity_group($cm, true);
	groups_print_activity_menu($cm, $CFG->wwwroot . "/mod/skype/view.php?id=$cm->id");

	$course_context = get_context_instance(CONTEXT_COURSE,$skype->course);
	$skype_users = get_enrolled_users($module_context, '', $currentgroup);

	if(empty($skype_users)){
		echo $OUTPUT->box(get_string('nobody', 'skype'), 'error');
	}else{
		echo $OUTPUT->box(print_skype_users_list($skype_users), 'generalbox boxaligncenter');
	}
}

// Finish the page
echo $OUTPUT->footer();
?>