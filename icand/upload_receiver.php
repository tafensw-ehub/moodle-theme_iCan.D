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
 * This script is included in an iframe embedded in the theme's settings form. 
 * The file uploader.php provides an HTML form that prompts the user for the path of a local file (e.g. graphic) to be 
 * uploaded to the server. When that form is SUBMITted, it passes the form values to this script.
 * This script
 *		1. gets the results of the HTTP upload initiated by uploader.php
 *		2a. if upload failed, displays error message
 *		2b. if upload succeeded, moves file from temp file to target folder (usually theme's /pix/uploaded)
 *		3. using javascript, passes the URL to the uploaded file to the parent page's javascript GetValueFromChild(..)
 *		4. displays a thumbnail of the uploaded file, with a link to upload again
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		27June2012
 * @see			uploader.php
 * @see			settings_inc.php
 * @see			config.php::$FILE_UPLOAD_MAXSIZE config.php::$FILE_UPLOAD_FOLDER_PATH
 * @todo		make prompts come from /lang file via get_string(..)
 */
require_once(dirname(__FILE__) . '/config.php'); // IcandConstantsConfig:: $FILE_UPLOAD_MAXSIZE, $FILE_UPLOAD_FOLDER_PATH
require_once(dirname(__FILE__) . '/lib/ThemeConstantsHTMLForms_inc.php');	// IcandConstantsHTMLForms::FORM_TAG_FOR_*
?><html>
<head>
<script language="javascript" type="text/javascript">
	/** 
	 * This file is included in an iframe. SendValueToParent communicates the URL of uploaded file to 
	 * a javascript function "GetValueFromChild" which must exist in the parent webpage.
	 */
    function SendValueToParent(p_newvalue ) { // target = container of this iFrame.
		l_my_frame_name = window.name;
		l_parts = l_my_frame_name.split("~");
		parent.GetValueFromChild(l_parts[0], p_newvalue );
		return false;
    }
</script>
</head>

<body>
<?php

function getMyPath() {
global $_SERVER;
	return 
		(  ($_SERVER['HTTPS']=='ON')? 'https' : 'http' )
		. '://'
		. $_SERVER['HTTP_HOST']
		. dirname($_SERVER['PHP_SELF']);
}

/**
 * match error codes to messages
 */
define("UPLOAD_ERR_EMPTY",5); 	// this case is missing from standard PHP
$upload_errors = array(
    UPLOAD_ERR_OK        	=> "No errors.",
    UPLOAD_ERR_INI_SIZE     => "Larger than upload_max_filesize.",
    UPLOAD_ERR_FORM_SIZE    => "Larger than form MAX_FILE_SIZE.",
    UPLOAD_ERR_PARTIAL    	=> "Partial upload.",
    UPLOAD_ERR_NO_FILE      => "No file.",
    UPLOAD_ERR_NO_TMP_DIR   => "No temporary directory.",
    UPLOAD_ERR_CANT_WRITE   => "Can't write to disk.",
    UPLOAD_ERR_EXTENSION    => "File upload stopped by extension.",
    UPLOAD_ERR_EMPTY        => "File is empty." // add this to avoid an offset
  );

  
	$source_path = $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['tmp_name'];	// where does HTTP transfer put files?
	$course_info = $_POST[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE_COURSE];			// get course #, to use as prefix for file names

	$target_filename =  (strlen( $course_info ) == 0 || intval($course_info) == 0)
					? (basename( $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE ]['name']))
					: ('c' . $course_info . '_' . basename( $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE ]['name'])); 
	$target_path = IcandConstantsConfig::$FILE_UPLOAD_FOLDER_PATH . $target_filename;

	// !!!! write permission is needed for this folder on the server!!!!
	$target_url = getMyPath() . '/' . $target_path;

	$isError = true;

// test if the HTTP upload was successful
$uploadResultCode = $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['error'];
	if ($uploadResultCode > 0) {
		echo 'File upload error ' . $upload_errors[$_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['error']];
		if ( $uploadResultCode == UPLOAD_ERR_FORM_SIZE ) {
			echo ' (MAX_FILE_SIZE=' . IcandConstantsConfig::$FILE_UPLOAD_MAXSIZE . ' bytes).';
		}
	} elseif (!(in_array( $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['type'], IcandConstantsConfig::$VALID_UPLOADER_FILE_TYPES ))) {
		echo 'invalid file type: ' . $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['type'];
	} 
	elseif ($_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['size'] > IcandConstantsConfig::$FILE_UPLOAD_MAXSIZE) { //should be caught by client
		echo 'File too big: ' . $_FILES[IcandConstantsHTMLForms::FORM_TAG_FOR_UPLOAD_FILE]['size'] . ' bytes';
	} else { // file received ok. Try to move it to destination.
		$isDuplicate = (file_exists($target_path));
		if (move_uploaded_file($source_path, $target_path)) {
			$isError = false;
		} else {
			echo 'There was an error on the server while moving the file to ' . IcandConstantsConfig::$FILE_UPLOAD_FOLDER_PATH . ' Probably WRITE permission has not been set by the server administrator.';
		}
	}


if ($isError) { 	// if there was an upload error, report it
?>
<br />
<form action="uploader.php">
<input type="submit" value="Try again" />
</form>
<?php } else { 		// if NO upload error, show a thumbnail, and pass the final file URL to parent.
?>
<!-- <br />URL: <a href="<?php echo $target_url; ?>"><?php echo $target_url;?></a>
<br />Image (scaled): -->
<img src="<?php echo $target_url; ?>" height="80" />Uploaded! <a href="uploader.php">Change file</a>
<script language="javascript" type="text/javascript">
	//return 
	SendValueToParent( "<?php echo $target_url ?>");
</script>
<?php } ?>
</body>
</html>