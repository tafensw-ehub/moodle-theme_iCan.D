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
 * @version		26July2012
 */
/* 
 *	changes:    25July2012 added background image option 'middle'
 *				26July2012 changed option 'middle' to top centred non-scroll
 *	TO DO: 	desktoptilegraphic will always scroll (layout type = 'tile'). Maybe give "fixed" option.
 */
if(class_exists('IcandBackgroundImages') != true)
{

class IcandBackgroundImages {

var $metaElementGraphicArray 	 = array (); // populated by setDependantOptions()
var $metaElementGraphicTreatment = array();

public function hasImage( $p_pageElement ) {
	$l_result = (array_key_exists( $p_pageElement, $this->metaElementGraphicArray ));
	if ($l_result) {
		$l_data = trim($this->metaElementGraphicArray[ $p_pageElement ]);
		$l_result = (strlen( $l_data ) > 0 );
	}
	return $l_result;
}//hasImage

protected function getLayoutType( $p_pageElement  ) {
	return (array_key_exists($p_pageElement, $this->metaElementGraphicTreatment))
		? $this->metaElementGraphicTreatment[$p_pageElement]
		: 'stretchall'
		;
}

public function setLayoutType( $p_elementId, $p_newValue ) {
	$this->metaElementGraphicTreatment[$p_elementId] = $p_newValue;
}

public function getLayoutHTML( $p_pageElement ) {
	$l_treatment = $this->getLayoutType($p_pageElement);
	return self::makeLayoutHTML(
			$this->getImageURL($p_pageElement)
		,	$l_treatment
		);
}//getLayoutHTML(..)

static public function makeLayoutHTML( $p_imageURL, $p_treatment ) {
// TODO: "stretch__" returns '>' at the start
	$l_resultArray = array();
	
	if ( IcandConfigOptions::isBackgroundTreatmentTile($p_treatment) ) {  // for TILED backgrounds
		$l_resultArray[] = ' style="background:url(\'';
		$l_resultArray[] = $p_imageURL ;
		$l_resultArray[] = '\') ';
		$l_resultArray[] = IcandConfigOptions::getBackgroundTileRepeatHTML( $p_treatment ); // repeat* + {left/center}
		$l_resultArray[] = ' top'	; // vertical has to be grouped with horizontal, else won't display in FF15
		$l_resultArray[] = IcandConfigOptions::isBackgroundTreatmentTileScrolling( $p_treatment )
							? ' scroll'
							: ' fixed'
							;
		$l_resultArray[] = ' transparent;"';		// added for all cases: no colours under image
	} else { 	// 'stretch' or 'stretchall' 							// for NON-TILED backgrounds
				// 'stretch' is default behaviour if nothing explicit
		$l_resultArray[] = '>';
		$l_resultArray[] = '<img src="' ;
		$l_resultArray[] = $p_imageURL ;
		$l_resultArray[] =  '" ' ;
		$l_resultArray[] =  'width="100%"' ; // 'height="100%"' ;//
		if ( $p_treatment == 'stretchall' ) {
			$l_resultArray[] = ' height="100%"'; //' width="100%"';//
		}//if
	}//else
	return implode (null, $l_resultArray );
}

public function setImage( $p_pageElement, $p_imgHandle ) {
	$this->metaElementGraphicArray[$p_pageElement ] = $p_imgHandle;
}

public function getImage( $p_pageElement, $p_imgHandle ) {
	return $this->metaElementGraphicArray[$p_pageElement];
}

public function getImageURL( $p_pageElement ) {
// $p_pageElement::'metadesktop','metaheader','metamain'
global $CFG;
	$l_imgHandle = $this->metaElementGraphicArray[$p_pageElement];
	return (strpos($l_imgHandle,'builtin')===false)		// is it "builtin*"
		? $l_imgHandle									// no: use full, literal string as URL
		: ($CFG->wwwroot								// yes, construct Moodle img getter URL
			. '/theme/image.php?theme=' 
			. current_theme() 
			. '&image='
//		. $this->metaElementPatternPNGfiles[ $this->metaElementGraphicArray[$p_pageElement] ]
//			. IcandConfigOptions::$l_optionsBuiltinTilesFiles[ $l_imgHandle ]
			. IcandConfigOptions::getBuiltinFile($l_imgHandle)
			. '&component=theme')
		;
}

}//class IcandBackgroundImages
}//class exists