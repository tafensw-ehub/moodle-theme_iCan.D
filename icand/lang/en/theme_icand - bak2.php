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
 * Strings for component 'theme_icand', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		11Dec2012
 */
 
require_once(dirname(__FILE__) . '/../../lib/ThemeConstantsBackend_inc.php');
require_once(dirname(__FILE__) . '/../../lib/ThemeConstantsCSS_inc.php');
require_once(dirname(__FILE__) . '/../../lib/ThemeWholePageOptions_inc.php');	// class IcandWholePageOptions
require_once(dirname(__FILE__) . '/../../lib/ThemeConstantsHTMLForms_inc.php');	// IcandConstantsHTMLForms::FORM_TAG_FOR_*


/*
 * WHAT A PAIN!! Moodle's get_string($identifier[,$plugin_name]) in /lib/moodlelib.php runs clean_param($identifier, PARAM_STRINGID)
 * on all key values, and returns an empty string if there is an "invalid" character. 
 * Valid characters for PARAM_STRINGID are: '|^[a-zA-Z][a-zA-Z0-9\.:/_-]*$|' 
 * 		SO: $string[$identifier] CAN'T HAVE THE FOLLOWING CHARs IN $identifier: ~ ! space
 *		IcandConstantsBackend::makeIdSafeForGetString( $identifier ) fixes ~
 */

$string['pluginname'] = 'iCan.D.';	// required by Moodle

$string['settingsPageMotto'] = '<div>
	<br />The upload function for graphics requires the web service have write access to /theme/icand/pix/uploaded.
	<br />Version <b>beta(7)</b>, 11 December 2012.
	</div>';
	
$string['settingsPageUplink'] 			= 'Up to contents';	
$string['settingsPageDownlink'] 		= 'Down to buttons';
$string['settingsPageTOCDownLink']		= 'DON\'T FORGET to click the Save button!';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'Title'] 		= 'Menu of configurable components';

// grouping titles on theme settings pages ============================================
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'layout'			] = 'Layout';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'content'			] = 'Content';	
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'style'			] = 'Style';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_SUPERGROUPS . 'administration' 	] = 'Administration';

// titles on theme settings pages ======================================================
/* settingsPageMenu . IcandConfigOptions::$sc_headingsCodeArray */
//$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'general'] 		= 'General';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'layout'] 		= 'Positioning and layout';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'pagewide' . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] 	= 'Columns, blocks and page gutters';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'pagewide' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] 	= 'Page background images';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'headfoot_bg'] 	= 'Header, toolbar and footer background images';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'menu'] 		= 'Header and footer global utilities';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'header'] 		= 'Header logos';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'footer'] 		= 'Footer logos'; //TODO re-assign footer tagline
//$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'page'] 		= 'Whole page layout';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'colour'] 		= 'Background colours and borders';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'permit'] 		= 'Permission restrictions';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS . 'sys'] 			= 'Site-wide back-end settings for theme operation';

/**
 *		configuration menu poup
 */
$string[IcandConstantsCSS::CSS_ID_EDITBUTTON] 				= $string['pluginname'] . ' course overrides';	// button to open course override menu
//--------------------------------------
$string[IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR]	= 'test reverse text direction (RTL)';
$string[IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE]	= 'inherit theme settings from course #';
$string[IcandConstantsCSS::CSS_ID_OVERRIDE_POPUP_TITLE] 	= $string['pluginname'] . ' theme: override of theme settings for this course';	//title of override menu
$string[IcandConstantsCSS::CSS_ID_CANCELBUTTON] 			= 'close (cancel)';	// button in course override menu
$string[IcandConstantsCSS::CSS_ID_TESTBUTTON] 				= 'Test';	// button in course override menu
$string[IcandConstantsCSS::CSS_ID_SAVEBUTTON] 				= 'Save';	// button in course override menu

/**
 * 		file uploader. !!! NOT USED. CAN'T CALL get_string(..) from iframe
 */
$string[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE] 				= 'Choose file to upload :';
$string[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE . 'submit' ] 	= 'Upload File';		// button label


/**
* Titles. Keys = "t." joined to contents of IcandConfigOptions::$sc_title 
*/
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'general_upload'] 		= 'Upload images';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'sys_parentcourse'] 		= 'Inherit settings of another course';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT] = 'Width of content area';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'dock_over'] 			= 'Docking bar';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'page_gutter'] 			= 'Built-in decoration styles (shading or borders for sides of page)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerlogopre'] 		= 'LEFT header logo<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerlogoprealt'] 		= 'LEFT header logo<br />Alternative text:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerlogopost'] 		= 'RIGHT header logo<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerlogopostalt'] 	= 'RIGHT header logo<br />Alternative text:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerbackground' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'iCan.D. built-in banner';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerbackground' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_TARGET_SUFFIX] = '<font color="#FF0000">Middle - </font>HEADER background image<br />Formatting:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'headerbackground' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_URL_SUFFIX] = '<font color="#FF0000">Middle - </font>HEADER background image<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'footerlogopre'] 		= 'LEFT footer Graphic<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'footerlogoprealt'] 		= 'LEFT footer logo<br />Alternative text';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'footerlogopost'] 		= 'LEFT footer graphic<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'footerlogopostalt'] 	= 'RIGHT footer logo<br />Alternative text';

//$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'colour_paletteset']		= 'Select what tonal value you would like your colour scheme to be.';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_off_dt'] 			= 'DESKTOP<br />Colour options:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_off_he'] 			= 'HEADER<br />Colour options:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_off_tb'] 			= 'TOOLBAR<br />Colour options:';	
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_off_mm'] 			= 'MAIN CONTENT<br />Colour options:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_off_fo'] 			= 'FOOTER<br />Colour options:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_pal_dt'] 			= 'DESKTOP<br />Text and background colour combination:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_pal_he'] 			= 'HEADER<br />Text and background colour combination:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_pal_tb'] 			= 'TOOLBAR<br />Text and background colour combination:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_pal_mm'] 			= 'MAIN CONTENT<br />Text and background colour combination:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'clr_pal_fo'] 			= 'FOOTER<br />Text and background colour combination:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'colour_intensity'] 		= 'Intensity scheme';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'colour_base'] 			= 'COLOUR OPTION 1';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'colour_base_supp'] 		= 'COLOUR OPTION  2';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'colour_paletteset'] 	= 'Override default built-in palette with tonal variations to colour scheme';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'colour_variety'] 		= 'Colour variety';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'gradient_covers'] 		= 'Gradient effects';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metaheader1'  . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = '<font color="#FF0000">Upper - </font>HEADER background image<br /> URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metaheader1'  . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '<font color="#FF0000">Upper - </font>HEADER background image<br />Formatting:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metafooter'   . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'FOOTER background image<br /> URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metafooter'	  . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'FOOTER background image<br />Formatting:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metatoolbar1' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Upper - TOOLBAR background image<br /> URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metatoolbar1' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'Upper - TOOLBAR background image<br />Formatting:';	
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metatoolbar2' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Lower - TOOLBAR background image<br /> URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metatoolbar2' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'Lower - TOOLBAR background image<br />Formatting:';		
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'coursepage'   . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'MAIN CONTENT background image<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'coursepage'   . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'MAIN CONTENT background image<br />Formatting:';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metadesktop'  . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Desktop - FRONT layer<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'metadesktop'  . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'Desktop - FRONT layer<br />Formatting:';	// NB IcandConstantsBackend::SETTINGS_STYLE_SUFFIX

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC ] = 'Desktop - BACK layer<br />Built-in tiled graphic background:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC . IcandConstantsBackend::SETTINGS_URL_SUFFIX] = 'Desktop - BACK layer<br />URL:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'Desktop - BACK layer<br />Formatting:';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET] 	= 'Separate course sections (weeks/topics)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'table_spacing'] 			= 'Spacing between columns in main content area';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'gutter_rules'] 				= 'Show vertical lines between columns';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'layout_type'] 				= 'Blocks positioning';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET ] 		= 'Blocks and column styling'; // 
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'layout_make_borders'] 		= 'Blocks and section borders';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ] = 'NAVIGATON TOOLS<br /> Set location:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'menu_useraccesstools'] 		= 'User accessibility control options';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'menu_userlogon'] 			= 'User login & profile link options:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'menu_userplacement'] 		= 'ACCESSIBILITY AND ADMIN TOOLS<br /> Set location:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'menu_pageidplacement'] 		= 'SITE AND COURSE TITLES<br />Set location:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'foottxtpre'] 				= 'CUSTOM FOOTER<p style="font-weight:normal;">Add plain text or HTML to these field to add custom content to your footer<br />NOTE: this text will display across the whole site.</p>LEFT:';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'foottxtmid'] 				= 'CENTRE';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'foottxtpost'] 				= 'RIGHT';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'sys_position_popup_button' ] = 'Position of button to open course override popup window';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'sys_inherit'] 				= 'Course setting inheritance';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . 'sys_override'] 				= 'Course overrides of default settings';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_MASTER] = 'Master setting for course-level overrides of theme options';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'general'] 			= 'General';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'menu'] 				= 'Utilities - navigation, login, accessibility, footer text and titles';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'header'] 			= 'Header logos';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'footer'] 			= 'Footer logos';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'layout'] 			= 'Content area width';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'pagewide' . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'Blocks and course boxes styling';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'pagewide' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Whole page - background images';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'headfoot_bg'] 		= 'Header and footer - background images';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_TITLES . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'colour'] 			= 'Colour settings';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_PERMISSIONS . IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_NONE ] = 'no-one';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_PERMISSIONS . IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_EDITOR ] = 'Course editors';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_PERMISSIONS . IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_CREATOR ] = 'Course creators';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_PERMISSIONS . IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_SITE_ADMIN ] = 'Site admins';
	
/**
* Descriptions. Keys = "d." joined to contents of IcandConfigOptions::$sc_title 
*/
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'pagewide' . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headfoot_bg'] = '<h4>Header</h4><p>You can display your banner as a background to the whole header. Select one of the options below.</p>
<p>
<ul>
<li>NOTE: The height of the header, footer and toolbar is determined by the content (logos & text), NOT the height of the background image.</li>
<li>If the image is only scaled to the width of the header, it could be clipped at the bottom (if the image is taller than the content space).</li>
<li>If using iCan.D. built-in headers, a background colour must also be selected (go to Background colours and borders > header background colour options)</li></ul></p>';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'footer'] = 'NOTE: The size of these images will affect the height of the footer.';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'layout'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'permit'] = 'Set any specific restrictions for course editors to override the theme settings above.';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'layout_decorations'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'page_gutter'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'table_spacing'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'gutter_rules'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'pagewide' 	. IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Insert a <strong>URL</strong> to link to a graphic, or <strong>upload</strong> an image from your own files';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metadesktop' 	. IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'coursepage' 	. IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = '(Region under course material and blocks)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'coursepage' 	. IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metatoolbar1' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metatoolbar1' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metatoolbar2' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metatoolbar2' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metafooter' 	. IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metafooter' 	. IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'menu_useraccesstools'] 	= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'foottxtpre'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'foottxtmid'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'foottxtpost'] 			= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'header'] 					= 'Add the URL to link to an image or upload an image from your computer.<br />NOTE: The size of these images will affect the height of the header';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerlogoprealt'] 		= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerlogopostalt'] 		= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'footerlogoprealt'] 		= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'footerlogopostalt'] 		= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'dock_over'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'layout_type'] 			= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour_paletteset'] 		= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_pal_dt'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_off_dt'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_pal_he'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_off_he'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_pal_tb'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_off_tb'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_pal_mm'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_off_mm'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_pal_fo'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'clr_off_fo'] 				= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'gradient_covers'] 		= 'Adds shade that fades to transparent (either light or dark) to background. Gradients can be applied on top of background colour, pattern or image.';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_MASTER] = '';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour'] 				= 'Applying colours:<p>NOTE: that the ability to change offset colour at course level will determined by the administrator and the permissions made available</p><ol><li>Select the colour palette tonal value if required</li><li>Select <strong>COLOUR OPTION 1</strong> (default is light blue)</li><li>Select <strong>COLOUR OPTION 2</strong> (default is blue)</li><li>In each region, select text colour and background colour combination (light text on dark background / dark text on light background)</li></ol>';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour_base'] 		= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour_base_supp'] 	= 'If you require a different colour for a specific region such as the header then under the header colour section select a colour offset number.<p>For example, instead of light blue in the header pink is preferred.</p><ul><li>If COLOUR OPTION 1 is defined as 8, then the default OPTION 2 colour is light blue.</li><li>Under <i><strong>Header</strong></i> > <i><strong>Background colour options</strong></i> select +4 which offsets the original colour to	pink.</li></ul><p>NOTE: If using transparent the text colour will reflect the next layer which has a background applied.</p>';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'layout_make_borders'] = 'This is the master control for turning borders on or off on blocks and course elements.<br /> <strong>NOTE: </strong> borders/sections need to be coloured or "transparent with borders"';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'menu'] 				= 'This section allows you to show/hide and position system menus: modify settings for the global menu, breadcrumbs, site name, course name, accessibility controls and custom footer content';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'sys'] 				= 'Special data for course-based overrides';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerlogopre'] 		= ''; // $uploadablePrompt 
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerlogopost'] 		= ''; // $uploadablePrompt 
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'footerlogopre'] 		= ''; // $uploadablePrompt 
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'footerlogopost'] 		= ''; // $uploadablePrompt 
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerlogoalt'] 		= 'alternative text for main logo';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC] = 'Select built-in graphic (will be ignored if custom URL selected, below). This background tile will always scroll.';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC . IcandConstantsBackend::SETTINGS_URL_SUFFIX] = 'URL of image file (overrides the selection of built-in graphics above)';
//$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour_explicit_list'] = 'Explicitly specify colours. If set, this will override all other colour settings. Use commas to separate. DO NOT USE SEMICOLONS. E.g. #f0f0f0,aqua,#ff5757';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour_palettes'] 	= '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'colour_offsets'] 		= 'For "transparent + optional border": 1. the actual border colour depends on whether the region uses the "light" or "dark" sub-palette (below). 2. the "add borders" option must also be selected (below)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metadesktop' 		. IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Enter a URL, or blank for none. (In the override popup, ~~inherit~~ will use the inherited value)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metaheader1' 		. IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Enter a URL, or blank for none. (In the override popup, ~~inherit~~ will use the inherited value)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'metaheader1' 		. IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX] = 'NOTE: the height of the header is determined by the content (logos & text), NOT the height of the background image. If the image is only scaled to the width of the header, it could be clipped at the bottom if the image is taller than the content space.';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerbackground' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX] = 'Built-in graphic for background of header (will be ignored if custom URL set, below)<br />NOTE: you must also set a background colour if selecting this background option (go to Background colours and borders > header background colour options).';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerbackground' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_TARGET_SUFFIX] = 'Within the header complex, the background can spread vertically to the whole header, or be restricted vertically to just the banner strip';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'headerbackground' . IcandConstantsBackend::SETTINGS_IMG_SUFFIX . IcandConstantsBackend::SETTINGS_URL_SUFFIX] = '';
//$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'menu_sitenavigation'] = 'Custom menu is defined in Site administration -> Appearance -> Themes -> Theme settings';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'menu_pageidplacement'] 	= 'Set location site and course titles';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_MENU_SITENAVPLACEMENT ] = '(Custom menu is defined in Site administration -> Appearance -> Themes -> Theme settings.)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'menu_userlogon'] 			= 'Select which links are available in header for users to log in or link to their profile page. (If turned off here, links can be provided in other places, e.g. blocks)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'menu_userplacement'] 		= 'Select location for user-specific links and tools.<br />NOTE: Course editor tools do not obey "none"';
	
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'sys_position_popup_button' ] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'sys_inherit'] 			= 'BE CAREFUL IF EDITING HERE.<br />comma-separated list child=>ancestor<br />Whitespace is allowed, and is ignored by the system';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . 'sys_override'] 			= 'BE CAREFUL IF EDITING HERE.<br />Comma-separated list coursenum=>{semicolon-separated list feature:value}.<br />Linebreaks are allowed, and are ignored by the system';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'layout'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'pagewidestyle'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'pagewideimg'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'headfoot_bg'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'general'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'menu'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'header'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'footer'] = '';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'colour'] = '';


$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_DESCRIPTIONS . IcandConstantsBackend::DB_FIELD_PERMIT_PREFIX . 'overrideheadergraphicurls'] = 'Permit course editors to upload graphic files to the theme folder of this Moodle server. (For this to work, the system administrator must also have given file-system permission for the web server to write files to the /pix/uploaded subfolder of the theme\'s folder).';	

/**
* Values of configuration options.
*/

$string['VALUE_NONE']			= '* none *';

// values used in X-Y radio button selection grids =========================================

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'dt'] = 'whole desktop';		
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'he'] = 'whole header';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'h1'] = 'top strip of header';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'h2'] = 'banner strip of header';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'h3'] = 'strip at base of header';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'tb'] = 'whole toolbar (below header)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 't1'] = 'top strip of toolbar';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 't2'] = 'bottom strip of toolbar';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'mm'] = 'whole background under both course material & blocks';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'cm'] = 'course material (sections, books)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'ce'] = '"pre" blocks';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'co'] = '"post" blocks';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'fo'] = 'whole footer'	;

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'none'] 		= 'none';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'shade'] 		= 'shaded';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'bordertb'] 	= 'horizontal borders';
					
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'header1.pre'] 	='header 1 left';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'header1.post'] 	='header 1 right';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'header2.pre'] 	='header 2 left';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'header2.post'] 	='header 2 right';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'header3.pre'] 	='header 3 left';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'header3.post'] 	='header 3 right';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'toolbar1.pre']	='toolbar 1 left';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'toolbar1.post']	='toolbar 1 right';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'toolbar2.pre']	='toolbar 2 left';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'toolbar2.post']	='toolbar 2 right';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'footer2.pre']		='footer left';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'footer2.mid']		='footer middle';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'footer2.post']	='footer right';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('base~light')]		= '<strong>COLOUR OPTION 1:</strong><br />Dark text on light background';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('base~dark')]		= '<strong>COLOUR OPTION 1:</strong><br />Light text on dark background';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('base_supp~light')]	= '<strong>COLOUR OPTION 2:</strong><br />Dark text on light background';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('base_supp~dark')]	= '<strong>COLOUR OPTION 2:</strong><br />Light text on dark background';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_NOTSET 		] ='transparent';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BORDER_ONLY 	] ='transparent + optional border';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BW 			] ='black/ white';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO			] ='gray';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'0'] 	='use base colour';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'1'] 	='base +1';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'2'] 	='base +2';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'3'] 	='base +3';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'4'] 	='base +4';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'5'] 	='base +5';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'6'] 	='base +6';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'7'] 	='base +7';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'8'] 	='base +8';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'9'] 	='base +9';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'10'] 	='base +10';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'11'] 	='base +11';

// gradient overlays
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'LT']				= 'light top (convex)';		// changed to capitals 02Oct2012
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'DT']				= 'dark top (concave)';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'LB']				= 'light bottom';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS .'DB']				= 'dark bottom';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'moodlecustommenu'	] = 'custom menu';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'breadcrumbs'			] = 'breadcrumbs';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'idsite'] 	= 'Site name';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'idcat'] 		= 'Course category';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'idcourse'] 	= 'Course full name';

$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'userlogonlink'] 		= 'Logon & profile links';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'userDisplay'] 		= 'Accessibility and language selectors';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'useredittools'] 		= 'Course editor\'s button';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'usermoodledocs'] 	= 'Link to MoodleDocs';
$string[IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . 'userHomeButton'] 	= 'Moodle "Home" button';

// values used in monotonic (pulldown) selectors ===============================================

$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('~~inherit~~') ]  = '~~inherit~~';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'none']   	= 'none';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . '']   	= 'none';		// used by colour_paletteset, desktoptilegraphic, headerbackgroundimg

//IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'page_layout' . IcandWholePageOptions::LAYOUT_ALL_WIDE] 	= 'All wide'; //
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'page_layout' . IcandWholePageOptions::LAYOUT_HEADER_WIDE] 	= 'Header wide';  // 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'page_layout' . IcandWholePageOptions::LAYOUT_BACKGROUNDS_WIDE] 	= 'Backgrounds wide'; // 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'page_layout' . IcandWholePageOptions::LAYOUT_ALL_NARROW ] 	= 'All narrow'; // 

// field 'dock_over'
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'dock' . IcandWholePageOptions::DOCK_ALWAYS_SQUEEZE ] 	= 'Never overlap'; //
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'dock' . IcandWholePageOptions::DOCK_ALWAYS_OVERLAP] 	= 'Always overlap'; // 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'dock' . IcandWholePageOptions::DOCK_NO_OVERLAP_ALL_WIDE ] 	= 'Overlap UNLESS layout is "All wide"'; // 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'dock' . IcandWholePageOptions::DOCK_NO_OVERLAP_HEADER_WIDE] 	= 'Overlap UNLESS layout has wide header'; // 

// field 'headerbackgroundimgtarget'
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'metaheader'] 		= 'Whole header';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'metaheader2'] 	= 'Only banner bar within header';

// field 'colour_intensity'
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_intensity0'] 	= 'normal:muted at front';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_intensity1'] 	= 'reversed';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_intensity2'] 	= 'all strong';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_intensity3'] 	= 'all muted';

// field 'colour_base' and 'colour_base_supp' 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base0'] 	
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp0']		='0';		// red
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base1'] 	
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp1'] 	='1';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base2'] 
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp2'] 	='2';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base3'] 
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp3'] 	='3';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base4'] 
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp4']		='4'; 		// green
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base5'] 
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp5']		='5';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base6'] 
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp6'] 	='6';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base7']
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp7'] 	='7';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base8']
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp8'] 	='8';	 	// blue
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base9']
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp9'] 	='9';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base10']
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp10'] 	='10';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base11']
= $string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'colour_base_supp11'] 	='11';

// field 'layout_type'
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . '2L'] 	='1 block column, on left';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . '2R'] 	='1 block column, on right';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . '3B'] 	='2 block columns, on either side';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . '3L'] 	='2 block columns, both on left';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . '3R'] 	='2 block columns, both on right';

$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'stretch'] 		='Single image, scaled to page width';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'stretchall'] 		='Single image, stretched in height and width to fill area';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'middle'] 			='Single image, no scrolling, in centre of page, no scaling';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'middle-sc'] 		='Single image, scrolling, in centre of page, no scaling';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'repeat'] 			='Tiled in 2 dimensions to fill area';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'repeat-x'] 		='Tiled horizontally only, no scrolling, across top of area';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'repeat-x-sc'] 	='Tiled horizontally only, scrolling, across top of area';		
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'repeat-y'] 		= 'Tiled vertically only, down centre of area';

$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileDiagonals' ] 	= 'Light diagonal grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileDiamond'] 	= 'Light rows of triangles grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileDiagonalHatch'] 	= 'Light diamond grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileFlatHatchSmall'] 	= 'Light vertical/horizontal narrow grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileFlatHatchMediumRev'] 	= 'Light vertical/horizontal wide grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileDiagonalsRev'] 	= 'Dark diagonal grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileDiamondRev'] 	= 'Dark rows of triangles grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileDiagonalHatchRev'] 	= 'Dark diamond grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileFlatHatchSmallRev'] 	= 'Dark vertical/horizontal narrow grid';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinTileFlatHatchMedium'] 	= 'Dark vertical/horizontal wide grid';

$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinStretchBigCircles1'] 	= 'Circles 1';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinStretchBigCircles2'] 	= 'Circles 2';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'builtinStretchBigWaves'] 	= 'Waves';

// field IcandConstantsBackend::DB_FIELD_DECORATION_SIDEBAR_TARGET
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'block'] 	='style blocks separately';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'cell'] 	='make full-height columns';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'box'] 	='group blocks in single box';


// field IcandConstantsBackend::DB_FIELD_DECORATION_COURSE_TARGET 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::DB_VALUE_DECORATION_COURSE_COMBO] 	= 'show course topics in a single box';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::DB_VALUE_DECORATION_COURSE_INDIV] 	= 'show course topics in individual boxes';

// field 'table_spacing'
//$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'none']   	= 'no space between columns';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts010'] 	= 'narrow space between columns, no margin';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts020'] 	= 'wide space between columns, no margin'	;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts101'] 	= 'narrow margins, no space between columns'	;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts202'] 	= 'wide margins, no space between columns'		;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts111'] 	= 'narrow space between columns, narrow margins'			;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts121'] 	= 'wide space between columns, narrow margins';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts212'] 	= 'narrow space between columns, wide margins'	;	
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'ts222'] 	= 'wide space between columns, wide margins'	;

// field 'gutter_rules'
//$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'NONE' ] 	= 'no vertical dividers';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'gr_vertical'] 	= 'show vertical lines between columns and at sides';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'gr_vertical_in'] 	= 'show vertical lines between columns'		;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'gr_vertical_out'] 	= 'show vertical lines at sides only'	;

// field 'layout_make_borders'
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'layout_borders_no'] 	= 'don\'t add borders';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'layout_borders_yes'] 	= 'add borders'	;

// field 'menu_useraccesstools' 
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'hires'] 	= '1: high resolution colours' ;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'textsize'] 	= '1: font enlarger' ;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'lang'] 	= '1: language selector' ;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('hires,textsize')] 	= '2: hi-res & enlarge fonts';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('hires,lang')] 	= '2: hi-res and language selector'		;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('textsize,lang')] 	= '2: font enlarger and language selector'			;
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('hires,textsize,lang')] 	= '3: hi-res, enlarge fonts, select language';

// field 'menu_userlogon'
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'logonmoodle'] 	= 'Moodle default: text, profile & login links';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'logonlink'] 	= 'login/logout link only';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString('logonprofile,logonlink')] 	= 'profile and login links with no other text';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'logonprofile'] 	= 'profile link only (no login link)';

/*'colour_paletteset' . */
// BEWARE: '' is shared with other values. The prompt for 'colour_paletteset' must work with the following when ''=> 'none';
//$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS .   ''] 		= 'built-in';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'light'] 		= 'light-primary';	// the CSS files are /style/palette_*.css	
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'saturated'] 	= 'saturated-primary';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'dark' ] 		= 'dark-primary';

//IcandConstantsBackend::DB_FIELD_PERMIT_MASTER
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_ALL] 	= 'ALLOW ALL options to be overridden at course level';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_NONE] 	= 'BLOCK ALL overriding of the settings above';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . IcandConstantsBackend::DB_VALUE_PERMIT_MASTER_SOME] 	= 'Allow course level overrides as specified below';

$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'sysbutton_embed' ] = 'embedded in header with Moodle edit button';
$string[ IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . 'sysbutton_fixed' ] = 'fixed at top of browser window';

/***********************************************************/
$string['region-side-post'] = 'Right';
$string['region-side-pre']  = 'Left';


$string['choosereadme'] = '
<div class="clearfix">
	<div class="icand_screenshot">
		<h2>iCan.D.</h2>
		<img src="icand/pix/screenshot.png" />
		<h3>Theme Discussion Forum:</h3>
		<p><a href="http://moodle.org/mod/forum/view.php?id=46">http://moodle.org/mod/forum/view.php?id=46</a></p>
		<h3>Theme Credits</h3>
		<p><a href="http://www.ehub.tafensw.edu.au/">NSW TAFE eLearning Hub</a></p>
		<h3>Theme Documentation:</h3>
		<p>to come</p>
	</div>
	<div class="icand_description">
		<h2>About</h2>
		<p>The eHub iCan.D. theme is coded for Moodle 2.x. It includes a settings page allowing site managers an extensive range of options for the appearance of the site. The site manager can also allow course editors to override a small number of settings at the course level.</p>
		<h2>Parents</h2>
		<p>This theme extends Base from the Moodle core.</p>
		<h2>Credits</h2>
		<p>This design was created by NSW TAFE eLearning Hub (Glen Byram, Renee Lance).
		<h2>License</h2>
		<p>This theme is licensed under the <a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a>.
	</div>
</div>';