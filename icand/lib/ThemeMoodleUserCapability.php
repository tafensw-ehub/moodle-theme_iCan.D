<?php
/**
 * Licence 
 * 
 * This software is distributed under the terms and conditions of the GNU General Public Licence, v 3 (GPL). 
 * The complete terms and conditions of the GPL are available at http://www.gnu.org/licenses/gpl-3.0.txt
 * 
 * Copyright Notice 
 *
 * Copyright © NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012 
 *
 * Acknowledgements
 * - Renee Lance, project manager and lead designer 
 * - Glen Byram, lead programmer
 *
 * Gathers data about what the logged-in user can do in the current context.
 *
 * For full information about creating Moodle themes, see:
 *  http://docs.moodle.org/dev/Themes_2.0
 *
 * @package    theme
 * @subpackage icand
 * @copyright  NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @author	   Glen Byram
 * @license	   All rights reserved.
 * @version	   04Oct2012
 */

if(class_exists('IcandMoodleUserCapability') != true) {
class IcandMoodleUserCapability {
	
	var $c_currentUserCapabilityLevel = 0;
	
	public function getCurrentUserCapabilityLevel() {
		return $this->c_currentUserCapabilityLevel;
	}
	
	public function __construct() {
		$this->c_currentUserCapabilityLevel = $this->findCurrentUserCapabilityLevel();
	}

	protected function getContextInstance( $contextName ) {
		global $COURSE;
		switch( $contextName ) {
			case 'course' 	: return get_context_instance(CONTEXT_COURSE, $COURSE->id );
			case 'category' : return get_context_instance(CONTEXT_COURSE, $COURSE->category ); 
			case 'site'		: return  get_context_instance(CONTEXT_SYSTEM ); 
		}//switch
	}	
	
	protected static $c_cap = array (
				'course' 	=> 'moodle/course:update'
			,	'category'	=> 'moodle/course:create'
			,	'site'		=> 'moodle/site:config'
			);
								
	protected function findCurrentUserCapabilityLevel() {
			if (has_capability(self::$c_cap['site'], $this->getContextInstance( 'site' ))) {
				return 8;
			} else {
				$l_context = $this->getContextInstance( 'category' );
				if (!empty( $l_context ) and (has_capability(self::$c_cap['category'], $l_context))) {
					return 6;
				} elseif  (has_capability(self::$c_cap['course'], $this->getContextInstance( 'course' ))) {
					return 3;
				} else {
					return 0;
				}
			}//else
	}
}//class
}//if class_exists