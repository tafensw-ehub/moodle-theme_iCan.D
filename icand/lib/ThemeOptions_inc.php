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
 * Defines values that may be changed for Moodle's TAFE eHub iCan.D. theme.
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
/**
* TO DO: some strings to be moved to lang/en file
* changes: 
*	25July2012 glen: added background image option 'middle'
*	03Aug2012 glen: added getDockingBodyClass(..) support
*	26Sep2012  glen: extended $sc_backgroundImgTreatments for scrolling/no scrolling
*/ 
 
if(class_exists('IcandConfigOptions') != true) {

require_once(dirname(__FILE__) . '/../lib/ThemeWholePageOptions_inc.php');	// class IcandWholePageOptions
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	// class IcandConstantsBackend
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsHTMLForms_inc.php');	// IcandConstantsHTMLForms::FORM_KEY_* 
 
class IcandConfigOptions {

//todo: maybe move this next value to IcandConstantsBackend??
public static $SC_INHERIT_ARRAY = array( IcandConstantsHTMLForms::FORM_VALUE_INHERIT => 'inherit' );

public static function getAllConfigKeys () {
	return self::$sc_titleKeys;
//	return array_keys( self::$sc_title );
}//getAllConfigKeys()

public static function getAllMultiselectKeys () {
	return array_keys( self::$sc_two_axis_options );
}//getAllMultiselectKeys()

public static function getAllConfigKeysAndDefaults() {
	$l_all_keys = self::getAllConfigKeys();
	$l_result = array();
	foreach ( $l_all_keys as $l_key ) {
		$l_result[$l_key] = self::getDefault( $l_key );
	}
	return $l_result;
}//getAllConfigKeysAndDefaults()

public static function getSuffixesOfSectionsWithColour () {
	return self::$sc_sectionsWithColours_suffix;
}

public static function getTitle($p_sectionName) {
return get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . $p_sectionName, IcandConstantsBackend::$g_wholeThemeName);
}//getTitle(..

public static function getDescription($p_sectionName) {
	return get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . $p_sectionName, IcandConstantsBackend::$g_wholeThemeName);
}//getDescription(..

public static function getDefault($p_sectionName) {
	if (array_key_exists( $p_sectionName, self::$sc_oneDimensionOptionsArray )) {
		$l_optionBlock = self::$sc_oneDimensionOptionsArray[$p_sectionName];
		if ($l_optionBlock[0] == null) {
			return '';		// TODO: fix for dynamic default lists
		} else {
//			$l_optionKeys = array_keys($l_optionBlock[0]);
			return $l_optionBlock[0][$l_optionBlock[1]];	// #1 is index into array #0
		}
	} elseif (array_key_exists( $p_sectionName, self::$sc_simpleOptionDefaultArray ))  {
		return self::$sc_simpleOptionDefaultArray[$p_sectionName];
	} else {
		return '';
	}
}//getDefault(..)

public static function hasOptionValues($p_sectionName) {
	return (array_key_exists( $p_sectionName, self::$sc_oneDimensionOptionsArray ));// self::$sc_optionValues ));
}//hasOptionValues(..)

protected static $sc_sectionsWithColours_suffix = array ( 
// prefix = clr_off_ and clr_pal_
		'dt', 'he', 'tb', 'mm', 'fo'
);

protected static $sc_sectionsWithColourOffset = array ( 
		'colour_offsets'
	,	'clr_off_dt', 'clr_off_he', 'clr_off_tb', 'clr_off_mm', 'clr_off_fo'
);
			
protected static $sc_sectionsWithColourPalette = array ( 
		'colour_palettes'
	,	'clr_pal_dt', 'clr_pal_he', 'clr_pal_tb', 'clr_pal_mm', 'clr_pal_fo'
);

/*
protected static $sc_sectionsWithPermissionLevels = array (
		'permit_menu', 'permit_header', 'permit_footer', 'permit_layout', 'permit_pagewidestyle'
	, 	'permit_pagewideimg' , 'permit_headfoot_bg', 'permit_colour'
);
*/

public static $sc_permissionLevels	= array (
		IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_NONE
	, 	IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_EDITOR
	,	IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_CREATOR
	,	IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_SITE_ADMIN
);

// =========================================================================================
//
//		functions to get sets of values for theme options
//
// =========================================================================================

/**
 * @param $p_sectionName string Id of input component
 * @return array [0] = array (x_key => display string), [1] = array (y_key => display string)
 */
public static function getTwoAxisOptionValues($p_sectionName ) { //,$p_addInherit=false) {
	// N.B. admin_setting_configtwoaxismultiradio2 prepends IcandConstantsHTMLForms::FORM_VALUE_INHERIT when needed
	
	if (in_array( $p_sectionName, self::$sc_sectionsWithColourOffset )) {  // special case, which has a shared set of x values
		$result = array();
		$result[0] = self::$sc_colourOffsetsForTwoAxis;
		$result[1] = self::$sc_two_axis_options[$p_sectionName][1];
	} elseif (in_array( $p_sectionName, self::$sc_sectionsWithColourPalette )) { // special case, which has a shared set of x values
		$result = array();
		$result[0] = self::$sc_colourPalettesForTwoAxis;
		$result[1] = self::$sc_two_axis_options[$p_sectionName][1];	
	} else {													// the general case
		$result = self::$sc_two_axis_options[$p_sectionName];
	}
	return $result;	
}//getTwoAxisOptionValues(..

public static function getOneAxisOptionValues( $p_elementName ) {
	if (is_null(self::$sc_oneDimensionOptionsArray[$p_elementName][0])) {
		switch ($p_elementName) {
			case 'desktoptilegraphic' : {
				self::$sc_oneDimensionOptionsArray[$p_elementName][0] = 	
					array_merge( 
						array('') //=> get_string( 'VALUE_NONE', IcandConstantsBackend::$g_wholeThemeName ))  // '* none *')
						, self::$sc_optionsBuiltinTilesNames
					);
				break;
			}
			case 'headerbackgroundimg' : {
				self::$sc_oneDimensionOptionsArray[$p_elementName][0] =
					array_merge( 
						array('') //=> get_string( 'VALUE_NONE', IcandConstantsBackend::$g_wholeThemeName ))  // '* none *')
						, self::$sc_optionsBuiltinStretchNames
					);
				break;
			}
			default : {	// meta*imgstyle
				self::$sc_oneDimensionOptionsArray[$p_elementName][0] =
						self::$sc_backgroundImgTreatments;
				break;			
			}
		}//switch
	}
	return self::$sc_oneDimensionOptionsArray[ $p_elementName ][0];
	//self::$sc_optionValues[ $p_elementName ];
}//getOneAxisOptionValues(..

// =========================================================================================
//
// =========================================================================================

//public static function makeHeadingStringLookup ($p_sectionName) {
//	return IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . $p_sectionName;
//}

public function getAllHeadingCodes() {
	return self::$sc_headingsCodeArray;
}//getAllHeadingCodes()

/**
 * @param $p_builtinFileHandle  =string e.g. 'builtinTileDiagonals'
 * @return string 
 */
public static function getBuiltinFile( $p_builtinFileHandle ) {
	if (array_key_exists($p_builtinFileHandle, self::$sc_optionsBuiltinTilesFiles)) {
		return self::$sc_optionsBuiltinTilesFiles[$p_builtinFileHandle];
	} elseif (array_key_exists($p_builtinFileHandle, self::$sc_optionsBuiltinStretchFiles)) {
		return self::$sc_optionsBuiltinStretchFiles[$p_builtinFileHandle];
	} else {
		return '';
	}
}//getBuiltinFile

/* ========================================================================================= */
// the following functions define the semantics of the background image styles


/**
 * Does the input treatment code require HTML rendering as tiles (=true) or single (scaled) image (=false)
 *
 * @param  string $p_backgroundTreatmentString 
 * @require $p_backgroundTreatment in array_keys($sc_backgroundImgTreatments) + "tile"
 * @return bool true=tile, false=single image
 */
public static function isBackgroundTreatmentTile( $p_backgroundTreatmentString ) {
	return array_key_exists( $p_backgroundTreatmentString, self::$sc_backgroundImgTreatmentRepeatArray );
}

/**
 * Does the input treatment code require HTML rendering as srollable (=true) or single (scaled) image (=false)
 *
 * @param  string $p_backgroundTreatmentString 
 * @require $p_backgroundTreatmentString in array_keys($sc_backgroundImgTreatments) + "tile"
 * @return bool true=scroll, false=fixed
 */
public static function isBackgroundTreatmentTileScrolling ( $p_backgroundTreatmentString ) {
	return (!(in_array( $p_backgroundTreatmentString, self::$sc_backgroundImgTreatmentIsNonScrollingArray, true ))); //3rd param = strict type
}


/**
 * For a tiled background, get HTML "repeat_" styling 
 *
 * @param  string $p_backgroundTreatmentString 
 * @require $p_backgroundTreatmentString in array_keys($sc_backgroundImgTreatmentRepeatArray)
 * @return string from { repeat repeat-x repeat-y no-repeat } plus { left center }
 */
public static function getBackgroundTileRepeatHTML ($p_backgroundTreatmentString) {
	$l_result = self::$sc_backgroundImgTreatmentRepeatArray[ $p_backgroundTreatmentString ];
	$l_result .= ( strpos($p_backgroundTreatmentString,'repeat-x') === FALSE  )
				? ' center' 
				: ' left'
				;
	return $l_result;
}

//===============================================================
//
//	protected functions and data
//
//===============================================================

// the following functions define the semantics of the background image styles
// TODO: move these descriptions to lang/en/theme_icand.php

protected static $sc_backgroundImgTreatments = array (	
			'stretch' 	// 'Single image, scaled to page width'
		,	'stretchall'// 'Single image, stretched in height and width to fill area'
		,	'middle'	// 'Single image, no scrolling, in centre of page, no scaling' // added 25July2012
		,	'middle-sc'	// 'Single image, scrolling, in centre of page, no scaling' // added 26Sep2012		
		,	'repeat'	// 'Tiled in 2 dimensions to fill area'
		,	'repeat-x'	// 'Tiled horizontally only, no scrolling, across top of area' // added "no scrolling" 26Sep2012
		,	'repeat-x-sc'// 'Tiled horizontally only, scrolling, across top of area' // added 26Sep2012		
		,	'repeat-y'	// 'Tiled vertically only, down centre of area'
		);
		
protected static $sc_backgroundImgTreatmentRepeatArray = array (
			'repeat-x' => 'repeat-x'
		,	'repeat-x-sc' => 'repeat-x'		// added 26Sep2012
		,	'repeat-y' => 'repeat-y'
		,	'repeat'   => 'repeat'
		,	'middle'   => 'no-repeat'	
		,	'middle-sc'  => 'no-repeat'		// added 26Sep2012
		,	'tile'	   => 'repeat'		//not a database-stored bg treatment, but explicit value used for built-in tile graphics
		);		
		
/*
 * Which backgroung image treatments don't scroll
 * used by isBackgroundTile(..)
 */

protected static $sc_backgroundImgTreatmentIsNonScrollingArray = array (
			'repeat-x'
		,	'middle'
		);		
		
//============================================================================

protected static $sc_headingsCodeArray = array (
			'general'
		,	'page'
		,	'pagewidestyle'
		,	'pagewideimg'
		,	'headfoot_bg'		
		,	'menu'	
		,	'header'
		,	'footer'
		,	'layout'
		,	'colour'
		,	'permit'
		,	'sys'		
		);	


/**
*	EVERY config option must be included in $sc_titleKeys,
*	because this is used by the install/upgrade tools to
*	set default values in the database. If something is not included,
*	it will not have an inital value in the DB, and then Moodle
*	(v2.0..v2.3) will go into an infinite loop of redirects
*	/admin/admin.php (line 6256 of /lib/adminlib.php if ($setting->get_setting() === NULL)
*	calling /admin/upgradesettings.php.
*/

protected static $sc_titleKeys = array(
		'general_upload'
	,	IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE
	,	IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT
	,	'dock_over'
	,	'page_gutter'
	,	'headerlogopre'
	,	'headerlogoprealt'
	,	'headerlogopost'
	,	'headerlogopostalt'
	,	'headerbackgroundimg'
	,	'headerbackgroundimgtarget'
	,	'headerbackgroundimgurl'
	,	'footerlogopre'
	,	'footerlogoprealt'
	,	'footerlogopost'
	,	'footerlogopostalt'

//	,	'colour_offsets'
	,	'clr_off_dt'
	,	'clr_off_he'
	,	'clr_off_tb'
	,	'clr_off_mm'
	,	'clr_off_fo'	
	
//	,	'colour_palettes'
	,	'clr_pal_dt'
	,	'clr_pal_he'
	,	'clr_pal_tb'
	,	'clr_pal_mm'
	,	'clr_pal_fo'		
	
	,	'colour_intensity'
	, 	'colour_base'
	, 	'colour_base_supp'
	,	'colour_paletteset'
	,	'colour_variety'
	,	'gradient_covers'

	,	'metadesktopimg'
	,	'metadesktopimgstyle' // NB IcandConstantsBackend::SETTINGS_STYLE_SUFFIX

	,	'metaheader1img'
	,	'metaheader1imgstyle'

	,	'metafooterimg'
	,	'metafooterimgstyle'
	
	,	'metatoolbar1img'
	,	'metatoolbar1imgstyle'

	,	'metatoolbar2img'
	,	'metatoolbar2imgstyle'	
	
	,	'coursepageimg'
	,	'coursepageimgstyle'
	
	,	'desktoptilegraphicurl'
	,	'desktoptilegraphic'
	,	'desktoptilegraphicstyle'
	,	IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET
	,	'table_spacing'
	,	'gutter_rules'
	
	,	'layout_type'
	,	IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET
	,	'layout_make_borders'
	,	IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT 
	,	'menu_useraccesstools'
	,	'menu_userlogon'
	,	'menu_userplacement'
	,	'menu_pageidplacement'
//	,	'menu_siteinfo'
	,	'foottxtpre'
	,	'foottxtmid'
	,	'foottxtpost'
	
	,	'sys_inherit'
	,	'sys_override'
		
	,	IcandConstantsBackend::DB_FIELD_PERMIT_MASTER
	,	'permit_general'
	,	'permit_menu'
	,	'permit_header'
	,	'permit_footer'
	,	'permit_layout'
	,	'permit_pagewidestyle'
	,	'permit_pagewideimg'
	,	'permit_headfoot_bg'
	,	'permit_colour'
);

/**
 * a SHARED x value set
 */
protected static $sc_colourOffsetsForTwoAxis = array(		
		IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_NOTSET 		// 'x' = 'transparent'
	,	IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BORDER_ONLY 	// 'n' = 'transparent + optional border'
	,	IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BW 			// 'w' = 'black/ white'
	,	IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO			// 'm' = 'gray'					
	,	'0'	// 'use base colour'
	,	'1'	// 'base +1'
	,	'2'	// 'base +2'
	,	'3'	// 'base +3'
	,	'4'	// 'base +4'
	,	'5'	// 'base +5'
	,	'6'	// 'base +6'
	,	'7'	// 'base +7'
	,	'8'	// 'base +8'
	,	'9'	// 'base +9'
	,	'10' // 'base +10'
	,	'11' // 'base +11'					
);

/**
 * a SHARED x value set
 */
protected static $sc_colourPalettesForTwoAxis = array(		
		'base~light' // 'dark text on light background, relative to main base colour'
	,	'base~dark'	 // 'light text on dark background, relative to main base colour'
	,	'base_supp~light' // 'dark text on light background, relative to supplementary base colour'					
	,	'base_supp~dark' // 'light text on dark background, relative to supplementary base colour'
);

protected static $sc_two_axis_options //[0]=x, [1]=y
	= array (
		'page_gutter'	=> array (
			array(		'none'   	// 'none'
					,	'shade'  	// 'shaded'
					,	'bordertb'	// 'horizontal borders'
			),
			array(		'he' // 'whole header'
					,	'mm' // 'area containing course material & blocks'
					,	'fo' // 'whole footer'
			)
		)
	,	'gradient_covers' => array (	// changed to upper case 02Oct2012. Name clash for 'dt' with "desktop"
			array(		'none' // 'none'
					,	'LT' // 'light top (convex)'
					,	'DT' // 'dark top (concave)'		
					,	'LB' // 'light bottom'
					,	'DB' // 'dark bottom'
			),
			array(
						'he' // 'whole header'
					,	'h1' // 'top strip of header'
					,	'h2' // 'banner strip of header'
					,	'h3' // 'strip at base of header'
					,	'tb' // 'whole toolbar (below header)'
					,	't1' // 'top strip of toolbar'
					,	't2' // 'bottom strip of toolbar'					
					,	'mm' // 'area under course material & blocks'					
					,	'fo' // 'whole footer'					
			)						
		)
	,	'colour_palettes' => array (	
			null			// $sc_colourPalettesForTwoAxis
		,	array(
						'dt' // 'whole desktop'			
					,	'he' // 'whole header'
					,	'h1' // 'top strip of header'
					,	'h2' // 'banner strip of header'
					,	'h3' // 'strip at base of header'
					,	'tb' // 'whole toolbar (below header)'
					,	't1' // 'top strip of toolbar'
					,	't2' // 'bottom strip of toolbar'					
					,	'mm' // 'area under course material & blocks'					
					,	'cm' // 'course material'
					,	'ce' // '"pre" blocks'
					,	'co' // '"post" blocks'
					,	'fo' // 'whole footer'					
			)			
		)
	,	'clr_pal_dt' => array (	
			null							// a special case. The real x values are a shared set $sc_colourPalettesForTwoAxis
		,	array(
						'dt' // 'whole desktop'						
			)			
		)
	,	'clr_pal_he' => array (	
			null							// a special case. The real x values are a shared set $sc_colourPalettesForTwoAxis
		,	array(
						'he' // 'whole header'
					,	'h1' // 'top strip of header'
					,	'h2' // 'banner strip of header'
					,	'h3' // 'strip at base of header'
			)
		)
	,	'clr_pal_tb' => array (	
			null							// a special case. The real x values are a shared set. $sc_colourPalettesForTwoAxis
		,	array(
						'tb' // 'whole toolbar (below header)'
					,	't1' // 'top strip of toolbar'
					,	't2' // 'bottom strip of toolbar'									
			)			
		)
	,	'clr_pal_mm' => array (	
			null							// a special case. The real x values are a shared set. $sc_colourPalettesForTwoAxis
		,	array(
						'mm' // 'area under course material & blocks'					
					,	'cm' // 'course material'
					,	'ce' // '"pre" blocks'
					,	'co' // '"post" blocks'				
			)			
		)
	,	'clr_pal_fo' => array (	
			null							// a special case. The real x values are a shared set. $sc_colourPalettesForTwoAxis
		,	array(
						'fo' // 'whole footer'					
			)			
		)		
	,	'colour_offsets' => array (
			null							// a special case. The real x values are a shared set. $sc_colourOffsetsForTwoAxis
		,	array(
						'dt' // 'whole desktop'			
					,	'he' // 'whole header'
					,	'h1' // 'top strip of header'
					,	'h2' // 'banner strip of header'
					,	'h3' // 'strip at base of header'
					,	'tb' // 'whole toolbar (below header)'
					,	't1' // 'top strip of toolbar'
					,	't2' // 'bottom strip of toolbar'					
					,	'mm' // 'area under course material & blocks'					
					,	'cm' // 'course material'
					,	'ce' // '"pre" blocks'
					,	'co' // '"post" blocks'
					,	'fo' // 'whole footer'					
			)			
		)
	,	'clr_off_dt' => array (
			null 						// a special case. The real x values are a shared set. $sc_colourOffsetsForTwoAxis
		,	array(
						'dt' // 'whole desktop'				
			)			
		)
	,	'clr_off_he' => array (
			null 						// a special case. The real x values are a shared set. $sc_colourOffsetsForTwoAxis
		,	array(	
						'he' // 'whole header'
					,	'h1' // 'top strip of header'
					,	'h2' // 'banner strip of header'
					,	'h3' // 'strip at base of header'				
			)			
		)
	,	'clr_off_tb' => array (
			null						// a special case. The real x values are a shared set. $sc_colourOffsetsForTwoAxis
		,	array(
						'tb' // 'whole toolbar (below header)'
					,	't1' // 'top strip of toolbar'
					,	't2' // 'bottom strip of toolbar'								
			)			
		)
	,	'clr_off_mm' => array (
			null 					// a special case. The real x values are a shared set. $sc_colourOffsetsForTwoAxis
		,	array(	
						'mm' // 'area under course material & blocks'					
					,	'cm' // 'course material'
					,	'ce' // '"pre" blocks'
					,	'co' // '"post" blocks'				
			)			
		)
	,	'clr_off_fo' => array (
			null // set to a shared array. $sc_colourOffsetsForTwoAxis
		,	array(
						'fo' // 'whole footer'					
			)			
		)		
	,	IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT  => array (
			array(		'none'
					,	'header1.pre'
					,	'header1.post'
					,	'toolbar1.pre'
					,	'toolbar1.post'
					,	'toolbar2.pre'
					,	'toolbar2.post'					
			),
			array( 		'moodlecustommenu' // 	=> 'custom menu'
					, 	'breadcrumbs' // 		=> 'breadcrumbs'
			)
		)
	, 	'menu_pageidplacement'  => array (
			array( 		'none'
					,	'header1.pre'
					,	'header1.post'
					,	'header2.pre'
					,	'header2.post'
					,	'header3.pre'
					,	'header3.post'						
			),
			array( 		'idsite'
					, 	'idcat'
					, 	'idcourse'					
			)
		)
	,	'menu_userplacement'	=> array (
			array( 		'none'
					,	'header1.pre'
					,	'header1.post'			
					,	'header3.pre'
					,	'header3.post'
					,	'toolbar1.pre'
					,	'toolbar1.post'
					,	'toolbar2.pre'
					,	'toolbar2.post'						
					,	'footer2.pre'
					,	'footer2.mid'					
					,	'footer2.post'
			),
			array(		'userlogonlink' // 'Logon & profile links'
					,	'userDisplay' // 'Accessibility and language selectors'
					,	'useredittools' // 'Course editor\'s button'
					,	'usermoodledocs' // 'Link to MoodleDocs'
					,	'userHomeButton' // 'Moodle "Home" button' // added 9Aug2012
			)
		)
	);

/**
*	DEFAULT settings for a number of options
*/
protected static $sc_simpleOptionDefaultArray = array (
		IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT  =>
			'moodlecustommenu~toolbar2.pre,breadcrumbs~toolbar1.pre'
	,	'menu_pageidplacement' =>
			'idsite~header1.pre,idcat~none,idcourse~header2.pre'
	,	'menu_userplacement' =>
			'userlogonlink~header1.post,userDisplay~none,useredittools~toolbar1.post,usermoodledocs~footer2.mid,userHomeButton~footer2.mid'
	,	'foottxtpre' => ''
	,	'foottxtmid' => ''
	,	'foottxtpost' => ''	
	// the following use values from IcandConstantsBackend::$PLACE_DECODE	
//	,	'colour_palettes' =>
	,	'clr_pal_dt' => 'dt~base~dark'
	,	'clr_pal_he' => 'he~base~dark,h1~base~dark,h2~base~dark,h3~base~dark'
	,	'clr_pal_tb' => 'tb~base~light,t1~base~light,t2~base~light'
	,	'clr_pal_mm' => 'mm~base~light,ce~base~light,co~base~light,cm~base~light'
	,	'clr_pal_fo' => 'fo~base~dark'
//	,	'colour_offsets' =>
	,	'clr_off_dt' => 'dt~x'
	,	'clr_off_he' => 'he~0,h1~x,h2~x,h3~x'
	,	'clr_off_tb' => 'tb~m,t1~x,t2~x'
	,	'clr_off_mm' => 'mm~0,ce~n,co~n,cm~x'
	,	'clr_off_fo' => 'fo~0'
	,	'gradient_covers' =>
			'he~none,h1~none,h2~none,h3~none,tb~none,t1~none,t2~none,fo~none'
	,	'page_gutter' =>
			'he~none,mm~none,fo~none'
	);
	
/**
 *
 * [0] is an array of option values
 * [1] is an integer, indicating which value in [0] is the defaul
 */
protected static $sc_oneDimensionOptionsArray 
	= array (
		'metadesktopimgstyle'	=> array(null, 0 )// default		// $sc_backgroundImgTreatments
	,	'metaheader1imgstyle'	=> array(null, 0 )// default
	,	'metatoolbar1imgstyle'	=> array(null, 0 )// default
	,	'metatoolbar2imgstyle'	=> array(null, 0 )// default		
	,	'coursepageimgstyle'	=> array(null, 0 )// default			
	,	'metafooterimgstyle'	=> array(null, 0 )// default
	
	,	'desktoptilegraphic' => array (	
			null		// initialised dynamically in getOneAxisOptionValues
		, 0 )
	,	'desktoptilegraphicstyle' => array (	
			null		// initialised dynamically in getOneAxisOptionValues
		, 0 )		
	,	'headerbackgroundimg' => array (
			null		// initialised dynamically in getOneAxisOptionValues
		, 0 )
	,	IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT		=> array(
			array(
			'page_layout0' 		// 'Allwide' //IcandWholePageOptions::LAYOUT_ALL_WIDE
		,	'page_layout1' 		// 'Headerwide' // IcandWholePageOptions::LAYOUT_HEADER_WIDE
		,	'page_layout2' 		// 'Backgroundswide' // IcandWholePageOptions::LAYOUT_BACKGROUNDS_WIDE
		,	'page_layout3'		// 'Allnarrow' // IcandWholePageOptions::LAYOUT_ALL_NARROW
		), 3 )// default
	,	'dock_over' => array(
			array(
			'dock0' 	// 'Neveroverlap' //IcandWholePageOptions::DOCK_ALWAYS_SQUEEZE
		,	'dock1' 	// 'Alwaysoverlap' // IcandWholePageOptions::DOCK_ALWAYS_OVERLAP
		,	'dock2' 	// 'OverlapUNLESS layout is "All wide"' // IcandWholePageOptions::DOCK_NO_OVERLAP_ALL_WIDE
		,	'dock3' 	// 'OverlapUNLESS layout has wide header' // IcandWholePageOptions::DOCK_NO_OVERLAP_HEADER_WIDE
		), 2 )// default	
	,	'headerbackgroundimgtarget' => array(
			array(	
			'metaheader' 			// 'Whole header'
		,	'metaheader2'			// 'Only banner bar within header'
		), 1 )// index of default
	,	'colour_intensity' 	=> array(
			array(		
			'colour_intensity0'		// 'normal:muted at front'
		,	'colour_intensity1'		// 'reversed'
		,	'colour_intensity2'		// 'all strong'
		,	'colour_intensity3'		// 'all muted'
		), 0 )// index of default
	,	'colour_base' => array( 
			array(	
			'colour_base0'		// '0' // red
		,	'colour_base1'		// '1'
		,	'colour_base2'		// '2'
		,	'colour_base3'		// '3'
		,	'colour_base4'		// '4' // green
		,	'colour_base5'		// '5'
		,	'colour_base6'		// '6'
		,	'colour_base7'		// '7'
		,	'colour_base8'		// '8' // blue
		,	'colour_base9'		// '9'
		,	'colour_base10'		// '10'
		,	'colour_base11'		// '11'
		), 8 )// index of default=8
	,	'colour_base_supp' => array( 
			array(	
			'colour_base_supp0'
		,	'colour_base_supp1'
		,	'colour_base_supp2'
		,	'colour_base_supp3'
		,	'colour_base_supp4'
		,	'colour_base_supp5'
		,	'colour_base_supp6'
		,	'colour_base_supp7'
		,	'colour_base_supp8'
		,	'colour_base_supp9'
		,	'colour_base_supp10'
		,	'colour_base_supp11'
		), 0 )// index of default=1		
	,	'layout_type'	=>	array (
			array(	
			'2L' // '1 block column, on left'
		,	'2R' // '1 block column, on right'
		,	'3B' // '2 block columns, on either side' 		
		,	'3L' // '2 block columns, both on left'
		,	'3R' // '2 block columns, both on right'
		), 0 )// index of default	
	,	IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET => array (
			array(	
			'block'// 'style blocks separately'
		,	'cell' // 'make full-height columns'
		,	'box' // 'group blocks in single box'
		), 0 )// index of default	
	,	IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET => array (
			array(	
			IcandConstantsBackend::DB_VALUE_DECORATION_COURSE_COMBO    //=> 'show course topics in a single box'
		,	IcandConstantsBackend::DB_VALUE_DECORATION_COURSE_INDIV  //=> 'show course topics in individual boxes'
		), 0 )// index of default
	,	'table_spacing' => array (
			array(	
			'none'		// 'no space between columns'
		,	'ts010'		// 'narrow space between columns, no margin'
		,	'ts020'		// 'wide space between columns, no margin'	
		,	'ts101'		// 'narrow margins, no space between columns'	
		,	'ts202'		// 'wide margins, no space between columns'		
		,	'ts111'		// 'narrow space between columns, narrow margins'			
		,	'ts121'		// 'wide space between columns, narrow margins'
		,	'ts212'		// 'narrow space between columns, wide margins'		
		,	'ts222'		// 'wide space between columns, wide margins'				
		), 5 )// index of default (all narrow)
	,	'gutter_rules' => array (
			array(	
			'none' 					// 'no vertical dividers'
		,	'gr_vertical'			// 'show vertical lines between columns and at sides'
		,	'gr_vertical_in'		// 'show vertical lines between columns'		
		,	'gr_vertical_out'		// 'show vertical lines at sides only'	
		), 0)// index of default (all narrow)
	,	'layout_make_borders' => array (
			array(	
			'layout_borders_no'		// 'don\'t add borders'
		,	'layout_borders_yes'	// 'add borders'
		), 1 )// index of default	
//--------------------------------------------------
// text, controls & links displayed in header & footer
//--------------------------------------------------	
	,	'menu_useraccesstools' => array (
			array(	
			'hires'					// '1: high resolution colours'
		,	'textsize'				// '1: font enlarger'
		,	'lang'					// '1: language selector'
		,	'hires,textsize'		// '2: hi-res & enlarge fonts'
		,	'hires,lang'			// '2: hi-res and language selector'		
		,	'textsize,lang'			// '2: font enlarger and language selector'			
		,	'hires,textsize,lang'	// '3: hi-res, enlarge fonts, select language'
		), 0 )// index of default
	,	'menu_userlogon'		=> array (
			array(	
			'logonmoodle'				// 'Moodle default: text, profile & login links'
		,	'logonlink'					// 'login/logout link only'
		,	'logonprofile,logonlink'	// 'profile and login links with no other text'
		,	'logonprofile'				// 'profile link only (no login link)'
//		,	'none'						// 'no profile or login links'
		), 0 )// index of default
	,	'colour_paletteset' =>  array(
			array(
			''						// 'built-in'
		, 	'light' 				// 'light-primary'	// the CSS files are /style/palette_*.css	
		,	'saturated'				// 'saturated-primary'
		, 	'dark' 					// 'dark-primary'
		), 0 )// index of default	
	, 	'colour_variety' => array(		// NOT USED ANY MORE
			array(
			'colour_varietyn'		// 'no colours: Gray scale'
		,	'colour_varietym'		// '1 colour: Monochromatic'
		,	'colour_varietyc'		// '2 colours: Complementary'		
		,	'colour_varietya1'		// '3 colours: Analogous 1'
		,	'colour_varietya2'		// '3 colours: Analogous 2'		
		,	'colour_varietyt1'		// '3 colours:Triadic 1'
		,	'colour_varietyt2'		// '3 colours:Triadic 2'		
		), 1 )// index of default			
//--------------------------------------------------
// permissions to override settings in course/s
//--------------------------------------------------	
	,	IcandConstantsBackend::DB_FIELD_PERMIT_MASTER		=> array (
			array(		
			IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_ALL// 'ALLOW ALL options to be overridden at course level'
		,	IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_NONE// 'BLOCK ALL overriding of the settings above'
		,	IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_SOME// 'Allow course level overrides as specified below'
		), 1 )// index of default
	,	'sys_position_popup_button'	=> array (
			array(
			'sysbutton_embed'
		,	'sysbutton_fixed'
		), 0 )// index of default
);		
//---------------------------------------------------
// used to supplement values in popup theme override menu	
public static $sc_defaultArray = array( IcandConstantsHTMLForms::FORM_SINGLE_VALUE_INHERIT //IcandConfigOptions::$sc_optionAcceptDefault
								=> IcandConstantsHTMLForms::FORM_SINGLE_VALUE_INHERIT //'* accept default *'
								);
//--------------------------------------------------------------
// specific value arrays

public static function isBuiltinTileName( $p_imgHandle ) {
	return (strpos( $p_imgHandle, IcandConstantsBackend::DB_VALUE_BACKGROUNDIMAGE_BUILTIN_PREFIX ) !== FALSE );
}

//=============================================================================================================
// Tiled background graphics. 			These should be in the config file, for admins to alter.
// the following keys all begin with IcandConstantsBackend::DB_VALUE_BACKGROUNDIMAGE_BUILTIN_PREFIX
public static $sc_optionsBuiltinTilesNames = array (
	'builtinTileDiagonals' 				// 'Light diagonal grid'
,	'builtinTileDiamond'				// 'Light rows of triangles grid'
,	'builtinTileDiagonalHatch'			// 'Light diamond grid'
,	'builtinTileFlatHatchSmall'			// 'Light vertical/horizontal narrow grid'
,	'builtinTileFlatHatchMediumRev'		// 'Light vertical/horizontal wide grid'

,	'builtinTileDiagonalsRev' 			// 'Dark diagonal grid'
,	'builtinTileDiamondRev'				// 'Dark rows of triangles grid'
,	'builtinTileDiagonalHatchRev'		// 'Dark diamond grid'
,	'builtinTileFlatHatchSmallRev'		// 'Dark vertical/horizontal narrow grid'
,	'builtinTileFlatHatchMedium' 		// 'Dark vertical/horizontal wide grid'
);

public static $sc_optionsBuiltinTilesFiles = array ( // decodes pattern names to PNG file names
	'builtinTileDiagonals' 				=> 'BGPattern1_Content'
,	'builtinTileDiamond'				=>  'BGPattern2_Content'
,	'builtinTileDiagonalHatch'			=>  'BGPattern3_Content'
,	'builtinTileFlatHatchSmall'			=>  'BGPattern4_Content'
,	'builtinTileFlatHatchMedium'		=>  'BGPattern5_Content'

,	'builtinTileDiagonalsRev' 			=>  'BGPatternRev1_Content'
,	'builtinTileDiamondRev'				=>  'BGPatternRev2_Content'
,	'builtinTileDiagonalHatchRev'		=>  'BGPatternRev3_Content'
,	'builtinTileFlatHatchSmallRev'		=>  'BGPatternRev4_Content'
,	'builtinTileFlatHatchMediumRev'		=>  'BGPatternRev5_Content'
);

//=============================================================================================================
// Stretching background graphics. 			These should be in the config file, for admins to alter.
public static $sc_optionsBuiltinStretchNames = array (
	'builtinStretchBigCircles1'			// 'Circles 1'
,	'builtinStretchBigCircles2'			// 'Circles 2'
,	'builtinStretchBigWaves'			// 'Waves'
);

public static $sc_optionsBuiltinStretchFiles = array (
	'builtinStretchBigCircles1'			=>  'bannerTransparent'		// name of .png file in /pix
,	'builtinStretchBigCircles2'			=>  'bannerTransparent2'		// name of .png file in /pix
,	'builtinStretchBigWaves'			=>  'bannerTransparent_v2'	// name of .png file in /pix
);
//=============================================================================================================

}//class IcandConfigOptions

//IcandConfigOptions::InitialiseComplexStatics();	// call initialiser

}//if class exists