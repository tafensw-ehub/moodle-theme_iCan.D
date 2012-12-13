<?php
//DOCUMENTER http://phpdocu.sourceforge.net/howto.php
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
 * class IcandBuildPage
 *
 * Produces HTML code, using data in an array (text=>mixed) of appearance parameters,
 * and values from globals $CFG, $OUTPUT, $PAGE ( $COURSE current for debug )
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		15Oct2012
 *
 * @todo in getHTMLcourseBoxStart:refine (!$l_boxHasPushdownStyles) test for padding
 * @todo have no margins for logo graphics (currently in makeOneCell, but needs work)
 * @todo better check for opacity in getMainElementAllCssClasses(..) 
 */ 

if(class_exists('IcandBuildPage') != true)
{

require_once(dirname(__FILE__) . '/../lib/ThemeBackgroundImages.php');
require_once(dirname(__FILE__) . '/../lib/ThemeColourPalette.php');
require_once(dirname(__FILE__) . '/../lib/ThemeBackgroundIntegrator.php');

class IcandBuildPage {

//---------------------------------------------------------
/** @var array(string=>string) */
var $themeConfigArray;
// pre-extracted values from $themeConfigArray
var $c_themeConfigPushToBlock = false;
var $c_themeConfigPushToSection = false;
var $c_themeConfigUseRadius	= false;
var $c_themeConfigForceBorderOnColour = false;
var $c_themeConfigDecorationTarget; //string: {box, cell}
var $c_flipRTL = false;

var $c_showVerticalLinesInBodyTable = array( false, false ); // outside, inside
var $c_main_strip_spacing_styles = array( null, null, null );

// helper classes
/** @var ThemeTextContent widgets & text for headers, footers, toolbars */
var $myThemeTextContent = null;
/** @var IcandBackgroundImages for elements that have special locations */
var $myThemeBackgroundImages = null;
// the generic cases are dealt with directly in getHtmlMetaElementDecorationDIVs
// [location]img
// [location]style

var $myThemeBackgroundIntegrator = null;
/** @var IcandWholePageOptions layout configurations */
var $myWholePage = null;

var $metaElementGradientArray = array (); 

/** @var boolean turns on yucky HTML tables on top of CSS table-layout */
var $isMSIE7compatible = true; // false; // 

//============================================================
public function setThemeConfig ( $p_themeconfig ) {
	$this->themeConfigArray = $p_themeconfig ;
}//setThemeConfig

public function setTextContent ( $p_new ) {
	$this->myThemeTextContent = $p_new;
}//setTextContent

public function setWholePageOptions( $p_new ) {
	$this->myWholePage = $p_new;
}//setWholePageOptions
//-------------------------------------------------------------

public function setOptions( &$p_themeconfig, &$p_themeTextContent, &$p_WholePageOptions ) {
	$this->myThemeBackgroundImages	= new IcandBackgroundImages();
//	$this->myThemeCoverImages		= new IcandBackgroundImages();
	$this->myThemeColourPalette		= new IcandColourPalette();
	$this->myThemeColourPalette->initialise( $p_themeconfig );
	
	$this->myThemeBackgroundIntegrator = new IcandBackgroundIntegrator($this->myThemeBackgroundImages, $this->myThemeColourPalette );
	
	$this->setThemeConfig ( $p_themeconfig );

	$this->c_themeConfigPushToBlock   = $this->themeConfigArray['push_to_block']; //false; true
	$this->c_themeConfigPushToSection = $this->themeConfigArray[IcandConstantsBackend::CONFIG_REWRITE_PUSHTOSECTION];
	$this->c_themeConfigUseRadius	  = $this->themeConfigArray['use_radius'];
	$this->c_themeConfigForceBorderOnColour = ($this->themeConfigArray['layout_make_borders'] == 'layout_borders_yes');
	$this->c_themeConfigDecorationTarget = $this->themeConfigArray[IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET];
	
	$this->setGradientCovers();
	$this->setTextContent ( $p_themeTextContent );
	$this->setWholePageOptions( $p_WholePageOptions );
	$this->setDependantOptions();

	$l_columnSpacing = $p_themeconfig['table_spacing'];	
	if (strpos( $l_columnSpacing, 'ts' ) !== false ) {
		$l_columnSpacing = substr( $l_columnSpacing, strlen( 'ts' ));//get suffix
		if (strlen($l_columnSpacing) == 0 )	{// legacy
			$this->c_main_strip_spacing_styles[1] = 2; // medium space
		} else {
			$this->c_main_strip_spacing_styles[0] = intval( substr($l_columnSpacing,0,1));
			$this->c_main_strip_spacing_styles[1] = intval( substr($l_columnSpacing,1,1));			
			$this->c_main_strip_spacing_styles[2] = intval( substr($l_columnSpacing,2,1));			
		}
		
		$l_gutterRulesSetting = $p_themeconfig['gutter_rules'];
		
		if (($l_gutterRulesSetting == 'gr_vertical' )
			or $l_gutterRulesSetting == 'gr_vertical_out' ) {
			$this->c_showVerticalLinesInBodyTable[0] = true;
		}
		if (($l_gutterRulesSetting == 'gr_vertical' )
			or $l_gutterRulesSetting == 'gr_vertical_in' ) {
			$this->c_showVerticalLinesInBodyTable[1] = true;
		}		
	}//if(strpos(...,'ts'
}//setOptions(..)

public function setDependantOptions() {
// call this AFTER the 3 object setters have been called.
	$l_imgHandle = $this->getImageHandleFromThemeConfig( IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC );
	$l_isBuiltInTile = false;
	if ( $l_imgHandle != null ) {
		$l_targetElementName = ($this->myWholePage->isDesktopOKforImage())?'metadesktop':'metamain';
		$this->myThemeBackgroundImages->setImage( $l_targetElementName, $l_imgHandle);
		if (!$l_isBuiltInTile) {
			$l_isBuiltInTile = IcandConfigOptions::isBuiltinTileName( $l_imgHandle );
		}//if
		if ($l_isBuiltInTile) {
			$l_layout = 'tile';
		} else {
			$l_layout = $this->themeConfigArray[IcandConstantsBackend::DB_FIELD_DESKTOP_TILE_GRAPHIC . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX];
		}//else
		$this->myThemeBackgroundImages->setLayoutType( $l_targetElementName,$l_layout);
	}//desktoptilegraphic
	
	$l_imgHandle = $this->getImageHandleFromThemeConfig( 'headerbackgroundimg' );
	if ($l_imgHandle != null) {
		$l_targetElementName = $this->themeConfigArray['headerbackgroundimg' . IcandConstantsBackend::SETTINGS_TARGET_SUFFIX];
		$this->myThemeBackgroundImages->setImage($l_targetElementName, $l_imgHandle);
		$this->myThemeBackgroundImages->setLayoutType($l_targetElementName,'stretchall');
	}//headerbackgroundimg	
}//setDependantOptions()


protected function getImageHandleFromThemeConfig( $p_configKey ) {
	$l_result = null;
	$l_trialURL = null;
	if (isset($this->themeConfigArray[$p_configKey . IcandConstantsBackend::SETTINGS_URL_SUFFIX ])) {	// added 26Sep2012
		$l_trialURL = $this->themeConfigArray[$p_configKey . IcandConstantsBackend::SETTINGS_URL_SUFFIX];
		if (strlen( $l_trialURL ) < 1 ) {
			$l_trialURL = null;
		}
	}
	if ($l_trialURL !== null) {
		$l_result = $l_trialURL;
	} elseif ( isset($this->themeConfigArray[$p_configKey])) {	
		$l_result = $this->themeConfigArray[$p_configKey];
	}//elseif	
	return $l_result;
}//getImageHandleFromThemeConfig(..)

//---------------------------------------------------------
// gradient overlays
//---------------------------------------------------------					
protected function setGradientCovers() {
	$l_gradientSettings = IcandConstantsBackend::explodeMultiselect($this->themeConfigArray['gradient_covers'] );					
	if ( count( $l_gradientSettings ) > 0 ) {
		foreach ( $l_gradientSettings as $l_placeCode => $l_styleCode ) {
			$this->setGradient($l_placeCode,$l_styleCode);
		}//foreach
	}//if
}//setGradientCovers()

protected function setGradient( $p_element, $p_gradientCode ) {
	if ((strlen( $p_gradientCode ) > 0 )
		and ($p_gradientCode!=IcandConstantsHTMLForms::FORM_VALUE_NONE)) {
		$this->metaElementGradientArray[(IcandConstantsBackend::$PLACE_DECODE[$p_element])] 
			= IcandConstantsBackend::$GRADIENT_DECODE[$p_gradientCode];
	}
}//setGradient(..)

protected function hasGradient ( $p_pageElement ) {
	return (array_key_exists( $p_pageElement, $this->metaElementGradientArray ));
}//hasGradient(..)

protected function getGradientStyle ( $p_pageElement ) {
	return $this->metaElementGradientArray[ $p_pageElement ];
}//getGradientStyle(..)

//---------------------------------------------------------
// tile overlays
//---------------------------------------------------------	

//var $metaElementGraphicTiles = array (); // populated by setDependantOptions()

var $metaElementGraphicTreatment = array ();

var $metaElementBorderArray = array ();
				
//---------------------------------------------------------
//	for all colour styles
//---------------------------------------------------------

public function getHtmlMetaElementDecorationDIVs( $p_pageElement ) {
// $p_pageElement will start with 'meta'
	$l_classForAll = '<div class="icand_decoration_layer icand_cover_all '; // a const
	$l_resultArray = array();
	// background colour
	if ($this->myThemeColourPalette->hasBackgroundColourSetting( $p_pageElement )) {
		$l_resultArray[] = $l_classForAll;
		$l_colourPaletteCSSClasses = $this->myThemeColourPalette->getElementBackgroundColourCSSClasses( $p_pageElement );
		$l_resultArray[] = implode( ' ', $l_colourPaletteCSSClasses );
		//$this->myThemeColourPalette->getElementBackgroundColourCSSClasses($p_pageElement);
		$l_resultArray[] = '"></div>';
	}	
	// grayscale gradient
	if (  $this->hasGradient( $p_pageElement ) ) {
		$l_resultArray[] = $l_classForAll;
		$l_resultArray[] = $this->getGradientStyle($p_pageElement); // 'icand_mono_gradient
		$l_resultArray[] = '"></div>';
	}
	// watermark or graphic
	if ( $this->myThemeBackgroundImages->hasImage( $p_pageElement ) ) {
		$l_resultArray[] = $l_classForAll;
		$l_resultArray[] = '"';	// close class list	
		$l_resultArray[] = $this->myThemeBackgroundImages->getLayoutHTML( $p_pageElement );
		$l_resultArray[] = '></div>';
	}
	// custom graphics
	$l_configOverrideElName = $p_pageElement . IcandConstantsBackend::SETTINGS_IMG_SUFFIX;
	if ( array_key_exists ($l_configOverrideElName, $this->themeConfigArray )) {
		$l_src = trim($this->themeConfigArray[$l_configOverrideElName]);
		if (strlen( $l_src ) > 0 ) {
			$l_treatment = $this->themeConfigArray[$l_configOverrideElName . IcandConstantsBackend::SETTINGS_STYLE_SUFFIX];
			$l_resultArray[] = $l_classForAll;
			$l_resultArray[] = '"';	// close class list	
			$l_resultArray[] = IcandBackgroundImages::makeLayoutHTML(
									$l_src
								,	$l_treatment
								);
			$l_resultArray[] = '></div>';
		}//if strlen
	}//if array_key_exists
	
	if ($this->myWholePage->hasGutterExtension( $p_pageElement)) {
		$l_gutterStyle = $this->myWholePage->getGutterExtensionClass($p_pageElement);
		$l_resultArray[] = '<div class="icand_guttershading_under_left icand_strct_contained ';
		$l_resultArray[] = $l_gutterStyle;
		$l_resultArray[] = '"></div>';
		$l_resultArray[] = '<div class="icand_guttershading_under_right icand_strct_contained ';
		$l_resultArray[] = $l_gutterStyle;
		$l_resultArray[] = '"></div>';				
	}
	return implode ('', $l_resultArray  );
}//getHtmlMetaElementDecorationDIVs()


public function getMetaElementBorderCSSClassNames( $p_elementId ) {
// returns a CSS class name
	return (in_array($p_elementId , $this->metaElementBorderArray))
		? $metaElementBorderArray
		: ''
	;
}//getMetaElementBorderCSSClassNames(..)

public function isFullWidthHeader() 	 {return $this->myWholePage->isFullWidthHeader();}
public function isHeaderDesktopVisible() {return $this->myWholePage->isHeaderDesktopVisible();}
public function isDesktopOKforImage() 	 {return $this->myWholePage->isDesktopOKforImage();}

public function getTextElementForPosition( $p_positionTag ) {
	return ($this->myThemeTextContent->isTextMapped( $p_positionTag ) )
			? $this->myThemeTextContent->get( $p_positionTag )
			: false;
}

/* used in 2places in buildpage_inc.php */
public function getPageWidthClass( $p_elementId ) {return $this->myWholePage->getPageWidthClass( $p_elementId ); }

//---------------------------------------------------------
// styling for "main" elements, i.e. blocks and course content
//---------------------------------------------------------

protected function isOuterContainerStylingClasses ( $p_elementId ) {		// pre post main
	$l_result 	= ($p_elementId == 'courseMain') 
					? $this->c_themeConfigPushToSection
					: $this->c_themeConfigPushToBlock ;
	return $l_result;
}//isOuterContainerStylingClasses(..)

/**
 * @return string : with several CSS class names, up to 5 CSS classes: bgcolourStyle border radius padding vertSpace
 * @todo: border should apply to CELL, not BOX.
 */
protected function getMainElementAllCssClasses (  
								$p_elementId 		// {pre post main}
							,	$p_elementType 		// {cell,box}
							,	$p_isMSIE = false ) {
	if ($p_elementType != $this->c_themeConfigDecorationTarget )
		return '';		// no decorations for non-target elements
	//...else...
	// set local variables ------------------
	$l_targetIsBlock = $l_targetIsSection =  false; // initialise to false
	if ($p_elementType == IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_BOX) { // caller checks this == $THEME_OPTION_targetType 
			if ($p_elementId != 'courseMain') {
				$l_targetIsBlock = $this->c_themeConfigPushToBlock;
			} else {
				$l_targetIsSection = $this->c_themeConfigPushToSection;
			}
	}//if
	$l_targetIsInner = ( $l_targetIsBlock or $l_targetIsSection );
	$l_makePreAndPostSame	= false;
	$l_onlyMonochromeBorders = false; // true forces _mono style, false leave colour zone							
	// interpret local variables---------------
	$l_addVerticalSpace = ( strpos( 'ts', $this->themeConfigArray['table_spacing'] )
							!== false );

	if ($l_makePreAndPostSame && ($p_elementId == 'coursePost' )) {
			$p_elementId = 'coursePre';
	}//if
	
	// styles with the suffix are defined to apply styling to inner elements only
	$l_suffix = ($l_targetIsBlock) 
				? IcandConstantsCSS::CSS_SUFFIX_PUSHDOWN_TO_BLOCK 
				: (($l_targetIsSection)
					? IcandConstantsCSS::CSS_SUFFIX_PUSHDOWN_TO_SECTION
					:'');  // will be appended to all styles.
	$l_suffix .= ' '; // add a space at the end for safe string concatenation
	
// use values to construct class string
	$l_resultArray = array();
	if ($this->myThemeColourPalette->hasBackgroundColourSetting($p_elementId)) {
			// "BackgroundColour" could be "transparent with optional border"		
		//colourStyle
		$l_colourPaletteCSSClasses = $this->myThemeColourPalette->getElementBackgroundColourCSSClasses( $p_elementId );
		if ($this->myThemeColourPalette->hasTransparentBackground($p_elementId )){
			//maybe light/dark is different?
			$l_indexOfLast = count($l_colourPaletteCSSClasses)-1;
			$l_colourPaletteCSSClasses[$l_indexOfLast] 
				= $this->myThemeColourPalette->getCSSDarkLightClass(
						$this->myThemeBackgroundIntegrator->getEffectiveBackgroundColourSetNumber($p_elementId)
					);
		}
		if ($l_targetIsInner and (count($l_colourPaletteCSSClasses) == 3)) {//TODO: better check for opacity
			$l_colourPaletteCSSClasses[0] = ''; // turn off opacity
		}
		$l_resultArray[] = implode( ' ', $l_colourPaletteCSSClasses );
		
		//borderStyle
		if ( $this->c_themeConfigForceBorderOnColour ) {
			$l_borderStyle = IcandConstantsCSS::CSS_TURN_ON_BORDER;
			if ($l_onlyMonochromeBorders) { 
				$l_borderStyle .= IcandConstantsCSS::CSS_SUFFIX_FOR_GREYSCALE; 
			}//if only monochrome
			$l_resultArray[] = $l_borderStyle;
		}//if force border
	}//if hasBackgroundColourSetting
		
	//radiusStyle	
	if ($this->c_themeConfigUseRadius) {
		$l_resultArray[] = IcandConstantsCSS::CSS_TURN_ON_RADIUS;
	}//if useRadius

	//padding style
	if ($l_targetIsInner and !$p_isMSIE) { // prevent double padding
		$l_resultArray[] = IcandConstantsCSS::CSS_ADD_PADDING;
		if ($l_addVerticalSpace) {
			$l_resultArray[] = IcandConstantsCSS::CSS_ADD_VERTICAL_SPACE; // . $l_suffix; //implode() would leave off last entry
		}//if
	}//if
	
	return implode( $l_suffix, $l_resultArray ) . $l_suffix ;	// join all styles into 1 string, adding suffix
}//getMainElementAllCssClasses()


//-----------------------------------------------------------------------------
// Write HTML of page
//-----------------------------------------------------------------------------

//=====================================================================================
// META area (not "main content")
//-------------------------------------------------------------------------------------
public function echoHtmlHeaderFooter($p_block) {
// $p_block < {IcandConstantsBackend::PLACE_HEADER,IcandConstantsBackend::PLACE_FOOTER}
$l_metaName 	= 'meta' . $p_block;
$l_css_id = ( $p_block == IcandConstantsBackend::PLACE_HEADER )
		? IcandConstantsCSS::CSS_MOODLE_ID_HEADER
		: IcandConstantsCSS::CSS_MOODLE_ID_FOOTER ;
$l_outputbuffer = array();
	$l_outputbuffer[] = "\n<!-- Start of "; 
	$l_outputbuffer[] = $l_css_id;
	$l_outputbuffer[] = " -->\n";
	$l_outputbuffer[] = '<div id="';
	$l_outputbuffer[] = $l_css_id;
	$l_outputbuffer[] = '" class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_CONTAINER;
	$l_outputbuffer[] = "\">\n";

	$l_outputbuffer[] = $this->makeHtmlDivs($p_block);
	
	$l_outputbuffer[] = "\n</div>\n<!-- end of ";
	$l_outputbuffer[] = $l_css_id;
	$l_outputbuffer[] = " -->\n";		
	
	echo implode( null, $l_outputbuffer );
}//echoHtmlHeaderFooter(..)

protected function makeHtmlDivs($p_blockId) {
	$l_outputbuffer = array();
	$l_outputbuffer[] = $this->getHtmlMetaElementDecorationDIVs( 'meta' . $p_blockId );
/**/
	$l_outputbuffer[] = '<div class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_FRONTMOST_CONTENTS;	// icand_over
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = $this->myThemeBackgroundIntegrator->getElementTextColourCSSClass($p_blockId);
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = $this->getPageWidthClass( $p_blockId );
//	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = '">';//"over" class
/**/	
	//-----------write layers-------------------------------
	$l_outputbuffer[] = $this->makeHorizontalContentDIVs($p_blockId, IcandConstantsCSS::CSS_VERTICAL_JUSTIFY_MIDDLE );	// TODO vert align set
	//-----------------------end of variable content---------------------
/**/	$l_outputbuffer[] =  	'  </div><!--';
		$l_outputbuffer[] =  	IcandConstantsCSS::CSS_FRONTMOST_CONTENTS;
		$l_outputbuffer[] =  	" -->\n";
	return implode( null, $l_outputbuffer );
}//makeHtmlDivs()
//==================================================================
protected function makeHorizontalContentDIVs ($p_blockId, $p_verticalAligment) {
	$l_outputbuffer = array();
	$l_metaName = 'meta' . $p_blockId;
	for ($l_contentRowIndex = 1; $l_contentRowIndex<4; $l_contentRowIndex++ ) {
		$l_outputbuffer[] = $this->makeOneHorizontalContentDIV(
								$p_blockId . $l_contentRowIndex		// suffixes 
							,	$l_metaName . $l_contentRowIndex
							,	$p_verticalAligment
							);
	}//for
	return implode( null, $l_outputbuffer );
}//makeHorizontalContentDIVs
//--------------------------------------------------------------
// make a single row of header, toolbar, etc.
protected function makeOneHorizontalContentDIV( $p_blockId, $p_metaname, $p_verticalAligment ) {
		$l_contentArrayKeys = $this->myThemeTextContent->getKeysWithPrefix( $p_blockId );
		if (count( $l_contentArrayKeys ) == 0 ) {
			return null;		// bail out early
		}
		// ...else...
		$l_outputbuffer = array();		
						
		$l_metaDecorations = $this->getHtmlMetaElementDecorationDIVs( $p_metaname );
		$l_hasMetaDecorations = ( $l_metaDecorations != '' );
		
		if ( $l_hasMetaDecorations) {
			$l_outputbuffer[] = '	<div';
			$l_outputbuffer[] = ' id="';		// to fix MSIE7 stacking context bug
			$l_outputbuffer[] = 'id_' . $p_blockId;
			$l_outputbuffer[] = '" class="';
			$l_outputbuffer[] = IcandConstantsCSS::CSS_STYLING_CONTAINER;
			$l_outputbuffer[] = '">';
			$l_outputbuffer[] = $l_metaDecorations;
		}
		
// START OF CONTENT CONTAINER OPENING
// removed class icand_textcontainer
		$l_containerClasses = 'icand_content icand_strct_nonbodytablespacing';
		
/* needed for MSIE7 to have	breadcrumbs not covered by background */
		if ( $l_hasMetaDecorations ) {
			$l_containerClasses .= (' ' . IcandConstantsCSS::CSS_FRONTMOST_CONTENTS );
		}//if
		$l_containerClasses .= ' ' . $this->myThemeBackgroundIntegrator->getElementTextColourCSSClass($p_blockId);
		$l_outputbuffer[] = $this->getHorizontalContainerOpeningHTML_tableStyle(
										'page-' . $p_blockId
									, 	$l_containerClasses
									);
		$l_blockSizeClass = '';
	$l_outputbuffer[] = $this->iterateAcrossRow($p_blockId, $l_contentArrayKeys, $l_blockSizeClass, $p_verticalAligment  );

// START OF CONTENT CONTAINER CLOSING
		$l_outputbuffer[] = $this->getHorizontalContainerClosingHTML_tableStyle( 'page-' . $p_blockId );
		$l_outputbuffer[] = "\n";
// END OF CONTENT CONTAINER CLOSING
	
		if ( $l_hasMetaDecorations ) {
			$l_outputbuffer[] =  	'</div>'; // IcandConstantsCSS::CSS_STYLING_CONTAINER
			$l_outputbuffer[] = "\n";		
		}			
//		}//if
	return implode( null, $l_outputbuffer );
}
//--------------------------------------------------------------
public function setFlipRTL( $p_newValue ) {
	$this->c_flipRTL = $p_newValue ;
}


protected function iterateAcrossRow($p_blockId, &$p_contentArrayKeys, $p_blockSizeClass, $p_verticalAligment  ) {
	$l_outputbuffer = array();
	$l_places = array ( 'pre' => IcandConstantsCSS::CSS_SIDED_PRE
					,	'mid' => IcandConstantsCSS::CSS_SIDED_MID
					,	'post' => IcandConstantsCSS::CSS_SIDED_POST);
	if ($this->c_flipRTL) {
		$l_places = array_reverse($l_places, true); //RTL
	}
	foreach ( $l_places as $l_side => $l_sideCSSclass ) { // do pre & post
		$l_blockIdSided = $p_blockId . '.' . $l_side;
		if (in_array ( $l_blockIdSided , $p_contentArrayKeys )){		
			$l_outputbuffer[] = $this->makeOneCell($l_blockIdSided, $l_sideCSSclass, $p_verticalAligment );
		}
	}//foreach	
	return implode( null, $l_outputbuffer );
}//iterateAcrossRow(..)
//--------------------------------------------------------------
protected function makeOneCell ($p_blockIdSided, $p_sideCSSclass, $p_verticalAligment, $p_isTableStyle = true ) {
	$l_outputbuffer = array( );
	
	$l_noMargin = false; //TODO depends on the content from myThemeTextContent->get
	//--outerdiv open-------------
	if ( $p_isTableStyle ) {
		$l_outputbuffer[] = $this->getHorizontalCellOpeningHTML_tableStyle(
							''						// no TD styles
						,	$p_verticalAligment		// 1 DIV style
						);
	}
	//--innerdiv open--------------
	$l_outputbuffer[] = '	  <div class="';
	$l_outputbuffer[] =	IcandConstantsCSS::CSS_SIDED_FLOAT;
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_SIDED_CONTENT ;
	$l_outputbuffer[] = ' ';	
	$l_outputbuffer[] = $p_sideCSSclass ;
	if ($l_noMargin) { // TODO!!!
		$l_outputbuffer[] = ' ';
		$l_outputbuffer[] =	IcandConstantsCSS::CSS_NO_MARGIN;	
	}
	
	$l_outputbuffer[] = '">';
	//--content--------------
	$l_outputbuffer[] = $this->myThemeTextContent->get( $p_blockIdSided );
	//--innerdiv close--------------
	$l_outputbuffer[] =  	'</div>'; 
	$l_outputbuffer[] = "\n";
	//--outerdiv close--------------
	if ( $p_isTableStyle ) {
		$l_outputbuffer[] = $this->getHorizontalCellClosingHTML_tableStyle();
	}
	return implode( null, $l_outputbuffer );
}//makeOneCell(..)
//--------------------------------------------------------------
//--------------------------------------------------------------
// make header rows, toolbar rows, etc.

public function echoHTMLTopOfBody($OUTPUT, $PAGE ) {
$l_param_block = 'toolbar'; // will have prefix 'meta' prefixed
	$l_outputbuffer = array();
	$l_outputbuffer[] = '<!--  START CUSTOMMENU AND NAVBAR  -->';
	$l_outputbuffer[] = '<div id="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_ID_INNERHEADER;
	$l_outputbuffer[] = '" class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_MOODLE_CLEARFIX ;
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_STYLING_CONTAINER ;
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_BLOCK_HORIZONTAL_JUSTIFY_CENTRED ;
	$l_outputbuffer[] = '">';

	$l_outputbuffer[] = $this->makeHtmlDivs($l_param_block);

	$l_outputbuffer[] = '	</div><!-- id:';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_ID_INNERHEADER;//closing comment
	$l_outputbuffer[] = ' -->';
	echo implode( null, $l_outputbuffer );
}//echoHTMLTopOfBody()
//-------------------------------------------------------------------------------------
// main strip - table
//-------------------------------------------------------------------------------------
/**
 * echos to standard output the entire HTML of the blocks, course and containing div.
 */
protected function writeHTMLMainBody_tableStyle ()/*&$p_spacingStylesArray=null*/ {
// echo, not l_outputbuffer, because of synchronisation
//$p_spacingStylesArray=($this->themeConfigArray['table_spacing']=='table_spacing_loose')
//		? array(1,0)
//		: null;	//added 18jun2012

//	$l_spaceOutside = (($p_spacingStylesArray!=null) and ($p_spacingStylesArray[0]));
//	$l_spaceInside 	= (($p_spacingStylesArray!=null) and ($p_spacingStylesArray[1]));

	echo $this->getHorizontalContainerOpeningHTML_tableStyle( IcandConstantsCSS::CSS_MOODLE_ID_MAIN_BOX ,
//									, $this->themeConfigArray['table_spacing'] . //REMOVED18jun2012
									' icand_strct_boxspacingcontainer'
//									. (($this->c_showVerticalLinesInBodyTable) ? ' icand_table_borders_on' : '')
									);
	$l_numOfColumns = count($this->themeConfigArray['columnorder']);
	$l_thisColumn   = 1;
	if ( $this->c_main_strip_spacing_styles[0] != null ) {
//	if ($l_spaceOutside ) {
		echo $this->getHorizontalCellOpeningHTML_tableStyle(
								''							
							,	  ' icand_outer_spacer'
								. ' icand_spacer_left'
								. ' icand_spacer_' . $this->c_main_strip_spacing_styles[0]
								. (($this->c_showVerticalLinesInBodyTable[0])? ' icand_table_borders_on' : '')
								. ' '
						);
		echo $this->getHorizontalCellClosingHTML_tableStyle ();
	}//if
	foreach (($this->themeConfigArray['columnorder']) as $l_whichcolumn) {
		switch ($l_whichcolumn) {
			case 'coursePre'  :$this->echoHtmlPreBox();  break;
			case 'coursePost' :$this->echoHtmlPostBox(); break;
			case 'courseMain' :$this->echoHtmlMainBox(); break;
		}//switch
		if ( 	($this->c_main_strip_spacing_styles[1] != null) 
			and ( $l_thisColumn < $l_numOfColumns )) {
//		if ( $l_spaceInside and ( $l_thisColumn < $l_numOfColumns )) {
			echo $this->getHorizontalCellOpeningHTML_tableStyle(
								''							
							,	'icand_inner_spacer '
								. 'icand_spacer_' . $this->c_main_strip_spacing_styles[1]
						);
					echo '<div class="icand_inner_spacer_border';
					if ($this->c_showVerticalLinesInBodyTable[1]) {
						echo ' icand_table_borders_on';
					}
					echo '"></div>';
			echo $this->getHorizontalCellClosingHTML_tableStyle ();
		}
		$l_thisColumn++; 
	}//foreach
	if ( $this->c_main_strip_spacing_styles[2] != null ) {	
//	if ($l_spaceOutside ) {
		echo $this->getHorizontalCellOpeningHTML_tableStyle(
								''							
							,	' icand_outer_spacer'
								. ' icand_spacer_right '
								. 'icand_spacer_' . $this->c_main_strip_spacing_styles[2]
								. (($this->c_showVerticalLinesInBodyTable[0])? ' icand_table_borders_on' : '')							
								. ' '							
						);	
		echo $this->getHorizontalCellClosingHTML_tableStyle ();
	}//if
	echo $this->getHorizontalContainerClosingHTML_tableStyle(IcandConstantsCSS::CSS_MOODLE_ID_MAIN_BOX);
}//writeHTMLMainBody_tableStyle()
//-------------------------------------------------------------------------------------
// container -- table
//-------------------------------------------------------------------------------------
/**
 * @param $p_id string ID of HTML block
 * @param  $p_tableSpacingClassString string optional : HTML class/es which define margins, padding, etc.
 * @global $this->isMSIE7compatible boolean : include MSIE7-compatible HTML
 * @return string HTML code
 */
protected function getHorizontalContainerOpeningHTML_tableStyle( $p_id, $p_tableSpacingClassString='') {
	$l_outputbuffer = array();
	
	$l_outputbuffer[] = '<div';
	$l_outputbuffer[] = ' id="';
	$l_outputbuffer[] = $p_id;
	$l_outputbuffer[] = '"';
	$l_outputbuffer[] = ' class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_PSEUDO_TABLE;
	$l_outputbuffer[] = ' ';
	$l_outputbuffer[] = $p_tableSpacingClassString; 
	$l_outputbuffer[] = '">';
	$l_outputbuffer[] = '<div class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_PSEUDO_TABLE_ROW;
	$l_outputbuffer[] = '">';
	
	if ($this->isMSIE7compatible) { //11Oct2012. This used to come before the standard HTML, now it comes after (so table is inner).
		$l_outputbuffer[] = '<!--[if lte IE 7]><table';
		$l_outputbuffer[] = ' id="';
		$l_outputbuffer[] = $p_id;		// Add a custom ID for MSIE table/s apply special style/s. Not currently used in CSS
 		$l_outputbuffer[] = IcandConstantsCSS::CSS_MSIE_TABLE_ID_SUFFIX;	// add suffix to avoid ID conflict with the div below
		$l_outputbuffer[] = '" ';		// end of id
		$l_outputbuffer[] = 'class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MSIE_TABLE;
		$l_outputbuffer[] = ' ';
		$l_outputbuffer[] = $p_tableSpacingClassString; 
		$l_outputbuffer[] = '">';			// end of class and table tag
		$l_outputbuffer[] = '<tr class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MSIE_TABLE_ROW;
		$l_outputbuffer[] = '"><![endif]-->';			
	}//if isMSIE7compatible	
	return implode( null, $l_outputbuffer );
}//getHorizontalContainerOpeningHTML_tableStyle


/**
 * @param $p_id string ID of HTML block
 * @global $this->isMSIE7compatible boolean : include MSIE7-compatible HTML
 * @return string HTML code
 */
protected function getHorizontalContainerClosingHTML_tableStyle($p_id) {
	$l_result  = 	'';

	
	if ($this->isMSIE7compatible) { // close TR and TABLE for MSIE7 mode		11Oct2012 this used to be AFTER the standard HTML
			$l_result .= '<!--[if lte IE 7]></tr></table><![endif]-->';
	}	
	
	// close 2 DIVS for standards-compliant browsers
	$l_result .= 	'</div><!-- ';
	$l_result .= 	IcandConstantsCSS::CSS_PSEUDO_TABLE_ROW;	// add an HTML comment to show what is being closed
	$l_result .= 	' -->';
	$l_result .= 	'</div><!-- id:' . $p_id . ' -->';	
	

	return $l_result;
}//getHorizontalCellClosingHTML_tableStyle
//-------------------------------------------------------------------------------------
// cells -- table
//-------------------------------------------------------------------------------------
protected function getHorizontalCellOpeningHTML_tableStyle($p_tableClasses, $p_divClasses ) {
/**
 * open 1 td, 1 div
 */
	$l_outputbuffer = array();
	if ($this->isMSIE7compatible) {
		$l_outputbuffer[] = '<!--[if lte IE 7]><td class="';
		$l_outputbuffer[] = IcandConstantsCSS::CSS_MSIE_TABLE_TD;
		$l_outputbuffer[] = ' ';
		$l_outputbuffer[] = $p_tableClasses;
		$l_outputbuffer[] = '"><![endif]-->';  // obsolete browsers use HTML table
	}
	$l_outputbuffer[] = '<div class="icand_strct_containercell ';
	if ($this->isMSIE7compatible) {
		$l_outputbuffer[] = 'icand_strct_inside_msie_td ';
	}
	$l_outputbuffer[] = $p_divClasses;
	$l_outputbuffer[] = '">';	
	
	return implode( null, $l_outputbuffer );	
}

protected function getHorizontalCellClosingHTML_tableStyle () {
/** 
*	close 1 div, 1 td 
*/
	return ($this->isMSIE7compatible) 
			? "</div>\n<!--[if lte IE 7]></td><![endif]-->\n"
			: "</div>\n";
}
//=====================================================================================
// MAIN content area (not "meta")
//-------------------------------------------------------------------------------------
public function writeHTMLMainBody ($p_isTableStyle=true) {
	if ($p_isTableStyle) {
		$this->writeHTMLMainBody_tableStyle();
	}
}//writeHTMLMainBody(..)

protected function writeHTMLMainBody_floatStyle () {
/* !!!!!!!!!! NOT FINISHED !!!!!!!!!!!!! */
	if (in_array('coursePre',  $this->themeConfigArray['columnorder'])) {
		$this->echoHtmlPreBox();
	}
	if (in_array('coursePost',  $this->themeConfigArray['columnorder'])) {
		$this->echoHtmlPostBox();
	}	
	$this->echoHtmlMainBox();
}

//-------------- constructing components --------------------------------------
protected function getBlockContent($p_PreOrPost) {
/** 
*	@param string $p_PreOrPost Case sensitive, starts with capital letter.
*	@returns array of string (html fragments)
*/
global $OUTPUT;
	$l_elementIdLower = strtolower( $p_PreOrPost );
	if (	($this->themeConfigArray[IcandConstantsBackend::CONFIG_CALC_SHOWSIDE_PREFIX . $l_elementIdLower ]) 
		|| ($this->themeConfigArray[IcandConstantsBackend::CONFIG_CALC_COMBINEBLOCKS]))
	{ 
	$l_elementId = 'course' . $p_PreOrPost;
	
	$l_outputbuffer = array();//contains HTML fragments 
	$l_outputbuffer[] = $this->getHTMLcourseBoxStart($l_elementId );	
	if ($this->themeConfigArray[IcandConstantsBackend::CONFIG_CALC_COMBINEBLOCKS]) { //general.php this has checked that both sides have content
//$PAGE->blocks->region_has_content('side-post', $OUTPUT);	
		$l_outputbuffer[] = $OUTPUT->blocks_for_region('side-pre'); 
		$l_outputbuffer[] = $OUTPUT->blocks_for_region('side-post'); 
	} else { // only 1 content
		$l_outputbuffer[] = $OUTPUT->blocks_for_region('side-' . $l_elementIdLower); 	
	}
	$l_outputbuffer[] = $this->getHTMLcourseBoxEnd($l_elementId );
	
//	$l_outputbuffer[] =  $this->getHorizontalCellClosingHTML_tableStyle();	
	return $l_outputbuffer;		
	} 
}//getBlockContent(..

protected function echoHtmlPreBox() {
	echo implode( null, $this->getBlockContent( 'Pre' ));
}//echoHtmlPreBox()

protected function echoHtmlPostBox () {
	echo implode( null, $this->getBlockContent( 'Post' ));
}//echoHtmlPostBox()

protected function getHTMLcourseBoxEnd ($p_boxId) {
	$l_outputbuffer = array();
	$l_outputbuffer[] = '</div>';
	$l_outputbuffer[] =  '</div><!-- region-';
	$l_outputbuffer[] =  $p_boxId;
	$l_outputbuffer[] =  " -->\n";
	$l_outputbuffer[] =  $this->getHorizontalCellClosingHTML_tableStyle();	//moved here 18June2012
	return implode( null, $l_outputbuffer );
}

/**
 * @param $p_boxId string = { 'courseMain', 'coursePre', 'coursePost' }
 * @return string of HTML
 */
protected function getHTMLcourseBoxStart ($p_boxId) {
		if ($p_boxId == 'courseMain' ) {
			$p_sizeClass = 'icand_strct_msie_resizingcell';
			$p_divId	 = 'id="' . IcandConstantsCSS::CSS_MOODLE_ID_REGION_MAIN . '" ';
			$p_boxClass  = 'icand_strct_msiedouble_mainbox' . ' ';
			$l_contentClass = 'course-content';
		} else { // coursePre, coursePost
			$p_sizeClass = 'icand_varstruct_widthset_sidecell';
			$p_divId	 = '';
			$p_boxClass  = 'icand_strct_msiedouble_box' . ' ';
			$l_contentClass = 'region-content';
		}
		
		$l_boxHasPushdownStyles = $this->isOuterContainerStylingClasses($p_boxId );
		
		$l_outputbuffer = array();													// accumulator for a the string output
		$l_outputbuffer[] =  $this->getHorizontalCellOpeningHTML_tableStyle(
									$p_sizeClass 
									. ' icand_text_top '
									. $this->getMainElementAllCssClasses($p_boxId,IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_CELL,true)		// get the MSIE7 code
								,	'icand_strct_boxrowcell '
									. $p_sizeClass 
									. ' icand_text_top '
									. $this->getMainElementAllCssClasses($p_boxId,IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_CELL)				// get the good code
							);
		$l_outputbuffer[] = '	  <div ';
		$l_outputbuffer[] = $p_divId;
		$l_outputbuffer[] = 'class="icand_strct_boxrowcontent ';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_STYLING_CONTAINER;
	$l_outputbuffer[] = ' ';
	if ($l_boxHasPushdownStyles) { // put styles on outer container
		$l_outputbuffer[] = $this->getMainElementAllCssClasses($p_boxId,IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_BOX,false );
	}//if
	$l_outputbuffer[] = $p_boxClass; //icand_strct_msiedouble_mainbox ';
	$l_outputbuffer[] = '">';

	if (!$l_boxHasPushdownStyles) {
	 $l_outputbuffer[] = '<div class="icand_decoration_layer icand_cover_all ';	
	 $l_outputbuffer[] = $this->getMainElementAllCssClasses($p_boxId,IcandConstantsBackend::CONFIG_REWRITE_DECORATION_TARGET_BOX);
	 $l_outputbuffer[] = '"></div>';
	}//if
	$l_outputbuffer[] =  '		<div class="';
	$l_outputbuffer[] = IcandConstantsCSS::CSS_FRONTMOST_CONTENTS;
	$l_outputbuffer[] = ' ';
	if (!$l_boxHasPushdownStyles) {	// TODO: maybe not padded in all contexts !!
	 $l_outputbuffer[] = ' icand_dec_padded ';
	}//if
	$l_outputbuffer[] = $this->myThemeBackgroundIntegrator->getElementTextColourCSSClass($p_boxId);
		$l_outputbuffer[] =  ' icand_content ';
		$l_outputbuffer[] =  $l_contentClass; //	course-content'
		$l_outputbuffer[] =  '">';	
		return implode( null, $l_outputbuffer );			// join all the strings together & return the result
}//function getHTMLcourseBoxStart(..)

/**
 * @param nothing
 * @return nothing
 * @side-effect echoes HTML to output stream
 */
protected function echoHtmlMainBox() {
	global $OUTPUT, $PAGE;
	$l_outputbuffer = array();
	$l_outputbuffer[] =  $this->getHTMLcourseBoxStart( 'courseMain' ); 
	$l_outputbuffer[] =  $OUTPUT->main_content();
	$l_outputbuffer[] =  $this->getHTMLcourseBoxEnd( 'courseMain' );
	echo implode( null, $l_outputbuffer );
}//echoHtmlMainBox
//=====================================================================================

function echoDebugInfo($p_otherText = null) {
global $PAGE, $COURSE;
echo $p_otherText;
//print_r( $this->metaElementGraphicTreatment );
//print_r( $this->metaElementGraphicArray );
//print_r( $buildPage->themeconfig );
return;	
	echo 'context:';
	print_r ($PAGE->categories ); // $PAGE->context->contextlevel
	echo '<hr/>' ;
	if ( $PAGE->user_can_edit_blocks() ) { echo 'can edit blocks' ; }
	if ($PAGE->user_allowed_editing() ) { echo 'allowed editing' ; }
	echo 	' activityname:' . $PAGE->activityname
			. ' heading:' . $PAGE->heading    // ->course->fullname)
			. '  subpage:' . $PAGE->subpage
			. ' title:' . $PAGE->title
			. '<br/>'
			;
	echo '(site:' . $SITE->fullname . ' ' . $SITE->shortname . ' id:' . $SITE->id . ' cat"' . $SITE->category . ')<br/>'; 
	echo '(course:' . $COURSE->fullname . ' ' . $COURSE->shortname . ' id:' . $COURSE->id  . ' cat:' . $COURSE->category . ')<br/>';
	//print_r ($PAGE->categories ); //in order from current to root:(1st:key=$COURSE->category) =>[name]=>eLearning Design 
	echo 'category:'. $PAGE->categories[$COURSE->category]->name . '<br/>';
	//print_r ($PAGE->context);
	echo 'path:' . $PAGE->context->path . '<br/>';
	//print_r ($COURSE );		
	echo $PAGE->debug_summary();
	//print_r( $PAGE->blocks );
}//echoDebugInfo()
}//class IcandBuildPage
}