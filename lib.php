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
 * Callbacks
 *
 * @package     theme_nova
 * @copyright   2019 Bas Brands <bas@sonsbeekmedia.nl>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Implementation of $THEME->scss
 *
 * @param theme_config $theme
 * @return string
 */
function theme_nova_get_main_scss_content($theme) {
    global $CFG;
    $scss = '';
    $scss .= '$loginbackground: "' . $theme->setting_file_url('loginbackground', 'loginbackground') . '";';
    $scss .= '$frontpageimage: "' . $theme->setting_file_url('frontpageimage', 'frontpageimage') . '";';
    $scss .= file_get_contents($CFG->dirroot . '/theme/nova/scss/default.scss');
    return $scss;
}

/**
 * Inject additional SCSS.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_nova_get_extra_scss($theme) {
    return !empty($theme->settings->scss) ? $theme->settings->scss : '';
}

function theme_nova_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($filearea === 'catalogue') {
        theme_nova_send_file($context, $filearea, $args, $forcedownload, $options);
    }

    if ($filearea === 'catalogue_cropped') {
        theme_nova_send_file($context, $filearea, $args, $forcedownload, $options);
    }

    if ($filearea === 'course') {
        theme_nova_send_file($context, $filearea, $args, $forcedownload, $options);
    }
    if ($filearea === 'course_cropped') {
        theme_nova_send_file($context, $filearea, $args, $forcedownload, $options);
    }
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('nova');
        if (!array_key_exists('cacheability', $options)) {
            $options['cacheability'] = 'public';
        }
        if ($filearea === 'loginbackground') {
            return $theme->setting_file_serve('loginbackground', $args, $forcedownload, $options);
        } else if ($filearea === 'frontpageimage') {
            return $theme->setting_file_serve('frontpageimage', $args, $forcedownload, $options);
        }
    } else {
        send_file_not_found();
    }
}

/**
 * Based on theme function setting_file_serve.
 * Always sends item 0
 *
 * @param $context
 * @param $filearea
 * @param $args
 * @param $forcedownload
 * @param $options
 * @return bool
 */
function theme_nova_send_file($context, $filearea, $args, $forcedownload, $options) {
    $revision = array_shift($args);
    if ($revision < 0) {
        $lifetime = 0;
    } else {
        $lifetime = DAYSECS * 60;
    }

    $filename = end($args);
    $contextid = $context->id;
    $fullpath = "/$contextid/theme_nova/$filearea/0/$filename";
    $fs = get_file_storage();
    $file = $fs->get_file_by_hash(sha1($fullpath));

    if ($file) {
        send_stored_file($file, $lifetime, 0, $forcedownload, $options);
        return true;
    } else {
        send_file_not_found();
    }
}

/**
 * Get compiled css.
 *
 * @return string compiled css
 */
function theme_nova_get_precompiled_css() {
    global $CFG;
    return file_get_contents($CFG->dirroot . '/theme/nova/style/moodle.css');
}