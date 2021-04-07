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
 * Event observers for theme nova.
 *
 * @package    theme_nova
 * @copyright  2020 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nova;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/filestorage/file_storage.php");

/**
 * Event observer for theme_nova.
 */
class observer {

    /**
     * Observer for \core\event\course_created event.
     *
     * @param \core\event\course_created $event
     * @return void
     */
    public static function course_created(\core\event\course_created $event) {
        $course = $event->get_record_snapshot('course', $event->objectid);
        $courseimage = self::get_course_image($course);
        if ($courseimage) {
            self::create_advert_image($courseimage);
        }
    }

    /**
     * Get the course image file object.
     *
     * @param object $course
     * @return stored_file instance of course image file.
     */
    public static function get_course_image($course) {
        global $CFG;
        $courseinlist = new \core_course_list_element($course);
        foreach ($courseinlist->get_course_overviewfiles() as $file) {
            if ($file->is_valid_image()) {
                $pathcomponents = [
                    '/pluginfile.php',
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea() . $file->get_filepath() . $file->get_filename()
                ];
                $path = implode('/', $pathcomponents);
                return $file;
            }
        }
        return false;
    }

    /**
     * Get the advert image file object.
     *
     * @param object $course
     * @return stored_file instance of advert image file.
     */
    public static function get_advert_image($course) {
        $context = \context_course::instance($course->id);
        $fs = get_file_storage();

        $files = $fs->get_area_files($context->id, "theme_nova", 'course', 0, "itemid, filepath, filename", false);
        if (!$files) {
            return false;
        }

        return (end($files));
    }

    /**
     * Overwrite the advert image.
     *
     * @param object $courseimage
     * @param object $advertimage || false
     * @return stored_file instance of newly created file
     */
    public static function create_advert_image($courseimage, $advertimage = false) {
        $fs = get_file_storage();

        $contextid = $courseimage->get_contextid();

        if ($advertimage) {
            // First delete the old advert images
            $fs->delete_area_files($contextid, 'theme_nova', 'course', 0);
            $fs->delete_area_files($contextid, 'theme_nova', 'course_cropped', 0);
        }

        $newadvertimage = [
            'contextid' => $contextid,
            'component' => 'theme_nova',
            'filearea' => 'course',
            'itemid' => 0,
            'filepath' => '/',
            'filename' => $courseimage->get_filename()
        ];

        return $fs->create_file_from_storedfile($newadvertimage, $courseimage);
    }

    /**
     * Observer for \core\event\course_updated event.
     *
     * @param \core\event\course_updated $event
     * @return void
     */
    public static function course_updated(\core\event\course_updated $event) {

        $course = $event->get_record_snapshot('course', $event->objectid);
        $courseimage = self::get_course_image($course);
        $advertimage = self::get_advert_image($course);

        if ($courseimage && $advertimage) {
            // Only overwrite the advert image if it is older than the courseimage.
            if ($courseimage->get_timemodified() > $advertimage->get_timemodified()) {
                self::create_advert_image($courseimage, $advertimage);
            }
        }
        if ($courseimage && !$advertimage) {
            self::create_advert_image($courseimage, false);
        }

        return true;
    }
}
