<?php

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

