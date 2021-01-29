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
 * Prints a particular instance of skype oops.html.
 *
 * The Skype activity links Moodle users to the Skyp application.
 *
 * @package    mod_skype
 * @copyright  2020 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */


require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID.

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

//$PAGE->set_url('/mod/skype/oops.php', null);
$PAGE->set_title('Using Skype Activity');
//$PAGE->set_title($skype->name);


$output = '';

//echo $this->output->box_start('generalbox boxwidthwide boxaligncenter');
echo '<style>
    html {
        padding-right: 0px; padding-left: 0px; padding-BOTTOM: 0px; margin: 0px; padding-TOP: 0px
    }
    body {
        padding-right: 0px; padding-left: 0px; padding-BOTTOM: 0px; margin: 0px; padding-TOP: 0px
    }
    body {
        background: url("pix/bg.png") white no-repeat left top; FONT: 78%/130% "Lucida Grande",Verdana,Arial,sans-serif; overflow: hidden; width: 540px; color: white; height: 305px
    }
    h1 {
        padding-right: 0px; display: block; padding-left: 0px; FONT-WEIGHT: 600; FONT-SIZE: 38px; left: 107px; padding-BOTTOM: 0px; margin: 0px; color: #00aff0; line-height: 38px; padding-TOP: 0px; FONT-FAMILY: "Helvetica Neue Light",Helvetica,Arial,sans-serif; POSITION: absolute; TOP: 36px
    }
    p {
        padding-right: 0px; padding-left: 0px; padding-BOTTOM: 0px; margin: 0px; padding-TOP: 0px
    }
    p#col1 {
        display: block; left: 25px; WIDTH: 240px; POSITION: absolute; TOP: 107px
    }
    p#col2 {
        display: block; left: 280px; WIDTH: 240px; POSITION: absolute; TOP: 107px
    }
    div {
        padding-right: 0px; padding-left: 0px; left: 25px; padding-BOTTOM: 0px; margin: 0px; WIDTH: 495px; color: black; padding-TOP: 0px; POSITION: absolute; TOP: 195px
    }
    div p {
        margin-BOTTOM: 10px
    }
    a {
        FONT-WEIGHT: bold; color: white; TEXT-DECORATION: underline
    }
    form {
        padding-right: 0px; padding-left: 0px; padding-BOTTOM: 0px; margin: 0px; padding-TOP: 0px
    }


    </style>';
echo '<h1>Hello!</h1>';
echo '<p id=col1>The Skype actvity requires that you have the latest version of Skype 
installed. (Don’t worry, you only need to do this once.)</p>';
echo '<p id=col2>Skype is a little piece of software that lets you make free calls 
over the internet.<BR><a href="http://www.skype.com/" target=_blank>Learn more 
about Skype</a></p>';
echo '<div><p><strong>ELA-Skype is free, easy and quick to download and install.</strong></p>';
echo '<p>It works with Windows, Mac OS X, Linux and Pocket PC, and contains absolutely 
no spyware, adware, malware or anything like that';

//echo '<div><p>';
echo '<form name="download-ff" id="download-ff" method="get" action="http://www.skype.com/go/getskype" target=_blank>';
echo '<input type="submit" class="btn btn-primary" name="download" value="Download Skype" />';

echo '</form></p>';
echo '</div>';



//echo '<input class="btn btn-primary" id="btnDownload" name="download" type="submit" value="Download Skype" />';
//echo $this->output->box_end();
?>
