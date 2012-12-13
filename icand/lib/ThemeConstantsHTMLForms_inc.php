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
if(class_exists('IcandConstantsHTMLForms') != true) {
class IcandConstantsHTMLForms {
//======================================================================================
/** 
 *
 */
//--------------------------------------------------------------------
// for HTML form in settings page
const FORM_VALUE_EMPTY				= '~~empty~~';
const FORM_SINGLE_VALUE_INHERIT		= '~~inherit~~';
const FORM_VALUE_NONE				= 'none';
const FORM_VALUE_INHERIT			= 'inherit';

const FORM_KEY_FIELD_IS_OVERRIDE	= 'override_theme';
const FORM_KEY_FIELD_IS_SAVE		= 'override_save';
const FORM_KEY_FIELD_IS_TEST		= 'override_test';
const FORM_KEY_FIELD_FLIP_TEXT_DIR	= 'override_testRTL';

const FORM_FIELD_SPECIAL_MOODLE_THING = 'xxxxx'; // Moodle adds a hidden field to select arrays. Dunno why.

//-----------------------------------------------------------------------
/* coordinates between uploader.php and upload_receiver.php ********** */
const FORM_TAG_FOR_UPLOAD_FILE 			= 'uploadedfile';
const FORM_TAG_FOR_UPLOAD_FILE_COURSE 	= 'courseinfo';

}//class
}//if