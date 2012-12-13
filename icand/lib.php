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
 *	NOT CURRENTLY USED, as there are no theme-wide CSS variables.
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		25June2012
 */

/**
 * Makes textual replacements in the theme's CSS file/s (if not in designer mode, these will be consolidated into one cached file)
 * by looking for placeholders (in [[..]] format) and substituting a theme-defined value.
 *
 * @param string $css
 * @param theme_config $theme
 * @return string 
 */
 
function icand_process_css($css, $theme) {
/*
	$css = str_replace($l_css_replacementKeys, $l_css_replacementValues, $css);
    return $css;
*/	
}

/**
 * Sets the link color variable in CSS
 *
 */
/*
function icand_set_linkcolor($css, $linkcolor) {
    $tag = '[[setting:linkcolor]]';
    $replacement = $linkcolor;
    if (is_null($replacement)) {
        $replacement = '#2a65b1';
    }
    $css = str_replace($tag, $replacement, $css);
    return $css;
}

function icand_set_linkhover($css, $linkhover) {
    return $css;
}

function icand_set_backgroundcolor($css, $backgroundcolor) {
    return $css;
}
*/
