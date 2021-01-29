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
 * Moodle renderer used to display special elements of the skype module.
 *
 * @package    mod_skype
 * @copyright  2019 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

defined('MOODLE_INTERNAL') || die();

/**
 * A custom renderer class that extends the plugin_renderer_base and is used by the skype module.
 *
 * @package    mod_skype
 * @copyright  2019 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_skype_renderer extends plugin_renderer_base {
    /**
     * Returns the header for the skype module.
     *
     * @param skype $skype a skype object.
     * @param int $cm id of the skype course module that needs to be displayed.
     * @param string $extrapagetitle String to append to the page title.
     * @return string.
     */
    public function header($skype, $cm, $extrapagetitle = null) {
        global $CFG;

        $activityname = format_string($skype->name, true);

        if (empty($extrapagetitle)) {
            $title = $this->page->course->shortname.": ".$activityname;
        } else {
            $title = $this->page->course->shortname.": ".$activityname.": ".$extrapagetitle;
        }

        $context = context_module::instance($cm->id);

        // Header setup.
        $this->page->set_title($title);
        $this->page->set_heading($this->page->course->fullname);
        $output = $this->output->header();

        if (has_capability('mod/skype:setup', $context)) {
            $output .= $this->output->heading_with_help($activityname, 'overview', 'skype');
        } else {
            $output .= $this->output->heading($activityname);
        }
        return $output;
    }

    /**
     * Returns the footer
     * @return string
     */
    public function footer() {
        return $this->output->footer();
    }

    /**
     * Returns HTML for a skype inaccessible message
     *
     * @param string $message
     * @return <type>
     */
    public function skype_inaccessible($message) {
        global $CFG;
        $output  = $this->output->box_start('generalbox boxaligncenter');
        $output .= $this->output->box_start('center');
        $output .= (get_string('notavailable', 'skype'));
        $output .= $message;
        $output .= $this->output->box('<a href="'.$CFG->wwwroot.'/course/view.php?id='
                . $this->page->course->id .'">'
                . get_string('returnto', 'skype', format_string($this->page->course->fullname, true))
                .'</a>', 'skypebutton standardbutton');
        $output .= $this->output->box_end();
        $output .= $this->output->box_end();
        return $output;
    }
}