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

class block_faces extends block_base {




function init() {

    $this->title   = 'Faces';
    $plugin = new stdClass();
    $plugin->version   = 2014040113;      // The current module version (Date: YYYYMMDDXX)
    $plugin->requires  = 2011070110.00;      // Requires this Moodle version


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



