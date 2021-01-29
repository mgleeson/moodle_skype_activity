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
 * This page lists all the instances of skype in a particular course
 *
 * @package mod_skype
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @copyright 2020 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

require_once(__DIR__ . "/../../config.php");
require_once("lib.php");

$id = required_param('id', PARAM_INT);   // Course.

//if (! $course = $DB->get_record("course", array("id" => $id))) {
//    print_error("Course ID is incorrect");
//}

// require_course_login($course);
?>

var activex = ((navigator.userAgent.indexOf('Win')  != -1)
    && (navigator.userAgent.indexOf('MSIE') != -1)
    && (parseInt(navigator.appVersion) >= 4 ));
var CantDetect = ((navigator.userAgent.indexOf('Safari')  != -1) || (navigator.userAgent.indexOf('Opera')  != -1));

function oopsPopup() {
    if((navigator.language && navigator.language.indexOf("ja") != -1)
        || (navigator.systemLanguage && navigator.systemLanguage.indexOf("ja") != -1)
        || (navigator.userLanguage && navigator.userLanguage.indexOf("ja") != -1)) {
        var URLtoOpen = "skype_not_found/oops.html";
    } else {
        var URLtoOpen = "skype_not_found/oops.html";
    }
    var windowName = "ELA";
    var popW = 540, popH = 305;
    var scrollB = 'no';
    w = screen.availWidth;
    h = screen.availHeight;
    var leftPos = (w-popW)/2, topPos = (h-popH)/2;
    oopswindow = window.open(URLtoOpen, windowName,'width=' + popW + ',height=' + popH + ',scrollbars=' + scrollB + ',
        screenx=' +leftPos +',screeny=' +topPos +',top=' +topPos +',left=' +leftPos);
    return false;
}

if(typeof(detected) == "undefined" && activex) {
    document.write(
        ['<script language="VBscript">',
        'Function isSkypeInstalled()',
        'on error resume next',
        'Set oSkype = CreateObject("Skype.Detection")',
        'isSkypeInstalled = IsObject(oSkype)',
        'Set oSkype = nothing',
        'End Function',
        '</script>'].join("\n")
    );
}

function skypeCheck() {
    if(CantDetect) {
        return true;
    } else if(!activex) {
        var skypeMime = navigator.mimeTypes["application/x-skype"];
        detected = true;
        if(typeof(skypeMime) == "object") {
            return true;
        } else {
            return oopsPopup();
        }
    } else {
        if(isSkypeInstalled()) {
            detected = true;
            return true;
        }
    }
    
    detected = true;
    return oopsPopup();
}

function loadDetection() {
    if(document.getElementById && document.getElementsByTagName) {
        if (window.addEventListener) window.addEventListener('load', addDetection, false);
        else if (window.attachEvent) window.attachEvent('onload', addDetection);
    }
}

function addDetection() {
    var pageLinks = document.getElementsByTagName("a");
    for (var i=0; i < pageLinks.length; i++) {
        if(pageLinks[i].childNodes[0] && pageLinks[i].childNodes[0].src) {
            if((pageLinks[i].childNodes[0].src.indexOf('download.skype.com\/share\/skypebuttons') != -1
                || pageLinks[i].childNodes[0].src.indexOf('mystatus.skype.com') != -1)
                && (typeof(pageLinks[i].onclick) == "undefined" || pageLinks[i].onclick == null)) {
                pageLinks[i].onclick = function sChk() { return skypeCheck(); }
            }
        }
    }
}

loadDetection();