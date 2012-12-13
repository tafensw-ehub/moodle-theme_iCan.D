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
 * @version		9July2012
 */
defined('MOODLE_INTERNAL') || die();
require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');//class IcandConfigOptions
 
function xmldb_theme_icand_install() {
/** run if no version # in previous versions */
	$l_themeName = 'icand';
	$l_pluginName = 'theme_' . $l_themeName;

	$l_currentSettings = get_config($l_pluginName);
	
 
/* UPGRADE 
    $currentsetting = get_config($l_pluginName);

    // Create a new config called settinga and give it settingone's value.
    set_config('settingtest', $currentsetting->settingone, $l_pluginName);
    // Remove settingone
    unset_config('settingone', $l_pluginName);
*/

	$l_all_configs = IcandConfigOptions::getAllConfigKeysAndDefaults();
	foreach ( $l_all_configs as $l_key => $l_default ) {
		set_config($l_key, $l_default, $l_pluginName);
	}//foreach

    return true;
}