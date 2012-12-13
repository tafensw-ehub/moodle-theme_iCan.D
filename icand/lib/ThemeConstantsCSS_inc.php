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
 * @version		12Oct2012
 *
 * Constants starting with CSS_MOODLE_ express CSS classes and ids used in the core Moodle base theme.
 */
/*	CSS cleanups
 *
 * Reduce redundant selector
 * #region-main .course-content 	=> .course-content
 * #page-content .course-content 	=> .course-content
 *
 * From 11Oct2012 (using Moodle core IDs more)
 * #icand_strct_page	-> #page
 * #main_strip		 	-> #region-main-box
 * #main_box			-> #region-main
 */
 
if(class_exists('IcandConstantsCSS') != true)
{
class IcandConstantsCSS {

// =====================================================
// defined in MOODLE
// =====================================================
// in settings menu
const CSS_MENUCELL 				= 'icand_menucell'; // !!! NOT DEFINED IN MOODLE ??

// ===============================================================================
//			classes and IDs defined in Moodle's base theme 
// ===============================================================================
// const CSS_ID_PAGE		 			= 'icand_strct_page';		// 11Oct2012 replaced with moodle "page"
const CSS_MOODLE_ID_PAGE				= 'page';					// 11Oct2012 superceded CSS_ID_PAGE
const CSS_MOODLE_ID_HEADER 				= 'page-header';
	// !!! note: iCan.D. makes nested divs, e.g. page-header1, which are NOT defined by Moodle.
	
// base theme has custommenu
const CSS_MOODLE_ID_FOOTER 				= 'page-footer';
const CSS_MOODLE_ID_CONTENT				= 'page-content';
//const CSS_ID_MAIN_STRIP 				= 'main_strip'; 		// 11Oct2012 replaced with CSS_MOODLE_ID_MAIN_BOX 
const CSS_MOODLE_ID_MAIN_BOX 			= 'region-main-box'; 	// 11Oct2012 CHANGE FROM main_strip
// base theme has region-post-box inside, but it is useless.
	const CSS_MOODLE_ID_MAIN_WRAP 		= 'region-main-wrap'; 	// NOT USED YET
	const CSS_MOODLE_ID_REGION_MAIN 	= 'region-main';		// 11Oct2012 CHANGE FROM main_box
//	const CSS_ID_MAIN_BOX	 			= 'main_box';			// 11Oct2012 replaced with CSS_MOODLE_ID_REGION_MAIN 

	const CSS_MOODLE_CLASS_BLOCK_REGION = 'block-region' ; // NOT USED YET
	const CSS_MOODLE_ID_REGION_PRE  	= 'region-pre'; // NOT USED YET
	const CSS_MOODLE_ID_REGION_POST 	= 'region-post'; // NOT USED YET

	const CSS_MOODLE_CLEARFIX			= 'clearfix';
	
	const CSS_MOODLE_HOMEBUTTON			= 'homelink';	// not used by theme code, but in CSS
	const CSS_MOODLE_BREADCRUMB			= 'breadcrumb';
	const CSS_MOODLE_LOGININFO			= 'logininfo';
	
	/* also 
		IDs: report-main-content report-region-wrap report-region-pre
		classes: headermain headermenu region-content
				breadcrumb navbutton helplink
	*/
	
	// ------------------- in forms -----------------------------------------
	const CSS_MOODLE_FORMITEM			= 'form-item';
	const CSS_MOODLE_FORMSETTING		= 'form-setting';
	const CSS_MOODLE_FORMTEXT			= 'form-text';	// only used for search/replace
	const CSS_MOODLE_DEFAULTSNEXT		= 'defaultsnext';// only used for search/replace

// =====================================================
// 				custom CSS for iCan.D. theme
// =====================================================

// dock: icand_dock.css. Injected into body classes.
const CSS_DOCK_SQUEEZE_BODY 			= 'icand_dock_squeeze';
const CSS_DOCK_OVERLAP_BODY 			= 'icand_dock_overlap';

// ------------
const CSS_SUFFIX_PUSHDOWN_TO_BLOCK		= 'block'; 		//blocks are in pre/post column
const CSS_SUFFIX_PUSHDOWN_TO_SECTION	= 'section';	//main course material is in section

//-----------------------------------------
// structural containers
//-----------------------------------------
// IDs
const CSS_ID_INNERHEADER 				= 'icand_id_innerheader';	// the "toolbar" container below main header, at top of content
const CSS_ID_BODY		 				= 'icand_strct_body';

// classes
const CSS_LAYOUT_COVERALL				= 'icand_cover_all';
const CSS_LAYOUT_BLOCK 					= 'icand_block';

const CSS_CONTAINER						= 'icand_strct_container';
const CSS_FRONTMOST_CONTENTS			= 'icand_over';
const CSS_STYLING_CONTAINER				= 'icand_styling_container';

const CSS_FLOAT_BUTTON_CONTAINER		= 'icand_floatInput';

const CSS_MSIE_TABLE 					= 'icand_msie_table';
const CSS_MSIE_TABLE_ROW				= 'icand_msie_tr';
const CSS_MSIE_TABLE_TD					= 'icand_msie_td';
const CSS_MSIE_TABLE_ID_SUFFIX			= '_ietab';

const CSS_PSEUDO_TABLE					= 'icand_strct_tablecontainer';
const CSS_PSEUDO_TABLE_ROW				= 'icand_strct_containermainrow';

//-----------------------------------------
// for settings pages
//-----------------------------------------
const CSS_CLASS_BUTTON					= 'icand_button';			// generic class for all iCan.D. buttons
const CSS_ID_EDITBUTTON					= 'icand_editthemebutton'; 	// id for button that allows course editors to open config form
const CSS_ID_BUTTON_DIV					= 'icand_popupbuttons';		// id for div that contains the following 3 buttons
const CSS_ID_CANCELBUTTON 				= 'icand_cancelbutton';		// id for button in config form
const CSS_ID_TESTBUTTON					= 'icand_testbutton';		// id for button in config form
const CSS_ID_SAVEBUTTON					= 'icand_savebutton';		// id for button in config form

const CSS_ID_FIXED_CONTROL_AREA			= 'icand_fixedcontrolarea';	// DIV id for fixing position of control button/s

const CSS_TAG_FOR_OVERRIDE_POPUP_TITLE 	= 'H2';
const CSS_ID_OVERRIDE_POPUP_TITLE 		= 'icand_popuptitle';

const CSS_CLASS_MULTICHECKBOX 			= 'form-twodimensionmulticheckbox';	// an iCan.D. creation, but use pluging naming style
// ids for course override menu
const CSS_ID_EDITLAYER 					= 'icand_editlayer';
const CSS_ID_COVERSHADE 				= 'icand_cover_shade';
const CSS_ID_EDITMENU 					= 'icand_editmenu';	
// HR classes for course override menu
const CSS_THICK_HR 						= 'icand_settings_page_main_hr';
const CSS_THIN_HR 						= 'icand_settings_page_thin_hr';

// justification
const CSS_VERTICAL_JUSTIFY_MIDDLE 			= 'icand_verticalAlignMiddle';
const CSS_BLOCK_HORIZONTAL_JUSTIFY_CENTRED	= 'icand_strct_centredhoriz';

// apply transparency to div & contents
const TRANSLUCENT						= 'icand_dec_trans75'; // c_opacityCSSclass
// overlays with transparency
const CSS_GRADIENT_LT					= 'icand_gradientCover_LightTop';
const CSS_GRADIENT_DT					= 'icand_gradientCover_DarkTop';
const CSS_GRADIENT_LB					= 'icand_gradientCover_LightBottom';
const CSS_GRADIENT_DB					= 'icand_gradientCover_DarkBottom';

const CSS_BG_INVERTER					= 'icand_bg_inverted'; // NOT USED

const CSS_TURN_ON_BORDER 				= 'icand_dec_border';
const CSS_TURN_ON_RADIUS 				= 'icand_dec_radius';
const CSS_ADD_PADDING	 				= 'icand_dec_padded';
const CSS_ADD_VERTICAL_SPACE 			= 'icand_vertical_margin';



const CSS_NO_MARGIN						= 'icand_nomargin';
const CSS_HEADERFOOTER_CONTENT 			= 'icand_st_content';
// colour ==============================================
// whole class names
public static $c_textColourClasses = array (
			'icand_d_fg_clr_light'
		,	'icand_d_fg_clr_dark'
		);

// parts of class names, concatenated 
// background colours
const CSS_SUFFIX_FOR_ONLY_BORDER = 'onlyborder';
const CSS_SUFFIX_FOR_GREYSCALE   = 'mono';
const CSS_SUFFIX_FOR_BW   		 = 'bw';
const CSS_COLOUR_PREFIX			 = 'icand_d_bg_clr_';
public static  $cs_BGColourDarkLightSuffix = array ( 
 		'light' 
	,	'dark' );
	
public static function makeCSSDarkLightClass( $p_suffix ) {
	return 	self::CSS_COLOUR_PREFIX . $p_suffix;
}

public static function makeCSSColourOffsetClass( $p_colourOffsetSuffix ) {
	return 	self::CSS_COLOUR_PREFIX . $p_colourOffsetSuffix;
}	

// layout for flippable (LTR/RTL) sides=====================
// parts of class names, concatenated 
const CSS_SIDED_FLOAT 	= 'icand_gen_floater';
const CSS_SIDED_CONTENT	= 'icand_gen_content';
const CSS_SIDED_PRE 	= 'icand_pre';
const CSS_SIDED_MID		= 'icand_mid';
const CSS_SIDED_POST	= 'icand_post';
public static $CSS_TWO_POSITION_SUFFIX = array (	self::CSS_SIDED_PRE
										,	self::CSS_SIDED_POST );			
public static $CSS_THREE_POSITION_SUFFIX = array (self::CSS_SIDED_PRE
										,	self::CSS_SIDED_MID
										,	self::CSS_SIDED_POST );											

public static $fontfamily = array (
//Serif:
		'serif_Palatino' 	=> '\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif'
	,	'serif_Times' 		=> '\'Times New Roman\', Times, serif'
	,	'serif_Georgia' 	=> 'Georgia, Serif'
	,	'serif_Bookman'		=> '\'Bookman Old Style\', serif'
	//Sans-serif:
//	,	'sans-serif_MS' => '\'MS Sans Serif\', Geneva, sans-serif'
	,	'sans-serif_Tahoma' 	=> 'Tahoma, Geneva, sans-serif'
	,	'sans-serif_Verdana'	=> 'Verdana, Geneva, sans-serif'
	,	'sans-serif_Arial'		=> 'Arial, Helvetica, sans-serif'
	,	'sans-serif_Trebuchet'	=> '\'Trebuchet MS\', Helvetica, sans-serif'
	,	'sans-serif_Century_Gothic' => 'Century Gothic, sans-serif'
	,	'sans-serif_Lucida'		=> '\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif'
	,	'sans-serif_Arial'		=> '\'Arial Narrow\', sans-serif'
	,	'sans-serif_Gills'		=> 'Gill Sans / Gill Sans MT, sans-serif'
//	,	'sans-serif, ' => 'Copperplate / Copperplate Gothic Light, sans-serif'   ???no lowercase??
//monospace:
	,	'monospace_Lucida'		=> '\'Lucida Console\', Monaco, monospace'
	,	'monospace_Courier'		=> '\'Courier New\', Courier, monospace'	
	);		

}//class

}//if(class_exists
