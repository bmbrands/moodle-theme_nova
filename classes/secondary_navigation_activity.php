<?php
// This file is part of Moodle Workplace https://moodle.com/workplace based on Moodle
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
//
// Moodle Workplace Code is dual-licensed under the terms of both the
// single GNU General Public Licence version 3.0, dated 29 June 2007
// and the terms of the proprietary Moodle Workplace Licence strictly
// controlled by Moodle Pty Ltd and its certified premium partners.
// Wherever conflicting terms exist, the terms of the MWL are binding
// and shall prevail.

/**
 * Global theme elements.
 *
 * @package   theme_nova
 * @copyright 2020 Moodle Pty Ltd <support@moodle.com>
 * @author    Bas Brands <bas@moodle.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nova;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use moodle_url;
use Exception;
use moodle_exception;
use navigation_node;
use navigation_node_collection;

class secondary_navigation_activity extends navigation_node_collection {
    /** @var moodle_page the moodle page that the navigation belongs to */
    protected $page;

    /**
     * Constructor.
     *
     * @param moodle_page $page
     */
    public function __construct(&$page) {
        if (during_initial_install()) {
            return false;
        }
        $this->page = $page;
    }

    /**
     * Build the list of navigation nodes based on the current navigation and settings trees.
     *
     */
    public function initialise($modname) {
        global $CFG;

        $thiscontext = \context::instance_by_id($this->page->context->id);

        // Participants dropdown
        $activitynav = navigation_node::create($modname,
                null,
                navigation_node::TYPE_COURSE,
                '',
                $modname
        );
        $activitynav->make_active();
                $coursecontext = \context_course::instance($this->page->course->id);

                // Let plugins hook into course navigation.
        $pluginsfunction = get_plugins_with_function('extend_settings_navigation', 'lib.php');

        // if (isset($puginsfunction['mod'][$modname])) {
            
        // }
        foreach ($pluginsfunction as $plugintype => $plugins) {
            // Ignore the report plugin as it was already loaded above.
            if ($plugintype == 'mod') {
                continue;
            }
            foreach ($plugins as $pluginfunction) {
                $pluginfunction($this->page->settingsnav, $activitynav, $coursecontext);
            }
        }

        $this->add($activitynav);

        // Highlight the active 1st level dropdown.. This is probably nested a bit too deep. There
        // must be a nicer way of doing this.
        $pageurl = parse_url($this->page->url, PHP_URL_PATH);
        foreach ($this->get_key_list() as $key) {
            $node = $this->get($key);
            if ($node && $node->children) {
                foreach ($node->children as $childnode) {
                    if ($childnode->action) {
                        if (parse_url($childnode->action->out(), PHP_URL_PATH) === $pageurl) {
                            $node->make_active();
                            continue;
                        }
                    }
                    if ($childnode->children) {
                        foreach ($childnode->children as $subchild) {
                            if ($subchild->action && parse_url($subchild->action->out(), PHP_URL_PATH) === $pageurl ) {
                                $node->make_active();
                                continue;
                            }
                        }
                    }
                }
            }
        }
    }
}