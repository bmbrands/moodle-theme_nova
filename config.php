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
 * nova config.
 *
 * @package   theme_nova
 * @copyright 2020 Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

$THEME->name = 'nova';

$THEME->sheets = [];

$THEME->layouts = [
    // Most backwards compatible layout without the blocks - this is the layout used by default.
    'base' => array(
        'file' => 'columns2.php',
        'regions' => array(),
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Main course page.
    'course' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true),
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // My dashboard page.
    'mydashboard' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => true, 'langmenu' => true, 'nocontextheader' => true),
    ),
    // My public page.
    'mypublic' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => true),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nocoursefooter' => true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array()
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'columns1.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => false),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // The pagelayout used for reports.
    'report' => array(
        'file' => 'columns2.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre',
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'secure.php',
        'regions' => array('side-pre'),
        'defaultregion' => 'side-pre'
    )
];

// Make sure the boost layouts are loaded.
foreach ($THEME->layouts as $key => $value) {
    if (($THEME->layouts[$key]['file'] == 'columns2.php')) {
        $THEME->layouts[$key]['theme'] = 'nova';
    } else {
        $THEME->layouts[$key]['theme'] = 'boost';
    }
}

$THEME->scss = function($theme) {
    return theme_nova_get_main_scss_content();
};

$THEME->editor_sheets = [];
$THEME->parents = [];
$THEME->enable_dock = false;
$THEME->yuicssmodules = array();
$THEME->requiredblocks = '';
$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->addblockposition = BLOCK_ADDBLOCK_POSITION_FLATNAV;
$THEME->iconsystem = '\\theme_nova\\output\\icon_system_fontawesome';
