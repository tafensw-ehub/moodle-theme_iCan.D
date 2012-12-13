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
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		04Oct2012
 * @see			layout/general_inc.php
 * @see			layout/general.php
 *
 * This class draws on the permissions data for options in the iCan.D. theme stored in the database,
 * and reports to the page building the pop-up theme override menu about which controls to reveal.
 *
 * It is initialised in a call to setPermissions(..) in layout/general.php, 
 * and passed to writeOverrideMenu( ) in layout/general_inc.php.
 */
if(class_exists('IcandRealVisChecker') != true)
{

require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');	// class ThemeOptions
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	

class IcandRealVisChecker {

/**
 *
 * @return boolean = DB_FIELD_PERMIT_MASTER set to "BLOCK ALL" (DB_VALUE_PERMIT_MASTER_NONE)
 */ 
public static function isAllEditingBlocked () {
// DB_FIELD_PERMIT_MASTER
}

	var $c_isAnyPermitted = false;
	var $c_isAllPermitted = false;
	var $c_permission_array = null;
	var $c_usersCapabilityLevel = 0;
	
/**
 * The following can NEVER appear in popups. They are only accessible in the site admin menu
 */
	public function setUsersCapabilityLevel ( $p_newCapabilityLevel ) {
		$this->c_usersCapabilityLevel = $p_newCapabilityLevel;
	}
	
	public function isAlwaysForbidden($p_elementname) {return  (in_array( $p_elementname, 
													IcandConstantsBackend::$COURSE_OVERRIDES_NOT_ALLOWED ));}
	
	public function getUsersCapabilityLevel() {
		return $this->c_usersCapabilityLevel;
	}
							
	public function __construct($p_newCapabilityLevel = 0) {
		$this->setUsersCapabilityLevel($p_newCapabilityLevel);
	}
								
/**
 * Initialiser function.
 * @param $p_permissionsArray array of string. Each string is a comma seperated list of data fields from the theme's parameters.
 * @return nothing
 */
	public function setPermissions( &$p_permissionsArray ) {
		$l_usersCapabilityLevel = $this->getUsersCapabilityLevel();	// make a local copy, to avoid repeated function calls
		$l_masterValue = $p_permissionsArray[IcandConstantsBackend::DB_FIELD_PERMIT_MASTER];
		if ( $l_usersCapabilityLevel == 0 ) {
			$l_masterValue = IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_NONE;	// this user has no access.
		}
		
		if ($l_masterValue == IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_NONE ) {
			return;	// all is blocked
		} elseif ($l_masterValue == IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_ALL ) {
			$this->c_isAllPermitted = true;
			$this->c_isAnyPermitted = true;
		} else {
			
			$this->c_permission_array	= array();	//initialise
			foreach ( $p_permissionsArray as $l_permissionList) {
				if (strlen( $l_permissionList ) > 0 ) {
					$this->c_permission_array = array_merge(
										$this->c_permission_array
									, 	explode ( IcandConstantsBackend::MULTISELECT_COMBINER, $l_permissionList )
									);
//			$l_requiredLevel = (in_array($p_elementname, $this->c_permission_array))
//								? IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_ULTIMATE
//								: IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_EDITOR ;
//			$l_result = ( $p_userAccessLevel >= $l_requiredLevel);									
				}//if
			}
			$this->c_isAnyPermitted = (count($this->c_permission_array) > 0);
		}//else
	}//setPermissions(..)

/**
 * accessor
 * @return boolean = are there any (i.e. > 0) items which any user can modify?
 */			
	public function isAnyPermitted() {
		return $this->c_isAnyPermitted;
	}
/**
 * accessor
 * @param $p_elementname string = name of a data fields from the theme's configurable parameters
 * @return boolean = the 
 */
	public function isVisible( $p_elementname ) { 
		$l_result = false;
		if ( $this->isAlwaysForbidden ( $p_elementname ) ) {
			$l_result = false;
		} elseif ( $this->c_isAllPermitted ) {
			$l_result = true;
		} else {
			$l_result =(in_array($p_elementname, $this->c_permission_array));
		}
		return $l_result;
	}//isVisible(..)

/**
 * accessor
 * @param $p_elementNameArray array of string. Each element is a name of a data fields from the theme's configurable parameters
 * @return boolean = are ANY of the items in $p_elementNameArray accessible to user (with $p_userAccessLevel passed to 
 *		setPermissions(..) or greater?
 */	
	public function isAnyVisible( $p_elementNameArray
								, $p_userAccessLevel = IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_EDITOR ) { 
		foreach ( $p_elementNameArray as $l_elementName ) {
			if ($this->isVisible( $l_elementName, $p_userAccessLevel )) {
				return true;
			}//if
		}//foreach
		return false;	// no matches found
	}//isVisible(..)	

/**
 * accessor
 * @param $p_elementname string = name of one of the theme's configurable parameters.
 * @return boolean = is access to modify $p_elementname for administrators only?
 */		
	public function isAdminOnly($p_elementname ){ 
		return false;
	}
}//class IcandRealVisChecker
}//ifif(class_exists