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
 * Sets up input screen for configuration of the theme.
 * Values are written to the *_config_plugins table of the database.
 * Normally this code would be in a theme's settings.php file, but iCan.D. theme provides a course-specific 
 * override menu, so all the input fields are created in this file, to be included by settings.php or /layout/overridemenu_inc.php
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		13Dec2012
 * @see			~/lib/adminlip.php
 * @see			/layout/overridemenu_inc.php
 * @see			/settings.php
 */

/* EXTERNAL
 * from includers:
 * 		$uniqueHREFsuffix string (prepended to <A> anchor names
 *		$vischecker object	:: isVisible( $p_anything ) 
 *							:: isUploadOK()
 *							:: isAdminOnly( $p_anything )
 *		$popup_override boolean
 * 		$settings : an public array declared in /lib/adminlib.php 
 *			(overwritten when settings are displayed/edited)
 *
 * the settings objects (from /lib/adminlib.php 
 * get_string(..) will use values declared in this theme's ./lang/xx.php
 *
 * This is called EVERY TIME _ANY_ SETTINGS PAGE IS INVOKED for admin user!!!
 */
//if (!defined('SETTING_INC_PHP_INCLUDED')) { define('SETTING_INC_PHP_INCLUDED', true ); } 
if ($popup_override || $ADMIN->fulltree) { 		// without this guard around the whole file, some is included more than once by Moodle
defined('MOODLE_INTERNAL') || die;


$g_includeDownJumpLink = !($popup_override);		// only use the "down" jump in the site admin menu.
			// buttons were originally inline. 03Oct2012 changed style to "fixed", so jump not needed.

//require_once(dirname(__FILE__) . '/version.php');
require_once(dirname(__FILE__) . '/config.php');
require_once(dirname(__FILE__) . '/lib/ThemeConstantsBackend_inc.php');
require_once(dirname(__FILE__) . '/lib/ThemeConstantsHTMLForms_inc.php');	// IcandConstantsHTMLForms::FORM_TAG_FOR_*
require_once(dirname(__FILE__) . '/lib/ThemeOptions_inc.php');	// class IcandConfigOptions
require_once(dirname(__FILE__) . '/lib/ThemeSettingsWebInterface_inc.php' );
require_once(dirname(__FILE__) . '/lib/twodimensionmulticheck2.php');//admin_setting_configtwoaxismultiradio2

//require_once( 'version.php' );		// declares $plugin->component

/* *******************************************************
// for info: functions from core /lib/adminlip.php to create input fields for admin configuration forms
admin_setting_heading ($name, $heading, $information)
			$name: unique ascii name, either 'mysetting' for settings in config, or 'myplugin/mysetting' for ones in config_plugins.
		-- DISPLAYS $heading and $information
admin_setting_configtext ($name, $visiblename, $description, $defaultsetting, [??paramName??, $num_chars)
					PARAM_CLEAN, PARAM_URL

admin_setting_configtextarea($name, $visiblename, $description, $defaultsetting)
admin_setting_confightmleditor($name, $visiblename, $description, $defaultsetting, [$num_cols, $num_rows])

admin_setting_configcheckbox ($name, $visiblename, $description, $defaultsetting,
							$yes='1', $no='0')
admin_setting_configmulticheckbox ($name, $visiblename, $description, 	
							$defaultsetting, $choicesArray)
					saved in database as comma-seperated list of keys
admin_setting_configselect ($name, $visiblename, $description, 
							$defaultsetting, $choicesArray)
admin_setting_configcolourpicker($name, $visiblename, $description,
							$defaultsetting, array $previewconfig=null)
admin_setting_devicedetectregex($name, $visiblename, $description,
							$defaultsetting = '')
							
-------------
admin_setting_configtime ($hoursname, $minutesname, $visiblename, 
					$description, $defaultsetting)
admin_setting_courselist_frontpage ($loggedin)
			-- LIST OF OPTIONS TO SHOW USER ON FRONT PAGE
			
 + OTHERS THAT SEEM IRREVEVANT TO THEMES
-------------			
     $previewconfig = array('selector'=>'.block .content',
							   'style'=>'backgroundColor');
*/

/************************************************/
/* does the authoriser allow file uploads for the current user? */
$l_isUploadOK = $vischecker->isVisible('general_upload'); //$vischecker->isUploadOK();

// 2 tags for intra-page navigation
$l_thisPageHREFtagTop 	 = 'top';	
$l_thisPageHREFtagBottom = 'foot';
//------------------------
	// this array reflects the groupings used below, for navigation purposes
	$l_superGroup = array (
		'layout'  		=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'layout'
	,	'pagewidestyle'	=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'layout'	
	,	'pagewideimg'	=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'content'
	
	,	'menu' 			=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'content'
	,	'header' 		=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'content'
	,	'footer' 		=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'content'
	,	'headfoot_bg'	=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'content'
	,	'colour'		=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'style'
	,	'permit'		=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'administration'
	,	'sys'			=> IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'administration'	
	);
	
	$l_permissionGroup = array (
		// "layout"
		'general' => array (
				'general_upload'
			,	IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE	// not displayed
		),
		'layout' => array(
				IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT
			,	'dock_over'
			,	'layout_type'	
		),
		'pagewidestyle' => array(
				IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET
			,	IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET
			,	'layout_make_borders'		// moved from 'colour'			
			,	'page_gutter'
			,	'table_spacing'
		),
		// "content"
		'pagewideimg' => array(
				'metadesktopimg' 		
			,	'desktoptilegraphic'
			, 	'coursepageimg'
		),
		'headfoot_bg' => array(
				'headerbackgroundimg'
			,	'metaheader1img'
			,	'metatoolbar1img'
			,	'metatoolbar2img'
			,	'metafooterimg'			
		),
		'menu' => array(
				IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT 
			,	'menu_pageidplacement'
			,	'menu_userplacement'
			,	'menu_useraccesstools'
			,	'menu_userlogon'
			,	'foottxtpre'
			,	'foottxtmid'
			,	'foottxtpost'
		),
		'header' => array(
				'headerlogopre'	
			,	'headerlogopost'
		),
		'footer' => array(
				'footerlogopre'	
			,	'footerlogopost'
		),

		'colour' => array(
				'colour_base'
			,	'colour_base_supp'
			,	'colour_paletteset'
			,	'clr_pal_dt', 'clr_pal_he', 'clr_pal_tb', 'clr_pal_mm', 'clr_pal_fo'
			,	'clr_off_dt', 'clr_off_he', 'clr_off_tb', 'clr_off_mm', 'clr_off_fo'			
//			,	'layout_make_borders'		moved to 'pagewidestyle'
			,	'colour_variety'
			,	'colour_intensity'
		),		
		'permit' => array ()	,	// values not used
		'sys' 	=> array ()		// values not used		
	);

// -----------------------
// stuff for file uploading widget
	$uploaderURL = '../theme/icand/uploader.php';	// TO DO make this relative
	$g_generalPhp_courseId = (isset($COURSE))
				? $COURSE->id
				: 0;
	$uploaderURL .= ('?' . IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE_COURSE . '=' . $g_generalPhp_courseId );
	$inputNamePrefix = 's_' . IcandConstantsBackend::$g_wholeThemeName . '_';	//used in "name" fields for Moodle settings
/* -------------------------------------------- */

$themeSettingsWebInterface = new IcandSettingsWebInterface(IcandConstantsBackend::$g_shortThemeName);//helper class, to build admin controls

$uploadablePrompt 	= 'Insert URL to a graphic, or use the widget below to upload a file.';
$themeMotto			= get_string('settingsPageMotto', IcandConstantsBackend::$g_wholeThemeName );
	
//----------------------------------------------------------------------------
// define a block of HTML for internal jumps in page
$l_html_forIntrapageJumps 		= '<br /><a href="#'
					. $uniqueHREFsuffix 
					. $l_thisPageHREFtagTop 
					. '">'
					. get_string('settingsPageUplink', IcandConstantsBackend::$g_wholeThemeName ) //Up to contents
					. '</a>'	;
if ($g_includeDownJumpLink) {
	$l_html_forIntrapageJumps 		.=	'&nbsp;<a href="#' 
					. $uniqueHREFsuffix 
					. $l_thisPageHREFtagBottom 
					. '">'
					. get_string('settingsPageDownlink', IcandConstantsBackend::$g_wholeThemeName ) //	Down to buttons
					. '</a>'	;
				}
$l_html_forIntrapageJumps  .=	'<br /><br />' ;
//---------------------------------------------

//if ($popup_override || $ADMIN->fulltree) {

	$setting = new admin_setting_heading ( 'thememotto', '', $themeMotto );
	$settings->add($setting);

	$shortname		= 'themedescription';
	$name 			= IcandConstantsBackend::$g_wholeThemeName . '/' . $shortname;	
    $title = '<A name="'. $uniqueHREFsuffix . $l_thisPageHREFtagTop .'"></a>' 
			. '<hr />'
			. get_string(IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'Title', IcandConstantsBackend::$g_wholeThemeName ) // Menu of configurable components' 
			;
			
	$descriptionTags = array();	
	$l_lastSuperGroup = '';

	// write navigation menu
	foreach ( $l_permissionGroup as $l_headingCode => $l_elementArray ) {
		if (($l_headingCode != 'general')
			and ($vischecker->isAnyVisible($l_elementArray ))) {
			$l_currentSuperGroup = $l_superGroup[$l_headingCode];
			if ($l_currentSuperGroup != $l_lastSuperGroup) {			// write heading of "super" group when it changes
				$descriptionTags[] = '<b>';
				$descriptionTags[] = get_string( $l_currentSuperGroup , IcandConstantsBackend::$g_wholeThemeName );
				$descriptionTags[] = '</b><br />';
				$l_lastSuperGroup = $l_currentSuperGroup;
			}//if
			$descriptionTags[] = 										// write details of 
				'<a href="#' . $uniqueHREFsuffix . $l_headingCode . '">' 	
//				. IcandConfigOptions::getHeading($l_headingCode) // $themeSettingsWebInterface->getTitle($l_headingCode) 
				. get_string(	IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . $l_headingCode
							, 	IcandConstantsBackend::$g_wholeThemeName )
				. '</a><br />';
		}//if
	}//foreach
	$descriptionTags[] = '<br />';
	
	if ($g_includeDownJumpLink) {
		$descriptionTags[] = '<a href="#' . $uniqueHREFsuffix . $l_thisPageHREFtagBottom . '">' 
			. get_string(	'settingsPageDownlink'	, IcandConstantsBackend::$g_wholeThemeName )
			. '</a> '
			. get_string(	'settingsPageTOCDownLink' , 	IcandConstantsBackend::$g_wholeThemeName ) ;
	}
	
    $setting = new admin_setting_heading ( $name, $title, implode('',$descriptionTags) );
	$settings->add($setting);	

$defaultsetting = '';
//-----------------------
// Block layout options
	$l_groupName = 'layout';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
//	if ($vischecker->isVisible('layout')) {	
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));
		if ($vischecker->isVisible(IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT)) {
			$settings->add($themeSettingsWebInterface->makeSelect(IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT,$popup_override));
		}
		
		if ($vischecker->isVisible( 'dock_over' )) {
			$settings->add($themeSettingsWebInterface->makeSelect('dock_over',$popup_override));		
		}
		
		if ($vischecker->isVisible('layout_type')) {	
			$settings->add($themeSettingsWebInterface->makeSelect('layout_type',$popup_override));
		}
	}//'layout'
	
// -----------------------
// Whole page decoration options
	$l_groupName = 'pagewidestyle';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));
	
		if ($vischecker->isVisible(IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET)) {		
			$settings->add($themeSettingsWebInterface->makeSelect(IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET,$popup_override));
		}
		if ($vischecker->isVisible(IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET)) {	
			$settings->add($themeSettingsWebInterface->makeSelect(IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET,$popup_override));
		}
		
		if ($vischecker->isVisible('layout_make_borders')) {
			$settings->add($themeSettingsWebInterface->makeSelect('layout_make_borders',$popup_override));
		}//'layout_make_borders'			
		
		if ($vischecker->isVisible('page_gutter')) {	
			$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
					'page_gutter', $l_multichoiceOverrides )
					);					
		}
		$l_itemName = 'table_spacing';
		if ($vischecker->isVisible($l_itemName)) {	
			$settings->add($themeSettingsWebInterface->makeSelect($l_itemName,$popup_override));
		}
		
		$l_itemName = 'gutter_rules';
		if ($vischecker->isVisible($l_itemName)) {	
			$settings->add($themeSettingsWebInterface->makeSelect($l_itemName,$popup_override));
		}			
	}
	
	
// ===========================================================================
// -----------------------
	$l_groupName = 'pagewideimg';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));

		$groupshortname = 'metadesktopimg';
		if ($vischecker->isVisible($groupshortname)) {
			$shortname		= $groupshortname;
			$settings->add($themeSettingsWebInterface->makeText($shortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX ,$popup_override));			
		}//'metadesktopimg'

		$groupshortname = 'desktoptilegraphic';	
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname ,$popup_override));
			$shortname		= $groupshortname . IcandConstantsBackend::SETTINGS_URL_SUFFIX;
			$settings->add($themeSettingsWebInterface->makeText( $shortname ));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname ,$inputNamePrefix,$uploaderURL,100,600));
				$settings->add($themeSettingsWebInterface->makeSelect($groupshortname .'style', $popup_override));		
			}
		}//desktoptilegraphic
		
		$groupshortname = 'coursepageimg';	
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$shortname		= $groupshortname;
			$settings->add($themeSettingsWebInterface->makeText($shortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX ,$popup_override));			
		}//'coursepageimg'		
		
	} //'pagewideimg'


// -----------------------
	$l_groupName = 'headfoot_bg';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));	
		$groupshortname = 'headerbackgroundimg';
		if ($vischecker->isVisible($groupshortname)) {
				$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname ,$popup_override));	
			$settings->add($themeSettingsWebInterface->makeText($groupshortname . IcandConstantsBackend::SETTINGS_URL_SUFFIX ));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($groupshortname  . IcandConstantsBackend::SETTINGS_URL_SUFFIX,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_TARGET_SUFFIX ,$popup_override));
		}//'headerbackgroundimg'
		
		$groupshortname = 'metaheader1img';	
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$shortname		= $groupshortname;
			$settings->add($themeSettingsWebInterface->makeText($shortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX ,$popup_override));			
		}//'metaheader1img'

		$groupshortname = 'metatoolbar1img';	
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$shortname		= $groupshortname;
			$settings->add($themeSettingsWebInterface->makeText($shortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX ,$popup_override));			
		}//'metatoolbar1img'		
		
		$groupshortname = 'metatoolbar2img';	
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$shortname		= $groupshortname;
			$settings->add($themeSettingsWebInterface->makeText($shortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX ,$popup_override));			
		}//'metatoolbar2img'
		
		$groupshortname = 'metafooterimg';	
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$shortname		= $groupshortname;
			$settings->add($themeSettingsWebInterface->makeText($shortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($shortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeSelect($groupshortname . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX ,$popup_override));			
		}//'metafooterimg'		
	}//'headfoot_bg'	
//--------------------------
// System menus
	$l_groupName = 'menu';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));
		//---------------------
		$shortname		= IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ;
		if ($vischecker->isVisible($shortname)) {		
		$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
					$shortname, $l_multichoiceOverrides )
					);
		}					
		//---------------------
		$shortname		= 'menu_pageidplacement';
		if ($vischecker->isVisible($shortname)) {		
		$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
					$shortname, $l_multichoiceOverrides )
					);
		}
		//---------------------
		$groupshortname = 'menu_userstuff';
		$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));		

		$shortname		= 'menu_userplacement';
		if ($vischecker->isVisible($shortname)) {		
		$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
					$shortname, $l_multichoiceOverrides )
					);
		}
		//---------------------		
		$shortname		= 'menu_userlogon';
		if ($vischecker->isVisible($shortname)) {	
			$settings->add($themeSettingsWebInterface->makeSelect($shortname,$popup_override));
		}//'menu_userlogon'
		//---------------------	
		$shortname		= 'menu_useraccesstools';
		if ($vischecker->isVisible($shortname)) {	
			$settings->add($themeSettingsWebInterface->makeSelect($shortname,$popup_override));
		}//'menu_useraccesstools'
		
		
		$settings->add($themeSettingsWebInterface->makeDivider($groupshortname.'foot', false));
		$shortname		= 'foottxtpre';
		if ($vischecker->isVisible($shortname)) {	
			$settings->add($themeSettingsWebInterface->makeText($shortname));
		}//'foottxtpre'
		
		$shortname		= 'foottxtmid';
		if ($vischecker->isVisible($shortname)) {	
			$settings->add($themeSettingsWebInterface->makeText($shortname));
		}//'foottxtmid'
		
		$shortname		= 'foottxtpost';
		if ($vischecker->isVisible($shortname)) {	
			$settings->add($themeSettingsWebInterface->makeText($shortname));
		}//'foottxtpost'		
		
	}//menu
//-------------------
// Header contents options
	$l_groupName = 'header';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));
		$groupshortname		= 'headerlogopre';
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$settings->add($themeSettingsWebInterface->makeText($groupshortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($groupshortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeText($groupshortname . IcandConstantsBackend::SETTINGS_ALT_SUFFIX ));
		}//'headerlogopre';
	//------------------------------------
		$groupshortname = 'headerlogopost';
		if ($vischecker->isVisible($groupshortname)) {
			$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$settings->add($themeSettingsWebInterface->makeText($groupshortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($groupshortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeText($groupshortname . IcandConstantsBackend::SETTINGS_ALT_SUFFIX ));

		}//'headerlogopost'	
	//----------------------------------------------------
	}//'header'			
//----------------------------	
// Footer content options
	$l_groupName = 'footer';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
//	if ($vischecker->isVisible('footer')) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));
		//---------------------------------------
		$groupshortname	= 'footerlogopre';
		if ($vischecker->isVisible($groupshortname)) {
				$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));
			$settings->add($themeSettingsWebInterface->makeText($groupshortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($groupshortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeText($groupshortname . IcandConstantsBackend::SETTINGS_ALT_SUFFIX ));	
		}//'footerpregraphic'
		//---------------------------------------
		$groupshortname	= 'footerlogopost';
		if ($vischecker->isVisible($groupshortname)) {
				$settings->add($themeSettingsWebInterface->makeDivider($groupshortname, false));	
			$settings->add($themeSettingsWebInterface->makeText($groupshortname));
			if ($l_isUploadOK ) {
				$settings->add($themeSettingsWebInterface->makeIframe($groupshortname,$inputNamePrefix,$uploaderURL,100,600));
			}
			$settings->add($themeSettingsWebInterface->makeText($groupshortname . IcandConstantsBackend::SETTINGS_ALT_SUFFIX ));
		}//'footerpostgraphic'
	}//'footer'
// -----------------------

// Background color settings
	$l_groupName = 'colour';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));

		if ($vischecker->isVisible('colour_paletteset')) {
			$settings->add( new admin_setting_heading ( 
					$themeSettingsWebInterface->makeSettingName('colour_examples') 
					, IcandConfigOptions::getTitle('colour_examples')
					, $themeSettingsWebInterface->makeDemoColours() ));
			$settings->add($themeSettingsWebInterface->makeSelect('colour_paletteset',$popup_override));
			$settings->add($themeSettingsWebInterface->makeDivider('colour_paletteset_rule', false));			
			//--------------------------------------------------------------
		}		
		
		$settings->add( new admin_setting_heading ( 
				$themeSettingsWebInterface->makeSettingName('palette_examples') 
				, IcandConfigOptions::getTitle('palette_examples') 
				, $themeSettingsWebInterface->makeDemoPallete() ));	

		if ($vischecker->isVisible('colour_base')) {
			$settings->add($themeSettingsWebInterface->makeSelect('colour_base',$popup_override));
		}

		if ($vischecker->isVisible('colour_base_supp')) {
			$settings->add($themeSettingsWebInterface->makeSelect('colour_base_supp',$popup_override));
		}
	
		foreach (IcandConfigOptions::getSuffixesOfSectionsWithColour() as $l_suffix ) {
			$settings->add($themeSettingsWebInterface->makeDivider($l_suffix . '_rule', false));
			if ($vischecker->isVisible('clr_pal_' . $l_suffix)) {
				$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
						'clr_pal_' . $l_suffix, $l_multichoiceOverrides )
						);			
			}//if			
			if ($vischecker->isVisible('clr_off_' . $l_suffix)) {
				$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
						'clr_off_' . $l_suffix, $l_multichoiceOverrides )
						);			
			}//if
		}//foreach 
		
		if ($vischecker->isVisible('gradient_covers')) {
			$settings->add($themeSettingsWebInterface->makeDivider('gradient_covers_rule', false));
			$settings->add($themeSettingsWebInterface->makeTwoAxisRadio(
					'gradient_covers', $l_multichoiceOverrides )
					);			
		}			
	}//'colour'

// -----------------------
// Delegation of permissions
	$l_groupName = 'permit';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName,$uniqueHREFsuffix, $l_html_forIntrapageJumps ));

		$settings->add($themeSettingsWebInterface->makeSelect(IcandConstantsBackend::DB_FIELD_PERMIT_MASTER));	
/** / 
// old way, with check boxes	
		foreach ( $l_permissionGroup as $l_groupName => $l_groupArray ) {
			foreach ( $l_groupArray as $l_arrayIndex => $l_groupKey ) {
				if ($vischecker->isAlwaysForbidden( $l_groupKey )) {	
					unset( $l_groupArray[$l_arrayIndex] );
				}//if
			}//foreach
			if (count($l_groupArray) > 0 ) {
				$l_DB_keyName = IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . $l_groupName;	// construct key for database storage
				$settings->add($themeSettingsWebInterface->makeMultiSelectFromArray( 
							$l_DB_keyName
						, 	get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . $l_DB_keyName
									, 	IcandConstantsBackend::$g_wholeThemeName )
						,	$l_groupArray
						)
				);
			}//if
		}//foreach
/ */
/**/
		foreach ( $l_permissionGroup as $l_groupName => $l_groupArray ) {
			foreach ( $l_groupArray as $l_arrayIndex => $l_groupKey ) {
				if ($vischecker->isAlwaysForbidden( $l_groupKey )) {	
					unset( $l_groupArray[$l_arrayIndex] );
				}//if
			}//foreach
			if (count($l_groupArray) > 0 ) {
				$l_DB_keyName = IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . $l_groupName;	// construct key for database storage
				$settings->add($themeSettingsWebInterface->makeTwoAxisRadioRaw( 
															$l_DB_keyName
														,	IcandConfigOptions::$sc_permissionLevels	// x array
														,	$l_groupArray		// y array
														,	IcandConstantsBackend::DISPLAY_PREFIX_FOR_PERMISSIONS			
														,	IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES
														, null
														)
								);
			}//if
		}//foreach
/**/		
		
	}//'permit'
// -----------------------
// Data arrays.
	$l_groupName = 'sys';
	if ($vischecker->isAnyVisible($l_permissionGroup[$l_groupName])) {
//	if ($vischecker->isVisible('sys')) {

		$settings->add($themeSettingsWebInterface->makeHeading($l_groupName, $uniqueHREFsuffix, $l_html_forIntrapageJumps ));

		$settings->add($themeSettingsWebInterface->makeSelect('sys_position_popup_button'));
		
		$settings->add($themeSettingsWebInterface->makeConfigtextarea('sys_inherit'));

		$settings->add($themeSettingsWebInterface->makeConfigtextarea('sys_override'));
	}//sys

	//==============================================
	$uploaderScript ="<script language='javascript' type='text/javascript'>\n"
    . "function GetValueFromChild(p_targetname, p_newvalue ) {\n"
	. "var oTarget = document.getElementById('id_' + p_targetname);\n"
	. "oTarget.value = p_newvalue;\n"
	. "}</script>\n";

	$name 			= IcandConstantsBackend::$g_wholeThemeName . '/' . 'end_of_page';		
    $title 			= '<A name="'. $uniqueHREFsuffix . $l_thisPageHREFtagBottom . '"></a>'
					. $uploaderScript;
    $setting = new admin_setting_heading ( $name, $title, '' );
	$settings->add($setting);	
}//HUGE IF: if($ADMIN->fulltree)

//}//if !defined(..)