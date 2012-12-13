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
 * @version		02Oct2012
 */
if(class_exists('IcandWholePageOptions') != true) {


class IcandWholePageOptions {

const CSS_WIDTH_FULL = 'icand_full_width';
const CSS_WIDTH_PART = 'icand_strct_centredhoriz icand_constrained_width';
const CSS_GUTTERS_SHADED = 'icand_dec_trans25 icand_dec_shading1';
const CSS_GUTTERS_BORDERTB = 'icand_border_topbottom';

	// results from getFullPageWidthType
	const WIDTH_FULL 	= 1;
	const WIDTH_NARROW 	= 2;
	const WIDTH_INHERIT	= 0;
	// inputs to setPageLayout, value of $pageLayout
	const LAYOUT_ALL_WIDE 	 		= 0;
	const LAYOUT_HEADER_WIDE 		= 1;
	const LAYOUT_BACKGROUNDS_WIDE	= 2;
	const LAYOUT_ALL_NARROW			= 3;
//	const LAYOUT_ALL_NARROW_PLUS_BORDER = 4;
//	const LAYOUT_ALL_NARROW_PLUS_SHADOW = 5;

	// getDockingBodyClass(..)
	const DOCK_ALWAYS_SQUEEZE		 = 0;
	const DOCK_ALWAYS_OVERLAP		 = 1;
	const DOCK_NO_OVERLAP_ALL_WIDE	 = 2;
	const DOCK_NO_OVERLAP_HEADER_WIDE = 3;
	
	var $c_gutterExtensions = array ();

	static $c_gutterExtensionsCSSclass = array (
		'shade'  	=> self::CSS_GUTTERS_SHADED
	,	'bordertb'	=> self::CSS_GUTTERS_BORDERTB
	);
	
//	var $headerFooterExtensions = self::HEADER_EXTENSION_NONE;
	var $pageLayout 		 	= self::LAYOUT_ALL_NARROW;
	//self::HEADER_EXTENSION_NONE
	public function setPageLayout( $p_pageLayout, $p_shadowOptionArray = null) {
		$this->pageLayout = $p_pageLayout;
		if ( $p_shadowOptionArray != null ) {
			foreach( $p_shadowOptionArray as $l_placeCode => $l_styleCode ) {
				$this->c_gutterExtensions[IcandConstantsBackend::$PLACE_DECODE[$l_placeCode]] = $l_styleCode;
			}//foreach
		}//if
	}//setPageLayout(..)
	
	public function isFullWidth($p_pageElement) {
		if ($this->pageLayout == self::LAYOUT_ALL_WIDE ) {
			return true;
		}elseif( strpos($p_pageElement,'head') !== false) {
			return ($this->pageLayout == self::LAYOUT_HEADER_WIDE);
		} else {
			return false;
		}
	}//isFullWidth(..)
	
	public function hasGutterExtension($p_pageElement) { 
		return ($this->isFullWidth( $p_pageElement )) 
			? false
			: array_key_exists( $p_pageElement,  $this->c_gutterExtensions );
	}//hasGutterExtension(..)
	
	public function getGutterExtensionClass( $p_pageElement ) {
	/** @requires hasGutterExtension($p_pageElement) */
		$l_treatment = $this->c_gutterExtensions[ $p_pageElement ];
		return (self::$c_gutterExtensionsCSSclass[$l_treatment]);
	}
	
public function getPageWidthClass( $p_elementId ) {
// provides a width: definition to 2 elements: id=icand_strct_page(metapage) and id=
	switch ($this->getFullPageWidthType( $p_elementId ) ) {
		case self::WIDTH_FULL   : return self::CSS_WIDTH_FULL;
		case self::WIDTH_NARROW : return self::CSS_WIDTH_PART;
		default: return '';
	}//switch
}//getPageWidthClass(..)

public function getDockingBodyClass( $p_stylingCode ) {
	$l_result = IcandConstantsCSS::CSS_DOCK_SQUEEZE_BODY;//default
	switch ($p_stylingCode) {
		case self::DOCK_ALWAYS_SQUEEZE : break; //never overlap
		case self::DOCK_ALWAYS_OVERLAP : $l_result = IcandConstantsCSS::CSS_DOCK_OVERLAP_BODY;
				break; //always overlap
		case self::DOCK_NO_OVERLAP_ALL_WIDE : { //overlap when all wide
				if ($this->pageLayout !== self::LAYOUT_ALL_WIDE) {
					$l_result = IcandConstantsCSS::CSS_DOCK_OVERLAP_BODY;
				}
				break;
			}
		case self::DOCK_NO_OVERLAP_HEADER_WIDE : { //overlap if header or all wide
				if ($this->pageLayout > self::LAYOUT_HEADER_WIDE) {
					$l_result = IcandConstantsCSS::CSS_DOCK_OVERLAP_BODY;
				}
				break;
			}
		}//switch
	return $l_result;
}//getDockingBodyClass(..)

	
//	public function getHeaderFooterExtensions() { return $this->headerFooterExtensions;}
//	public function isShadowUnderHead() { return ($this->headerFooterExtensions == self::HEADER_EXTENSION_SHADOW);}
//	public function isHeaderFooterExtensions() { return ($this->headerFooterExtensions != self::HEADER_EXTENSION_NONE);}
	public function isDesktopOKforImage() {
		return ($this->pageLayout !== self::LAYOUT_HEADER_WIDE);
	}//isDesktopOKforImage()
	
	public function isHeaderDesktopVisible() { 
			return ($this->pageLayout == self::LAYOUT_HEADER_WIDE
				|| $this->pageLayout == self::LAYOUT_ALL_NARROW);
	}//isHeaderDesktopVisible()
	
	public function isFullWidthHeader() { 
		return ($this->pageLayout == self::LAYOUT_HEADER_WIDE); 
	}//isFullWidthHeader()
	
	public function getFullPageWidthType ($p_elementId ) {
		if ($p_elementId == 'metapage') {
			if ($this->pageLayout == self::LAYOUT_ALL_WIDE || $this->pageLayout == self::LAYOUT_BACKGROUNDS_WIDE)
				return self::WIDTH_FULL;	// FULL WIDTH
			else // LAYOUT_HEADER_WIDE or LAYOUT_ALL_NARROW
				return self::WIDTH_NARROW;
		} else {
			if ($this->pageLayout == self::LAYOUT_BACKGROUNDS_WIDE)
				return self::WIDTH_NARROW;
			else 
				return self::WIDTH_INHERIT;
		}//else
	}//getFullPageWidthType(..)
}//class
}//if