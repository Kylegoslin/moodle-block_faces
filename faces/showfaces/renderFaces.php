<?php

//require_once("../../../config.php");
global $CFG, $DB;
require_login();

/*
 * 
 * 
 *
 * 
 * */ 
function renderGroup(){
		
	global $DB, $cid, $CFG;
	$outputHTML = '';
	
	$cid = required_param('cid', PARAM_INT);
	$selectedGroupId = optional_param('selectgroupsec', '', PARAM_INT);
	
	$appendOrder = '';
	$orderBy = optional_param('orderby', '', PARAM_TEXT);
	
	
		
		if($orderBy == 'byid'){
			$appendOrder = ' order by userid';
		}
		else if($orderBy == 'firstname'){
			$appendOrder = ' order by  (select firstname from '.$CFG->prefix.'user usr where userid = usr.id)';
		}
		else if($orderBy == 'lastname'){
			$appendOrder = ' order by  (select lastname from '.$CFG->prefix.'user usr where userid = usr.id)';
		}
		 else {
			$appendOrder = ' order by userid';
		}
	
	

	
	
	
	
	
	$groupName = $DB->get_record('groups', array('id'=>$selectedGroupId), $fields='*', $strictness=IGNORE_MISSING); 
	
	$query = 'select * from '.$CFG->prefix.'groups_members where groupid = ?' . $appendOrder;
	
	
	
	$result = $DB->get_records_sql($query,array($selectedGroupId));
	$date = date('d-m-y');
	
	
	$courseName = $DB->get_record('course', array('id'=>$cid), 'fullname', $strictness=IGNORE_MISSING); 

	$outputHTML .= '<span style="font-size:12px"> <b>'. get_string('course', 'block_faces').':</b> ' .$courseName->fullname.'</span><br>';
	$outputHTML .= '<span style="font-size:12px"> <b>'. get_string('date', 'block_faces').':</b> ' .$date.'</span><p></p>';
	
	if(isset($groupName)){
		$outputHTML .= '<span style="font-size:18px">'. $groupName->name . '</span><p></p>';
	}
	$outputHTML .= '<table width="800px"  border="0px"><tr>';
	
	$colCounter = 0;
	$totalRows = 0;
	
	foreach($result as $face){


		$outputHTML .=  printSingleFace($face->userid, $cid);
		$colCounter++;
		
		if($colCounter == 6){
			$colCounter = 0;
			$totalRows++;
			$outputHTML .= '</tr><tr>';
		}
		
		if($totalRows == 5){
	
			$outputHTML .= '</tr></table>';
			
			$outputHTML .= '<table width="700px"  border="0px"><tr>';
			
			$outputHTML .= '<DIV style="page-break-after:always"></DIV>';
			$totalRows = 0;
	
		}
	}
	

	
	$outputHTML .= '</tr></table>';
	
	return $outputHTML;
	
	
}


/*
 * 
 * Render the entire class 
 * 
 * */
function renderAll(){
	
	global $DB, $cid, $OUTPUT, $CFG;
	
	
	$appendOrder = '';
	$orderBy = '';
	$cid = required_param('cid', PARAM_INT);
	$orderBy = optional_param('orderby', '', PARAM_TEXT);
	
		
		if($orderBy == 'byid'){
			$appendOrder = ' order by userid';
		}
		else if($orderBy == 'firstname'){
			$appendOrder = ' order by  (select firstname from '.$CFG->prefix.'user usr where userid = usr.id)';
		} 
		else if($orderBy == 'lastname'){
			$appendOrder = ' order by  (select lastname from '.$CFG->prefix.'user usr where userid = usr.id)';
		} 
		
		else {
			$appendOrder = ' order by  (select firstname from '.$CFG->prefix.'user usr where userid = usr.id)';
	
		}
	
	$query = "select userid from ".$CFG->prefix."user_enrolments en where en.enrolid IN (select e.id from ".$CFG->prefix."enrol e where courseid= ?)" . $appendOrder;
	
	
	
	// Get the list of users for this particular course
	$result = $DB->get_records_sql($query, array($cid));

	$date = date('d-m-y');
	$courseName = $DB->get_record('course', array('id'=>$cid), 'fullname', $strictness=IGNORE_MISSING); 
	$outputHTML = '';
	$outputHTML .= '<span style="font-size:12px"> <b>'. get_string('course', 'block_faces').':</b> ' .$courseName->fullname.'</span><br>';
	$outputHTML .= '<span style="font-size:12px"> <b>'. get_string('date', 'block_faces').':</b> ' .$date.'</span><p></p>';
	
	$outputHTML .= '<table width="700px"  border="0px"><tr>';
	
	$colCounter = 0;
	$totalRows = 0;

	foreach($result as $face){


		$outputHTML .=  printSingleFace($face->userid, $cid);
		$colCounter++;
		
		if($colCounter == 6){
			$colCounter = 0;
			$totalRows++;
			$outputHTML .= '</tr><tr>';
		}

		if($totalRows == 5){
			
			$outputHTML .= '</tr></table>';
			
			$outputHTML .= '<table width="700px"  border="0px"><tr>';
			
			$outputHTML .= '<DIV style="page-break-after:always"></DIV>';
			$totalRows = 0;
		}
	}
	
	
	
	$outputHTML .= '</tr></table>';
	
	return $outputHTML;
	
}
/*
 *  Render a single profile face
 * 
 * 
 */
function printSingleFace($uid, $cid){
	global $DB, $USER, $OUTPUT;
	
	
	
	
	$singleRec = $DB->get_record('user', array('id'=> $uid), $fields='*', $strictness=IGNORE_MISSING); 
	
	$firstName = $singleRec->firstname;
	$lastname = $singleRec->lastname;

	$user = $DB->get_record('user', array('id' => $uid));
	
	
	$picOutput = '';
	
	global $PAGE; 
	
	
	$picOutput = $OUTPUT->user_picture($user, array('courseid'=>$cid, 'size' =>100));
	
	
	
	$outputHTML =  '
				
				
				<td>
				<table border="0" width="110" height="160px">
				<tr height="120">
					<td>'.$picOutput.'</td>
				</tr>
				
				<tr height="50">
					<td><b>' . $firstName . ' ' . $lastname . '</b></td>
				</tr>
				</table>
				
				</td>
		';

return $outputHTML;

}
