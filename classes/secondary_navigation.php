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

class secondary_navigation extends navigation_node_collection {
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
        global $CFG;

        $thiscontext = \context::instance_by_id($this->page->context->id);

        // Section navigation dropdown
        $sectionnav = navigation_node::create('Content',
                null,
                navigation_node::TYPE_COURSE,
                '',
                'sections'
        );

        $allsections = navigation_node::create('All sections',
            new moodle_url('/course/view.php', ['id' => $this->page->course->id]),
            navigation_node::TYPE_COURSE,
            '',
            'allsections'
        );
        $allsections->divider = true;

        $sectionnav->add_node($allsections);

        // Get the course sections to put in the first dropdown.
        $format = course_get_format($this->page->course);
        $sections = $format->get_sections();
        foreach ($sections as $section) {
            $i = $section->section;
            if (!$section->uservisible) {
                continue;
            }

            if (!empty($section->name)) {
                $title = format_string($section->name, true, ['context' => $thiscontext]);
            } else {
                $title = $format->get_section_name($section);
            }

            $sectionnode = navigation_node::create($title,
                $format->get_view_url($section),
                navigation_node::TYPE_COURSE,
                '',
                'section' . $i
            );
            $sectionnav->add_node($sectionnode);
        }

        $this->add($sectionnav);

        // Participants dropdown
        $participantsnav = navigation_node::create('Participants',
                null,
                navigation_node::TYPE_COURSE,
                '',
                'participants'
        );

        enrol_add_course_navigation($participantsnav, $this->page->course);
        $this->add($participantsnav);


        $coursecontext = \context_course::instance($this->page->course->id);
        $adminoptions = course_get_user_administration_options($this->page->course, $coursecontext);

        // REPORTS
        if ($adminoptions->reports) {
            $reportnav = navigation_node::create(get_string('reports'),
                null,
                navigation_node::TYPE_COURSE,
                null,
                'coursereports'
            );
            $coursereports = \core_component::get_plugin_list('coursereport');
            foreach ($coursereports as $report => $dir) {
                $libfile = $CFG->dirroot.'/course/report/'.$report.'/lib.php';
                if (file_exists($libfile)) {
                    require_once($libfile);
                    $reportfunction = $report.'_report_extend_navigation';
                    if (function_exists($report.'_report_extend_navigation')) {
                        $reportfunction($reportnav, $course, $coursecontext);
                    }
                }
            }

            $reports = get_plugin_list_with_function('report', 'extend_navigation_course', 'lib.php');
            foreach ($reports as $reportfunction) {
                $reportfunction($reportnav, $this->page->course, $coursecontext);
            }
            $this->add($reportnav);
        }

        // Check if we can view the gradebook's setup page.
        if ($adminoptions->gradebook) {
            $gradenav = navigation_node::create(get_string('grades'),
                null,
                navigation_node::TYPE_COURSE,
                null,
                'coursegrades'
            );
            if ($gradereports = \grade_helper::get_plugins_reports($this->page->course->id)) {
                foreach ($gradereports as $gradereport) {
                    $gradenode = navigation_node::create($gradereport->string,
                        $gradereport->link,
                        navigation_node::TYPE_COURSE,
                        '',
                        $gradereport->id
                    );
                    $gradenav->add_node($gradenode);
                }
            }
            $this->add($gradenav);
        }

        // Other Plugins
        // Note: other existing plugins rely on the core navigation structure so they are
        // no good for this custom secondary_navigation object.
        $pluginnav = navigation_node::create('Plugins',
            null,
            navigation_node::TYPE_COURSE,
            null,
            'pluginnav'
        );
        // Let plugins hook into course navigation.
        $pluginsfunction = get_plugins_with_function('extend_navigation_course', 'lib.php');
        foreach ($pluginsfunction as $plugintype => $plugins) {
            // Ignore the report plugin as it was already loaded above.
            if ($plugintype == 'report') {
                continue;
            }
            foreach ($plugins as $pluginfunction) {
                $pluginfunction($pluginnav, $this->page->course, $coursecontext);
            }
        }
        $this->add($pluginnav);

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