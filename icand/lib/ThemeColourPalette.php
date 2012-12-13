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
 * @version		11Oct2012
 */
if(class_exists('IcandColourPalette') != true)
{

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	// 
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsCSS_inc.php');	//names of CSS classes/ids 

class IcandColourPalette {

/**
* @todo getElementTextColourCSSClass has hack for "meta" not used in Buildpage
* @todo implement $c_opacityElements in settings
*/
/*
	-opacity
'metadesktop'

	+opacity -borders
'metaheader'
'metaheader1'
'metaheader2'
'metaheader3'
'metatoolbar'
'metatoolbar1'
'metatoolbar2'
'metamain'
'metafooter' 

	+opacity +borders
'courseMain'
'coursePre'
'coursePost'
*/

//var $c_opacityCSSclass = 'icand_dec_trans75';

//var $paletteOffset = 0; // updated from theme settings
var $c_baseOffsets = array ( 0, 0 );
/**
* by default colours are relative to base offset 1. This array holds the exceptions
*/
var $c_baseOffset2 		= array ();
//-----------------------------------------------
var $defaultcolourvarietychoices = IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO;

/** @var mixed index of colour variety super-palettes */
var $ver1_colourVariety;	// one of $colourvarietychoices

var $c_lightDarkArray = array();

var $c_opacityElements = array();	// array ('courseMain');

var $ver2_colourSelectorArray = array();// ('metaheader1'=>0,'metaheader2'=>0);

/*
// ''=no style OBSOLETE, 'm'=mono 'n' is transparent, 0..11 = colour palette
var $ver1_colourSelectorArray=	array ( 'metafooter' 	=>0		// NCI
								,	'metaheader'	=>IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO
//								,	'metaheader1' 	=>IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO //NCI--not being used						
								,	'metadesktop' 	=>1
//								,	'metatoolbar1'	=>''
//								,	'metamain' 		=>''	// NCI	
//								,	'courseMain'	=>IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BORDER_ONLY //IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO // NCI
								,	'coursePre'		=>1 // NCI
								,	'coursePost'	=>2	// NCI							
						);


var $headerHasBackground 	= false;
var $toolbarHasBackground	= false;
*/
//-------------------------------------------
/* VERSION 1 OF PALETTE CONTROL */


var $c_lightDarkSetChoices = array ( array(IcandConstantsBackend::LIGHT_SET,IcandConstantsBackend::DARK_SET)
							,	array(IcandConstantsBackend::DARK_SET,IcandConstantsBackend::LIGHT_SET)
							,	array(IcandConstantsBackend::LIGHT_SET,IcandConstantsBackend::LIGHT_SET)
							,	array(IcandConstantsBackend::DARK_SET,IcandConstantsBackend::DARK_SET)
							);
//one of the above	
var $c_lightDarkSetChoiceInUse = null;
//==========================================================================
public function initialise ($p_themeconfig) {
	$l_temp = substr($p_themeconfig['colour_base'], strlen('colour_base') );
	$this->c_baseOffsets[0] = intval($l_temp);

	$l_temp = substr($p_themeconfig['colour_base_supp'], strlen('colour_base_supp') );	
	$this->c_baseOffsets[1] = intval($l_temp);

	$l_temp = substr($p_themeconfig['colour_intensity'], strlen('colour_intensity'));
	$this->c_lightDarkSetChoiceInUse = $this->c_lightDarkSetChoices[intval($l_temp) ];	
	
//	$this->initialise_ver1($p_themeconfig);
	$this->initialise_ver2($p_themeconfig);
}

public function initialise_ver2($p_themeconfig) {
	foreach (IcandConfigOptions::getSuffixesOfSectionsWithColour() as $l_suffix ) {
		$l_settingsKey = 'clr_pal_' . $l_suffix;		// 'colour_palettes'
		$l_baseSettings = IcandConstantsBackend::explodeMultiselect($p_themeconfig[$l_settingsKey]);
		foreach ( $l_baseSettings as $l_placeCode => $l_baseAndShade ) {
			$l_placeId = IcandConstantsBackend::$PLACE_DECODE[$l_placeCode];
			list($l_base, $l_shade ) = explode (IcandConstantsBackend::PART_PLACE_JOINER,$l_baseAndShade );
			if ( $l_base == 'base_supp' ) { 
				$this->c_baseOffset2[] = $l_placeId;
			}//if
			$this->c_lightDarkArray[$l_placeId ] = 
					( $l_shade == 'dark' )
						? IcandConstantsBackend::DARK_SET
						: IcandConstantsBackend::LIGHT_SET ;
		}
		$l_settingsKey = 'clr_off_' . $l_suffix;
		$l_offsetSettings = IcandConstantsBackend::explodeMultiselect($p_themeconfig[$l_settingsKey]);	
		foreach ( $l_offsetSettings as $l_placeCode => $l_offset ) {
			if ((strlen($l_offset) > 0 ) and ($l_offset!=IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_NOTSET)) {
				$this->ver2_colourSelectorArray[IcandConstantsBackend::$PLACE_DECODE[$l_placeCode]] = $l_offset;
			}
		}
	}//foreach
}//initialise_ver
/*
public function initialise_ver1 ($p_themeconfig) {

	$l_temp = substr($p_themeconfig['colour_variety'], strlen('colour_variety'));
	if (!isset( IcandConstantsBackend::$colourvarietychoices[$l_temp] )) {
		$l_temp = $this->defaultcolourvarietychoices;
	}//if
	$this->ver1_colourVariety =  IcandConstantsBackend::$colourvarietychoices[$l_temp];
}//initialise_ver1
*/
// ============
// initialisers
// ============
public function addTransparency ( $p_elementId ) {
	$this->c_opacityElements[] = $p_elementId;
}

// ============
//   getters
// ============

/**
* //@return string one or two css classes (background colour + optional opacity)
* @return array of string (CSS classes)
* order of classes: 1st opacity (if set), 2nd last colour offset, last light/dark
* if no setting, returns empty array
*/
public function getElementBackgroundColourCSSClasses( $p_elementId ) {
//global $cs_BGColourDarkLightSuffix; //ThemeConstantsBackend_inc.php
	//$p_result = '';
	$p_result = array();
	if (!($this->hasBackgroundColourSetting( $p_elementId  ))) {
	//	$p_result = ''; 
	} else {
		if ( $this->hasOpacity( $p_elementId  )
			and (!($this->hasTransparentBackground( $p_elementId ))) ) {
			$p_result[] = $this->getOpacityClass( $p_elementId );//NB: not .=
		}	
		$p_result[] = $this->getCSSColourOffsetClass( $p_elementId );
		$l_set = $this->getExplicitColourSetNumber( $p_elementId );		
		$p_result[] = $this->getCSSDarkLightClass( $l_set ); // must go last. Can have suffixes added later.
	}//else
	return $p_result;
}//getElementBackgroundColourCSSClasses()


//==========================================================================
public function getExplicitColourSetNumber( $p_elementId ) {
// result = "dark" or "light"
	if (array_key_exists($p_elementId, $this->c_lightDarkArray)) {
		$l_result = $this->c_lightDarkArray[$p_elementId];
	} else { // legacy defaults
		$l_result = IcandConstantsBackend::DARK_SET;
		if (($p_elementId=='metapage') or (strpos($p_elementId,'course')!== false )) {
			$l_result = IcandConstantsBackend::LIGHT_SET; 
		}
	}
	return $l_result;
}

public function hasBackgroundColourSetting( $p_elementId ) {
	return (array_key_exists( $p_elementId, $this->ver2_colourSelectorArray ));
}

/**
* @requires: hasBackgroundColourSetting($p_elementId)
*/
public function hasTransparentBackground( $p_elementId ) {
	return (IcandConstantsBackend::isTransparentColour($this->ver2_colourSelectorArray[$p_elementId]));
} 

public function getBackgroundColourSetting( $p_elementId ) {
	if ($this->hasBackgroundColourSetting( $p_elementId )){
		return $this->ver2_colourSelectorArray[$p_elementId];
	} else {
		return 0;
	}
}


//==============================================================
protected function computeNumericIndex($p_elementCode, $p_elementID ) {
// adds base value for $p_elementID to offset($p_elementCode)
	return strval(
//			(( 12 - 1 + intval($p_elementCode) 
//			+ ($this->getBaseOffset($p_elementID)) ) % 12 ) + 1
			(( intval($p_elementCode) //+ 12				// force >= 0
			+ ($this->getBaseOffset($p_elementID)) ) % 12 )
	); // result 0..11
}

protected function getCSSColourOffsetClass( $p_elementId ) {
	return 	IcandConstantsCSS::makeCSSColourOffsetClass( 
				$this->getMainElementColourClassSuffix($p_elementId)
			);
}

/*
 * @param $p_elementID string
 * @return 0..1 or 'mono' or 'bw'
 */
protected function getMainElementColourClassSuffix ( $p_elementID ) {
	if ($this->hasBackgroundColourSetting($p_elementID)) {
//		$l_elementCode = $this->ver1_getIndexForPalette($p_elementID);
		$l_elementCode = $this->ver2_getIndexForPalette($p_elementID);		
		if ($l_elementCode === IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BORDER_ONLY) {
			$result = IcandConstantsCSS::CSS_SUFFIX_FOR_ONLY_BORDER; 	// see through
		} elseif (is_numeric($l_elementCode)) { // != '') { // if has a colour
			$result = $this->computeNumericIndex($l_elementCode, $p_elementID); // result 1..12
		} elseif ($l_elementCode == IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_MONO) {
			$result = IcandConstantsCSS::CSS_SUFFIX_FOR_GREYSCALE;
		} elseif ($l_elementCode == IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BW) {
			$result = IcandConstantsCSS::CSS_SUFFIX_FOR_BW;			
		} else {
			$result = '';
		}
		return $result; // returns '' or 0..11
	} else {
		return '';	//NOT_IN_PALETTE. should not happen!!
	}
}

//==============================================================
protected function hasOpacity( $p_elementId ) {
	return (in_array($p_elementId, $this->c_opacityElements));
}

protected function getOpacityClass( $p_elementId ) {
	return ' ' . IcandConstantsCSS::TRANSLUCENT . ' ';
}
//==============================================================
protected function getBaseOffsetGroup($p_elementID ) {
	return (in_array( $p_elementID, $this->c_baseOffset2 ))
			? 1 : 0;
}//getBaseOffsetGroup(..)

protected function getBaseOffset($p_elementID ) {
	return $this->c_baseOffsets[$this->getBaseOffsetGroup($p_elementID )];
}
//--------------------------------------------------------------
protected function ver1_getIndexForPalette($p_elementID) {
// REQUIRES $l_elementCode to be in range for ver1_colourVariety
	$l_elementCode = $this->getBackgroundColourSetting($p_elementID);
	return (is_numeric( $l_elementCode )) 
			? ($this->ver1_colourVariety[$l_elementCode])	// numbers are offsets to ver1_colourVariety
			: $l_elementCode;
}
/*
 * @REQUIRES $p_elementID to be in range for colourVariety
 *
 */
protected function ver2_getIndexForPalette($p_elementID) {
	return $this->getBackgroundColourSetting($p_elementID);
}

//-----------------------------------------------
public function getCSSDarkLightClass( $p_sequence_num ) {
	return 	IcandConstantsCSS::makeCSSDarkLightClass(IcandConstantsCSS::$cs_BGColourDarkLightSuffix[ $this->c_lightDarkSetChoiceInUse[ $p_sequence_num] ]);
}

/*
protected function ver2_getColourSetSuffix( $p_sequence_num ) {
//global $cs_BGColourDarkLightSuffix; //ThemeConstantsBackend_inc.php
	return IcandConstantsCSS::$cs_BGColourDarkLightSuffix[ $this->c_lightDarkSetChoiceInUse[ $p_sequence_num] ];
}
*/

protected function ver1_getColourSetPrefix( $p_sequence_num ) {
//global $cs_BGColourDarkLightSuffix; //ThemeConstantsBackend_inc.php
	return IcandConstantsCSS::$cs_BGColourDarkLightSuffix[ $this->c_lightDarkSetChoiceInUse[ $p_sequence_num] ];
}


}//class
}//if class exists