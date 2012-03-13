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
 * Internal library of functions for module skype
 *
 * All the skype specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package   mod_skype
 * @copyright 2011 Amr Hourani a.hourani@gmail.com
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function print_skype_users_list($skypeusers){
	global $CFG, $USER,$OUTPUT;
	
	$userlist = "<script src=\"$CFG->wwwroot/mod/skype/js/skypeCheck.js\"></script>
				 <script>
				 
				 function addthisname(skypeid){	 
					 var skypenamelist = '';
					 for (i = 0; i < document.makeskypecall.userskypeids.length; i++){
						if(document.makeskypecall.userskypeids[i].checked == true){
							skypenamelist += document.makeskypecall.userskypeids[i].value + ';';
						}
					 }
					if(skypenamelist !=''){					    
						document.getElementById('display_call_all_skype').style.display = 'block';
						document.getElementById('who_to_call').innerHTML='<a href=\"skype:'+skypenamelist+'?call\"><img src=\"pix/createconference.gif\" border=\"0\" alt=\"Call\" title=\"Call\" onclick=\"return skypeCheck();\"></a> <a href=\"skype:'+skypenamelist+'?chat\"><img src=\"pix/createchat.gif\" border=\"0\" alt=\"Chat\"  title=\"Chat\" onclick=\"return skypeCheck();\"></a> <a href=\"skype:'+skypenamelist+'?voicemail\"><img src=\"pix/sendvoicemail.gif\" alt=\"Voice Mail\" title=\"Voice Mail\" border=\"0\" onclick=\"return skypeCheck();\"></a> <a href=\"skype:'+skypenamelist+'?add\"><img src=\"pix/addcontact.gif\" border=\"0\" alt=\"Add Contact\" title=\"Add Contact\" onclick=\"return skypeCheck();\"></a> <a href=\"skype:'+skypenamelist+'?sendfile\"><img src=\"pix/send.gif\" border=\"0\"  alt=\"Send File\" title=\"Send File\" onclick=\"return skypeCheck();\"></a>';

					}else{						
						document.getElementById('display_call_all_skype').style.display = 'none';
						if(document.getElementById('who_to_call')){
							document.getElementById('who_to_call').innerHTML='';
						}
					}
				 }
				 
				 
				 </script>
				 <form id='makeskypecall' name='makeskypecall'>
				 ";
	$userlist .= '<table width="100%" cellspacing="5" cellpaddin="5" border="0">';
	$userlist .= "<tr><td>".get_string("select")."</td>";
	$userlist .= "<td>".get_string("photo","skype")."</td>";
	$userlist .= "<td>".get_string("name")."</td>";
	$userlist .= "<td>".get_string("skypeid","skype")."</td>";
	$userlist .= "<td>".get_string("options","skype")."</td>";
	$userlist .= "</tr>";
	
	if(!$skypeusers) return '';
	$all_userskype = '';
	
	foreach($skypeusers as $user){ //print_user_picture($user, 1)
		if(empty($user->skype)){
			$disabled = " disabled='disabled'";
			$userskypeid = get_string("noskypeid","skype");
		}else{
			$disabled = " onClick='addthisname(this.value);' ";
			$userskypeid = $user->skype;		
		}
		$userlist .="<tr><td><input type='checkbox' name='userskypeids' value='$user->skype' $disabled></td>";
		$userlist .= "<td>".$OUTPUT->user_picture($user, array('courseid'=>1))."</td>";
		$userlist .= "<td>".fullname($user)."</td>";
		$userlist .= "<td>".$userskypeid."</td>";
		if($user->skype){
			$userlist .= "<td>
			<a href=\"skype:$user->skype?call\"><img src='pix/createconference.gif' border='0' alt='Call' title='Call' onclick=\"return skypeCheck();\"></a> 
			<a href=\"skype:$user->skype?chat\"><img src='pix/createchat.gif' border='0' alt='Chat'  title='Chat' onclick=\"return skypeCheck();\"></a>
			<a href=\"skype:$user->skype?voicemail\"><img src='pix/sendvoicemail.gif' alt='Voice Mail' title='Voice Mail' border='0' onclick=\"return skypeCheck();\"></a>
			<a href=\"skype:$user->skype?add\"><img src='pix/addcontact.gif' border='0' alt='Add Contact' title='Add Contact' onclick=\"return skypeCheck();\"></a>
			<a href=\"skype:$user->skype?sendfile\"><img src='pix/send.gif' border='0'  alt='Send File' title='Send File' onclick=\"return skypeCheck();\"></a>
			</td>";
		}else{
			$userlist .= "<td>".$userskypeid."</td>";
		}
		$userlist .= "</tr>";

	}
	$userlist .= "<tr><td colspan='5'><div id='display_call_all_skype'>".get_string("withselected","skype");
	$userlist .= "</div><div id='who_to_call'></div>";

	$userlist .= "</td></tr>";
		
	$userlist .= "</table></form><script>document.getElementById('display_call_all_skype').style.display = 'none';</script>";
	return $userlist;	
}
?>