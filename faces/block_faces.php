	

<?php


class block_faces extends block_base {




function init() {

    $this->title   = 'Faces';
    $plugin = new stdClass();
    $plugin->version   = 2013090211;      // The current module version (Date: YYYYMMDDXX)
    $plugin->requires  = 2013051401.00;      // Requires this Moodle version


  }

function get_content() {

    if ($this->content !== NULL) {
      return $this->content;
    }

    global $CFG;
    global $COURSE;
	global $DB;

    $this->content =  new stdClass;
    $this->content->text = getFacesNav();
    $this->content->footer = '';

    return $this->content;
  }
}


/*
This is the main content generation function that is responsible for
returning the relevant content to the user depending on what status
they have (admin / student).

*/
function getFacesNav(){


	global $USER, $DB, $CFG;
	$cid = optional_param('id', '', PARAM_INT);    

	$bodyHTML = '<img src="'.$CFG->wwwroot. '/blocks/faces/faces.png"><a href="'.$CFG->wwwroot. '/blocks/faces/showfaces/show.php?cid='.$cid.'"> '. get_string('showallfaces', 'block_faces').'</a><br>
				
				';


	return $bodyHTML;


}



