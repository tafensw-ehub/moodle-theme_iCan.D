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
 * Defines CSS strings (id, class names) used by the theme.
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		3August2012 (1 bug line 531: getOverrideSettings() -> getOverrideSettingsArray()
 */
if(class_exists('IcandSettingsBase') != true) {

/**
* @todo array_diff_assoc
*/

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	
require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');	// getAllMultiselectKeys ()

class IcandSettingsBase {

//const PREG_REMOVE_FROM_OVERRIDES = '/\R/';	// \R=newlines		//'/\s+/'; // all whitespace
//const STR_APPEND_TO_OVERRIDES	 = "\n";  // add new line after each clause
//const PREG_REMOVE_FROM_INHERITS  = '/\s+/';	// all whitespace
//const STR_APPEND_TO_INHERITS	 = "\n";  // add new line after each clause	

	var $c0_courseId 							  = 0;	// integer // l_courseId
	// from saved records	
	var $c0_persistantAncestorCourseId 			  = IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR; //$l_sitewideAncestor
	var	$c0_persistantSitewideSettingsArray 	  = null;  // array::(parameter=>value)
	var $c0_persistantSitewideAncestorCourseArray = null; // array::(course =>parent course)
	var $c0_persistantSitewideOverridesArray	  = null; // array::(courseId=>string of overrides)
	// calculated
	var $c0_thisCourseAncestorList				 = null; // l_thisCourseThemeAncestorCourseList	
	var $c0_thisCourseOverridesFromDatabaseArray = null; // array::(parameter=>value)
	var $c0_multiSelectValuesArray				 = null; // string key => array (key=>value)
	//------------------------------------------------------------
	// constructor functions
	//------------------------------------------------------------
	/** 
	* @requires $p_theme_settings != null
	*/	
	public function __construct($p_courseId, &$p_theme_settings ) {
		//the 1st two aren't affected by course parent
		$this->c0_courseId  = $p_courseId;
		$this->initialise1_allSavedRawSettings( $p_theme_settings );
		
		//the following 3 depend on parent course being set
		$this->initialise2_makeMyAncestorList();
		$this->initialise3_settingsFromMyAncestorList();
		$this->initialise4_extractMyCourseOverrideSettings();
		
/* DEBUG 		
if ( $this->c0_courseId == 2 ){
	print_r( $this );
}*/
		
	}//__construct(..)

	//============================================================
	// read-only getters
	//============================================================
	/**
	* @return array of default settings for whole site (settingkey=>settinvalue)
	*/	
	protected function getSitewideSettings() {
		return $this->c0_persistantSitewideSettingsArray; 
	}//getSitewideSettings()
	
	//------------------------------------------------------------
	// ancestor course IDs
	//------------------------------------------------------------
	public function getAncestorCourseId() {
	// overridden in descendant
		return $this->c0_persistantAncestorCourseId	;
	}//getAncestorCourseId
	
	/* can be overridden in descendants */
	protected function getAncestorArray() {
		return $this->c0_persistantSitewideAncestorCourseArray;
	}//getAncestorArray()

	/** turns c0_persistantSitewideAncestorCourseArray into string */	
	public function getAllInheritanceAsString() { 
		return IcandConstantsBackend::implode2(
						IcandConstantsBackend::INHERITANCE_SEPARATOR . IcandConstantsBackend::STR_APPEND_TO_INHERITS
					,	IcandConstantsBackend::INHERITANCE_CONNECTOR
					,	$this->c0_persistantSitewideAncestorCourseArray );
	}//getAllInheritanceAsString()

	//------------------------------------------------------------
	// overrides
	//------------------------------------------------------------	
	/** 
	* @requires initialise3_settingsFromMyAncestorList() to be run previously
	* @ensures result!=null ( done by initialise3_settingsFromMyAncestorList() )
	* @return Array course-specific overrides from database
	*/	
	public function getOverrideSettingsArray() {
		return $this->c0_thisCourseOverridesFromDatabaseArray;
	}//getOverrideSettingsArray(

	/**
	* @return string all overrides for all courses, in string form. If no overrides: ''
	*/
	public function getAllOverridesAsString() {
		$l_result = IcandConstantsBackend::implode2(
						IcandConstantsBackend::OVERRIDE_ALL_SEPARATOR . IcandConstantsBackend::STR_APPEND_TO_INHERITS
					,	IcandConstantsBackend::OVERRIDE_ALL_CONNECTOR
					,	$this->c0_persistantSitewideOverridesArray );
		if (strlen( $l_result ) > 0) {
			$l_result .= IcandConstantsBackend::OVERRIDE_ALL_TERMINATOR;
		}
		return $l_result;
	}//getAllOverridesAsString()		

	/**
	* @param (usually integer) $p_courseId course_identifier
	* @return array(settingkey=>settingvalue) for the input course ID, or null if no overrides defined
	*/
	protected function extractDefaultOverridesForCourseId($p_courseId) {
		if (array_key_exists($p_courseId, $this->c0_persistantSitewideOverridesArray)) {
			$l_courseOverridesString = substr($this->c0_persistantSitewideOverridesArray[$p_courseId],1); //remove leading '{'
			$l_courseOverridesString = preg_replace( IcandConstantsBackend::PREG_REMOVE_FROM_OVERRIDES, '', $l_courseOverridesString );
				return IcandConstantsBackend::explode2(
						IcandConstantsBackend::OVERRIDE_ONE_SEPARATOR 
					,	IcandConstantsBackend::OVERRIDE_ONE_CONNECTOR
					,	$l_courseOverridesString
					,	2 );		// only get key and value
		}
		else {
			return null;
		}
	}//extractDefaultOverridesForCourseId()

	//---------------------------------------------
	//---------------------------------------------
	public function getEffectiveSettings () {
	// overridden in descendants
	// CALL LAST!!!
	// side effects: purges c0_persistantSitewideSettingsArray
		self::purgeDummyValues( $this->c0_persistantSitewideSettingsArray );
		$this->updateSitewideWithMultiselects();
		return $this->c0_persistantSitewideSettingsArray;
	}//getEffectiveSettings()
	
	//---------------------------------------------------------
	/**
	* usually USED TO GET NON-OVERRIDEABLE VALUES e.g. system permissions
	* searches $this->c0_persistantSitewideSettingsArray for array keys beginning with $p_keyPrefix
	*
	* @param string $p_keyPrefix prefix of settingsKey in c0_persistantSitewideSettingsArray, to search for
	* @param boolean $p_removeIfFound if true, then extracted values are deleted from $this->c0_persistantSitewideSettingsArray
	* @return array (matched keys => values)
	* @sideeffect if ($p_removeIfFound) c0_persistantSitewideSettingsArray will have entries matching $p_keyPrefix removed
	*/	
	public function extractGlobalSettings( $p_keyPrefix, $p_removeIfFound = false ) {
		$l_result = array();
		$l_foundKeys = preg_grep ( '/^' . $p_keyPrefix . '.+/' , array_keys($this->c0_persistantSitewideSettingsArray) );
		if ($p_removeIfFound) {
			foreach ($l_foundKeys as $l_foundKey){
				$l_result[ $l_foundKey ] = $this->c0_persistantSitewideSettingsArray[$l_foundKey];
				unset($this->c0_persistantSitewideSettingsArray[$l_foundKey]);
			}//foreach
		} else {
			foreach ($l_foundKeys as $l_foundKey){
				$l_result[ $l_foundKey ] = $this->c0_persistantSitewideSettingsArray[$l_foundKey];
			}
		}//else
		return $l_result;
	}//extractGlobalSettings	
	//=========================================================
	//	load persistent (database) data
	//=========================================================	
	protected function setSitewideSettings ($p_newArray) {
	/**
	* copies $p_newArray to $c0_persistantSitewideSettingsArray
	* @param array $p_newArray
	* @sideeffect $c0_persistantSitewideSettingsArray overrwritten
	*/
		$this->c0_persistantSitewideSettingsArray = $p_newArray;
		$this->c0_initialiseMultiSelects();
		$this->updateMultiselectsWithInput($p_newArray);
	}

//=====================================================================
// multi-select handlers
//=====================================================================	
	/**
	* updates c0_multiSelectValuesArray with relevant strings from multiselects in param 1
	*/
	protected function updateMultiselectsWithInput(&$p_stringArray) {
		foreach ( $this->c0_multiSelectValuesArray as $l_key => $l_oldArray ) {
			if ( (array_key_exists($l_key, $p_stringArray ))
				and (strlen($p_stringArray[$l_key]) > 0 ) ) {
					$l_newArray = IcandConstantsBackend::explodeMultiselect( $p_stringArray[$l_key] );
					if (count( $l_newArray ) > 0 ) {
						$this->c0_multiSelectValuesArray[$l_key] = array_merge($l_oldArray, $l_newArray );
					}//if
			}//if
		}//foreach
	}//updateMultiselectsWithInput
	
	/**
	* writes values of c0_multiSelectValuesArray back into c0_persistantSitewideSettingsArray
	*/
	protected function updateSitewideWithMultiselects() {
		foreach ( $this->c0_multiSelectValuesArray as $l_key => $l_valueArray ) {
			$this->c0_persistantSitewideSettingsArray[ $l_key] = IcandConstantsBackend::implodeMultiselect( $l_valueArray );
		}//foreach	
	}
	
	/**
	* initialises c0_multiSelectValuesArray to array of empty arrays
	*/
	protected function c0_initialiseMultiSelects() {
		$this->c0_multiSelectValuesArray = array();
		foreach (IcandConfigOptions::getAllMultiselectKeys() as $l_multiSelectKey ) {
			$this->c0_multiSelectValuesArray[$l_multiSelectKey] = array();	// make empty arrays
		}
	}//c0_initialiseMultiSelects()

//=====================================================================	
	/**
	* @param array $p_theme_settings
	* @param boolean $p_isInsertDummies
	* @requires if $p_theme_settings!=null && is_array($p_theme_settings)
	* @ensures c0_persistantSitewideAncestorCourseArray contains sitewide course=>parent
	* @ensures c0_persistantAncestorCourseId == parent of c0_courseId or IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR
	* @ensures c0_persistantSitewideOverridesArray initialised (courseId=>string)
	*/	
	protected function initialise1_allSavedRawSettings( &$p_theme_settings, $p_isInsertDummies=false) {
		$this->setSitewideSettings( self::getServerParameters( $p_theme_settings, $p_isInsertDummies));
		$this->c0_persistantSitewideAncestorCourseArray = self::parseAncestors( $this->c0_persistantSitewideSettingsArray[IcandConstantsBackend::DB_FIELD_COURSE_ANCESTOR] ); //$PAGE->theme->settings->sys_inherit)); //remove whitespace
		$this->c0_persistantAncestorCourseId = self::getAncestor($this->c0_courseId , $this->c0_persistantSitewideAncestorCourseArray);	
		
		$l_sitewideOverridesString 	 = isset( $this->c0_persistantSitewideSettingsArray[IcandConstantsBackend::DB_FIELD_COURSE_OVERRIDES] )
									 ? preg_replace( IcandConstantsBackend::PREG_REMOVE_FROM_OVERRIDES, '', $this->c0_persistantSitewideSettingsArray[IcandConstantsBackend::DB_FIELD_COURSE_OVERRIDES]) //remove whitespace from all overrides !!!
									 : '';
		if (strlen($l_sitewideOverridesString ) > 0 ){
			$l_sitewideOverridesString = substr( $l_sitewideOverridesString,0,-1); //loose trailing '}'
		}//if(strlen..	
		$this->c0_persistantSitewideOverridesArray = IcandConstantsBackend::explode2( 
							IcandConstantsBackend::OVERRIDE_ALL_SEPARATOR 
						,	IcandConstantsBackend::OVERRIDE_ALL_CONNECTOR
						,	$l_sitewideOverridesString ); // comma is used in multiselect lists, so can't be our delimiter
		// !!!! the resulting values will have a leading '{'. To be removed later
		
	}//initialise1_allSavedRawSettings(..)
	//---------------------------------------------------------
	/** 
	* @ensures (this course has parent)?is_array(c0_thisCourseAncestorList):c0_thisCourseAncestorList==null
	*/	
	protected function initialise2_makeMyAncestorList() {
		$l_sitewideThemeAncestorCourseArray = $this->getAncestorArray();
		if (count($l_sitewideThemeAncestorCourseArray) > 0) {	// if ANY inheritance defined...
			$this->c0_thisCourseAncestorList = self::makeAncestorList($this->c0_courseId ,$l_sitewideThemeAncestorCourseArray);
			if ($this->c0_thisCourseAncestorList === false) {	// check for error
				$this->c0_thisCourseAncestorList = null;
				// TODO: is there something else needed to prevent this bad inheritance being saved???
			}
			if (($this->c0_thisCourseAncestorList != null) && count($this->c0_thisCourseAncestorList)>0) {// if inheritance specific to this course...
				$this->c0_thisCourseAncestorList = array_reverse($this->c0_thisCourseAncestorList,true);//oldest 1st		
			} else {
				$this->c0_thisCourseAncestorList = null;
			}//else
		} //if count...
	}//initialise2_makeMyAncestorList()

	/**
	 * @ensures settings for every ancestor of this course merged into c0_persistantSitewideSettingsArray
	 */	
	protected function initialise3_settingsFromMyAncestorList() {
		if (count($this->c0_persistantSitewideOverridesArray) > 0) {	// if ANY overrides defined...
			if ($this->c0_thisCourseAncestorList != null) {
				foreach ($this->c0_thisCourseAncestorList as $l_ancestorCourseId ) {//for each ancestor
					$l_ancestorOverrides = $this->extractDefaultOverridesForCourseId($l_ancestorCourseId);
					if ($l_ancestorOverrides!=null) {
						$this->mergeSitewideSettings( $l_ancestorOverrides );
					}//if array_key_exists
				}//foreach
			}//if isset
		}//if(count(..	
	}
	
	protected function initialise4_extractMyCourseOverrideSettings() {
		if (array_key_exists($this->c0_courseId , $this->c0_persistantSitewideOverridesArray)) { // find overrides for this course
			$this->c0_thisCourseOverridesFromDatabaseArray = $this->extractDefaultOverridesForCourseId($this->c0_courseId );
			$this->mergeSitewideSettings( $this->c0_thisCourseOverridesFromDatabaseArray );
		}//if(array_key_exists
		 else {
			$this->c0_thisCourseOverridesFromDatabaseArray = array();
		}//else
	}//initialise4_extractMyCourseOverrideSettings()

	// ------------- mutators -----------------
	protected function mergeSitewideSettings (&$p_newArray) {
		// 1. incorporate simple values into simple string=>string array
		$this->c0_persistantSitewideSettingsArray = array_merge( 
										$this->c0_persistantSitewideSettingsArray
									,	$p_newArray );
		// 2. extract multiselectors & incorporate them into multiselects.
		$this->updateMultiselectsWithInput( $p_newArray );
		// NB: THE MULTISELECT FIELDS IN c0_persistantSitewideSettingsArray WILL BE OUT OF SYNC
	}	
	//=========================================================
	//	protected static functions
	//=========================================================
	
	//---------------------------------------------------------
	// ancestor course IDs
	//---------------------------------------------------------
	/**
	* @return IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR if $p_courseId has no parent or parent course id
	*/	
	protected static function getAncestor( $p_courseId, &$p_inheritanceArray ) {
		if ($p_inheritanceArray == null) {
			return IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR;
		}
		return (array_key_exists( $p_courseId, $p_inheritanceArray ))
			? $p_inheritanceArray[$p_courseId]
			: IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR ;
	}
	
	protected static function parseAncestors(&$p_ancestorString) {
		// returns array
		return IcandConstantsBackend::explode2( 
							IcandConstantsBackend::INHERITANCE_SEPARATOR 
						,	IcandConstantsBackend::INHERITANCE_CONNECTOR
						,	preg_replace( IcandConstantsBackend::PREG_REMOVE_FROM_INHERITS, '', $p_ancestorString ));
	}//parseAncestors(..)
	
	/**
	* @return list of course ids of ancestor, or false if a loop detected
	* direct parent is first in list, most remote ancestor is last in list
	* if no ancestors, returns empty list
	*/	
	protected static function makeAncestorList ( $p_myCourseId, &$p_inheritanceArray ) { //$p_inheritanceString )
		$l_result = array();
		$l_targetCourse = $p_myCourseId;
		while (isset( $p_inheritanceArray[$l_targetCourse] )) {
			$l_targetCourse = $p_inheritanceArray[$l_targetCourse];
			if ($l_targetCourse == $p_myCourseId) {
				return false; // ERROR: the startpoint is a descendant of itself
			}
			$l_result[] = $l_targetCourse;
		}//while
		return $l_result;
	}//makeAncestorList(..)	

	//---------------------------------------------------------
	// overrides
	//---------------------------------------------------------	
	protected static function parseSiteOverrides(&$p_overrideString) {
		// returns array
		return IcandConstantsBackend::explode2( 
							IcandConstantsBackend::OVERRIDE_ALL_SEPARATOR 
						,	IcandConstantsBackend::OVERRIDE_ALL_CONNECTOR
						,	$p_overrideString ); 
	}//parseSiteOverrides(..

	protected static function getPOSToverrides ( &$p_postArray, $p_keyPrefix='' ) {
		$l_result = array();
		if (isset($p_postArray[IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR])) {
			$l_result[IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR] = true;
		}
		$l_lengthOfPrefix = strlen ($p_keyPrefix);
		foreach ( array_keys( $p_postArray ) as $l_key ) {
			if (($l_lengthOfPrefix>0) and (strpos($l_key, $p_keyPrefix) === false)) { // if not from Moodle settings, put it in results
				$l_result[$l_key] = $p_postArray[$l_key];
			} else { //fields starting with $p_keyPrefix
				$l_value = $p_postArray[$l_key];
				if (is_array( $l_value )) {//multi selectors
					unset ($l_value[IcandConstantsHTMLForms::FORM_FIELD_SPECIAL_MOODLE_THING]);	// Moodle adds a hidden field. Dunno why.		
					if (isset($l_value[0])) { // associative array or sequential (list)?
						$l_value = implode ( IcandConstantsBackend::MULTISELECT_COMBINER , array_keys($l_value) );//dump the values (they are only '1' )
					} else {
						$l_noninherited_values = array();					
						foreach ($l_value as $l_value_key => $l_value_value ) { // purge any inherits
							if ($l_value_value !== $l_value_key.IcandConstantsBackend::PART_PLACE_JOINER.IcandConstantsHTMLForms::FORM_VALUE_INHERIT ) {
								$l_noninherited_values[] = $l_value_value;
							}
						}//foreach
						if (count( $l_noninherited_values ) > 0) {
							$l_value = implode( IcandConstantsBackend::MULTISELECT_COMBINER, $l_noninherited_values );// dump the keys
						} else {
							$l_value = null;
						}//else
					}//else
					if ( $l_value != null ) {
						$l_result[substr($l_key, $l_lengthOfPrefix)] = $l_value;
					}//if
				} elseif ( $l_value != IcandConstantsHTMLForms::FORM_SINGLE_VALUE_INHERIT ) { //ignores, if the value is inherit
					$l_result[substr($l_key, $l_lengthOfPrefix)] = $l_value;
					// strip $p_keyPrefix
				}//elseif
			}//if strpos
		}//foreach
		return $l_result;
	}//getPOSToverrides(..)

	protected static function purgeEmptyStrings(&$p_array, $p_isInsertDummies = false ) {
	// function by side-effect
		foreach ($p_array as $l_key=>$l_value) {
			if (is_string($l_value)) { //all should be string
				$l_value = trim($l_value);	// remove leading/trailing whitespace			
			}
			$p_array[$l_key] = $l_value;
		}//foreach
	}//purgeEmptyStrings(..)

	protected static function purgeDummyValues(&$p_array ) {
		foreach ($p_array as $l_key=>$l_value) {
			if 	(is_string($l_value) 
				&& ($l_value == IcandConstantsHTMLForms::FORM_VALUE_EMPTY)) {
				unset( $p_array [$l_key] );
			}
		}
	}//purgeDummyValues(..)

	protected static function getServerParameters($PAGE_theme_settings, $p_isInsertDummies=false) {
		$l_result = (Array)$PAGE_theme_settings;	// it is (stdClass) so coerce type
		self::purgeEmptyStrings( $l_result, $p_isInsertDummies );
		return $l_result;
	}//function getServerParameters(..)
}//class IcandSettingsBase

/* **************************************************************************** */
/* **************************************************************************** */
/* **************************************************************************** */

class IcandSettingsOverrides extends IcandSettingsBase {
	// transient data
	var $c1_overrideAncestorCourseId 	= IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR; //$l_PostOverrideAncestor
	var $c1_overridesSettingsFromForm 			= null; // changed later //l_themeParametersPostOverrides
//	var $c1_overrideAncestorCourseArray = null; // l_sitewideThemeAncestorCourseArray
	
	var $c1_changedOverrideValues		= false;
	
	//------------------------------------------------------------
	// constructor functions
	//------------------------------------------------------------
	public function __construct($p_courseId, &$p_theme_settings ) {
		//the 1st two aren't affected by course parent
		$this->c0_courseId  = $p_courseId;
		$this->initialise1_allSavedRawSettings( $p_theme_settings );
		// DON'T makeAncestorList yet. wait till overrides are initialised
	}//__construct(..)
	
	/**
	 * @param $p_overrideSource Array (string=>{string/Array}) from HTML <form>
	 * @param $p_fieldPrefix string A uniform prefix added by Moodle to all form fields
	 */
	public function setOverrideSettings( &$p_overrideSource, $p_fieldPrefix ) {
		// sets c1_overridesSettingsFromForm, c1_overrideAncestorCourseId
//		self::purgeDummyValues( $p_overrideSource ); // remove "inherit" values

		$this->initialise1a_overridesSettingsFromForm( $p_overrideSource, $p_fieldPrefix );

		// sets c0_thisCourseAncestorList
		$this->initialise2_makeMyAncestorList();//from parent class

		// updates c0_persistantSitewideSettingsArray, creates c0_thisCourseOverridesFromDatabaseArray
		$this->initialise3_settingsFromMyAncestorList();//from parent class
		// at this point c0_persistantSitewideSettingsArray incorporates all ancestors, but not current course overrides
		
		// find what is different between the overrides & underlying setttings
		$l_overrideDifferencesWithAncestors = array_diff_assoc( // TODO: MAYBE THIS IS WRONG FUNCTION
					$this->c1_overridesSettingsFromForm
				,	$this->c0_persistantSitewideSettingsArray );	
		$l_thisCoursePreviousOverrides = $this->extractDefaultOverridesForCourseId($this->c0_courseId);
		
		$l_oldWasEmpty = (($l_thisCoursePreviousOverrides == null) || (count($l_thisCoursePreviousOverrides)==0));
		$l_newIsEmpty  = (count($l_overrideDifferencesWithAncestors) == 0);		
		$this->c1_changedOverrideValues = ($l_oldWasEmpty !== $l_newIsEmpty);//1st test for change
		if ( !($l_oldWasEmpty ) && !($l_newIsEmpty) ) { // if both have values ..
			if (count($l_overrideDifferencesWithAncestors) != count($l_thisCoursePreviousOverrides)) {
				$this->c1_changedOverrideValues = true;	//different # of elements
				//2nd test. has count of overrides change?
			} else { // same number of elements, so compare values	
				$l_merge = array_merge( $l_thisCoursePreviousOverrides, $l_overrideDifferencesWithAncestors ); // later overwrites earlier
				$this->c1_changedOverrideValues = (count($l_merge) > count($l_thisCoursePreviousOverrides));
				if (!($this->c1_changedOverrideValues)) { // if still no difference found..
				// find if any old values were changed/removed
					$l_oldVsNew = array_diff_assoc( $l_thisCoursePreviousOverrides, $l_merge );//find old values that were overwritten
					$this->c1_changedOverrideValues = (count($l_oldVsNew) > 0);					
				}//if
			}//else
		}//if !..WasEmpty
		
		if ( $this->c1_changedOverrideValues ) {
			if ( $l_newIsEmpty ) {
				unset( $this->c0_persistantSitewideOverridesArray[$this->c0_courseId] );
			} else {
				$this->c0_persistantSitewideOverridesArray[$this->c0_courseId] =
							'{' 
						. 	(IcandConstantsBackend::implode2(
								IcandConstantsBackend::OVERRIDE_ONE_SEPARATOR . IcandConstantsBackend::STR_APPEND_TO_OVERRIDES
							,	IcandConstantsBackend::OVERRIDE_ONE_CONNECTOR
							,	$l_overrideDifferencesWithAncestors )
							); // double handling with initialise4_extractMyCourseOverrideSettings, but ensures all data is consistent 
			}//else			
		}
		$this->initialise4_extractMyCourseOverrideSettings();//get overrides from $this->c0_persistantSitewideOverridesArray
	}//setOverrideSettings
	
	//------------------------------------------------------------
	// 
	//------------------------------------------------------------
	
	/* read-only comparators */
	public function isInheritanceChanged() {
		return ( $this->c0_persistantAncestorCourseId != $this->c1_overrideAncestorCourseId );
	}

	/* read-only getters */
	public function getAncestorCourseId() { // PUBLIC
	// overrides inherited
		return $this->c1_overrideAncestorCourseId;
	}//getAncestorCourseId	
	
	public function isChangedOverrideValues() {
		return $this->c1_changedOverrideValues;
	}//isChangedOverrideValues(			
	
	public function getOverrideSettingsArray() {
	// overrides inherited
	// returns whatever was received from form
		if ( $this->c1_overridesSettingsFromForm == null ) {
			return parent::getOverrideSettingsArray();
		} elseif ($this->c0_thisCourseOverridesFromDatabaseArray == null ) {
			return $this->c1_overridesSettingsFromForm;
		} else { // neither are null
			return array_merge( $this->c0_thisCourseOverridesFromDatabaseArray,
								$this->c1_overridesSettingsFromForm );
		}
	}//getOverrideSettingsArray
	
	//=========================================================
	//	load transient data
	//=========================================================
	
	protected function updateAncestor() { // called by initialise1a_overridesSettingsFromForm
		// update site-wide inheritance with any form-posted values
		// requires: c0_persistantSitewideAncestorCourseArray!=null && isArray()
		if ($this->c1_overrideAncestorCourseId==IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR) {
			if (count($this->c0_persistantSitewideAncestorCourseArray) != 0) {
				if (array_key_exists($this->c0_courseId ,$this->c0_persistantSitewideAncestorCourseArray)) {
					unset($this->c0_persistantSitewideAncestorCourseArray[$this->c0_courseId]); //remove old key
				}
			}
		} else { // add new key
			$this->c0_persistantSitewideAncestorCourseArray[$this->c0_courseId ] = $this->c1_overrideAncestorCourseId; 
		}//if..else	
	}//updateAncestor()

	protected function initialise1a_overridesSettingsFromForm( &$p_overrideSource, $p_fieldPrefix ) {
	/** 
	* sets c1_overridesSettings with clean content of $p_overrideSource
	* @requires $p_overrideSource does not contain keys: sys_override, sys_inherit
	* @ensures IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE does NOT exist in c1_overridesSettings
	*/
		$l_overrideSettingsFromForm = self::getPOSToverrides( $p_overrideSource, $p_fieldPrefix);//get override values
//		self::purgeEmptyStrings($l_overrideSettingsFromForm,true);//just trims. Not much use.
		$l_formParentCourseKey = IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE; // just to reduce text in this function
		
		$l_tempParentCourseId = IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR;	// default
		// 1st, ignore parent setting if it isn't a real course ID
		if (isset($l_overrideSettingsFromForm[$l_formParentCourseKey])) {
			$l_tempValue = $l_overrideSettingsFromForm[$l_formParentCourseKey]; // get value
			unset( $l_overrideSettingsFromForm[$l_formParentCourseKey] );		// remove from form values
			if ( 	($l_tempValue != IcandConstantsHTMLForms::FORM_VALUE_EMPTY ) //ignore bogus values
				&& 	($l_tempValue != '' ) ) {
				$l_tempParentCourseId = $l_tempValue;
			}//if
		}//if isset(...
		$this->c1_overrideAncestorCourseId = $l_tempParentCourseId;
		if ( $this->isInheritanceChanged() ) { // test if parent course now different to value from database
			$this->updateAncestor();			
		}//if
		$this->c1_overridesSettingsFromForm = $l_overrideSettingsFromForm;
	}//initialise1a_overridesSettingsFromForm(..)
	
}//class IcandSettingsOverrides
}//if(class_exists