<?php
// block_faces is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// block_faces is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
// FACES BLOCK FOR MOODLE
// by Kyle Goslin & Daniel McSweeney
// Copyright 2013-2018 - Institute of Technology Blanchardstown.
//
/**
 * FACES BLOCK FOR MOODLE
 *
 * @package    block_faces
 * @copyright  2014-2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */ 
global $CFG, $DB;
require_login();

/**
 * Render a group
 * */ 
function render_group() {
		
	global $DB, $cid, $CFG;
	$outputhtml = '';
	
	$cid = required_param('cid', PARAM_INT);
	$selectedgroupid = optional_param('selectgroupsec', '', PARAM_INT);
	
	$appendorder = '';
	$orderby = optional_param('orderby', '', PARAM_TEXT);
	
	
		
    if ($orderby == 'byid') {
        $appendorder = ' order by userid';
    }
    else if ($orderby == 'firstname') {
        $appendorder = ' order by  (select firstname from '.$CFG->prefix.'user usr where userid = usr.id)';
    }
    else if ($orderby == 'lastname') {
        $appendorder = ' order by  (select lastname from '.$CFG->prefix.'user usr where userid = usr.id)';
    }
    else {
        $appendOrder = ' order by userid';
    }
	
	
	$groupname = $DB->get_record('groups', array('id'=>$selectedgroupid), $fields='*', $strictness=IGNORE_MISSING); 
	
	$query = 'select * from '.$CFG->prefix.'groups_members where groupid = ?' . $appendorder;
	
	
	
	$result = $DB->get_records_sql($query,array($selectedgroupid));
	$date = date('d-m-y');
	
	
	$coursename = $DB->get_record('course', array('id'=>$cid), 'fullname', $strictness=IGNORE_MISSING); 

	$outputhtml .= '<span style="font-size:12px"> <b>'. get_string('course', 'block_faces').':</b> ' .$coursename->fullname.'</span><br>';
	$outputhtml .= '<span style="font-size:12px"> <b>'. get_string('date', 'block_faces').':</b> ' .$date.'</span><p></p>';
	
	if (isset($groupname)) {
		$outputhtml .= '<span style="font-size:18px">'. $groupname->name . '</span><p></p>';
	}
	$outputhtml .= '<table width="800px"  border="0px"><tr>';
	
	$colcounter = 0;
	$totalrows = 0;
	
	foreach ($result as $face) {


		$outputhtml .=  print_single_face($face->userid, $cid);
		$colcounter++;
		
		if ($colcounter == 6) {
			$colcounter = 0;
			$totalrows++;
			$outputhtml .= '</tr><tr>';
		}
		
		if ($totalrows == 5) {
	
			$outputhtml .= '</tr></table>';
			
			$outputhtml .= '<table width="700px"  border="0px"><tr>';
			
			$outputhtml .= '<DIV style="page-break-after:always"></DIV>';
			$totalrows = 0;
	
		}
	}
	

	
	$outputhtml .= '</tr></table>';
	
	return $outputhtml;
	
	
}


/**
 * Render the entire class 
 * */
function render_all() {
	
	global $DB, $cid, $OUTPUT, $CFG;
	
	
	$appendorder = '';
	$orderby = '';
	$cid = optional_param('cid',0, PARAM_INT);
	$orderby = optional_param('orderby', '', PARAM_TEXT);
	
		
		if ($orderby == 'byid') {
			$appendorder = ' order by userid';
		}
		else if ($orderby == 'firstname') {
			$appendorder = ' order by  (select firstname from '.$CFG->prefix.'user usr where userid = usr.id)';
		} 
		else if($orderby == 'lastname') {
			$appendorder = ' order by  (select lastname from '.$CFG->prefix.'user usr where userid = usr.id)';
		} 
		
		else {
			$appendorder = ' order by  (select firstname from '.$CFG->prefix.'user usr where userid = usr.id)';
	
		}
	
	$query = "select userid from ".$CFG->prefix."user_enrolments en where en.enrolid IN (select e.id from ".$CFG->prefix."enrol e where courseid= ?)" . $appendorder;
	
	
	
	// Get the list of users for this particular course
	$result = $DB->get_records_sql($query, array($cid));

	$date = date('d-m-y');
	$coursename = $DB->get_record('course', array('id'=>$cid), 'fullname', $strictness=IGNORE_MISSING); 
	$outputhtml = '';
	$outputhtml .= '<span style="font-size:12px"> <b>'. get_string('course', 'block_faces').':</b> ' .$coursename->fullname.'</span><br>';
	$outputhtml .= '<span style="font-size:12px"> <b>'. get_string('date', 'block_faces').':</b> ' .$date.'</span><p></p>';
	
	$outputhtml .= '<table width="700px"  border="0px"><tr>';
	
	$colcounter = 0;
	$totalrows = 0;

	foreach ($result as $face) {


		$outputhtml .=  print_single_face($face->userid, $cid);
		$colcounter++;
		
		if ($colcounter == 6) {
			$colcounter = 0;
			$totalrows++;
			$outputhtml .= '</tr><tr>';
		}

		if ($totalrows == 5) {
			
			$outputhtml .= '</tr></table>';
			
			$outputhtml .= '<table width="700px"  border="0px"><tr>';
			
			$outputhtml .= '<DIV style="page-break-after:always"></DIV>';
			$totalrows = 0;
		}
	}
	
	
	
	$outputhtml .= '</tr></table>';
	
	return $outputhtml;
	
}
/**
 *  Render a single profile face
 * @param int $uid user id
 * @param int $cid course id
 */
function print_single_face($uid, $cid) {
	global $DB, $USER, $OUTPUT;
	
	
	$singlerec = $DB->get_record('user', array('id'=> $uid), $fields='*', $strictness=IGNORE_MISSING); 
	
	$firstname = $singlerec->firstname;
	$lastname = $singlerec->lastname;

	$user = $DB->get_record('user', array('id' => $uid));
	if (!$user) return '';
	
	$picoutput = '';
	
	global $PAGE; 
	
	
	$picoutput = $OUTPUT->user_picture($user, array('courseid'=>$cid, 'size' =>100));
	
	$outputhtml =  '
				
				<td>
				<table border="0" width="110" height="160px">
				<tr height="120">
					<td>'.$picoutput.'</td>
				</tr>
				
				<tr height="50">
					<td><b>' . $firstname . ' ' . $lastname . '</b></td>
				</tr>
				</table>
				
				</td>
		';

    return $outputhtml;

}
