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
 * This theme uses settings_inc.php to share the same configuration menu between both
 * the site admin's menu (which is standard in Moodle) and a special popup for courses. 
 * To use core Moodle admin_setting objects in the POPUP page for this theme, an Moodle compliant
 * container has to be provided. 
 * The following is just a copy of the key components of /lib/adminlip.php::admin_externalpage
 *
 * @package		theme
 * @subpackage	icand
 * @copyright	NSW Technical and Further Education Commission (TAFE NSW), TAFE eLearning Hub 2012
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author		Glen Byram (NSW TAFE eLearning Hub)
 * @version		28Sep2012
 * @see			/lib/adminlip.php::admin_settingpage
 * @see			settings_inc.php
 */
 
// 28Sep2012 renamed Popup_admin_setting_page to Icand_admin_setting_page

class Icand_admin_setting_page {

    /** @var string An internal name for this external page. Must be unique amongst ALL part_of_admin_tree objects */
    public $name;

    /** @var string The displayed name for this external page. Usually obtained through get_string(). */
    public $visiblename;

    /** @var mixed An array of admin_setting objects that are part of this setting page. */
    public $settings;

    /** @var string The role capability/permission a user must have to access this external page. */
    public $req_capability;

    /** @var object The context in which capability/permission should be checked, default is site context. */
    public $context;

    /** @var bool hidden in admin tree block. */
    public $hidden;

    public function __construct($name, $visiblename, $req_capability='moodle/site:config', $hidden=false, $context=NULL) {
        $this->settings    = new stdClass();
        $this->name        = $name;
        $this->visiblename = $visiblename;
        if (is_array($req_capability)) {
            $this->req_capability = $req_capability;
        } else {
            $this->req_capability = array($req_capability);
        }
        $this->hidden      = $hidden;
        $this->context     = $context;
    }

    public function add($setting) {
        if (!($setting instanceof admin_setting)) {
            debugging('error - not a setting instance');
            return false;
        }
        $this->settings->{$setting->name} = $setting;
        return true;
    }

    public function output_html() {
        $return = '<fieldset>'."\n".'<div class="clearer"><!--   --></div>'."\n";
        foreach($this->settings as $setting) {
            $fullname = $setting->get_full_name();
                $data = $setting->get_setting();
                // do not use defaults if settings not available - upgrade settings handles the defaults!
//            }
            $return .= $setting->output_html($data);
        }
        $return .= '</fieldset>';
        return $return;
    }
}