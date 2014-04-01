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
require_once("../../../config.php");

global $CFG, $DB;
require_login();
$PAGE->set_context(get_system_context());
require_once('../showfaces/renderFaces.php');


$renderType = optional_param('rendertype', '', PARAM_TEXT);
if(isset($renderType)){
	
	if($renderType == 'all' || $renderType == ''){
		
		echo renderAll();
		
	}
	else if($renderType == 'group'){
	
		echo renderGroup();
	
	}
	
} else {

	renderGroup();
}


?>

<script>window.print();</script> 
