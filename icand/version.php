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
 * Theme version info
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		2012121301 13Dec2012
 */

defined('MOODLE_INTERNAL') || die;

/*
* every release: change version date in "Motto" string: /lang/_/theme_icand.php
* 2012071301 beta release to institutes
* 2012072301 changed CSS to block MSIE hack from Safari (icand_core_layout.css)
* 2012072501 added "middle" layout option for background graphics (ThemeBackgroundImages.php, ThemeOptions_inc.php)  
* 2012080201 added 2 rules to icand_dock.css to fix a scrolling bug in MSIE.
* 2012080202 changed general.php to allow options in config.php to override "page_layout". 
*				Objective: to force "full width" in report layout for gradebook, etc.
* 2012080301 added option to control dock positioning (overlap or squeeze)
*				added CSS for .activity (.autocompletion/.togglecompletion) in icand_content_spacing.css
* 2012080901 added "Moodle home button" to user controls
*			 fixed overlapping menu styles in 
*				icand_databasemodule.css
* 2012081701 added 2 files icand-fixer_*.js to solve name clash
*				of "$" between course_menu block and jQuery
* 2012081701 renamed from Quicksilver to iCan.D.
* 2012081801 added /secret/copy.php to import from Quicksilver theme
* 2012100401 multi-level permissions (db change), "fixed" position popup buttons, strings in /lang/en
* 2012101502 many changes to CSS, renamed twodimensionmulticheck to +2 to avoid clash with May2012 Quicksilver
*/

$plugin->version   = 2012121301; // The current theme (module) version (Date: YYYYMMDDXX)
$plugin->component = 'theme_icand'; // Full name of the plugin (used for diagnostics)
$plugin->maturity  = MATURITY_RC; 
// MATURITY_ALPHA MATURITY_BETA MATURITY_RC MATURITY_STABLE;

$plugin->requires  = 2010090501; // Requires this Moodle version

