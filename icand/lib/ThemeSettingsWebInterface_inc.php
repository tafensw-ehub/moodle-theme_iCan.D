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
 * @version		13Dec2012
 */
if(class_exists('IcandSettingsWebInterface') != true)
{


require_once(dirname(__FILE__) . '/ThemeOptions_inc.php');					// class IcandConfigOptions
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	// 
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsCSS_inc.php');		//names of CSS classes/ids 

// supporting class for settings.php

class IcandSettingsWebInterface {
	var $c_themeName;
	var $c_systemThemeName;
	var $c_systemThemeNamePrefix;
	var $c_textBoxSize = 80;	// # chars in a text box
	
	// demonstration colours from alternative palettes.
	var $c_colour_palettesetRGB = array(
			''			=> array ('#900;','#090;','#009;')	
		, 	'light' 	=> array ('#FE8080;','#FEFF80;','#809FFE;')	
		,	'light2' 	=> array ('#FE80B9;','#FEF280;','#80C8FE;')	
		,	'saturated'	=> array ('#FF0000;','#FFFF00;','#0033CC;')	
		,	'saturated2'=> array ('#FF3300;','#CCFF00;','#1919B3;')	
		, 	'dark' 		=> array ('#B20000;','#B2B300;','#00248E;')
		, 	'dark2' 	=> array ('#A10048;','#B2A100;','#00477D;')		
	);
//	var $c_backToTop = '<br /><a href="#top">Back to contents</a>';
	
	// pass IcandConfigOptions object in to constructor for this class, 
	//		i.e. this should be generic
	
	/*public function getAllHeadingCodes() { return (array_keys(IcandConfigOptions::$sc_headingsArray));}*/
		
	public function __construct($p_themeName) {
		$this->c_themeName = $p_themeName;
		$this->c_systemThemeName = 'theme_' . $p_themeName;
		$this->c_systemThemeNamePrefix = $this->c_systemThemeName . '/';
	}
	
	//-----------------
	// construct admin_setting_**** objects
	public function makeHeading ( $p_sectionName, $p_uniquePrefix = '', $p_backToTop='' ) {
		return (new admin_setting_heading (
				$this->makeSettingName( $p_sectionName )
			,	$this->makeHrefTarget($p_sectionName, $p_uniquePrefix )
			,	$p_backToTop . IcandConfigOptions::getDescription($p_sectionName) )); 
	}//makeHeading(..)
	
/**
 *
 * @return admin_setting_configmulticheckbox
 */
	public function makeMultiSelectFromArray($p_sectionName, $p_displayName, $p_elementArray ) {
			$l_choicesArray = array();
			foreach ( $p_elementArray as $l_element ) {
				$l_choicesArray[$l_element] = IcandConfigOptions::getTitle($l_element);
			}//foreach
			return  new admin_setting_configmulticheckbox (
							$this->makeSettingName($p_sectionName) // internal name
						,	$p_displayName // $visiblename, 
						,	''			// $description, 	
						,	''			// $defaultsetting, 
						,	$l_choicesArray
						);						
	}//makeMultiSelectFromArray(..)
	
/**
 *
 * @return admin_setting_configtextarea
 */	
	public function makeConfigtextarea( $p_sectionName, $p_isOverride = false ) {			
		return  new admin_setting_configtextarea(
						$this->makeSettingName( $p_sectionName )
					,	IcandConfigOptions::getTitle($p_sectionName)	
					,	IcandConfigOptions::getDescription($p_sectionName)
					, 	IcandConfigOptions::getDefault($p_sectionName)
					);
	}//makeConfigtextarea(..)

/**
 * @param $p_sectionName string			The label of the input control. Corresponds to a key value in the database.
 * @param $p_isOverride	boolean			If true, an extra input choice "inherit" is added to the list of values.
 * @return admin_setting_configselect	An input control from a core Moodle class.
 */
	public function makeSelect( $p_sectionName, $p_isOverride = false ) {
		$l_options = IcandConfigOptions::getOneAxisOptionValues($p_sectionName);
		if ($p_isOverride) {
			$l_options = array_merge( IcandConfigOptions::$sc_defaultArray, $l_options );
		}//if
		
		// match each back-end value with a display string in the user's language
		$l_optionMap = array();
		foreach ( $l_options as $l_key ) { //=> $l_value ) {
			$l_optionMap[ $l_key ] = get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . /*$p_sectionName . */ IcandConstantsBackend::makeIdSafeForGetString($l_key), IcandConstantsBackend::$g_wholeThemeName );
		}//foreach
		
		return	new admin_setting_configselect ( 
						$this->makeSettingName( $p_sectionName ) 				// internal name
					,	IcandConfigOptions::getTitle($p_sectionName)			// title
					,	IcandConfigOptions::getDescription($p_sectionName)		// description
					,	IcandConfigOptions::getDefault($p_sectionName)			// default
					,	$l_optionMap											// array values = prompt
					);
	}//makeSelect(..)

/**
 * @param $p_sectionName string			The label of the input control. Corresponds to a key value in the database.
 * @return admin_setting_configcheckbox	An input control from a core Moodle class.
 */	
	public function makeCheckbox( $p_sectionName ) {  // NOT USED. FOR OVERRIDES, PULLDOWN IS BEST
		if (IcandConfigOptions::hasOptionValues( $p_sectionName )) {
			$l_optionArray = IcandConfigOptions::getOneAxisOptionValues( $p_sectionName ); 
			return new admin_setting_configcheckbox(
						$this->makeSettingName( $p_sectionName ) 	// internal name
					,	IcandConfigOptions::getTitle($p_sectionName)		// displayed title
					,	IcandConfigOptions::getDescription($p_sectionName) // description
					,	IcandConfigOptions::getDefault($p_sectionName)	// options
					,	$l_optionArray[0]							// mapped value for selected
					,	$l_optionArray[1]							// mapped value for unselected
					);
		} else {
			return new admin_setting_configcheckbox(
						$this->makeSettingName( $p_sectionName ) 	// internal name
					,	IcandConfigOptions::getTitle($p_sectionName)		// displayed title
					,	IcandConfigOptions::getDescription($p_sectionName) // description
					,	IcandConfigOptions::getDefault($p_sectionName)
					);
		}
	} // makeCheckbox

/**
 * @param $p_sectionName string = database field
 * @param $p_multichoiceOverrideArray array of overridden values.
 * @return admin_setting_configtwoaxismultiradio2 (a new class, defined in iCan.D. theme
 */	
	public function makeTwoAxisRadio( $p_sectionName, &$p_multichoiceOverrideArray=null ) {
		$l_overrides = null;
		$l_isOverride = (($p_multichoiceOverrideArray!==null) 
						and (array_key_exists( $p_sectionName, $p_multichoiceOverrideArray)));
		$l_overrides = ($l_isOverride) 
					 ? $p_multichoiceOverrideArray[$p_sectionName]
					 : null;
		$l_twoAxisOptions = IcandConfigOptions::getTwoAxisOptionValues($p_sectionName);	//,$l_isOverride);
		
		return $this->makeTwoAxisRadioRaw(	$p_sectionName
										,	$l_twoAxisOptions[0]
										,	$l_twoAxisOptions[1]
										,	IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS 
										,	IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS 
										,	$l_overrides
										);
/*		
		$l_xArray = array();
		foreach( $l_twoAxisOptions[0] as $l_key) { // => $l_display ) {
			$l_xArray[ $l_key ] = get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString($l_key), IcandConstantsBackend::$g_wholeThemeName );
		}//foreach

		$l_yArray = array();
		foreach( $l_twoAxisOptions[1] as $l_key ) { // => $l_display ) {
			$l_yArray[ $l_key ] = get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_XY_OPTIONS . IcandConstantsBackend::makeIdSafeForGetString($l_key), IcandConstantsBackend::$g_wholeThemeName );
		}//foreach	
		
		return	new admin_setting_configtwoaxismultiradio2(
					$this->makeSettingName($p_sectionName)
				,	IcandConfigOptions::getTitle($p_sectionName)		// displayed title
				,	IcandConfigOptions::getDescription($p_sectionName) // description
				,	IcandConfigOptions::getDefault($p_sectionName)	// array()	// defaults TO DO FIX
				,	$l_xArray  //  $l_twoAxisOptions[0]
				,	$l_yArray // $l_twoAxisOptions[1]
				,	$l_overrides);
*/				
	}//makeTwoAxisRadio
	
	public function makeTwoAxisRadioRaw(	$p_sectionName
										,	$p_xArray
										,	$p_yArray
										,	$p_xPrefixForGetString
										,	$p_yPrefixForGetString
										, 	$p_overrides ) {
		$l_xArray = array();
		foreach( $p_xArray as $l_key) { // => $l_display ) {
			$l_xArray[ $l_key ] = get_string( $p_xPrefixForGetString 
											. IcandConstantsBackend::makeIdSafeForGetString($l_key)
											, IcandConstantsBackend::$g_wholeThemeName );
		}//foreach

		$l_yArray = array();
		foreach( $p_yArray as $l_key ) { // => $l_display ) {
			$l_yArray[ $l_key ] = get_string( $p_yPrefixForGetString 
											. IcandConstantsBackend::makeIdSafeForGetString($l_key)
											, IcandConstantsBackend::$g_wholeThemeName );
		}//foreach		
		return	new admin_setting_configtwoaxismultiradio2(
					$this->makeSettingName($p_sectionName)
				,	IcandConfigOptions::getTitle($p_sectionName)		// displayed title
				,	IcandConfigOptions::getDescription($p_sectionName) // description		. restored 11Dec2012
				,	IcandConfigOptions::getDefault($p_sectionName)	// array()	// defaults TO DO FIX
				,	$l_xArray  //  $l_twoAxisOptions[0]
				,	$l_yArray // $l_twoAxisOptions[1]
				,	$p_overrides);	
	
	}
	
	public function makeText( $p_sectionName, $p_isOverride = false ) {
		$l_result = new admin_setting_configtext (  
						$this->makeSettingName( $p_sectionName ) 	// internal name
					,	IcandConfigOptions::getTitle($p_sectionName)		// displayed title
					,	IcandConfigOptions::getDescription($p_sectionName) // description
					,	IcandConfigOptions::getDefault($p_sectionName)
					,   PARAM_RAW
					,	$this->c_textBoxSize
					);
		return $l_result;
	}//makeText
	
	public function makeIframe( $p_sectionName, $p_namePrefix, $p_contentURL, $p_height, $p_width ) {
		return new admin_setting_heading ( 
					  $this->makeSettingName( $p_sectionName ) . 'uploader'
					, ''
					, '<div class="' 
						. IcandConstantsCSS::CSS_MOODLE_FORMITEM
						. '"><div class="'
						. IcandConstantsCSS::CSS_MOODLE_FORMSETTING
						. '"><iframe name="'
						. $p_namePrefix
						. $p_sectionName
						. '~setter" height="' . $p_height
						. '" width="' .			$p_width
						. '" src="'    . 		$p_contentURL
						. '"></iframe></div></div>' );
	}//makeIframe(..)
	
	public function makeHrefTarget( $p_shortcut, $p_uniquePrefix = '' ) {
		return ($this->makeDividerHTML(true)) 
			. '<a name="' . $p_uniquePrefix . $p_shortcut . '"></a>' 
			. get_string(	IcandConstantsBackend::DISPLAY_PREFIX_FOR_SETTINGS_GROUPS .$p_shortcut
						, 	IcandConstantsBackend::$g_wholeThemeName );
//		. IcandConfigOptions::getHeading($p_shortcut);
	}
	
	protected function makeDividerHTML( $p_name, $p_isThick=true ) {
		return ( $p_isThick )
				? '<hr class="' . IcandConstantsCSS::CSS_THICK_HR . '"/>'
				: '<hr class="' . IcandConstantsCSS::CSS_THIN_HR  . '"/>'
				;
	}
	
	public function makeDivider( $p_name, $p_isThick=true ) {
		return new admin_setting_heading ( $p_name .'divider', '', $this->makeDividerHTML( $p_name, $p_isThick) );
	}	
	
//---------------------------------------------------------------------
public function makeDemoPallete() {
		$l_outputbuffer = array();
		$l_outputbuffer[] = '<div class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MENUCELL;
		$l_outputbuffer[] = '">';
		$l_outputbuffer[] = '<div class="' ;
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_FORMITEM;
		$l_outputbuffer[] = '"><div class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_FORMSETTING;
		$l_outputbuffer[] = '">';	
		$l_outputbuffer[] = '<table>';
		$GLOBAL_NUM_COLOURS = 12;
		
//		for ($p_set=0;$p_set<=1;$p_set++) 
		$p_set = 1;		// only the dark set. per Renee/Kerrin 13Dec2012
		{
			$l_outputbuffer[] = '<tr>';
				$l_outputbuffer[] = '<td class="' ;
				$l_outputbuffer[] = IcandConstantsCSS::makeCSSColourOffsetClass( 'bw' );
				$l_outputbuffer[] = ' ';
				$l_outputbuffer[] = IcandConstantsCSS::makeCSSDarkLightClass(IcandConstantsCSS::$cs_BGColourDarkLightSuffix[$p_set ]);
				$l_outputbuffer[] = ' ';
				$l_outputbuffer[] = IcandConstantsCSS::$c_textColourClasses[1-$p_set];
				$l_outputbuffer[] = '">&nbsp;';
				$l_outputbuffer[] = 'bw';
				$l_outputbuffer[] = '&nbsp;</td>';			
			
				$l_outputbuffer[] = '<td class="' ;
				$l_outputbuffer[] = IcandConstantsCSS::makeCSSColourOffsetClass( 'mono' );
				$l_outputbuffer[] = ' ';
				$l_outputbuffer[] = IcandConstantsCSS::makeCSSDarkLightClass(IcandConstantsCSS::$cs_BGColourDarkLightSuffix[$p_set ]);
				$l_outputbuffer[] = ' ';				
				$l_outputbuffer[] = IcandConstantsCSS::$c_textColourClasses[1-$p_set];
				$l_outputbuffer[] = '">&nbsp;';
				$l_outputbuffer[] = 'g';
				$l_outputbuffer[] = '&nbsp;</td>';
			
			for ($l_pal=0;$l_pal<$GLOBAL_NUM_COLOURS;$l_pal++) {
				$l_outputbuffer[] = '<td class="' ;
				$l_outputbuffer[] = IcandConstantsCSS::makeCSSColourOffsetClass( $l_pal );	// colour 0..11
				$l_outputbuffer[] = ' ';
				$l_outputbuffer[] = IcandConstantsCSS::makeCSSDarkLightClass(IcandConstantsCSS::$cs_BGColourDarkLightSuffix[$p_set ]);
				$l_outputbuffer[] = ' ';
				$l_outputbuffer[] = IcandConstantsCSS::$c_textColourClasses[1-$p_set];
				$l_outputbuffer[] = '">&nbsp;';
				$l_outputbuffer[] = $l_pal; // display 0..11
				$l_outputbuffer[] = '&nbsp;</td>';
			}//for
			$l_outputbuffer[] = '</tr>';
		}//for
		$l_outputbuffer[] = '</table>';
		$l_outputbuffer[] = '</div></div>';		
		$l_outputbuffer[] =  '</div>';
		return (implode (null, $l_outputbuffer ));
	}		
//---------------------------------------------------------------------
	public function makeDemoColours() {
		$l_outputbuffer[] = '<div class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MENUCELL;
		$l_outputbuffer[] = '">';
		$l_outputbuffer[] = '<div class="' ;
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_FORMITEM;
		$l_outputbuffer[] = '"><div class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_FORMSETTING;
		$l_outputbuffer[] = '">';		
	//	$l_widthCount = 0;
		foreach (IcandConfigOptions::getOneAxisOptionValues('colour_paletteset') as $l_key ) {  // => $l_value ) {
			$l_optionValueArray = $this->c_colour_palettesetRGB;
			$l_demos = ( $l_optionValueArray[$l_key] );
			if ($l_demos ) {
				$l_outputbuffer[] = get_string( IcandConstantsBackend::DISPLAY_PREFIX_FOR_MONO_OPTIONS . $l_key, IcandConstantsBackend::$g_wholeThemeName ) ;   // $l_value;
				$l_outputbuffer[] = $this->makeDemoColourRow( $l_demos );
				$l_outputbuffer[] = '&nbsp;';
			}//if
		}//foreach
		$l_outputbuffer[] = '</div></div>';		
		$l_outputbuffer[] =  '</div>';
		return (implode (null, $l_outputbuffer ));
	}
	
	protected function makeDemoColourRow( $p_arrayOfColours ) {
	// returns string of html
		$l_result = '';
		foreach ($p_arrayOfColours as $l_colour ) {
			$l_result .= '<span style="background-color:' . $l_colour . '">Az</span>';
		}
		return $l_result;
	}
	
//------------------------------------------------------	
	
	public function makeDemoVariety($p_baseColour=1) {
//		global IcandConstantsBackend::$cs_BGColourDarkLightSuffix;
		$l_outputbuffer = array();
		$l_outputbuffer[] = '<div class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MENUCELL;
		$l_outputbuffer[] = '">';
		foreach ( IcandConstantsBackend::$colourvarietychoices as $l_key => $l_varietyArray ) {
			$l_lastValue = -1;
			$l_outputbuffer[] =  $l_key;	// this is a char
			for ($l_partIndex = 0; $l_partIndex < 3; $l_partIndex++ ) {	
				$l_currentOffset = ($l_varietyArray[$l_partIndex] );
				if ($l_lastValue != $l_currentOffset) {	
					$l_outputbuffer[] =  '<span class="' ;
					$l_outputbuffer[] =  IcandConstantsCSS::$cs_BGColourDarkLightSuffix[IcandConstantsBackend::DARK_SET ];
					$l_calculatedColour =  ($l_currentOffset === 'm')
										? 'mono'
										: (($p_baseColour + intval($l_currentOffset) - 1) %12) + 1;
					$l_outputbuffer[] =  $l_calculatedColour; // append to make css class name
					$l_outputbuffer[] =  '"> ';
					$l_outputbuffer[] = $l_partIndex;
					$l_outputbuffer[] =  ' </span>'; //'AZ</span>';
					if ($l_lastValue == -1) {
						$l_lastValue = $l_currentOffset;
					}
				}
			}//for
			$l_outputbuffer[] =  " \n";
		}//foreach
		$l_outputbuffer[] =  '</div>';
		return (implode (null, $l_outputbuffer ));
	}			
	
	public function makeSettingName( $p_name ) {
		return $this->c_systemThemeNamePrefix . $p_name;
	}
	
//	public function getTitle( $p_shortcut ) { return IcandConfigOptions::$sc_headingsArray[$p_shortcut]; }
	
	// ========================================
	// protected functions
	// ----------------------------------------

}//IcandSettingsWebInterface
}//class exists