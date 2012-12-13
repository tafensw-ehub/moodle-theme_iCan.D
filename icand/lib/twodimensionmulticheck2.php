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
 * @created		6June2012
 * @version 	15Oct2012	- renamed by adding '2' to end. There was an old old version about that caused a name clash.
 *
 * output_html(..) sets off accessibility checker alerts due to a label without a referent. This has
 * been caused by Moodle's /adminlib.php::admin_page_managefilters::format_admin_setting(..) which generates
 * a label even when there is no referent.
 */
if(class_exists('admin_setting_configtwoaxismultiradio2') != true)
{

require_once(dirname(__FILE__) . '/../lib/ThemeConstantsBackend_inc.php');
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsCSS_inc.php');//defines class for this html
require_once(dirname(__FILE__) . '/../lib/ThemeConstantsHTMLForms_inc.php'); // IcandConstantsHTMLForms::FORM_TAG_FOR_*
require_once($CFG->libdir.'/adminlib.php'); // class admin_setting

/**
 * Multiple checkboxes, each represents different value, stored in csv format
 */
class admin_setting_configtwoaxismultiradio2 extends admin_setting 
{

    /** 
	 * @var array Array of choices value=>choise 
	 */
    var	$c_choicesX = null;
    var	$c_choicesY = null;
	var $c_isOverride = false;
	var	$c_inheritedSettings = null;	// array();
	var	$c_inheritedSettingsYKeys = null; //array();	

    /**
     * Constructor: uses parent::__construct
     *
     * @param string $p_name unique ascii name, either 'mysetting' for settings that in config, or 'myplugin/mysetting' for ones in config_plugins.
     * @param string $p_visiblename localised
     * @param string $p_description long localised info
     * @param array $p_defaultsetting array of selected
     * @param array $p_choicesX array of $value=>$label for horizontal axis of grid 
     * @param array $p_choicesY array of $value=>$label for vertical axis of grid 	 
	 * (N.B. $label values will have appropriate language strings loaded by caller) 
	 * @param array $p_inheritedSettingsArray optional array of (x,y) pairs
     */
    public function __construct(
						$p_name
					, 	$p_visiblename
					,	$p_description
					,	$p_defaultsetting
					,	$p_choicesX
					,	$p_choicesY
					,	&$p_inheritedSettingsArray=null) {			
		if ( $p_inheritedSettingsArray === null ) { //=== needed. Empty array==null!!!
			$this->c_choicesX = $p_choicesX ;
		} else {
			if ( is_string($p_inheritedSettingsArray )) {
				$p_inheritedSettingsArray = explode(IcandConstantsBackend::MULTISELECT_COMBINER,$p_inheritedSettingsArray);
			}
			$this->c_inheritedSettings = $p_inheritedSettingsArray;	
			$this->c_isOverride = true;
			$this->c_inheritedSettingsYKeys = array();
				// insert "inherit" as one of the options
			$this->c_choicesX = (array_merge( array(IcandConstantsHTMLForms::FORM_VALUE_INHERIT=>IcandConstantsHTMLForms::FORM_VALUE_INHERIT), $p_choicesX ));
			foreach ( $this->c_inheritedSettings as $l_choiceX ) {
				$this->c_inheritedSettingsYKeys[] = substr($l_choiceX,0,strpos($l_choiceX,IcandConstantsBackend::PART_PLACE_JOINER) );		
			}//foreach		
		}//else
        $this->c_choicesY = $p_choicesY;
        parent::__construct($p_name, $p_visiblename, $p_description, $p_defaultsetting);
    }

    /**
     * This public function may be used in ancestors for lazy loading of choices
     *
     * @todo Check if this function is still required content commented out only returns true
     * @return bool true if loaded, false if error
     */

    public function load_choices() {
        //   .... load choices here
        return true;
    }

    /**
     * Returns the current setting from database if it is set
     * @return mixed null if null, else an Array (key=>1)
	 * @todo Is the output format correct? How is this used?
	 * CALLED by /admin/index.php. If NULL, then redirect to /admin/upgradesettings.php
     */
    public function get_setting() {
        $l_db_string = $this->config_read($this->name);
        if (is_null($l_db_string)) {
            return array(); //NULL; // NEVER RETURN NULL!!! adminlib doesn't know what to do
        } elseif ($l_db_string === '') {
            return array();
        } else {
			$l_selections = explode(IcandConstantsBackend::MULTISELECT_COMBINER, $l_db_string);//explode into value pairs		
			$l_result = array();
			foreach ($l_selections as $l_selection ) {
//				($l_Xval, $l_Yval) = explode( IcandConstantsBackend::PART_PLACE_JOINER, $l_selection,2); 
				$l_result[$l_selection] = 1;
			}//foreach
			return $l_result;
		}//else
    }//get_setting
	
    /**
     * Saves the setting(s) provided in $p_currentSelections to database
     *
     * @param array $p_currentSelections An array of data, if not array returns empty str
     * @return mixed empty string on useless data or bool true=success, false=failed
     */
    public function write_setting($p_currentSelections) {
        if (!is_array($p_currentSelections)) {
            return ''; // ignore it
        } elseif (!$this->load_choices() or empty($this->c_choicesY)) {
            return '';
        } else {
			$result = array();
			foreach ($p_currentSelections as $l_key => $l_value) {
				if ($l_value and array_key_exists($l_key, $this->c_choicesY)) {
					$result[] = $l_value; // values are Y~X compounds
				}//if
			}//foreach
			return $this->config_write($this->name, implode(IcandConstantsBackend::MULTISELECT_COMBINER, $result)) ? '' : get_string('errorsetting', 'admin');
		}//else
    }//write_setting	

    /**
     * Returns XHTML field(s) as required by choices
     *
     * Relies on data being an array should data ever be another valid vartype with
     * acceptable value this may cause a warning/error
     * if (!is_array($p_currentSelections)) would fix the problem
     *
     * @todo Add vartype handling to ensure $p_currentSelections is an array
     *
     * @param array $p_currentSelections An array of checked values
     * @param string $p_query
     * @return string XHTML field
     */
	function get_defaultsetting() { 
		return parent::get_defaultsetting();
	} // TODO

/**
 * Return XHTML for the control
 *
 * @param array $p_currentSelections Default data array
 * @param string $query
 * @return string XHTML to display control
 */
    public function output_html($p_currentSelections, $p_query='') {
        if (!$this->load_choices() 
				or empty($this->c_choicesY)
				or empty($this->c_choicesX)) {
            return '';
        }

        if (is_null($p_currentSelections)) {	// ensure array is not empty
            $p_currentSelections = array();
        }
        $l_arrayOfOptionsFormattedAsHTMLTableRows 	= array(); // array to aggregate all the option strings
		
        $l_defaultStringFromSystem = $this->get_defaultsetting();	// string
        $l_defaultAsPairsArray = (is_null($l_defaultStringFromSystem))
						? array()
						: IcandConstantsBackend::explodeMultiselect( $l_defaultStringFromSystem);
						
		$l_defaultAsStringArray = (is_null($l_defaultStringFromSystem))
						? array()
						: explode( IcandConstantsBackend::MULTISELECT_COMBINER, $l_defaultStringFromSystem);
		
		// generate row of column titles----------------------------------------------------------
		$l_oneRowOptions = array();
		$l_oneRowOptions[] = '<td>&nbsp;</td>';			// start with empty cell in top-left corner
		
		// generate each column title
		foreach ($this->c_choicesX as $l_keyX=>$l_descriptionX) {
			$l_oneRowOptions[] = '<td>' . $l_descriptionX . '</td>';
		}
		$l_arrayOfOptionsFormattedAsHTMLTableRows[] = $l_oneRowOptions;
		
		// generate each row of value selectors from $this->c_choicesY ----------------------------
        foreach ($this->c_choicesY as $l_keyY=>$l_descriptionY) {  // each row in HTML table
			$l_oneRowOptions = array();
			$l_oneRowOptions[] = '<th>' . $l_descriptionY . '</th>'; // row title	
			$l_thisYHasOverride = ($this->c_isOverride and (in_array( $l_keyY, $this->c_inheritedSettingsYKeys )));
			$l_isFirstX = true;
			foreach ($this->c_choicesX as $l_keyX=>$l_descriptionX) {	// each cell in a single row
				$l_compoundKey = $l_keyY . IcandConstantsBackend::PART_PLACE_JOINER . $l_keyX;
				$l_checkedSubstring = '';
				$l_td_class = '';
				
				// show which unique value in the row has been selected
				if 	($this->c_isOverride ) {
					if ((!$l_thisYHasOverride and ($l_keyX === IcandConstantsHTMLForms::FORM_VALUE_INHERIT) ) 
						or (in_array($l_compoundKey, $this->c_inheritedSettings)) ) {
						$l_checkedSubstring	= 'checked="checked"';	
						if (in_array($l_compoundKey, $this->c_inheritedSettings)) { 		// highlight if value differs from "inherited"
							$l_td_class = ' class="' . IcandConstantsCSS::CSS_BG_INVERTER . '"';
						}
					}
				} else {
					if (isset($p_currentSelections[$l_compoundKey])) {
						$l_checkedSubstring	= 'checked="checked"';
						if (!in_array($l_compoundKey, $l_defaultAsStringArray )) {
							$l_td_class = ' class="' . IcandConstantsCSS::CSS_BG_INVERTER . '"';
						}
					}
				}
				$l_isFirstX = false;
				
				// construct HTML radio button in a td cell
				$l_oneRowOptions[] = 	
								'<td'
							. 	$l_td_class
							.	'><input type="radio" id="'
							.	$this->get_id().'_'.$l_compoundKey
							.	'" title="' . $this->c_choicesX[ $l_keyX ]
							.	'" name="'.$this->get_full_name().'['.$l_keyY.']" value="'.$l_compoundKey.'" '.$l_checkedSubstring.' />' 
							;
					// ACCESSIBILITY: in this dense input table, LABEL would take too much space. All inputs have TITLE,
					// which satifies all the screenreaders we tested: Jaws, Window-Eyes, NVDA
			}//foreach X
			$l_arrayOfOptionsFormattedAsHTMLTableRows[] = $l_oneRowOptions;		// add completed row to HTML table
        }//foreach Y

		$l_defaultText = array();	
		foreach ($l_defaultAsPairsArray as $l_keyY => $l_keyX ) {
			$l_defaultText[] = ($this->c_choicesY[$l_keyY] . ':' . $this->c_choicesX[$l_keyX]);
		} //foreach
		
		// get the informational "default" value string to display under the form
		$l_defaultinfo 	= (empty($l_defaultText))  // human-readable string of defaults
						? get_string('none')
						: implode(', ', $l_defaultText);
						
		// concatenate all the radio selector rows into a single string of HTML -------------------------------
		$l_html_input_code  = '<table class="';
		$l_html_input_code .= IcandConstantsCSS::CSS_CLASS_MULTICHECKBOX ;
		$l_html_input_code .= '">';
		$l_rowCount = 0;
        if ($l_arrayOfOptionsFormattedAsHTMLTableRows) {
			foreach ($l_arrayOfOptionsFormattedAsHTMLTableRows as $l_oneRowOptions) {
				$l_html_input_code .= '<tr class="row' . ($l_rowCount % 2). '">';
				foreach ($l_oneRowOptions as $l_option) {
					$l_html_input_code .= $l_option;
				}//foreach X
				$l_html_input_code .= '</tr>';
				$l_rowCount++;
			}//foreach Y
        }//if
		$l_html_input_code .= '</table>';
		
		// constuct a Moodle object containing all the elements (including the HTML radio button table) ------------------------
        return format_admin_setting(
					$this					// setting
				,	$this->visiblename		// title
				,	$l_html_input_code		// form
				,	$this->description		// description
				, 	false					// boolean = include "label for". No label here, as the inputs are a layer down.
				,	''						// warning
				,	$l_defaultinfo			// default
				,	$p_query);				// query
				
		/* Moodle's admin_page_managefilters::format_admin_setting(..) is bad. It inserts a <label> wrapper even
		 * if you don't have a "label for" attribute. So there is either an accessility error for an empty label,
		 * or a label with "for" but no referent.
		 */
				
    }//output_html
}//class
}//class exists