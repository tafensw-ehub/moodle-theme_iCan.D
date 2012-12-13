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
 * Configuration for Moodle's TAFE eHub iCan.D. configurable theme.
 *
 * DO NOT MODIFY THIS THEME!
 * COPY IT FIRST, THEN RENAME THE COPY AND MODIFY IT INSTEAD.
 *
 * For full information about creating Moodle themes, see:
 *  http://docs.moodle.org/dev/Themes_2.0
 * 
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		15Oct2012
 */
/*
 *	'page_layout' is defined in IcandConstantsBackend::DB_FIELD_PAGE_LAYOUT
 */

if (empty($THEME)) {	
/* This is just here for safety if the server's pHp is running in strict mode. This will happen if 
  Moodle debugging has "DEVELOPER:show extra error messages.
  Just using $THEME 1st time for installation up violates "Strict standards: Creating default object from empty value"
  because the only place Moodle does this is theme_config::find_theme_config() in /lib/outputlib.php
*/
	$THEME = new stdClass();	
}
 
$THEME->name = 'icand';

////////////////////////////////////////////////////
// Name of the theme. The name of
// the directory in which this file resides.
////////////////////////////////////////////////////


$THEME->parents = array(
    'base'
);

/////////////////////////////////////////////////////
// Which existing theme(s) in the /theme/ directory
// do you want this theme to extend. A theme can
// extend any number of themes. Rather than
// creating an entirely new theme and copying all
// of the CSS, you can simply create a new theme,
// extend the theme you like and just add the
// changes you want to your theme.
////////////////////////////////////////////////////

//$THEME->sheets = array();

/*
$THEME->sheets = array(
    'core',				// inherit from base & canvas
    'pagelayout',		// start from scratch
    'colors',			// 1st def in this theme
    'menu',				// 1st def in this theme
);
*/

////////////////////////////////////////////////////
// Name of the stylesheet(s) you've including in
// this theme's /styles/ directory.
////////////////////////////////////////////////////

$THEME->parents_exclude_sheets = array(
    'base'=>array(
        'pagelayout'
	,	'dock'
	,	'course'
    )
);

$THEME->sheets = array(
    'icand_core_layout'	/** Must come first: Page layout **/
,   'icand_core_generic'	/** Must come second: default styles **/
,	'icand_core_font'
,	'icand_core_colour'
,	'palette_default'	/* can be overridden by palette_XXXX.css */

,	'icand_content_colours'	/* colour in course divs */
,	'icand_content_spacing'	/* appearance of course divs */

,	'icand_dock'		/* docking bar */
,	'icand_yui3'		/* fix up YUI menu */

/* extras */
,   'categorylists'
,   'forumModule'
,   'gallerygrid'
,   'icand_databasemodule'
,   'icand_glossarymodule'
,	'icand_modquiz'

/* the following are only for "widget" plugins that use javascript. Not actually part of the theme. */
,   'templatetwocol'
,	'themeroller'
,	'widgetsgeneral'
,   'cycleCore'
,   'cycleonecolumn'
);

/* some CSS is loaded in-line 
	option_icand_hires.css	- high contrast. Only loads if the accessibility tools are loaded.
	palette_dark.css 		- overrides palette_default.css
	palette_light.css 		- overrides palette_default.css
	palette_saturated.css	- overrides palette_default.css
*/

$THEME->enable_dock = true;

////////////////////////////////////////////////////
// Do you want to use the new navigation dock?
////////////////////////////////////////////////////

$THEME->editor_sheets = array('editor');

////////////////////////////////////////////////////
// An array of stylesheets to include within the
// body of the editor.
////////////////////////////////////////////////////
// pages set their layout with $PAGE->set_pagelayout( STRING )

$THEME->layouts = array(
    // Most backwards compatible layout without the blocks - this is the layout used by default
    'base' => array(
        'file' => 'general.php',
        'regions' => array(),
'options' => array('debug'=>'base' ),		
    ),
    // Standard layout with blocks, this is recommended for most pages with general information
    'standard' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
'options' => array('debug'=>'standard' ),		
    ),
    // Main course page
    'course' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu'=>true, 'debug'=>'course'),
    ),
    'coursecategory' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
'options' => array('debug'=>'coursecategory' ),				
    ),
    // part of course, typical for modules - default page layout if $cm specified in require_login()
	// "HTML pages" (which are activites) use this!!
    'incourse' => array(
        'file' => 'general.php',
		'regions' => array('side-pre', 'side-post'), 
		'defaultregion' => 'side-pre',
		'options' => array('debug'=>'incourse'
				//	, 'page_layout' => 'page_layout0' 		// NOT POPULAR, SO REMOVED 17Sep2012
				),
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
'options' => array('debug'=>'frontpage' ),				
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
'options' => array('debug'=>'admin' ),		
    ),
    // My dashboard page
    'mydashboard' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu'=>true, 'debug'=>'mydashboard'),
    ),
    // My public page
    'mypublic' => array(
        'file' => 'general.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
'options' => array('debug'=>'mypublic' ),			
    ),
    'login' => array(
        'file' => 'general.php',
        'regions' => array(),			// NO SIDEBARS FOR LOGIN
        'options' => array('langmenu'=>true, 'debug'=>'login' ),
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array( 'debug'=>'popup'
						,	'page_layout' => 'page_layout0'
						,	'nofooter'=>true
						, 	'nonavbar'=>true
						, 	'nocustommenu'=>true
						, 	'nologininfo'=>true
						),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('debug'=>'frametop',  'nofooter'=>true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible
    'embedded' => array(
        'file' => 'general.php', /* !!!!!!!!!!!!!!!!!!!!! */
        'regions' => array(),
        'options' => array('debug'=>'embedded',  
					'page_layout' => 'page_layout0', 'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true),
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, and it is good idea if it does not have links to
    // other places - for example there should not be a home link in the footer...
    'maintenance' => array(
		// after upgrading plugins
        'file' => 'general.php',
        'regions' => array(),
//    ,    'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true), // 'noblocks'=>true, 
'options' => array('debug'=>'maintenance' ),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'general.php',
        'regions' => array(),
        'options' => array('debug'=>'print',  'noblocks'=>true, 'nofooter'=>true, 'nonavbar'=>false, 'nocustommenu'=>true),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
		// called when 
        'file' => 'general.php', /* !!!!!!!!!!! embedded !!!!!!!! */
        'regions' => array(),
        'options' => array('debug'=>'redirect',  'nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true),
    ),
    // The pagelayout used for reports. DOESN'T SEEM TO INCLUDE SITE ADMIN REPORTS!!
    'report' => array(
        'file' => 'general.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
		'options' => array('debug'=>'report',   'page_layout' => 'page_layout0' ), //added 2aug
    ),
);

///////////////////////////////////////////////////////////////
// These are all of the possible layouts in Moodle. The
// simplest way to do this is to keep the theme and file
// variables the same for every layout. Including them
// all in this way allows some flexibility down the road
// if you want to add a different layout template to a
// specific page.
///////////////////////////////////////////////////////////////

//$THEME->csspostprocess = 'icand_process_css';

////////////////////////////////////////////////////
// Allows the user to provide the name of a function
// that all CSS should be passed to before being
// delivered.
////////////////////////////////////////////////////

// $THEME->javascripts

////////////////////////////////////////////////////
// An array containing the names of JavaScript files
// located in /javascript/ to include in the theme.
// (gets included in the head)
////////////////////////////////////////////////////

$THEME->javascripts_footer = array (
	'icand-fixer_start'  // put this before possible conflicts with $() function
		// for various decorative widgets...
,	'jquery-1.8.0.min'	// for decorations: should be moved to activity plugin
,	'jquery-ui-1.8.24.custom.min'	/* added 15Oct2012 */
/* removed 15Oct2012,	'jquery.ui.core'	// for decorations: should be moved to activity plugin */
/* removed 15Oct2012 ,	'jquery.ui.widget'	// for decorations: should be moved to activity plugin */
/* removed 15Oct2012,	'jquery.ui.tabs'	// for decorations: should be moved to activity plugin */
/* removed 15Oct2012,	'jquery.ui.accordion' // for decorations: should be moved to activity plugin */
,	'multiWidgets'		// for decorations: should be moved to activity plugin
,	'jquery.cycle.all'	// for decorations: should be moved to activity plugin
,	'multiSlideshow'	// for decorations: should be moved to activity plugin
,	'icand-fixer_end'  // put this after possible conflicts with $() function

,	'icand_utility'		// THIS IS THE ONLY JS _REQUIRED_ BY THEME. for hi-res, change text size & position footer
						// The others are for embedded HTML widgets which have to be sourced externally.
);


////////////////////////////////////////////////////
// As above but will be included in the page footer.
////////////////////////////////////////////////////

$THEME->larrow    = '<';	//'&lang;'; // &lang; not supported by MSIE7

////////////////////////////////////////////////////
// Overrides the left arrow image used throughout
// Moodle
////////////////////////////////////////////////////

$THEME->rarrow    = '>';	//'&rang;'; // &rang; not supported by MSIE7

////////////////////////////////////////////////////
// Overrides the right arrow image used throughout Moodle
////////////////////////////////////////////////////

// $THEME->layouts

////////////////////////////////////////////////////
// An array setting the layouts for the theme
////////////////////////////////////////////////////

// $THEME->parents_exclude_javascripts

////////////////////////////////////////////////////
// An array of JavaScript files NOT to inherit from
// the themes parents
////////////////////////////////////////////////////

//$THEME->parents_exclude_sheets

////////////////////////////////////////////////////
// An array of stylesheets not to inherit from the
// themes parents
////////////////////////////////////////////////////

// $THEME->plugins_exclude_sheets

////////////////////////////////////////////////////
// An array of plugin sheets to ignore and not
// include.
////////////////////////////////////////////////////

// $THEME->rendererfactory

////////////////////////////////////////////////////
// Sets a custom render factory to use with the
// theme, used when working with custom renderers.
////////////////////////////////////////////////////

/**********************************************************/
/* 	Configuration ONLY used by iCan.D.                    */
/**********************************************************/

if(class_exists('IcandConstantsConfig') != true) {

class IcandConstantsConfig {

/*  If uploading of graphics is permitted in the site admin control panel
 *  (which also requires giving write permission to /pix/uploaded),
 *  this is the maximum size in bytes for a single file.
 */
	static $FILE_UPLOAD_MAXSIZE = 300000; //300Kb
	
/*  The folder where uploads are sent. Only used by upload_receiver.php.
 *	The full URLs to uploaded files are saved explicitly in database.
 */
	static $FILE_UPLOAD_FOLDER_PATH = 'pix/uploaded/';

/* 
 *	Which MIME filetypes will upload_receiver.php accept to upload to server
 */	
	static $VALID_UPLOADER_FILE_TYPES = array(
						  'image/gif'
						, 'image/jpeg'
						, 'image/pjpeg'			// MSIE
						, 'image/bmp'
						, 'image/png'
						, 'image/x-png'						
						, 'image/tiff'
						, 'image/x-xbitmap'
						, 'image/x-tiff'		// is this real??
						, 'image/x-bmp'			// is this real??
						);
}//class
}//if class_exists