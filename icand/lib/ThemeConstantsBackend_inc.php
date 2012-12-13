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
 */
if(class_exists('IcandConstantsBackend') != true) {

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsCSS_inc.php');	//names of CSS classes/ids 

class IcandConstantsBackend {

static $g_shortThemeName = 'icand'; // $THEME->name !!! doesn't work for pop-up!!
static $g_wholeThemeName = 'theme_icand' ;

//======================================================================================
/**
 *	The following are used in the datbase &/or to communicate between code modules in the theme.
 */

const LIGHT_SET = 0;
const DARK_SET 	= 1;

/** theme configuration array maps key::string => value::mixed
 *	Keys are either DB_FIELD_ 	= same as database key
 *					_CALC_	= calculated, based on other values
 *	Values are either 	???			= as stored in database
 *						_REWRITE_	= modified
 */
const DB_FIELD_PAGE_LAYOUT					= 'page_layout';

//--------------------------------------------------------------------------- 
const DB_FIELD_DECORATION_SIDEBAR_TARGET 	= 'layout_decorations';
const CONFIG_REWRITE_PUSHTOSECTION 			= 'push_to_section';
const CONFIG_REWRITE_DECORATION_TARGET 		= 'main_decorations';
const CONFIG_REWRITE_DECORATION_TARGET_BOX 	= 'box';
const CONFIG_REWRITE_DECORATION_TARGET_CELL	= 'cell';
//---------------------------------------------------------------------------
const DB_FIELD_DECORATION_COURSE_TARGET		= 'layout_coursesections';
const DB_VALUE_DECORATION_COURSE_COMBO		= 'coursesections_combined';
const DB_VALUE_DECORATION_COURSE_INDIV		= 'coursesections_individual';
//--------------------------------------------------------------------------- 
const CONFIG_CALC_SHOWSIDE_PREFIX 			= 'page_showside';	// 'pre' or 'post' are suffixes
const CONFIG_CALC_COMBINEBLOCKS	  			= 'local_combinePreAndPost';
//--------------------------------------------------------------------------- 
const DB_FIELD_DESKTOP_TILE_GRAPHIC 		  = 'desktoptilegraphic';
const DB_VALUE_BACKGROUNDIMAGE_BUILTIN_PREFIX = 'builtinTile';	// IcandConfigOptions::$sc_optionsBuiltinTilesNames

static $COURSE_OVERRIDES_NOT_ALLOWED = array (	'sys'		// a whole group
									, 	'permit'	// a whole group
									,	'foottxtpre'	// not yet set up for inheritance
									,	'foottxtmid'	// not yet set up for inheritance
									,	'foottxtpost'   // not yet set up for inheritance
								);
//--------------------------------------------------------------------------- 

// strings added at the end of database keys to designated related data fields
const SETTINGS_STYLE_SUFFIX = 'style';	// stretching/repeating layout for background images
const SETTINGS_URL_SUFFIX 	= 'url';
const SETTINGS_ALT_SUFFIX 	= 'alt';	// alt text for images
const SETTINGS_TARGET_SUFFIX= 'target'; // values are zones in html page

const SETTINGS_IMG_SUFFIX 	= 'img';

// for lookups in /lang/x/theme_icand.php
const DISPLAY_PREFIX_FOR_TITLES 			= 't.';		
const DISPLAY_PREFIX_FOR_DESCRIPTIONS 		= 'd.';
const DISPLAY_PREFIX_FOR_SUPERGROUPS		= 'sg.';
const DISPLAY_PREFIX_FOR_PERMISSIONS		= 'th_perm.';
const DISPLAY_PREFIX_FOR_SETTINGS_GROUPS 	= 'settingsPageMenu'; // a prefix used in get_string by settings page, for main blocks
const DISPLAY_PREFIX_FOR_XY_OPTIONS			= 'XY_OPTION';
const DISPLAY_PREFIX_FOR_MONO_OPTIONS		= 'MONO_OPTION';


//const SETTINGS_IMG_DATATYPE_URL = 'url';	// a value stored in database

const MOODLE_BUILTIN_ID_FOR_SETTINGS_FORM = 'adminsettings';

const DUMMY_VALUE_FOR_NO_ANCESTOR		= 0;
const SETTINGS_CALCULATED_PARENT_COURSE =  'sys_parentcourse'; //'override_inherit';	//a new parent course

const DB_FIELD_COURSE_ANCESTOR	= 'sys_inherit'; // key for LIST of all course inheritance
const DB_FIELD_COURSE_OVERRIDES	= 'sys_override';// key for LIST of all course overrides

// for permissions
const DB_FIELD_PERMIT_PREFIX		= 'permit_';
const DB_FIELD_PERMIT_MASTER 		= 'permit_master';
	const DB_VALUE_PERMIT_MASTER_NONE 	= 'permit_master_none';
	const DB_VALUE_PERMIT_MASTER_SOME 	= 'permit_master_mixed';
	const DB_VALUE_PERMIT_MASTER_ALL  	= 'permit_master_all';

const DB_VALUE_PERMISSION_LEVEL_NONE 		  	= 0;	
const DB_VALUE_PERMISSION_LEVEL_COURSE_EDITOR 	= 3;
const DB_VALUE_PERMISSION_LEVEL_COURSE_CREATOR 	= 5;
const DB_VALUE_PERMISSION_LEVEL_SITE_ADMIN	 	= 8;
const DB_VALUE_PERMISSION_LEVEL_ULTIMATE 	  	= 9;

// fields 'colour_offsets', 'clr_off_dt', 'clr_off_he', 'clr_off_tb', 'clr_off_mm', 'clr_off_fo'
const DB_VALUE_COLOUR_OFFSET_NOTSET 		= 'x';
const DB_VALUE_COLOUR_OFFSET_BORDER_ONLY 	= 'n';
const DB_VALUE_COLOUR_OFFSET_MONO 	   		= 'm';
const DB_VALUE_COLOUR_OFFSET_BW 			= 'w';


const DB_FIELD_MENU_SITENAVPLACEMENT = 'menu_sitenavplacement'; // a combo field

//--------------------------------------------------------------------

public static function isTransparentColour( $p_colourOffsetCode ) {
	return (($p_colourOffsetCode == self::DB_VALUE_COLOUR_OFFSET_BORDER_ONLY)
		or ($p_colourOffsetCode == self::DB_VALUE_COLOUR_OFFSET_NOTSET));
}

//--------------------------------------------------------------------

// used to construct strings for inheritance-related fields in database
const INHERITANCE_CONNECTOR			= '=>';
const INHERITANCE_SEPARATOR			= ',';
const OVERRIDE_ALL_CONNECTOR		= '=>';
const OVERRIDE_ALL_SEPARATOR		= '},';
const OVERRIDE_ALL_TERMINATOR		= '}';
const OVERRIDE_ONE_CONNECTOR		= ':';
const OVERRIDE_ONE_SEPARATOR		= ';';

const MULTISELECT_COMBINER			= ',';
const PART_PLACE_JOINER				= '~';		// this char not allowed by get_string(..).

const HTML_PLACE_SIDE_JOINER		= '.';

//====================================================================

const PREG_REMOVE_FROM_OVERRIDES = '/\R/';	// \R=newlines		//'/\s+/'; // all whitespace
const STR_APPEND_TO_OVERRIDES	 = "\n";  // add new line after each clause
const PREG_REMOVE_FROM_INHERITS  = '/\s+/';	// all whitespace
const STR_APPEND_TO_INHERITS	 = "\n";  // add new line after each clause	

//====================================================================

// match gradient code to CSS classes
static $GRADIENT_DECODE = array (		// keys changed to uppercase, 02Oct2012
		'LT' => IcandConstantsCSS::CSS_GRADIENT_LT 
	,	'DT' => IcandConstantsCSS::CSS_GRADIENT_DT 
	,	'LB' => IcandConstantsCSS::CSS_GRADIENT_LB 
	,	'DB' => IcandConstantsCSS::CSS_GRADIENT_DB 
);

// match short codes to long codes. 
// (The long codes were from an earlier generation of code. These could be consolidated, except alpha versions of the
// theme were released into production, so it is dangerous to retro-fit the shorter versions.
static $PLACE_DECODE = array (
		'dt' => 'metadesktop'			
	,	'he' => 'metaheader'
	,	'h1' =>	'metaheader1'
	,	'h2' =>	'metaheader2'
	,	'h3' => 'metaheader3'
	,	'tb' => 'metatoolbar'
	,	't1' => 'metatoolbar1'
	,	't2' => 'metatoolbar2'
	,	'mm' => 'metamain'
	, 	'cm' =>	'courseMain'
	,	'ce' => 'coursePre'
	,	'co' =>	'coursePost'
	,	'fo' => 'metafooter'
	,	'f1' => 'metafooter1'
	,	'f2' => 'metafooter1'
	,	'f3' => 'metafooter1'	
);
//public static function decodePlace( $p_placeShort ) { return this::$PLACE_DECODE[$p_placeShort ]; }
	
const PLACE_HEADER				= 'header';
const PLACE_FOOTER				= 'footer';
	
static $LOGO_PLACES 			= array (self::PLACE_HEADER , self::PLACE_FOOTER);
static $TWO_POSITION_SUFFIX		= array ( 'pre', 'post' );

const LOGO_TAG_CORE 			= 'logo';

/**
 * For each key, give the element that immediately underlies it in the page HTML model.
 */ 
static $sc_underlyingElement = array (
		'courseMain'	=> 'coursepage'
	,	'coursePre'		=> 'coursepage'
	,	'coursePost'	=> 'coursepage'
	
	,	'coursepage'	=> 'metamain'
	,	'metatoolbar'	=> 'metamain'
	
	,	'metaheader'	=> 'metapage'
	,	'metamain'		=> 'metapage'	
	,	'metafooter'	=> 'metapage'

	,	'metapage'		=> 'metadesktop'
	,	'metadesktop'	=> null
);

	public static  function getUnderlyingElement( $p_elementId ) {
	if (is_numeric( substr( $p_elementId, -1 )) ) {
		return substr( $p_elementId,0, -1 );
	} else {
		return self::$sc_underlyingElement[$p_elementId];
	}
}//getUnderlyingElement(..)


// FUNCTIONS =======================================================

 /* WHAT A PAIN!! Moodle's get_string($identifier[,$plugin_name]) in /lib/moodlelib.php runs clean_param($identifier, PARAM_STRINGID)
 * on all key values, and returns an empty string if there is an "invalid" character. 
 * Valid characters for PARAM_STRINGID are: '|^[a-zA-Z][a-zA-Z0-9\.:/_-]*$|' 
 * 		SO: $string[$identifier] CAN'T HAVE THE FOLLOWING CHAR IN $identifier: ~ ! space
 */
	static $BAD_CHAR_FOR_GET_STRING = array ( self::PART_PLACE_JOINER, self::MULTISELECT_COMBINER, ' ' );
	
	public static function makeIdSafeForGetString ( $identifier ) {
		return str_replace( self::$BAD_CHAR_FOR_GET_STRING, '_', $identifier );	// replace any banned char with '_'
	}
	

//================================================================================
// 		some utility string <=> array functions
//--------------------------------------------------------------------------------
/**
 * if a row includes $p_delim2, the result will contain ROW_BEFORE_DELIM => ROW_AFTER_DELIM
 * if a row doesn't include $p_delim2, the result will contain ROW => null
 */
	public static function explode2 ( $p_delim1, $p_delim2, $p_string, $p_limit=2 ) {
	// returns empty array if $p_string is null 
		$l_result = array();
		if (!empty( $p_string )) {
			$l_temparray =  explode( $p_delim1, $p_string );
			if ( $l_temparray !== FALSE ) {
				foreach ( $l_temparray as $l_string ) {
					list ($key,$value) = explode( $p_delim2, $l_string, $p_limit );//only use 1st instance of delimiter
					$l_result[$key] = $value;
				} // foreach
			}//if
		}//if !empty
		return $l_result;
	}//explode2(..)

	public static function implode2 ( $p_delim1, $p_delim2, &$p_array ) {
	// returns empty string if $p_array is null or empty
		$l_result = '';
		if (!empty( $p_array )) {
			$l_tempArray = array();
			foreach ( $p_array as $l_key => $l_value ) {
				$l_tempArray[] = ( $l_value == null)		// to make reciprocal of explode2
								? $l_key
								: $l_key . $p_delim2 . $l_value;
			}//foreach
			$l_result = implode ( $p_delim1, $l_tempArray );	
		}//if !empty
		return $l_result;
	}//implode2(..)
	
	public static function explodeMultiselect( $p_multiString, $p_deleteValue=null ) {
		$l_result = self::explode2(
							self::MULTISELECT_COMBINER
						,	self::PART_PLACE_JOINER
						,	$p_multiString );
		$l_badKeys = array_keys( $l_result, '' ); // remove entries with blank values
		foreach ($l_badKeys as $l_badKey ) {
			unset( $l_result[$l_badKey] );
		}//foreach
		
		if ($p_deleteValue!==null) {
			$l_badKeys = array_keys( $l_result, $p_deleteValue );
			foreach ($l_badKeys as $l_badKey ) {
				unset( $l_result[$l_badKey] );
			}//foreach
		}//if
		return $l_result;
	}
	
	public static function implodeMultiselect( &$p_multiArray ) {
		return self::implode2(
							self::MULTISELECT_COMBINER
						,	self::PART_PLACE_JOINER
						,	$p_multiArray );
	}
}//class

}//if defined