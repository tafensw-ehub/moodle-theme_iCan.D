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
 * @version		12Oct2012
 */

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	 
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsCSS_inc.php'); 
require_once(dirname(__FILE__) . '/../settings_popup.php');	// class Icand_admin_setting_page
$settings = new Icand_admin_setting_page('themeOverride','Theme override');

require_once($CFG->libdir.'/adminlib.php'); // declares classes used in settings_inc.php

$popup_override = true;

// must set $l_multichoiceOverrides BEFORE including settings_inc.php
//$l_multichoiceOverrides = array();// TODO: get overrides in here

$uniqueHREFsuffix = 'OVERRIDE_';
require(dirname(__FILE__) . '/../settings_inc.php');

require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');	// class IcandConfigOptions
// uses BuildPage::$cs_BGColourDarkLightSuffix, HEADER_SET, BODY_SET

function makeSelections ( $p_array ) {
	$l_defaultValue = array (IcandConfigOptions::$l_optionAcceptDefault => '**theme default**');
	return array_merge( $l_defaultValue, $p_array );
}

function writeOverrideMenu (&$p_themeconfig, &$settings, &$p_vischecker, &$p_localOverrides=null ) {
global $l_isAbleToEditCourse, $COURSE;

	$l_themeFormPrefix = 's_' . IcandConstantsBackend::$g_wholeThemeName . '_'; 

	$l_userIsAdmin = true; //false; //true; !!!!!!!!!!!!!!!!!!!!!!! MUST BE SET TO SOME VALUE
	$l_outputbuffer = array();
	$l_outputbuffer[] = '<div id="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_ID_EDITLAYER;
	$l_outputbuffer[] = '" class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_LAYOUT_COVERALL;
	$l_outputbuffer[] = '">';
	$l_outputbuffer[] = '<div id="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_ID_COVERSHADE;
	$l_outputbuffer[] = '" class="';
	$l_outputbuffer[] = IcandConstantsCSS::TRANSLUCENT;
	$l_outputbuffer[] = '">&nbsp;</div>';
	$l_outputbuffer[] = '<div id="';
	$l_outputbuffer[] =	IcandConstantsBackend::MOODLE_BUILTIN_ID_FOR_SETTINGS_FORM ;
	$l_outputbuffer[] =	'">';	 // MOODLE should make this an html class
	$l_outputbuffer[] = 	'<div id="';
	$l_outputbuffer[] = 	IcandConstantsCSS::CSS_ID_EDITMENU ;
	$l_outputbuffer[] = 	'">';
	$l_outputbuffer[] = 		'<div class="';
	$l_outputbuffer[] = 		IcandConstantsCSS::CSS_MENUCELL;
	$l_outputbuffer[] = 		'"><' ;
	$l_outputbuffer[] = 		IcandConstantsCSS::CSS_TAG_FOR_OVERRIDE_POPUP_TITLE ;	// e.g. H2
	$l_outputbuffer[] = 		' id="' ;
	$l_outputbuffer[] = 		IcandConstantsCSS::CSS_ID_OVERRIDE_POPUP_TITLE ;
	$l_outputbuffer[] = 		'">';
	$l_outputbuffer[] = 		get_string( IcandConstantsCSS::CSS_ID_OVERRIDE_POPUP_TITLE, IcandConstantsBackend::$g_wholeThemeName ); //'iCan.D. theme: Course override of theme settings';
	$l_outputbuffer[] = 		' #';
	$l_outputbuffer[] = 		$COURSE->id;
	$l_outputbuffer[] = 		' </' ;
	$l_outputbuffer[] = 		IcandConstantsCSS::CSS_TAG_FOR_OVERRIDE_POPUP_TITLE ; // close e.g. H2
	$l_outputbuffer[] = 		'></div>';

	$l_outputbuffer[] = 			'<form action="';
	$l_outputbuffer[] = $_SERVER['REQUEST_URI']	;	// call back to same page
	$l_outputbuffer[] = 				'" method="post">';
	$l_outputbuffer[] = 			'<input type="hidden" name="';
	$l_outputbuffer[] = 			IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_OVERRIDE;
	$l_outputbuffer[] = 			'" value="';
	$l_outputbuffer[] = 			'MagicNumber';	
	$l_outputbuffer[] = 			'"/>';
	
	//---------------------------------------------
	// inputs relevant to course overrides only
	//---------------------------------------------
	$l_outputbuffer[] = '<div class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_CLEARFIX ;
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_LAYOUT_BLOCK;
	$l_outputbuffer[] = '"> </div><hr/>';
	
	$l_outputbuffer[] = '<label for="';
	$l_outputbuffer[] = IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR ;
	$l_outputbuffer[] = '"/>';
	$l_outputbuffer[] = get_string( IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR, IcandConstantsBackend::$g_wholeThemeName ); // 'test reverse text direction (RTL)'	;
	$l_outputbuffer[] = '</label>';
	
	$l_outputbuffer[] = '<input type="checkbox" name="';
	$l_outputbuffer[] = IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR ;
	$l_outputbuffer[] = '" id="' ;	
	$l_outputbuffer[] = IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR ;
	$l_outputbuffer[] = '" value="' ;
	$l_outputbuffer[] = IcandConstantsHTMLForms::FORM_KEY_FIELD_FLIP_TEXT_DIR ;
	$l_outputbuffer[] = '"/>&nbsp;&nbsp;';
	
	if ( $p_vischecker->isVisible(IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE )) {
		$l_outputbuffer[] = '<label for="';
		$l_outputbuffer[] = IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE ;
		$l_outputbuffer[] = '"/>';
		$l_outputbuffer[] = get_string( IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE, IcandConstantsBackend::$g_wholeThemeName ); // 'inherit theme settings from course#';
		$l_outputbuffer[] = '</label>';		
		
		$l_outputbuffer[] = '<input type="text" size="5" id="';
		$l_outputbuffer[] = IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE ;
		$l_outputbuffer[] = '" name="';
		$l_outputbuffer[] = IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE ;
		$l_outputbuffer[] = '" id="' ;			
		$l_outputbuffer[] = IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE ;
		$l_outputbuffer[] = '" value="';
		if (isset($p_themeconfig[IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE])) {		// 'sys_parentcourse'
			$l_outputbuffer[] = $p_themeconfig[IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE];
		}
		$l_outputbuffer[] = '" />';
	}//if $p_vischecker...
	$l_outputbuffer[] = '<br />'	;		
	//---------------------------------------------
	$l_moodleSettingsHtml = $settings->output_html();
	// text inputs: changed displayed values to "~~inherit~~"
	$l_targetPattern = '~(<div class="'
					. IcandConstantsCSS::CSS_MOODLE_FORMTEXT 
					. ' '
					. IcandConstantsCSS::CSS_MOODLE_DEFAULTSNEXT
					. '"><input type="text" size=".+value=")[^"]*(" \/></div>)~im';
	$l_moodleSettingsHtml = preg_replace( $l_targetPattern, '\1'.  IcandConstantsHTMLForms::FORM_SINGLE_VALUE_INHERIT . '\2',   $l_moodleSettingsHtml );	

	// removed displayed value in selects
	$l_moodleSettingsHtml = str_replace(' selected="selected"' , '', $l_moodleSettingsHtml ); 
	
	// if there are overrides, set these as defaults in the form
	if (isset( $p_localOverrides )) {
		foreach ( $p_localOverrides as $l_key => $l_value ) {
			$l_isSelection = IcandConfigOptions::hasOptionValues($l_key);
			$l_targetName = $l_themeFormPrefix . $l_key;
			if ($l_isSelection) {
				$l_targetPattern = '/(name="' . $l_targetName .'">.*)<option value="' . $l_value . '"/im';
				$l_replace = '<option selected="selected" value="' . $l_value . '"';	
				$l_moodleSettingsHtml = preg_replace( $l_targetPattern, '\1'. $l_replace,   $l_moodleSettingsHtml );
			} else { // is text
				$l_targetPattern = '/(<input type="text" .* name="' . $l_targetName . '" value=")~~inherit~~"/im';
				$l_moodleSettingsHtml = preg_replace( $l_targetPattern, '\1'. $l_value .'"',  $l_moodleSettingsHtml );
			}//else
		}//foreach
	}//if(isset..
	
	$l_outputbuffer[] = $l_moodleSettingsHtml;
	//---------------------------------------------
		$l_outputbuffer[] = '<div class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_CLEARFIX ;
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_LAYOUT_BLOCK;
	$l_outputbuffer[] = '">&nbsp;</div><hr/>'; //DOESN'T WORK IN msie7
	
	// buttons that sit at the bottom of the form: cancel/test/save
	$l_outputbuffer[] = 		'<div id="'
								. IcandConstantsCSS::CSS_ID_BUTTON_DIV
								. '">';  //'<div class="atRight">'
	$l_outputbuffer[] = 			'<input type="button" id="' 
								. IcandConstantsCSS::CSS_ID_CANCELBUTTON
								. '" class="'
								. IcandConstantsCSS::CSS_CLASS_BUTTON 								
								. '" value="'
								. get_string( IcandConstantsCSS::CSS_ID_CANCELBUTTON, IcandConstantsBackend::$g_wholeThemeName ) //close (cancel)
								. '" onclick="setitemdisplay(\''
								. IcandConstantsCSS::CSS_ID_EDITLAYER
								. '\',\'none\');"/>';
	if ( $l_isAbleToEditCourse || $l_userIsAdmin) {
		$l_outputbuffer[] = '&nbsp;&nbsp;';
		$l_outputbuffer[] = 			'<input type="submit" id="'
										. IcandConstantsCSS::CSS_ID_TESTBUTTON // testbutton
										. '" class="'
										. IcandConstantsCSS::CSS_CLASS_BUTTON								
										. '" name="' 
										. IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_TEST
										. '" value="'
										. get_string( IcandConstantsCSS::CSS_ID_TESTBUTTON, IcandConstantsBackend::$g_wholeThemeName ) // Test
										. '"/>';
		$l_outputbuffer[] = '&nbsp;&nbsp;';
		$l_outputbuffer[] = 			'<input type="submit" id="'
										. IcandConstantsCSS::CSS_ID_SAVEBUTTON //savethemebutton
										. '" class="'
										. IcandConstantsCSS::CSS_CLASS_BUTTON										
										. '" name="' 
										. IcandConstantsHTMLForms::FORM_KEY_FIELD_IS_SAVE 
										. '" value="'
										. get_string( IcandConstantsCSS::CSS_ID_SAVEBUTTON, IcandConstantsBackend::$g_wholeThemeName ) // Save
										. '"/>';
	}//if ableToEdit...
	$l_outputbuffer[] = 		'</div>';
	$l_outputbuffer[] = 		'</form>';
	$l_outputbuffer[] = 	'</div>'; //icand_editmenu
	$l_outputbuffer[] = 	'</div>'; //adminsettings	
	$l_outputbuffer[] = '</div>';//icand_editlayer
	echo implode (null, $l_outputbuffer );
}//writeOverrideMenu ()