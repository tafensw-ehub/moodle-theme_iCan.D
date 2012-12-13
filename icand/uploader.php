<html>
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
 * This script generates an HTML form that allows users to select a file from their local file browser, and
 * click a button to "upload" it to the webserver. The form calls upload_receiver.php to do process the results of the uploading.
 * In the iCan.D. theme, the form HTML is included in an iFrame embedded in the theme settings form (settings_inc.php).
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub) 
 * @version		28Sep2012
 * @see			upload_receiver.php
 * @see			settings_inc.php
 * @see			config.php::$FILE_UPLOAD_MAXSIZE
 * @todo		work out how to move "Choose file to upload:" and "Upload File" to lang file.
 */
require_once(dirname(__FILE__) . '/config.php'); // IcandConstantsConfif::$FILE_UPLOAD_MAXSIZE
require_once(dirname(__FILE__) . '/lib/ThemeConstantsHTMLForms_inc.php');	// IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_* 
?>
<body>
<form enctype="multipart/form-data" action="upload_receiver.php" method="POST">
	<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo IcandConstantsConfig::$FILE_UPLOAD_MAXSIZE; ?>" />
	<input type="hidden" name="<?php echo IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE_COURSE ?>" value="<?php
		echo $_GET[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE_COURSE]
	?>" />
	<label for="<?php  echo IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE; ?>">Choose file to upload:</label>
	<input name="<?php echo IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE; ?>" id="<?php echo IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE; ?>" type="file" />
	<input type="submit" value="Upload File" />
</form>
</body>
</html>