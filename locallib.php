<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   local_accessibilitytool
 * @author    Mark Sharp <m.sharp@chi.ac.uk>
 * @copyright 2018 University of Chichester {@link www.chi.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_accessibilitytool;

defined('MOODLE_INTERNAL') || die();

/**
 * Adds user menuitem if not already present.
 */
function add_usermenuitem() {
    $menuitems = get_config("core", 'customusermenuitems');
    if (strpos($menuitems, "accessibilitytool,local_accessibilitytool") !== false) {
        return;
    }

    $linetoadd = "accessibilitytool,local_accessibilitytool|/local/accessibilitytool/manage.php|accessibility\n";
    $menuitems = $linetoadd . $menuitems;
    set_config('customusermenuitems', $menuitems);
}

/**
 * Removes the menu item, if plugin is disabled or uninstalled.
 */
function remove_usermenuitem() {

}

/**
 * Applies user preferences to current page.
 */
function apply_settings(\moodle_page $page) {

    $page->add_body_class('accessibilitytool');
    $extraclasses = [];
    $font = get_user_preferences('accessibilitytool_font', "default");
    if (in_array($font, ['modern', 'classic', 'comic', 'mono'])) {
        $extraclasses[] = 'at-font-' . $font;
    }

    $size = get_user_preferences('accessibilitytool_size', "default");
    if (in_array($size, ['large', 'huge', 'massive', 'gigantic'])) {
        $extraclasses[] = 'at-size-' . $size;
    }

    $contrast = get_user_preferences('accessibilitytool_contrast', "default");
    if (in_array($contrast, ['by', 'yb', 'wg', 'bb', 'br', 'bw'])) {
        $extraclasses[] = 'at-contrast';
        $extraclasses[] = 'at-contrast-' . $contrast;
    }

    $bold = get_user_preferences('accessibilitytool_bold', 0);
    if ($bold) {
        $extraclasses[] = 'at-bold';
    }

    $spacing = get_user_preferences('accessibilitytool_spacing', 0);
    if ($spacing) {
        $extraclasses[] = 'at-spacing';
    }

    $readtome = get_user_preferences('accessibilitytool_readtome', 0);
    if ($readtome) {
        $page->requires->js_call_amd('local_accessibilitytool/readtome', 'init');
    }

    $stripstyles = get_user_preferences('accessibilitytool_stripstyles', 0);
    if ($stripstyles) {
        $page->requires->js_call_amd('local_accessibilitytool/stripstyles', 'init');
    }

    $page->add_body_classes($extraclasses);

    $page->requires->strings_for_js(['help'], 'moodle');
}