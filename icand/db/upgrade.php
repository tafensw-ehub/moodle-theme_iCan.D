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

require_once(dirname(__FILE__) . '/../lib/ThemeOptions_inc.php');	// class IcandConfigOptions
require_once(dirname(__FILE__) . '/../version.php');	//

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');	// upgrade uses implodeMultiselect, ...

global $f_versionphp_plugin;
$f_versionphp_plugin = $plugin;

function splitOldColourValues( $p_oldDBvalue, $p_newPrefix ) {
	$l_result = array();
	$l_oldValueArray = IcandConstantsBackend::explodeMultiselect($p_oldDBvalue);
	if (isset($l_oldValueArray['fo'])) {
		$l_result[$p_newPrefix . 'fo'] = 'fo~'.$l_oldValueArray['fo'];
		unset($l_oldValueArray['fo']);
	}
	if (isset($l_oldValueArray['dt'])) {
		$l_result[$p_newPrefix . 'dt'] = 'dt~'.$l_oldValueArray['dt'];
		unset($l_oldValueArray['dt']);
	}
	$l_newValues = array();
	$l_newValues['t'] = array();
	$l_newValues['c'] = array();
	$l_newValues['h'] = array();
	$l_map = array ( 't'=> 'tb' , 'c' => 'mm' , 'h' => 'he' );
	foreach( $l_oldValueArray as $l_key => $l_value ) {
		$l_first_char = $l_key[0];
		if ( $l_first_char == 'm' ) { $l_first_char = 'c'; }//adjust 'mm'
		$l_newValues[$l_first_char][$l_key] = $l_value;
	}//foreach
	foreach ( $l_newValues as $l_first_char => $l_values ) {
		if (count($l_values) > 0) {
			$l_result[($p_newPrefix . $l_map[$l_first_char])] = IcandConstantsBackend::implodeMultiselect($l_values);
		}
	}//foreach
	return $l_result;
}//splitOldColourValues(..)

function upgrade_coursePermissions($p_currentSettings, $p_pluginName ) {
	$l_fieldsToUpdate = array ( 'permit_general' , 'permit_menu' , 'permit_header' , 'permit_footer', 'permit_layout', 'permit_pagewidestyle' , 'permit_pagewideimg', 'permit_headfoot_bg', 'permit_colour' );
	// change from boolean to integer for multi-select
	foreach ( $l_fieldsToUpdate as $l_fieldToUpdate ) {
		if (isset( $p_currentSettings[$l_fieldToUpdate])) {
			$l_permissionArray = IcandConstantsBackend::explode2(	IcandConstantsBackend::MULTISELECT_COMBINER
																,	IcandConstantsBackend::PART_PLACE_JOINER
																,	$p_currentSettings[$l_fieldToUpdate]
															);
			$l_changeCount = 0;
			foreach ( $l_permissionArray as $l_key => $l_value ) {
				if ($l_value == null ) {
					$l_permissionArray[$l_key] = IcandConstantsBackend::DB_VALUE_PERMISSION_LEVEL_COURSE_EDITOR;
					$l_changeCount++;
				}
			if ( $l_changeCount > 0 ) {	// write changes back
				$l_newString = IcandConstantsBackend::implode2( IcandConstantsBackend::MULTISELECT_COMBINER
															,	IcandConstantsBackend::PART_PLACE_JOINER
															,	$l_permissionArray
														);
				set_config( $l_fieldToUpdate, $l_newString,	$p_pluginName);	// write update to DB
			}
			}//foreach
		}//if
	}//foreach
}

function upgrade_colourFields($p_currentSettings, $p_pluginName ) {
// ver 2012062001 separated 'colour_offsets' & 'colour_palettes' into 5 fields each.
	// 1. site-wide 'colour_offsets'
	if (isset( $p_currentSettings['colour_offsets'])) {
		$l_newSettingsArray = splitOldColourValues(
				$p_currentSettings['colour_offsets']
			,	'clr_off_' );	
		foreach ( $l_newSettingsArray as $l_newKey => $l_newValue ) {
			set_config( $l_newKey, $l_newValue,	$p_pluginName);
		}//foreach
		set_config( 	'colour_offsets_bak'
					, 	$p_currentSettings['colour_offsets']
					,	$p_pluginName);
		unset_config('colour_offsets', $p_pluginName);
	}
	// 2. site-wide 'colour_palettes'
	if (isset( $p_currentSettings['colour_palettes'])) {
		$l_newSettingsArray = splitOldColourValues(
				$p_currentSettings['colour_palettes']
			,	'clr_pal_' );
			
		foreach ( $l_newSettingsArray as $l_newKey => $l_newValue ) {
			set_config( $l_newKey, $l_newValue,	$p_pluginName);
		}//foreach
		set_config( 	'colour_palettes_bak'
					, 	$p_currentSettings['colour_palettes']
					,	$p_pluginName);
		unset_config('colour_palettes', $p_pluginName);			
	}//if (isset(...
	
	// 3. course overrides for 'colour_offsets' and 'colour_palettes'
	$l_allOverridesString = $p_currentSettings[IcandConstantsBackend::DB_FIELD_COURSE_OVERRIDES];
	$l_allOverridesString = preg_replace( IcandConstantsBackend::PREG_REMOVE_FROM_OVERRIDES, '', $l_allOverridesString); 		
	$l_allOverridesString = substr( $l_allOverridesString,0,-1);
	$l_allOverridesArray = IcandConstantsBackend::explode2( 
						IcandConstantsBackend::OVERRIDE_ALL_SEPARATOR 
					,	IcandConstantsBackend::OVERRIDE_ALL_CONNECTOR
					,	$l_allOverridesString );
	$l_madeAnyChange = false;
	foreach ( $l_allOverridesArray as $l_course => $l_courseOverridesString ) {
		$l_courseOverridesString = substr($l_courseOverridesString,1); //remove leading '{'
		$l_courseOverridesString = preg_replace( IcandConstantsBackend::PREG_REMOVE_FROM_OVERRIDES, '', $l_courseOverridesString );
		$l_courseOverridesArray = IcandConstantsBackend::explode2(
					IcandConstantsBackend::OVERRIDE_ONE_SEPARATOR 
				,	IcandConstantsBackend::OVERRIDE_ONE_CONNECTOR
				,	$l_courseOverridesString
				,	2 ); // only get key and value
		$l_madeChangeToCourse = false;
		if (isset( $l_courseOverridesArray['colour_offsets'])) {
			$l_madeChangeToCourse = true;
			$l_newSettingsArray = splitOldColourValues(
					$l_courseOverridesArray['colour_offsets']
				,	'clr_off_' );
			$l_courseOverridesArray = array_merge($l_courseOverridesArray,$l_newSettingsArray );				
			unset( $l_courseOverridesArray['colour_offsets'] ); 
		}
		if (isset( $l_courseOverridesArray['colour_palettes'])) {
			$l_madeChangeToCourse = true;
			$l_newSettingsArray = splitOldColourValues(
					$l_courseOverridesArray['colour_palettes']
				,	'clr_pal_' );
			$l_courseOverridesArray = array_merge($l_courseOverridesArray,$l_newSettingsArray );
			unset( $l_courseOverridesArray['colour_palettes'] ); 			
		}
		if ($l_madeChangeToCourse) {
			$l_madeAnyChange = true;
			$l_allOverridesArray[ $l_course ] = 
					'{' 
					. 	(IcandConstantsBackend::implode2(
							IcandConstantsBackend::OVERRIDE_ONE_SEPARATOR . IcandConstantsBackend::STR_APPEND_TO_OVERRIDES
						,	IcandConstantsBackend::OVERRIDE_ONE_CONNECTOR
						,	$l_courseOverridesArray )
						);
		}
	}//foreach
	if ($l_madeAnyChange) {	// write course overrides back to database
		$l_newValue = IcandConstantsBackend::implode2(
						IcandConstantsBackend::OVERRIDE_ALL_SEPARATOR . IcandConstantsBackend::STR_APPEND_TO_OVERRIDES
					,	IcandConstantsBackend::OVERRIDE_ALL_CONNECTOR
					,	$l_allOverridesArray );
		if (strlen( $l_newValue ) > 0) {
			$l_newValue .= IcandConstantsBackend::OVERRIDE_ALL_TERMINATOR;
			set_config( IcandConstantsBackend::DB_FIELD_COURSE_OVERRIDES
				, 	$l_newValue
				,	$p_pluginName);	
		}//if		
	}//if
}//upgrade_colourFields(..)


function xmldb_theme_icand_upgrade($p_oldversion) {
global $f_versionphp_plugin; // from version.php	
// NB NAME OF PROCEDURE INCLUDES NAME OF THEME!!! CHANGE THIS IF THEME RENAMED
$l_currentVersion = $f_versionphp_plugin->version;
//	$l_themeName = 'icand';
//echo 'XXcurrent' . $l_currentVersion;
    if ($p_oldversion >= $l_currentVersion) {
		return true;
	} else {
		$l_pluginName = $f_versionphp_plugin->component; // from version.php
		$l_themeName = substr( $l_pluginName, strpos( $l_pluginName, '_' )+1); // get end of string
		
        $l_currentSettings = (Array)(get_config($l_pluginName));
		
		//-----------------------------------------------------------		
		//	generic: add defaults for all fields that are missing
		//-----------------------------------------------------------
		// !!! do this first. It will OVERWRITE any fields added for the 1st time.
		$l_all_configs = IcandConfigOptions::getAllConfigKeysAndDefaults();		
		foreach ( $l_all_configs as $l_key => $l_default ) {
			if (!isset($l_currentSettings[$l_key])) {
				set_config($l_key, $l_default, $l_pluginName);
			}//if
		}//foreach

		//-----------------------------------------------------------
		// ver 2012060108  -> ver 2012062001 et. seq.
		//-----------------------------------------------------------
		if ( $p_oldversion < 2012062001 ) {
			upgrade_colourFields($l_currentSettings, $l_pluginName);
		}

		//-----------------------------------------------------------
		// ver x  -> ver 2012104001 et. seq.
		//-----------------------------------------------------------		
		if ( $p_oldversion < 2012104001 ) {
			upgrade_coursePermissions($l_currentSettings, $l_pluginName);
		}
		
        // Upgrade the version that Moodle knows is installed 
		// so that this upgrade isn't run again.
        upgrade_plugin_savepoint(true, $l_currentVersion, 'theme', $l_themeName);
		return true;
    }//if
 
	return true;
 
//	return false;//failure, if we get here???
}