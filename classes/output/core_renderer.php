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
 * Overridden and custom renderers for this theme.
 *
 * @package    theme_nova
 * @copyright  2020 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nova\output;

use \theme_boost\output\core_renderer as boost_core_renderer;
use core_course\external\course_summary_exporter;
use stdClass;
use moodle_url;
use Exception;
use moodle_exception;
use navigation_node;
use flat_navigation_node;

defined('MOODLE_INTERNAL') || die;

class core_renderer extends boost_core_renderer {

    public function loggedin() {
        return isloggedin();
    }

    public function hasdrawer() {
        return isloggedin();
    }

    public function navbar() {
        global $DB, $OUTPUT;

        $template = (object)[];
        $thiscontext = \context::instance_by_id($this->page->context->id);
    }

    /**
     * Wrapper for header elements.
     *
     * @return string HTML to display the main header.
     */
    public function full_header() {

        $thiscontext = \context::instance_by_id($this->page->context->id);
        $pageurl = parse_url($this->page->url, PHP_URL_PATH);

        if ($this->page->include_region_main_settings_in_header_actions() &&
                !$this->page->blocks->is_block_present('settings')) {
            // Only include the region main settings if the page has requested it and it doesn't already have
            // the settings block on it. The region main settings are included in the settings block and
            // duplicating the content causes behat failures.
            $this->page->add_header_action(html_writer::div(
                $this->region_main_settings_menu(),
                'd-print-none',
                ['id' => 'region-main-settings-menu']
            ));
        }

        $header = (object)[];

        // The default variables from core that contruct the page header.
        $header->settingsmenu = $this->context_header_settings_menu();
        $header->contextheader = $this->context_header();
        $header->hasnavbar = empty($this->page->layout_options['nonavbar']);
        $header->navbar = $this->navbar();
        $header->pageheadingbutton = $this->page_heading_button();
        $header->courseheader = $this->course_header();

        // Altered page header when visiting a Module.
        if ($thiscontext->contextlevel == CONTEXT_MODULE) {
            global $DB;
            $header->activitycontext = true;
            if ($cm = $DB->get_record_sql(
                    "SELECT cm.*, md.name AS modname
                                           FROM {course_modules} cm
                                           JOIN {modules} md ON md.id = cm.module
                                           WHERE cm.id = ?",
                    [$thiscontext->instanceid]
            )) {
                $format = course_get_format($this->page->course);
                $course = $format->get_course(); // Needed to have numsections property available.
                $modinfo = get_fast_modinfo($course);
                $header->modname = $cm->modname;
                $module = $modinfo->cms[$cm->id];
                $courseurl = new \moodle_url(
                    '/course/view.php',
                    ['id' => $course->id]
                );
                $contextheader = (object)[];
                $contextheader->pagename = $module->get_formatted_name();
                $contextheader->contextuplink = $courseurl;
                $contextheader->contextupname = $course->fullname;
                $contextheader->icon = $module->get_icon_url();

                $header->secondarynav = false;

                $header->contextheader = $this->render_from_template('theme_nova/contextheader', $contextheader);
            }
        }

        // Higlight the "Contents" dropdown if we are on the course page
        if ($this->page->pagelayout === 'course') {
            $header->incourse = true;
        }

        // Altered page header when visiting a course category page.
        if ($thiscontext->contextlevel == CONTEXT_COURSECAT) {
            $contextheader = (object)[];
            $contextheader->pagename = $this->page->heading;
            // $contextheader->contextuplink = $categoryurl;
            // $contextheader->contextupname = $category->name;
            $contextheader->bgicon = $this->get_generated_image_for_id($thiscontext->instanceid);

            $header->contextheader = $this->render_from_template('theme_nova/contextheader', $contextheader);

        }

        if ($thiscontext->contextlevel == CONTEXT_COURSE && $this->page->course->id == 1) {
            $contextheader = (object)[];
            $contextheader->pagename = $this->page->heading;
            $contextheader->bgicon = $this->get_logo_url();

            $header->contextheader = $this->render_from_template('theme_nova/contextheader', $contextheader);

        // Altered page header when visiting a course.
        } else if ($thiscontext->contextlevel == CONTEXT_COURSE) {

            // Retreive the information about this course to show in the page header
            $header->coursecontext = true;
            $course = $this->page->course;

            // Shown category info as the breadcrumb.
            $category = \core_course_category::get($course->category);
            $categoryurl = new \moodle_url(
                '/course/index.php',
                ['categoryid' => $category->id]
            );
            $exporter = new course_summary_exporter($course, ['context' => $thiscontext]);
            $courseexport = $exporter->export($this);

            // Generate the variables for the context header.
            $contextheader = (object)[];
            $contextheader->pagename = $courseexport->fullname;
            $contextheader->contextuplink = $categoryurl;
            $contextheader->pagelink = new moodle_url('/course/view.php', ['id' => $course->id]);
            $contextheader->contextupname = $category->name;
            $contextheader->bgicon = $courseexport->courseimage;

            $header->secondarynav = new moodle_url('/course/admin.php', ['courseid' => $course->id]);

            $header->contextheader = $this->render_from_template('theme_nova/contextheader', $contextheader);

        }

        return $this->render_from_template('core/full_header', $header);
    }

    public function pageactions() {
        $data = (object)[];
        $data->headeractions = $this->page->get_header_actions();
        $data->pageheadingbutton = $this->page_heading_button();
        return $this->render_from_template('theme_nova/pageactions', $data);
    }

    /**
     * Remove items from the flat navigation menu.
     */
    public function nova_flatnav() {

        $navdrawernav = new \theme_nova\navdrawer_navigation($this->page);
        $navdrawernav->initialise();
        return $navdrawernav;
    }
}
