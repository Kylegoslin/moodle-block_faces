<?php
// ---------------------------------------------------------------------------------
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
// ---------------------------------------------------------------------------------

/** 
 * FACES BLOCK FOR MOODLE
 * 
 * This block allows you to view the faces of students
 * who are currently enrolled in your class.
 * @package    block_faces
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
* Main block interface
 * @package    block_faces
* @copyright  2015 Kyle Goslin, Daniel McSweeney
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class block_faces extends block_base {



/** block init */
function init() {

    $this->title   = 'Faces';
    $plugin = new stdClass();
}

/** Get the content for the block */
function get_content() {

    if ($this->content !== NULL) {
      return $this->content;
    }

    global $CFG;
    global $COURSE;
	global $DB;

    $this->content =  new stdClass;
    $this->content->text = get_faces_nav();
    $this->content->footer = '';

    return $this->content;
  }
  
  
  /**
     * Locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('course-view' => true);
    }
    

	/** One faces block per course */
	public function instance_allow_multiple() {
		return false;
	}
  
}


/**
* This is the main content generation function that is responsible for
* returning the relevant content to the user depending on what status
* they have (admin / student).
*
*/
function get_faces_nav() {


	global $USER, $DB, $CFG;
	$cid = optional_param('id', '', PARAM_INT);    

	$bodyhtml = '<img src="'.$CFG->wwwroot. '/blocks/faces/faces.png">
                 <a href="'.$CFG->wwwroot. '/blocks/faces/showfaces/show.php?cid='.$cid.'"> '. get_string('showallfaces', 'block_faces').'</a><br>';


	return $bodyhtml;


}



