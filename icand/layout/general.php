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
 *	The code in this file takes data from the theme settings (out of database) and
 *	from the environment and then calls general_inc.php, which generates the HTML.
 *	Using the theme override popup requires the user to have sufficient permission
 *	to allow the "Turn editing on" button to be available.
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		30Oct2012
 */

/** TO DO
 *  configure header/footer levels. Currently '2' hardcoded for both
 *	standard_footer_html() is forced to footer2.mid ("purge caches" link). Make option to include or not.
 */

$l_isDebugMode = false; //true; // if true, debug messages output to html. 
ob_start();	// capture Output Buffer. (Turn this off just ahead of doctype.

/* Capture all output buffers, e.g. debug messages. If these come before the doctype,
 MSIE7 can't evaluate many styles. */

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	//  IcandConstantsBackend
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsHTMLForms_inc.php');	// IcandConstantsHTMLForms::FORM_KEY_* 
require_once(dirname(__FILE__) . '/../lib/ThemeTextContent_inc.php' );	// class IcandTextContent
require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');	// class ThemeOptions
require_once(dirname(__FILE__) . '/../lib/ThemeSettings_inc.php');	// class ThemeSettings, IcandSettingsBase

// =======================================
/* get all the variables from the theme settings */

// =================================================
// functions for processing configurable page layout
// -------------------------------------------------

function makeColumnLayoutArray(   $p_numcolumns
								, $p_sidebarpositioning
								, $p_showsidepost
								) {
	if ( $p_numcolumns == 1 ) {
		$resultArray =  array('courseMain');
	} elseif ($p_numcolumns == 2 ) {
		$l_second_column = ($p_showsidepost) ? 'coursePost' : 'coursePre';
		if ( ($p_showsidepost) && ($p_sidebarpositioning == 'B'))
			$p_sidebarpositioning = 'R';
		if ( $p_sidebarpositioning == 'R' ) {
			$resultArray = array( 'courseMain', $l_second_column);
		} else {
			$resultArray = array( $l_second_column, 'courseMain'); // B""both" or L:"left"
		}
	} else { // ($p_numcolumns == 3 )
		if ( $p_sidebarpositioning == 'R' ) {
			$resultArray = array( 'courseMain', 'coursePre', 'coursePost' ); // ?????????
		} elseif ( $p_sidebarpositioning == 'L' ) {
			$resultArray = array( 'coursePre', 'coursePost', 'courseMain');
		} else {
			$resultArray = array( 'coursePre', 'courseMain', 'coursePost');
		}
	}
	return $resultArray;
}//makeColumnLayoutArray(..)

//=====================================================
// START OF MAIN PHP ROUTINE
//---------------------------------------------
//--- get course-specific overrides

$g_generalPhp_courseId = $COURSE->id;	// 1 is homepage + all admin pages

$l_isThemeOverrideByPost 	 = (isset($_POST[IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_OVERRIDE])); // get form-posted overrides
if ($l_isThemeOverrideByPost) {
	$l_themeDatabaseKey = 'theme_' . $PAGE->THEME->name;	// value to save to database
//echo 'DEBUG-general-' . $PAGE->THEME->name . '--------X--';
	$l_themeFormPrefix = 's_' . $l_themeDatabaseKey . '_' ; // HTML keys in <form>
	$l_isThemeSaveOverrideByPost = (isset($_POST[IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_SAVE]) && ($g_generalPhp_courseId!==1)); //can't override system "course")

	// remove fields not needed by renderer
	unset($_POST[IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_OVERRIDE]);
	unset($_POST[IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_SAVE]);
	unset($_POST[IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_TEST]);
}

// Get data from site-wide settings (database) for the theme.
$themeSettings =  ( $l_isThemeOverrideByPost )
				? new IcandSettingsOverrides( $g_generalPhp_courseId, $PAGE->theme->settings )
				: new IcandSettingsBase($g_generalPhp_courseId, $PAGE->theme->settings )
				;
				
// =============================================================================================
// handle theme-based overriding of configuration										
// =============================================================================================


// Get permissions for course overrides
/*
$l_isAbleToEditCourse = ( 		($g_generalPhp_courseId != 0)
							&&  (strpos( $PAGE->button
							,	'<input type="hidden" name="edit" value=' )
							!== false )
						); // is there an "Turn editing *" button?

$l_isAbleToEditCourse = true; // debug	
*/
$l_isAbleToEditCourse = false; //default
				
if (in_array($PAGE->pagelayout, array( 'frontpage', 'course' ))) {		// see class moodle_page in /lib/pagelib.php 
// only allow editing in frontpage or course, not report, admin, etc.
//echo 1;
	require_once(dirname(__FILE__) . '/../lib/ThemeMoodleUserCapability.php');	// class IcandMoodleUserCapability
	$l_MoodleUserCapabilityObject = new IcandMoodleUserCapability();
	$l_userCapability = $l_MoodleUserCapabilityObject->getCurrentUserCapabilityLevel();
	
	if ( $l_userCapability > 0 ) {	// if user has no rights, don't check further
//echo 2;	
		require_once(dirname(__FILE__) . '/../lib/ThemeRealVisChecker_inc.php');	// class IcandRealVisChecker
		$vischecker = new IcandRealVisChecker($l_userCapability);
		$l_overridePermissions = $themeSettings->extractGlobalSettings( IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX, true );	
		$vischecker->setPermissions( $l_overridePermissions );
		$l_isAbleToEditCourse = ( $vischecker->isAnyPermitted() );
	}//if
}//if ($l_isAbleToEditCourse)


// Get overrides from HTML form
if ( $l_isThemeOverrideByPost ) { // if values provided by override form, get the inputs
	$themeSettings->setOverrideSettings( $_POST, $l_themeFormPrefix );
}//if($l_isThemeOverrideByPost)

// at this point:
// -	$themeconfig = all inherited values, including site-wide & this course preset
// -	$l_thisCourseOverridesArray has any saved overrides for this course
// -	$l_themeParametersPostOverrides has any new overrides from the form

// If form data was received, check if values have to be saved to database
if ( $l_isThemeOverrideByPost ) { // if values provided by override form
	if ( $l_isThemeSaveOverrideByPost ) {
		if ($themeSettings->isInheritanceChanged()) { // check if ancestors have changed	
			$l_new_sys_inherit = $themeSettings->getAllInheritanceAsString();//TODO ????? WHAT ABOUT OTHER COURSES??
			set_config(IcandConstantsBackend::DB_FIELD_COURSE_ANCESTOR, $l_new_sys_inherit, $l_themeDatabaseKey);
		}//if($l_sitewideAncestor...	
		if ($themeSettings->isChangedOverrideValues()) { // check if other override values changed
			$l_allCurrentOverridesAsString = $themeSettings->getAllOverridesAsString();
			set_config(IcandConstantsBackend::DB_FIELD_COURSE_OVERRIDES, $l_allCurrentOverridesAsString, $l_themeDatabaseKey);
		}//if($l_changedOverrideValues)
	}//if($l_isThemeSaveOverrideByPost)
}//if($l_isThemeOverrideByPost)

//===============================
// Get theme settings, incorporating any preview or freshly saved overrides from form.
	$themeconfig = $themeSettings->getEffectiveSettings();

//=======================================================
// start processing $themeconfig[] values
//=======================================================

//-------------------------------------------------------
// header & footer text/menu contents
//-------------------------------------------------------
// put config for control elements in special array, to intialise $themeTextContent
$l_textContentControlElementsArray = array();	//initialise array
// don't split menu_pageid lists... this will be done in IcandTextContent::makeIdElements(..)
//if (isset($themeconfig['menu_pageid'])) {
//	$l_textContentControlElementsArray['menu_pageid'] = $themeconfig['menu_pageid'];//store key=>value pair
//	unset($themeconfig['menu_pageid']);
//}//if

if (isset($themeconfig['menu_pageidplacement'])) {
	$l_textContentControlElementsArray['menu_pageidplacement'] = $themeconfig['menu_pageidplacement'];//store key=>value pair
	unset($themeconfig['menu_pageidplacement']);
}//if

if (isset($themeconfig[IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ])) {
	$l_textContentControlElementsArray[IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ] = $themeconfig[IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ];//store key=>value pair
	unset($themeconfig[IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ]);
}//if

if (isset($themeconfig['menu_userplacement'])) {
	$l_value = $themeconfig['menu_userplacement'];
	if (strpos($l_value, 'userlogonlink~' )===false) {
		$l_value .= ',userlogonlink~header1.post'; // force login link
	}
	$l_textContentControlElementsArray['menu_userplacement'] = $l_value;//store key=>value pair
	unset($themeconfig['menu_userplacement']);
}//if
else {
	$l_textContentControlElementsArray['menu_userplacement'] = 'userlogonlink~header1.post';
}

//get all other config items with keys starting with 'menu_'
$l_foundKeys = preg_grep ( '/^menu_.+/' , array_keys($themeconfig) );
foreach ($l_foundKeys as $l_foundKey ) {
	// add the values corresponding to each found key
	$l_allValues = explode ( IcandConstantsBackend::MULTISELECT_COMBINER , $themeconfig[$l_foundKey] );
	if (count($l_allValues) == 1) {
		$l_textContentControlElementsArray[] = $l_allValues[0];
	} else {
		$l_textContentControlElementsArray = array_merge( $l_textContentControlElementsArray,$l_allValues ); 
	}
	unset( $themeconfig[$l_foundKey]); // remove a key once handled
}//if

if ( $l_isAbleToEditCourse) {
	$l_textContentControlElementsArray[] = 'themeoverride';				// include a button for theme overrides
	if (isset($themeconfig['sys_position_popup_button']))  {
		if ($themeconfig['sys_position_popup_button'] == 'sysbutton_fixed') {
			$l_textContentControlElementsArray[] = 'themeoverride_fixed';	// instruct  a button for theme overrides
		}
	}
	unset( $themeconfig['themeoverride_fixed']); // remove key once handled
}//if

if (!isset( $l_textContentControlElementsArray['menu_userlogon']) ) {
	$l_textContentControlElementsArray['menu_userlogon'] = 'logonmoodle'; // force login option to exist
}
//---------------------------------------------
// get any overrides from theme config.php file
//---------------------------------------------
//added 2Aug2012
if (!empty($PAGE->layout_options[IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT])) {
	$themeconfig[IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT] = $PAGE->layout_options[IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT];
}

if (!empty($PAGE->layout_options['debug'])) {
	if ($l_isDebugMode) {
		echo 'DEBUG:' . $PAGE->layout_options['debug'] . '//';
	}
}

//---------------------------------------------
// body contents
//-------------------------------------------------------
//$themeconfig['table_spacing'] 	= 'table_spacing_loose' ; // table_spacing_tight table_spacing_loose
$themeconfig['use_radius']	  	= true; // true false

// the following are only used below, & not passed to page drawer
$l_sidebarpositioning	= $themeconfig['layout_type'][1]; //2nd char //$PAGE->theme->settings->navposition;
$l_theme_numcolumns		= intval($themeconfig['layout_type'][0]); // $PAGE->theme->settings->numcolumns; // might reduce later

$l_temp = substr($themeconfig[IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT], strlen(IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT) );
$l_wholePageLayoutType	= intval( $l_temp );

//=====================================================

// get vars from $OUTPUT
$output_custommenu = $OUTPUT->custom_menu();  // TODO : delete this

// get vars from $PAGE 
$l_theme_allowblocks	= empty($PAGE->layout_options['noblocks']);	//used below

$themeconfig['page_hasnavbar'] = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$themeconfig['page_hasfooter'] = (empty($PAGE->layout_options['nofooter']));

$themeconfig['page_hascustommenu'] = (empty($PAGE->layout_options['nocustommenu']) 
					&& !empty($output_custommenu));				
$themeconfig['page_hasheading'] = (!empty($PAGE->heading));	

$themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'pre'] = $l_theme_allowblocks 
				&& ($PAGE->blocks->region_has_content('side-pre', $OUTPUT))
//				&& !($PAGE->blocks->region_completely_docked('side-pre', $OUTPUT))
				;
$themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'post'] = $l_theme_allowblocks
				&& ($PAGE->blocks->region_has_content('side-post', $OUTPUT))
//				&& !($PAGE->blocks->region_completely_docked('side-post', $OUTPUT))
				;

//===================================================================================
//$themeconfig[IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET] =  IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_BOX; // cell box
//$themeconfig['push_to_block'] 	= true; // true false  HAS NO EFFECT UNLESS [IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET]==box
//$themeconfig['colour_intensity']	= 0;
//===================================================================================

//===================================================================================
// ALL THE PAGE DISPLAY PARAMETERS HAVE BEEN SET. Now, process parameters to
// help the page builder.
//===================================================================================

$themeconfig['local_custombodyclasses'] = array();	// used for CSS_DOCK_*_BODY
/* ------- */

$themeconfig[IcandConstantsBackend::CONFIG_REWRITE_PUSHTOSECTION] = ($themeconfig[IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET] == IcandConstantsBackend::DB_VALUE_DECORATION_COURSE_INDIV);

switch ( $themeconfig[IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET] ) {
	case 'cell' : $themeconfig[IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET]	= IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_CELL;	
					$themeconfig['push_to_block'] 	= false; // not used!!
																break;
	case 'box'	: $themeconfig[IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET]	= IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_BOX;
				  $themeconfig['push_to_block'] 	= false;	break;
	case 'block': $themeconfig[IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET]	= IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_BOX;
				  $themeconfig['push_to_block'] 	= true;		break;						  
					
}

// if we have 3 cols of content, but only 2 of display
$themeconfig[IcandConstantsBackend::CONFIG_CALC_COMBINEBLOCKS] = 	
			($l_theme_numcolumns == 2) 
		&& ($themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'pre'])
		&& ($themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'post']);

// can we drop some columns from the display?
if ($l_theme_numcolumns > 1) {	
	// don't change columns if ShowHiddenBoxes
	if ($l_theme_numcolumns == 3) {
		if (!$themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'pre'])
			$l_theme_numcolumns--;
		if (!$themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'post'])
			$l_theme_numcolumns--;
	} else { // == 2
		if (!($themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'post'] || $themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'pre'] ))
			$l_theme_numcolumns = 1;
	}
}

$themeconfig['columnorder'] = makeColumnLayoutArray(
									  $l_theme_numcolumns
									, $l_sidebarpositioning
									, $themeconfig[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . 'post']
									);
																
$themeconfig['local_custommaincontentclasses'] = array();	/* !!!! NOT USED !! */

if ($themeconfig['page_hascustommenu']) {
    $themeconfig['local_custombodyclasses'][] = 'has_custom_menu';
}
//---------------------


// ============= TEXT DIRECTION ============================================
$l_isRealRTL = (strpos($PAGE->bodyclasses,'dir-rtl') !== false );
$l_debugFlipLTR = isset($themeconfig[IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR]);
$l_displayRTL = ($l_isRealRTL || (!$l_isRealRTL && $l_debugFlipLTR ));

if ($l_debugFlipLTR) {
	$themeconfig['local_custombodyclasses'][] = 
		(($l_isRealRTL) ? 'dir-ltr' : 'dir-rtl');	//override text direction
}

if ($l_displayRTL) {
	$themeconfig['columnorder'] = array_reverse($themeconfig['columnorder'], true);
}

//---------------------

//$PAGE->requires->js('/theme/'. current_theme() .'/javascript/icand_utility.js');		// Moodle gets stuck 1/2 way between themes when setting new
$PAGE->requires->js('/theme/icand/javascript/icand_utility.js');						// 30Oct2012

// all the parameters are in $themeconfig[]. Apply them to the page layout...

//====================================================

if ( $l_wholePageLayoutType	> IcandWholePageOptions::LAYOUT_ALL_NARROW ) {
//	$l_wholePageHeaderExtensions = ($l_wholePageLayoutType - IcandWholePageOptions::LAYOUT_ALL_NARROW);
	$l_wholePageLayoutType	= IcandWholePageOptions::LAYOUT_ALL_NARROW;
}
$themeWholePageOptions = new IcandWholePageOptions();

$l_wholePageGutterStyleArray 
	= IcandConstantsBackend::explodeMultiselect( $themeconfig['page_gutter'], 'none');

$themeWholePageOptions->setPageLayout( $l_wholePageLayoutType, $l_wholePageGutterStyleArray );

// set CSS class for positioning dock
if (!empty($themeconfig['dock_over'])) {
	$l_temp = substr($themeconfig['dock_over'], strlen('dock') );
	$l_dockOverlapType	= intval( $l_temp );
	$themeconfig['local_custombodyclasses'][] = $themeWholePageOptions->getDockingBodyClass($l_dockOverlapType);
}	

// set text for header/s & footer
$themeTextContent = new IcandTextContent();
$themeTextContent->setRemoveTitleFromCustomMenu(true);	// TODO: make this user-configurable
$themeTextContent->setRemoveTitleFromBreadcrumbs(true);

//do header graphics before text
$l_headerNum = 2; 		/* TODO configure header levels */

// foreground graphics for header and footer
// put them in header2, footer2
function getSublayerNumber( $p_place ) {
	if ($p_place == IcandConstantsBackend::PLACE_HEADER)
		return 2;
	else //footer
		return 2;
}

// look for "logo" graphics in header & footer ($LOGO_PLACES), pre & post ($TWO_POSITION_SUFFIX)
foreach (IcandConstantsBackend::$LOGO_PLACES as $l_place) {
	foreach (IcandConstantsBackend::$TWO_POSITION_SUFFIX as $l_side ) {
		$l_elementName = $l_place . IcandConstantsBackend::LOGO_TAG_CORE . $l_side;
		if (isset($themeconfig[$l_elementName])) {
			$l_URL = trim($themeconfig[$l_elementName]);
			if (strlen($l_URL) > 0) {
				$l_logoalt = (isset($themeconfig[$l_elementName . IcandConstantsBackend::SETTINGS_ALT_SUFFIX]))
						? $themeconfig[$l_elementName . IcandConstantsBackend::SETTINGS_ALT_SUFFIX]
						: 'logo';
				$themeTextContent->addImage( 
								$l_place
							. 	getSublayerNumber( $l_place ) 
							.	'.'
							.	$l_side
						, $l_URL
						, $l_logoalt );
			}//if strlen
		}//if isset
	}//foreach side
}//foreach place

$l_sourceName = 'foottxtpre';
if (isset($themeconfig[$l_sourceName ])) {
	$l_value = trim($themeconfig[$l_sourceName ]);
	if (strlen( $l_value ) > 0 ) {
		$themeTextContent->setCooked('footer3.pre' , $l_value );
	}
}//if

$l_sourceName = 'foottxtmid';
if (isset($themeconfig[$l_sourceName ])) {
	$l_value = trim($themeconfig[$l_sourceName ]);
	if (strlen( $l_value ) > 0 ) {
		$themeTextContent->setCooked('footer3.mid' , $l_value );
	}
}//if

$l_sourceName = 'foottxtpost';
if (isset($themeconfig[$l_sourceName ])) {
	$l_value = trim($themeconfig[$l_sourceName ]);
	if (strlen( $l_value ) > 0 ) {
		$themeTextContent->setCooked('footer3.post' , $l_value );
	}
}//if

// the next line should come after the logos, so the logos appear on outside of page
$themeTextContent->setElements ( $l_textContentControlElementsArray, $l_displayRTL ); //, $themeconfig['menu_pageid'] );
										
$themeTextContent->setCooked('footer2.mid'	,$OUTPUT->standard_footer_html() );//TODO make this configurable

//-------------------------------------------------------

require("general_inc.php");	// passes all theme config variables in $themeconfig to build html page