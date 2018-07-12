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
// Copyright 2013-2018 - Institute of Technology Blanchardstown.
// 
/**
 * FACES BLOCK FOR MOODLE
 *
 * @package    block_faces
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once("../../../config.php");

global $CFG, $DB;
require_login();
$cid = optional_param('cid',0, PARAM_INT);
$PAGE->set_context(context_course::instance($cid));
require_once('../showfaces/renderFaces.php');


$rendertype = optional_param('rendertype', '', PARAM_TEXT);
if (isset($rendertype)) {
	
	if ($rendertype == 'all' || $rendertype == '') {
		
		echo render_all();
		
	}
	else if($rendertype == 'group') {
	
		echo render_group();
	
	}
	
} else {

	render_group();
}


?>

<script>window.print();</script> 

