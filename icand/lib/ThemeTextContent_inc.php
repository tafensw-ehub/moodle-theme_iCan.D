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
 * class IcandTextContent
 *
 * Stores & provides strings to be included in headers & footer of theme pages
 * uses global $OUTPUT, $PAGE (in set(..), to eval )
 * 
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		03Oct2012
 * @todo		1. use language lookup for labels in HIRES_HTML, FONT_HTML
 *				2. get(..) doesn't use type or alt data
 *				3. deal with when override button is NOT being displayed inline. Inject HTML elsewhere.
 *				4. move 'HiRes on', "A+" etc to lang/en
 *
 * This class supports positioning of textual element and buttons in a pages' header, footer, toolbars.
 * 
 */
if(class_exists('IcandTextContent') != true)
{

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsCSS_inc.php');	
//  IcandConstantsCSS::CSS_FLOAT_BUTTON_CONTAINER
class IcandTextContent {

// these will FLOAT so list them in FLOAT order, not left-to-right



var	$c_isHires 						= false;
var $c_includedElementList			= null;
var $c_removeTitleFromCustomMenu 	= false;	// prevent tooltips in custom menu
var $c_removeTitleFromBreadcrumbs 	= false; // prevent tooltips in breadcrumbs

//consts used in regular expression functions
const HREF_PATTERN = '/(<a\s+.+<\/a>)/Ui';
const TITLE_PATTERN = '/title=".*"/Ui';

//===============================================================
// text components and special (inline) styling
//---------------------------------------------------------------
	var $myTextMapping 		= array();
	var $myTextMappingType	= array();
	var $myTextMappingAlt	= array();
	var $myHTMLTextJoiner	= '&nbsp;'; // multiple calls to set(...) append strings
	var $myHTMLIdJoiner 	= '&mdash;' ;
	var $myIdElementArray = array 	( 0 => array('header1.pre','<h1>','</h1>')
									, 1 => array('header2.pre','<h2>','</h2>') );
									
	var $myLocationStyle = array (  'header1' => array('<h1>','</h1>')
								,	'header2' => array('<h2>','</h2>')
								,	'header3' => array('<h3>','</h3>')	
								);
	var $c_positionArray = array (
			'useredittools'	=> 'toolbar1.post'	// default, can be overridden
		);									

	//------------------------------------------------------------
	public function setElements ( &$p_elementArray, $p_displayRTL ) {
	global $OUTPUT, $PAGE;
	
		if (isset($p_elementArray[IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ] )) { // key=>value pair
			$l_idValue = $p_elementArray[IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ];
			if (strlen($l_idValue ) > 0 ) {
				$this->placeNavElements( $l_idValue, $p_displayRTL );
			}
		}
		
		if (isset($p_elementArray['menu_pageidplacement'] )) { // key=>value pair
			$l_idValue = $p_elementArray['menu_pageidplacement'];
			if (strlen($l_idValue ) > 0 ) {
				$this->placeIdElements( $l_idValue, $p_displayRTL );
			}
		}

		if (isset($p_elementArray['menu_userplacement'] )) { // key=>value pair
			$l_idValue = $p_elementArray['menu_userplacement'];
			if (strlen($l_idValue ) > 0 ) {
				$this->placeUserElements( $l_idValue, $p_displayRTL );
			}
		}
		
		$this->c_includedElementList = $p_elementArray;
		//------login/profile-----------------------------------
		$l_option = 0; 
		if ($this->isIncluded( 'logonlink')){
			$l_option = ($this->isIncluded( 'logonprofile'))
						? 3  // 2 options
						: 1; // 1 option
		} elseif ($this->isIncluded( 'logonprofile')){
			$l_option = 2; // only 1
		} elseif ($this->isIncluded( 'logonmoodle')){
			$l_option = 4;
		}
		if ($l_option != 0){
			$this->setCooked($this->getPosition('userlogonlink'), self::getLogin($l_option) );
		}
	//--------accessibility controls--------------------------
		$l_groupName = 'userDisplay';
		$l_position = $this->getPosition($l_groupName);
		if ($l_position !== false ) {
			if ($this->isIncluded( 'textsize')  ) {
				$l_FONT_HTML  = 	'<input type="button" class="' 
				.	IcandConstantsCSS::CSS_FLOAT_BUTTON_CONTAINER 
				.	'" value="A+" onclick="upSize();"/><input type="button" class="' 
				.	IcandConstantsCSS::CSS_FLOAT_BUTTON_CONTAINER 
				.	'" value="A=" onclick="defaultSize();"/><input type="button" class="'
				.	IcandConstantsCSS::CSS_FLOAT_BUTTON_CONTAINER 
				.	'" value="A-" onclick="downSize();"/>';			
			
				$this->setCooked($l_position,$l_FONT_HTML ); // FIRST, goes on outside
			}
			$this->c_isHires = ($this->isIncluded( 'hires'));			
			if ($this->isHires()) {
				$l_HIRES_HTML =	'<input type="button" class="' 
						.	IcandConstantsCSS::CSS_FLOAT_BUTTON_CONTAINER 
						.	'" id="HiRes" value="HiRes on" onclick="toggle_hires(this);"/>';
				$this->setCooked($l_position,$l_HIRES_HTML ); // LAST, goes on inside
			}
			// requires > 1 language configured for the site
			if ($this->isIncluded( 'lang')){
				$this->setCooked($l_position, $OUTPUT->lang_menu() );
			}		
		}//if
		
// @todo THIS NEEDS CHANGING, WHEN BUTTON FLOATS INSTEAD OF BEING EMBEDDED IN PAGE
		// add with 'useredittools'
		if ($this->isIncluded( 'themeoverride')) {
			$l_overrideButtonHTML = '<input type="button" id="'
					. IcandConstantsCSS::CSS_ID_EDITBUTTON 
					. '" class="'
					. IcandConstantsCSS::CSS_CLASS_BUTTON 
					. '" value="'
					. get_string( IcandConstantsCSS::CSS_ID_EDITBUTTON, IcandConstantsBackend::$g_wholeThemeName ) // Override theme
					. '" onclick="setitemdisplay(\''
						. IcandConstantsCSS::CSS_ID_EDITLAYER
						. '\',\'block\');"/>'  ;
			if ($this->isIncluded('themeoverride_fixed')) {	// SHOW_BUTTON_INLINE
				$this->setRaw('browserTop', $l_overrideButtonHTML );
			} else {	// FLOAT BUTTON
				$this->setCooked($this->getPosition('useredittools'), $l_overrideButtonHTML );
			}
		}

		$l_temp = trim($PAGE->button);
		if (strlen( $l_temp ) > 0) {
			$this->setCooked($this->getPosition('useredittools'),$l_temp); // edit course, search (in category view)	
		}
	}//setElements(..
	//------------------------------------------------------------
	public function isHires() {
		return $this->c_isHires;
	}
	
	public function setRemoveTitleFromCustomMenu( $p_value = true ) {
		$this->c_removeTitleFromCustomMenu = $p_value;
	}
	
	public function setRemoveTitleFromBreadcrumbs( $p_value = true ) {
		$this->c_removeTitleFromBreadcrumbs = $p_value;
	}	
	//------------------------------------------------------------
	public function setCooked( $p_key, $p_str, $p_type = 'text', $p_alt=null ) {
		$p_str = '<div class="' . IcandConstantsCSS::CSS_FLOAT_BUTTON_CONTAINER . '">' . $p_str . '</div>';
		$this->setRaw( $p_key, $p_str, $p_type, $p_alt);
	}//set()		
	
	public function addImage ( $p_position, $p_imgURL, $p_altText='') {
		$p_imgURL = trim( $p_imgURL );
		if (strlen( $p_imgURL ) > 0 )
			$this->setCooked($p_position,
				'<img src="'. $p_imgURL .'" alt="'. $p_altText .'"/>'
		);
	}//addImage	
	
	public function getKeysWithPrefix( $p_sPrefix ) {
	/** called from BuildPage
	 */
		// return array of all keys that start with $p_sPrefix  
		return preg_grep( '/^' . $p_sPrefix . '.*/i', array_keys($this->myTextMapping ) ); 
	}//getKeysWithPrefix()
	
//---------------------------------------------------------	
	public function isTextMapped( $p_key ) {
		return array_key_exists( $p_key,$this->myTextMapping );
	}

/**
* @return string = content for HTML page
*/ 	
	public function get( $p_key ) {
		$l_type = null;
		$l_alt 	= null;
		if ( array_key_exists($p_key,$this->myTextMappingType) ) {	// not used
			$l_type = $this->myTextMappingType[$p_key];
		}
		if ( array_key_exists($p_key,$this->myTextMappingAlt) ) { // not used
			$l_alt = $this->myTextMappingAlt[$p_key];
		}
		$l_result = $this->myTextMapping[$p_key];
		return $l_result;
	}//get()	
	//----------------------------------------------------
	protected function setRaw( $p_key, $p_str, $p_type = 'text', $p_alt=null ) {
		if ( $p_alt != null ) {
			$myTextMappingAlt[ $p_key] = $p_alt; // overwrite
		}
		if ( array_key_exists($p_key, $this->myTextMapping ) ) {
			$this->myTextMapping[$p_key] .= $this->myHTMLTextJoiner . $p_str;
		} else {
			$this->myTextMapping[$p_key] = $p_str;
		}
	}//set()
	
	protected function getPosition( $p_elementTag ) {
		if (array_key_exists($p_elementTag,$this->c_positionArray)) {
			return $this->c_positionArray[$p_elementTag ];
		} else {
			return false;
		}
	}
	//----------------------------------------------------
	protected function placeNavElements( $p_controlString, $p_displayRTL ){
		$l_pageIdParts	 = explode ( IcandConstantsBackend::MULTISELECT_COMBINER , $p_controlString  );
		foreach ( $l_pageIdParts as $l_pageIdAndPlace ) {
			list($l_part,$l_place) = explode( IcandConstantsBackend::PART_PLACE_JOINER, $l_pageIdAndPlace, 2 );
			if ( 	($l_place != IcandConstantsHTMLForms::FORM_VALUE_NONE )
				and	($l_place != IcandConstantsHTMLForms::FORM_VALUE_INHERIT )) {
				switch ($l_part) {
					case 'breadcrumbs':
						{$this->setCooked($l_place, $this->getNavbarSafe() ); break;}
					case 'moodlecustommenu':
						{$this->setCooked($l_place, $this->getCustomMenu()); break;}
				}//switch
			}//if
		}//foreach
	}//placeNavElements
	//----------------------------------------------------
	protected function placeUserElements( $p_controlString, $p_displayRTL ){
		$l_pageIdParts	 = explode ( IcandConstantsBackend::MULTISELECT_COMBINER , $p_controlString  );
		foreach ( $l_pageIdParts as $l_pageIdAndPlace ) {
			list($l_part,$l_place) = explode( IcandConstantsBackend::PART_PLACE_JOINER, $l_pageIdAndPlace, 2 );
			if ( 	($l_place != IcandConstantsHTMLForms::FORM_VALUE_NONE )
				and	($l_place != IcandConstantsHTMLForms::FORM_VALUE_INHERIT )) {
				switch ($l_part) {
					case 'userlogonlink':
					case 'userDisplay'	:
					case 'useredittools':
						{$this->c_positionArray[$l_part] = $l_place; break;}
					case 'usermoodledocs':
						{
							$this->setCooked($l_place, '<p class="helplink">'
												. page_doc_link(get_string("moodledocslink"))
												. '</p>' );
							break;
						}
					case 'userHomeButton' : //added 9Aug2012
						{
							$this->setCooked($l_place, $this->getHomeButton() );						
							break;
						}
				}//switch
			}//if
//			moodlecustommenu~header1.pre,breadcrumbs~inherit
		}//foreach	
	}
	
	protected function placeIdElements( $p_controlString, $p_displayRTL ){
	/**
	* @param string $p_controlString
	* @ensures selected site id, course id, category name, inserted into header 
	* @returns nothing
	*/
	global $SITE, $COURSE;
		// get all the text needed
		$l_siteString = ((strpos($p_controlString, 'idsite')) !== false)
							? $SITE->fullname
							: '';				
		$l_categoryString = ((strpos($p_controlString, 'idcat')) !== false)
							? trim($this->getCategoryNamesString($p_displayRTL))
							: '';
		$l_courseString = ((strpos($p_controlString, 'idcourse')) !== false)
							? $COURSE->fullname
							: '';
	
		$l_pageIdParts	= explode ( IcandConstantsBackend::MULTISELECT_COMBINER , $p_controlString  );
		$l_placements	= array();
		foreach ( $l_pageIdParts as $l_pageIdAndPlace ) {
			list($l_part,$l_place) = explode( IcandConstantsBackend::PART_PLACE_JOINER, $l_pageIdAndPlace, 2 );
			if ( 	($l_place != IcandConstantsHTMLForms::FORM_VALUE_NONE )
				and	($l_place != IcandConstantsHTMLForms::FORM_VALUE_INHERIT )) {
					if (!isset( $l_placements[$l_place] )) {
						$l_placements[$l_place] = array();
					}//if
					$l_placements[$l_place][] = $l_part;
			}//if
		}//foreach
		
		foreach ( $l_placements as $l_place => $l_partsArray ) {
			list($l_location,$l_side) = explode( IcandConstantsBackend::HTML_PLACE_SIDE_JOINER, $l_place, 2 );
			$l_styleParts = $this->myLocationStyle[$l_location ];
			$l_comboArray = array();
			if (in_array('idsite',$l_partsArray) && ($l_siteString!=''))
				$l_comboArray[] =  $l_siteString;
			if (in_array('idcat',$l_partsArray) && ($l_categoryString!=''))
				$l_comboArray[] = $l_categoryString ;
			if (in_array('idcourse',$l_partsArray) && ($l_courseString!=''))
				$l_comboArray[] = $l_courseString;
			if ($p_displayRTL)
				$l_comboArray = array_reverse($l_comboArray);
			$this->setCooked( $l_place, 
							$l_styleParts[0]
							. implode($this->myHTMLIdJoiner, $l_comboArray)
							. $l_styleParts[1] 
							);
		}//foreach
	}//placeIdElements(..)	
	//----------------------------------------------------
	protected function makeIdElements( $p_controlString, $p_displayRTL ){
	/**
	* @param string $p_controlString
	* @ensures selected site id, course id, category name, inserted into header 
	* @returns nothing
	*/
	global $SITE, $COURSE;
		if ( $p_controlString == 'none' ) {
			return;
		}
		$l_pageIdParts	 = explode ( IcandConstantsBackend::MULTISELECT_COMBINER , $p_controlString  );
		$l_siteString = ((strpos($p_controlString, 'idsite')) !== false)
							? $SITE->fullname
							: '';				
		$l_categoryString = ((strpos($p_controlString, 'idcat')) !== false)
							? trim($this->getCategoryNamesString($p_displayRTL))
							: '';
		$l_courseString = ((strpos($p_controlString, 'idcourse')) !== false)
							? $COURSE->fullname
							: '';
		$l_targetId = 0;
		foreach ( $l_pageIdParts as $l_pageIdPart ) {
			$l_partGroup = explode ('-' , $l_pageIdPart);
			if ( count($l_partGroup) == 1 ) {
				if ($l_partGroup[0]== 'idcourse') {
					$l_targetId = 1;
					$l_resultString = $l_courseString;
				} elseif ($l_partGroup[0]== 'idcat') {
					$l_targetId = (in_array('idcourse',$l_pageIdParts))
								? 0 	// put in top line, course on 2nd line
								: 1 ;   // put in 2nd line
					$l_resultString = $l_categoryString;
				} elseif ($l_partGroup[0]== 'idsite') {
					$l_targetId = 0;
					$l_resultString = $l_siteString;
				}
				$this->setCooked( $this->myIdElementArray[$l_targetId][0], $this->myIdElementArray[$l_targetId][1]  . $l_resultString. $this->myIdElementArray[$l_targetId][2]);
			} else {	// combined key
					$l_targetId = (in_array('idsite',$l_partGroup))
								? 0 : 1;
					$l_comboArray = array();
					if (in_array('idsite',$l_partGroup) && ($l_siteString!=''))
						$l_comboArray[] = $l_siteString;
					if (in_array('idcat',$l_partGroup) && ($l_categoryString!=''))
						$l_comboArray[] = $l_categoryString;
					if (in_array('idcourse',$l_partGroup) && ($l_courseString!=''))
						$l_comboArray[] = $l_courseString;
					if ($p_displayRTL)
						$l_comboArray = array_reverse($l_comboArray);
					$l_resultString = implode($this->myHTMLIdJoiner, $l_comboArray);
					$this->setCooked( $this->myIdElementArray[$l_targetId][0], $this->myIdElementArray[$l_targetId][1]  . $l_resultString . $this->myIdElementArray[$l_targetId][2]);	
			}//else
		}//foreach
	}//makeIdElements(..)

	//----------------------------------------------------
	protected function isIncluded( $p_searchKey  ) {
	/**
	* tests for existence of top-level selectors in input array
	*/
		return (in_array( $p_searchKey, $this->c_includedElementList ));
	}
	//----------------------------------------------------
	
	protected function getHomeButton() {
	/* this is multi-faced. In activity, returns to course. 
	In course, returns to site home.
	In site home, goes to Moodle.org
	*/
	global $OUTPUT;
		return  //  '<div class="' 
				//. IcandConstantsCSS::CSS_MOODLE_HOMEBUTTON
				//. '">'
				//. 
				$OUTPUT->home_link() ;
				// . '</div>';
	}


	protected function getCustomMenu() {
	global $OUTPUT;
		$l_result = $OUTPUT->custom_menu();
		if ( $this->c_removeTitleFromCustomMenu ) {
			$l_result = $this->stripTitleAttribute ( $l_result );
		}
		return $l_result;
	}//getCustomMenu
	
	protected function getCategoryNames(){
		global $COURSE, $PAGE;
		$resultArray = array();
		$singleName = '';
		if ( $COURSE->category > 0 ) {
			$singleName = $PAGE->categories[$COURSE->category]->name ;
		} 
		if ( $COURSE->category == 0 || $singleName == '' )
		{
			if ( count($PAGE->categories) > 0 ) {
				foreach ( $PAGE->categories as $l_category ) {
					$resultArray[] = $l_category->name;
				}//foreach
			}
		} else {
			$resultArray[] = $singleName;
		}
		return $resultArray ;
	}//getCategoryNames()

	protected function getCategoryNamesString($p_displayRTL) {
		$l_temp = $this->getCategoryNames();
		if (!$p_displayRTL) {
			$l_temp = array_reverse($l_temp,true);
		}
		$l_resultString = '';
		if (count($l_temp) > 0 ) {
			foreach ( $l_temp as $l_name )
				$l_resultString .= ($l_name . ' ');
		}
		return $l_resultString;
	}
	
	protected function getNavbar() {
	global $OUTPUT;
		$l_result = $OUTPUT->navbar();
		if ($this->c_removeTitleFromBreadcrumbs) {
			$l_result = $this->stripTitleAttribute( $l_result );
		}
		return $l_result;
	}
	
	protected function getNavbarSafe() {
	global $PAGE, $OUTPUT;
		if ($PAGE->has_navbar()) {
			return '<span class="' . IcandConstantsCSS::CSS_MOODLE_BREADCRUMB . '">'. $this->getNavbar() .'</span>';
		} else {	// changed 17Sep2012
//			$l_homeLinkHTML = $OUTPUT->home_link();
			return str_replace( IcandConstantsCSS::CSS_MOODLE_HOMEBUTTON, IcandConstantsCSS::CSS_MOODLE_BREADCRUMB, $OUTPUT->home_link() ); // change class name
			// return $OUTPUT->home_link();	// on start page = link to Moodle.org, otherwise home
		}	
	}//getNavbarSafe()
	
/**
*	@param $p_optionNumber integer  1=login/out link, 2=user profile, 3=both
*									4=use Moodle default
*	@return string of html
*/

const LOGIN_URL_CONTAINS 		= '/login/'; //'/login/logout.php' or '/login/index.php'
const PROFILE_URL_CONTAINS		= 'profile.php';
const SWITCHROLE_URL_CONTAINS	= 'switchrole=';
const SWITCHURSER_URL_CONTAINS	= 'loginas.php';
// * logout message includes login/logout.php
// * profile includes user/profile.php
// * return to role includes switchrole= (if user has switched role)
// * log in as yourself include loginas.php (if user switched to other user's login)

	
	protected function getLogin($p_optionNumber) {
	global $OUTPUT;
		$l_result = $l_moodleString = $OUTPUT->login_info();//get the string from moodle
		if ($p_optionNumber < 4) { // option 4, no further processing needed
//			$l_pattern = '/(<a\s+.+<\/a>)/Ui';
			$l_hrefs = array();
			// get all hrefs in login message
			$l_count = preg_match_all( self::HREF_PATTERN, $l_result, $l_hrefs );
			$l_result = '';	// default. Suppresses "You are not logged in."
			if ($l_count > 0) {// will be 0 if "You are not logged in."
				$l_loginLink = '';
				if ($p_optionNumber==1 or $p_optionNumber==3) {// login/logout button
					foreach ($l_hrefs[0] as $l_href ) {
						if ( strpos($l_href,self::LOGIN_URL_CONTAINS)!==false) {
							$l_loginLink = $l_href;
							break;
						}
					}//foreach
				}//if
				
				$l_profileLink = '';
				$l_isSwithrole = false;
				if ($p_optionNumber==2 or $p_optionNumber==3) {// profile link
					$l_switchLink = null;
					foreach ($l_hrefs[0] as $l_href ) {
						if ( strpos($l_href,self::PROFILE_URL_CONTAINS)!==false) {
							$l_profileLink = $l_href;
						} elseif ( strpos($l_href,self::SWITCHROLE_URL_CONTAINS)!==false) {
							$l_switchLink = $l_href;
							$l_isSwithrole = true;
						} elseif ( strpos($l_href,self::SWITCHURSER_URL_CONTAINS)!==false) {
							$l_switchLink = $l_href;
						}
					}//foreach
					if ($l_switchLink != null){
						if ($l_isSwithrole) {
							$l_profileLink = substr( $l_moodleString, strpos($l_moodleString, '<a' ) ); 
						} else {
							$l_profileLink = '[' . $l_switchLink . '] ' . $l_profileLink;
						}
					}
				}//if			
				
				$l_result = '<div class="' . IcandConstantsCSS::CSS_MOODLE_LOGININFO .'">';
				switch ($p_optionNumber){
					case 2: {$l_result .= $l_profileLink; break;} // profile only
					case 3: {$l_result .= $l_profileLink . ' '; }	// both. dropthrough
					case 1:	{$l_result .= $l_loginLink; break;}	// login/out only					
				}//switch
				$l_result .= '</div>';
			}//$l_count>0
		}
		return $l_result;
	}//getLogin
	
	protected function stripTitleAttribute ( $p_intputHTML ) { 
		return preg_replace ( self::TITLE_PATTERN, '' , $p_intputHTML );
	}

}//class themeTextContent
}//class exists