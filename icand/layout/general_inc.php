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
 * @version		04Oct2012
 */
/**
* TODO Unfortunately the text content floats 
* above the borders and background div, not inside it. 
* Perhaps borders should be forced around the text layer too?
*/

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	// 
require_once(dirname(__FILE__) . '/../lib/Buildpage_inc.php');	// class IcandBuildPage
require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');	

$buildPage = new IcandBuildPage();
$buildPage->setOptions( $themeconfig, $themeTextContent, $themeWholePageOptions );
if ($l_displayRTL) {
	$buildPage->setFlipRTL( true );
}
//============= GENERATE HTML=======================================

?><?php    
	$debug_output = ob_get_contents();	// save any debug code, output it later
    ob_end_clean(); 					// turn off Output Buffer
?><?php
	echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<!-- general_inc.php -->
<head>
    <title><?php echo $PAGE->title; ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
	<meta name="description" content="<?php p(strip_tags(format_text($SITE->summary, FORMAT_HTML))) ?>" />
    <?php echo $OUTPUT->standard_head_html(); ?>
	
<script type="text/javascript">
window.onload = function() {
	var target=document.getElementsByTagName("body")[0];
	var classToRemove = "icand_hideuntilpageloaded";
	target.className = target.className.replace(classToRemove,'');
}
</script>

<!--[if IE 7]>
	<style type="text/css">
		.icand_msie_bad {display:none;}	/*kill off any divs MSIE can't handle*/
		#footer { position:_static;} /*IE no good for javascript sticky footer. CONFLICTS WITH icand_strct_container */
	</style>
<![endif]-->

<?php
//=================================================
	$l_overrideStylesheetPrefix = 'palette_';
	
	$l_isOverrideStylesheet = false;
	if ( isset( $themeconfig['colour_paletteset'] )
		and ($themeconfig['colour_paletteset'] != '' )) {
		$l_isOverrideStylesheet = true;
		$l_overrideStylesheet = $themeconfig['colour_paletteset'];
	}
	
	
	if ($l_isOverrideStylesheet) {
	?>
		<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot . '/theme/'. current_theme() . '/style/' . $l_overrideStylesheetPrefix . $l_overrideStylesheet . '.css' ?>"/>	
	<?php } ?>
	<?php if ($themeTextContent->isHires()) { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $CFG->wwwroot . '/theme/'. current_theme() . '/style/' . 'option_icand_hires.css' ?>"/>	
	<?php } ?>
<style type="text/css">
/* !!!! WARNING: optional stylesheets loaded after this may override this setting */
	.icand_suppress_background {
		background:none;
	}
	</style>
<!-- theme version 2012101501 -->
</head><!-- real end of head -->
<?php flush(); //send part of the page so browser can start working on CSS, etc. 
	$l_bodyClassString = $PAGE->bodyclasses;
	$l_bodyClassString = str_replace( 'yui-skin-sam', '', $l_bodyClassString );	// get rid of any Y.U.I. CSS classes
	$l_bodyClassString = str_replace( 'yui3-skin-sam', '', $l_bodyClassString );
?>
<body id="<?php p($PAGE->bodyid) ?>" class="icand_hideuntilpageloaded icand_default_colours <?php p($l_bodyClassString.' '.join(' ', $themeconfig['local_custombodyclasses'])) ?>">
	<?php if ($l_isAbleToEditCourse ) {//write override menu for popup
//' cat='.$PAGE->category ; // this is an object stdClass
	
		$l_thisCourseEffectiveOverrides = $themeSettings->getOverrideSettingsArray();
		$l_ancestorCourse = $themeSettings->getAncestorCourseId();
		unset($themeconfig[IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE]);
		if ($l_ancestorCourse !== IcandConstantsBackend::DUMMY_VALUE_FOR_NO_ANCESTOR) {
			$themeconfig[IcandConstantsBackend::SETTINGS_CALCULATED_PARENT_COURSE] = $l_ancestorCourse;
		}//if

			//has to be included late, so override settings included when settings_inc.php is included
			$l_multichoiceOverrides = array(); 
			foreach (IcandConfigOptions::getAllMultiselectKeys() as $l_multiselectKey ) {
				$l_multichoiceOverrides[$l_multiselectKey] = 
					(array_key_exists($l_multiselectKey, $l_thisCourseEffectiveOverrides ))
					? $l_thisCourseEffectiveOverrides[$l_multiselectKey]
					: array();
//print_r($l_multichoiceOverrides);
			}//foreach

require_once('overridemenu_inc.php');	// defines writeOverrideMenu(..)
		
		writeOverrideMenu($themeconfig,$settings,$vischecker,$l_thisCourseEffectiveOverrides);
	} ?>
	<?php echo $OUTPUT->standard_top_of_body_html(); ?>
	<?php	// if there are controls to go at top of browser window (e.g. the button to pop-up the override menu) put them in.
		$l_fixedDivs = $buildPage->getTextElementForPosition('browserTop');
		if ( $l_fixedDivs !== false ) {
			echo '<div id="';
			echo IcandConstantsCSS::CSS_ID_FIXED_CONTROL_AREA;
			echo '">';
			echo $l_fixedDivs;
			echo '</div>';
		}
	?>
	<div id="<?php echo IcandConstantsCSS::CSS_ID_BODY; ?>" class="icand_styling_container">	
	<?php if ( $buildPage->isDesktopOKforImage() ) {
			echo $buildPage->getHtmlMetaElementDecorationDIVs('metadesktop');
		}//isDesktopOKforImage
		if ($buildPage->isFullWidthHeader()) { // does header go before or inside body strip?
			$buildPage->echoHtmlHeaderFooter( IcandConstantsBackend::PLACE_HEADER );
		}//isFullWidthHeader ?>	
		<div id="<?php echo IcandConstantsCSS::CSS_MOODLE_ID_PAGE; ?>" class="icand_strct_fullheight <?php // icand_strct_container 
			echo $buildPage->getPageWidthClass('metapage'); //width & centring
			echo ' ';		   
			echo $buildPage->getMetaElementBorderCSSClassNames( 'metapage' );
		?>">
		<?php 
			echo $buildPage->getHtmlMetaElementDecorationDIVs('metapage' );	
			if ($themeconfig['page_hasfooter']) { //for sticky footer ?>
			<div id="icand_nonfooter" class="_icand_strct_fullheight">
			<?php } ?>
				<?php 	if (!$buildPage->isFullWidthHeader()) {
						$buildPage->echoHtmlHeaderFooter( IcandConstantsBackend::PLACE_HEADER );  // N.B. "hasheading" has a narrow meaning
						}//isFullWidthHeader ?>
				<div id="<?php echo IcandConstantsCSS::CSS_MOODLE_ID_CONTENT; ?>" class="icand_styling_container <?php
					echo $buildPage->getPageWidthClass( IcandConstantsCSS::CSS_MOODLE_ID_CONTENT );?>">
					<?php echo $buildPage->getHtmlMetaElementDecorationDIVs('metamain' ); ?>	  
					<div class="over">
						<?php $buildPage->echoHTMLTopOfBody($OUTPUT, $PAGE); ?>
						<div id="icand_id_region-main-box_container" class="icand_styling_container">
							<?php echo $buildPage->getHtmlMetaElementDecorationDIVs('coursepage' );	?>					
							<div class="over">
								<?php $buildPage->writeHTMLMainBody (); ?>
							</div><!-- over -->
						</div><!-- icand_styling_container -->
					</div><!-- over -->
				</div><!-- <?php echo IcandConstantsCSS::CSS_MOODLE_ID_CONTENT; ?> -->					
			<?php if ($themeconfig['page_hasfooter']) { ?>
			</div><!-- icand_nonfooter --><?php
				$buildPage->echoHtmlHeaderFooter( IcandConstantsBackend::PLACE_FOOTER ); 
				$buildPage->echoDebugInfo($debug_output);
			}//page_hasfooter ?>
		</div><!-- id:<?php echo IcandConstantsCSS::CSS_MOODLE_ID_PAGE; ?> -->
	</div><!-- id:<?php echo IcandConstantsCSS::CSS_ID_BODY; ?> -->
	<?php echo $OUTPUT->standard_end_of_body_html(); ?>
</body>
</html>