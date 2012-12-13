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
 * @version		26June2012
 */
if(class_exists('IcandBackgroundIntegrator') != true)
{

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	// 
require_once(dirname(__FILE__) . '/../lib/ThemeBackgroundImages.php');
require_once(dirname(__FILE__) . '/../lib/ThemeColourPalette.php');

class IcandBackgroundIntegrator {
var $c_BackgroundImages = null;
var $c_ColourPalette = null;

public function __construct(&$p_BackgroundImages , &$p_ColourPalette ) {
	$this->c_BackgroundImages 	= $p_BackgroundImages;
	$this->c_ColourPalette		= $p_ColourPalette;
}

public function getElementTextColourCSSClass( $p_elementId ) {
/**
* @return string css class (colour)
*/
//$p_elementId must be course__ or meta__
	if (strpos($p_elementId,'course')=== false ) {
		if (strpos($p_elementId,'meta') === false ) {
			$p_elementId = 'meta' . $p_elementId;		//TODO make METAS & non-metas consistent
		}//if
	}//if

	$l_invertedColourSet = (1 - $this->getEffectiveBackgroundColourSetNumber( $p_elementId, true )); // invert background
	return IcandConstantsCSS::$c_textColourClasses[ $l_invertedColourSet ];
}

public function hasEffectiveBackgroundColour( $p_elementId, $p_includeImages=false ) {
// could be protected ?
/**
* @returns boolean
* @description ignores transparent blocks
* integrates background images and background colours
*/
	if ( $p_includeImages and $this->c_BackgroundImages->hasImage( $p_elementId ) ) {
		return true;
	} elseif ($this->c_ColourPalette->hasBackgroundColourSetting( $p_elementId )) {
		return (($this->c_ColourPalette->getBackgroundColourSetting($p_elementId)) !== IcandConstantsBackend::DB_VALUE_COLOUR_OFFSET_BORDER_ONLY);
	} else {
		return false;
	}
}

/**
* trace through underlying divs until background is found
*/
protected function getEffectiveBackgroundElement( $p_elementId, $p_includeImages ) {
	while ($p_elementId !== null ) {
		if ($this->hasEffectiveBackgroundColour( $p_elementId, $p_includeImages  )) {
			return $p_elementId;
		} else {
			$p_elementId = IcandConstantsBackend::getUnderlyingElement( $p_elementId  );//go deeper
		}//else
	}
	return $p_elementId; // null
}//getEffectiveBackgroundElement(..)

public function getEffectiveBackgroundColourSetNumber( $p_elementId, $p_includeImages=false ) {
	$p_elementId = $this->getEffectiveBackgroundElement( $p_elementId, $p_includeImages );
	if ($this->hasEffectiveBackgroundColour( $p_elementId, $p_includeImages  )) {
		return $this->c_ColourPalette->getExplicitColourSetNumber( $p_elementId );
	} else {
		return IcandConstantsBackend::LIGHT_SET;	// default = white
	}
}//getEffectiveBackgroundColourSetNumber

}//class

}//!class_exists