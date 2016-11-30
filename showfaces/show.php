<?php
//
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
// Copyright 2013-2014 - Institute of Technology Blanchardstown.
// 
/**
 * FACES BLOCK FOR MOODLE
 *
 * @package    block_faces
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * 
 * Description:
 * This is the main display page used for calling each different reresentation
 * of faces.
 * 
 * ----------------------------------------------------------------------
 */
require_once("../../../config.php");
global $CFG, $DB;
require_login();

require_once('renderFaces.php');

$cid = optional_param('cid',0, PARAM_INT);
$gid = optional_param('gid', 0, PARAM_INT);    


/** Navigation Bar **/
$PAGE->navbar->ignore_active();
$rendertype = '';

$selectgroupsec = optional_param('selectgroupsec', '', PARAM_TEXT);  
 
if (isset($selectgroupsec)) {
	
	if ($selectgroupsec == 'all') {
		$rendertype = 'all';
	}
	else if ($selectgroupsec == 'group') {
		$rendertype == 'group';
	} 
	
	if (is_numeric($selectgroupsec)) {
		$rendertype = 'group';
	}
	
		
} else {
		$rendertype = 'all';
}

if ($rendertype == 'all' || $rendertype == '') {
		$coursename = $DB->get_record('course', array('id'=>$cid), 'shortname', $strictness=IGNORE_MISSING); 
		$PAGE->navbar->add($coursename->shortname, new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $cid));
		$PAGE->navbar->add(get_string('showallfaces', 'block_faces'));
	
}
else if ($rendertype == 'group') {
		$coursename = $DB->get_record('course', array('id'=>$cid), 'shortname', $strictness=IGNORE_MISSING); 
		$PAGE->navbar->add($coursename->shortname, new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $cid));
		$PAGE->navbar->add(get_string('showfacesbygroup', 'block_faces'));
}


$PAGE->set_url('/blocks/faces/showfaces/show.php');
$PAGE->set_context(context_course::instance($cid));
$PAGE->set_heading(get_string('pluginname', 'block_faces'));
$PAGE->set_title(get_string('pluginname', 'block_faces'));

echo $OUTPUT->header();
echo build_menu($cid);


// Render the page
$selectgroupsec = optional_param('selectgroupsec', '', PARAM_TEXT);   
if (isset($selectgroupsec)) {
	
	if($selectgroupsec == 'all' || $selectgroupsec == ''){
		 
		echo render_all();
		
	} else {
		
		echo render_group();
	
	}
	
} else {

	echo render_all();
}




/**
 * 
 * Create the HTML output for the list on the right
 * hand side of the showfaces.php page
 * @param int $cid course id 
 * */
function build_menu($cid) {
	
	global $DB, $CFG, $rendertype;
	
	$orderby = '';
	$orderby = optional_param('orderby', 'firstname', PARAM_TEXT);
	
	
	$outputhtml = '<div style="float:right"><form action="'.$CFG->wwwroot. '/blocks/faces/showfaces/show.php?cid='.$cid.'" method="post">
				 ' .get_string('orderby', 'block_faces').': <select name="orderby" id="orderby">
								<option value="firstname">' .get_string('firstname', 'block_faces').'</option>
								<option value="lastname">'.get_string('lastname', 'block_faces').'</option>
						  </select>
						  
				 ' .get_string('filter', 'block_faces').': <select id="selectgroupsec" name="selectgroupsec">
				 	<option value="all">'.get_string('showallfaces', 'block_faces').'</option>
				 '. build_groups($cid).'	
				 </select>
				 <input type="submit" value="'.get_string('update', 'block_faces').'"></input>
				</form>
				<script>document.getElementById(\'orderby\').value="'.$orderby.'";</script>
				<span style="float:right">
				
				<form target="_blank" action="../print/page.php">
   				<input type="hidden" name="cid" value="'.$cid.'">
				<input type="hidden" name="rendertype" value="'.$rendertype.'">
				
				';
					
				// If a group was selected
				$selectgroupsec = optional_param('selectgroupsec', 'all', PARAM_TEXT); 

				if(isset($selectgroupsec)){
 					$outputhtml .= '<input type="hidden" name="selectgroupsec" value="'.$selectgroupsec.'">';
				}
					$outputhtml .= '
					<script>document.getElementById(\'selectgroupsec\').value="'.$selectgroupsec.'";</script>
				';
				$outputhtml .= '
				<input type="hidden" name="orderby" value="'.$orderby.'">
					
				
   				<input type="submit" value="'.get_string('print', 'block_faces').'">
				</form>
				</span>
				</div>
				';
	
	return $outputhtml;
	
}
/**
 * Build up the dropdown menu items with groups that are associated
 * to the currently open course.
 * @param int $cid course id
 */
function build_groups($cid) {
	
	global $DB;
	
	$buildhtml = '';
	$groups = $DB->get_records('groups',array('courseid'=>$cid));

	foreach($groups as $group){
		$groupid = $group->id;
		
		$buildhtml.= '<option value="'.$groupid.'">'. $group->name.'</option>';
	}
	
	return $buildhtml;
	
}


		
echo $OUTPUT->footer();

$selectgroupsec = optional_param('selectgroupsec', '', PARAM_TEXT); 
    if (isset($selectgroupsec)) {
 		$selecteditem = $selectgroupsec;
		echo '<script>
				document.getElementById("selectgroupsec").value = '.$selecteditem.'
			  </script>';
	}

$orderby = optional_param('orderby', '', PARAM_TEXT);
	if (isset($orderby)) {
		$orderitem = $orderby;
		
		echo '<script>
				document.getElementById("orderby").value = "'.$orderitem.'"
			  </script>';
			  
			  if($orderitem == ""){
			  	echo '<script>
				document.getElementById("orderby").value = "firstname";
			  </script>';
				
			  }
	} 
