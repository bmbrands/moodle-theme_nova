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
use pix_icon;
use navigation_node;
use flat_navigation_node;
use navigation_node_collection;

class navdrawer_navigation extends navigation_node_collection {
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
    public function initialise() {
        global $PAGE, $USER, $OUTPUT, $CFG, $COURSE, $SITE;

        if (during_initial_install()) {
            return;
        }

        // Add the home link
        $home = navigation_node::create(get_string('home'), new moodle_url('/', ['redirect' => 0]));
        $flat = new flat_navigation_node($home, 0);
        $flat->key = 'home';
        $flat->icon = new pix_icon('i/home', '');
        $flat->set_collectionlabel(get_string('home'));
        $this->add($flat);

        // Add the dashboard link
        $dashboard = navigation_node::create(get_string('myhome'), new moodle_url('/my'));
        $flat = new flat_navigation_node($dashboard, 0);
        $flat->key = 'myhome';
        $flat->icon = new pix_icon('i/dashboard', '');
        $flat->set_collectionlabel(get_string('myhome'));
        $this->add($flat);

        // Add the calendar link.
        $courseid = $COURSE->id;
        $params = ['view' => 'month'];
        if ($courseid != $SITE->id) {
            $params['course'] = $courseid;
        }
        $calendar = navigation_node::create(get_string('calendar', 'calendar'), new moodle_url('/calendar/view.php', $params));
        $flat = new flat_navigation_node($calendar, 0);
        $flat->key = 'calendar';
        $flat->icon = new pix_icon('i/calendar', '');
        $flat->set_collectionlabel(get_string('calendar', 'calendar'));
        $this->add($flat);

        $admin = $PAGE->settingsnav->find('siteadministration', navigation_node::TYPE_SITE_ADMIN);
        if (!$admin) {
            // Try again - crazy nav tree!
            $admin = $PAGE->settingsnav->find('root', navigation_node::TYPE_SITE_ADMIN);
        }
        if ($admin) {
            $flat = new \flat_navigation_node($admin, 0);
            $flat->set_showdivider(true, get_string('sitesettings'));
            $flat->key = 'sitesettings';
            $flat->icon = new \pix_icon('t/preferences', '');
            $this->add($flat);
        }

        // Add-a-block in editing mode.
        if (isset($this->page->theme->addblockposition) &&
                $this->page->theme->addblockposition == BLOCK_ADDBLOCK_POSITION_FLATNAV &&
                $PAGE->user_is_editing() && $PAGE->user_can_edit_blocks() &&
                ($addable = $PAGE->blocks->get_addable_blocks())) {
            $url = new moodle_url($PAGE->url, ['bui_addblock' => '', 'sesskey' => sesskey()]);
            $addablock = navigation_node::create(get_string('addblock'), $url);
            $flat = new \flat_navigation_node($addablock, 0);
            $flat->set_showdivider(true, get_string('blocksaddedit'));
            $flat->key = 'addblock';
            $flat->icon = new pix_icon('i/addblock', '');
            $this->add($flat);
            $blocks = [];
            foreach ($addable as $block) {
                $blocks[] = $block->name;
            }
            $params = array('blocks' => $blocks, 'url' => '?' . $url->get_query_string(false));
            $PAGE->requires->js_call_amd('core/addblockmodal', 'init', array($params));
        }
    }


    /**
     * Override the parent so we can set a label for this collection if it has not been set yet.
     *
     * @param navigation_node $node Node to add
     * @param string $beforekey If specified, adds before a node with this key,
     *   otherwise adds at end
     * @return navigation_node Added node
     */
    public function add(navigation_node $node, $beforekey=null) {
        $result = parent::add($node, $beforekey);
        // Extend the parent to get a name for the collection of nodes if required.
        if (empty($this->collectionlabel)) {
            if ($node instanceof flat_navigation_node) {
                $this->set_collectionlabel($node->get_collectionlabel());
            }
        }

        return $result;
    }
}