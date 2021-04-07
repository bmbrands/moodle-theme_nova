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
 * Global theme elements.
 *
 * @package   theme_nova
 * @copyright 2018 Moodle
 * @author    Bas Brands
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_nova;

defined('MOODLE_INTERNAL') || die();

use stdClass;
use moodle_url;
use Exception;
use moodle_exception;
use navigation_node;
use flat_navigation_node;

class nova {

    /**
     * Remove items from the flat navigation menu.
     */
    public function removenav() {
        global $PAGE, $CFG;
        $flatnav = $PAGE->flatnav;

        // Initialise variables.
        $in = $hasappraisal = $appraisal
            = $hascatalogue = $catalogue
            = $hasaccordion = $accordion
            = $haslearningpath = $learningpath
            = $hasreportbuilder = $reportbuilder
            = false;

        // Create the Appraisal nav item.
        $appraisalfile = $CFG->dirroot . '/local/onlineappraisal/version.php';
        if (file_exists($appraisalfile)) {
            $hasappraisal = true;
        }
        if ($hasappraisal) {
            $aurl = new moodle_url('/local/onlineappraisal/index.php');
            $anav = navigation_node::create(get_string('pluginname', 'local_onlineappraisal'), $aurl);
            $appraisal = new flat_navigation_node($anav, 0);
            $appraisal->key = 'appraisal';
            $appraisal->icon->pix = 'a/appraisal';
            $appraisal->icon->component = 'theme';
            if (preg_match('/page-local-onlineappraisal(.*)/', $PAGE->bodyid)) {
                $in = $appraisal;
            }
        }

        // Create the Catalogue nav item.
        $cataloguefile = $CFG->dirroot . '/local/catalogue/version.php';
        if (file_exists($cataloguefile)) {
            $hascatalogue = true;
        }
        if ($hascatalogue) {
            $curl = new moodle_url('/local/catalogue/index.php');
            $cnav = navigation_node::create(get_string('pluginname', 'local_catalogue'), $curl);
            $catalogue = new flat_navigation_node($cnav, 0);
            $catalogue->key = 'catalogue';
            $catalogue->icon->pix = 'a/catalogue';
            $catalogue->icon->component = 'theme';
            if (preg_match('/page-local-catalogue(.*)/', $PAGE->bodyid)) {
                $in = $catalogue;
            }
        }

        // Create the Accordion nav item.
        $accordionfile = $CFG->dirroot . '/local/accordion/version.php';
        if (file_exists($accordionfile)) {
            $hasaccordion = true;
        }
        if ($hasaccordion) {
            $curl = new moodle_url('/local/accordion/index.php',
                ['catalogue' => 'accordion']);
            $cnav = navigation_node::create(get_string('pluginname', 'local_accordion'), $curl);
            $accordion = new flat_navigation_node($cnav, 0);
            $accordion->key = 'accordion';
            $accordion->icon->pix = 'a/accordion';
            $accordion->icon->component = 'theme';
            if (preg_match('/page-course-index/', $PAGE->bodyid)) {
                $in = $accordion;
            }
        }

        // Create the Learning Path nav item.
        $learningpathfile = $CFG->dirroot . '/local/wa_learning_path/version.php';
        if (file_exists($learningpathfile)) {
            $haslearningpath = true;
        }
        if ($haslearningpath) {
            $curl = new moodle_url('/local/wa_learning_path/index.php',
                ['c' => 'learning_path', 'mode' => 'tiles']);
            $cnav = navigation_node::create(get_string('menu_plugin_navigation', 'local_wa_learning_path'), $curl);
            $learningpath = new flat_navigation_node($cnav, 0);
            $learningpath->key = 'learningpath';
            $learningpath->icon->pix = 'a/learningpath';
            $learningpath->icon->component = 'theme';
            if (preg_match('/page-local-wa_learning_path(.*)/', $PAGE->bodyid)) {
                $in = $learningpath;
            }
        }

        // Create the Reportbuilder nav item.
        $reportbuilderfile = $CFG->dirroot . '/local/reportbuilder/version.php';
        if (file_exists($reportbuilderfile)) {
            $hasreportbuilder = true;
        }
        if ($hasreportbuilder) {
            $curl = new moodle_url('/local/reportbuilder/myreports.php');
            $cnav = navigation_node::create(get_string('reports', 'core'), $curl);
            $reportbuilder = new flat_navigation_node($cnav, 0);
            $reportbuilder->key = 'reportbuilder';
            $reportbuilder->icon->pix = 'a/reportbuilder';
            $reportbuilder->icon->component = 'theme';
            if (preg_match('/page-local-reportbuilder(.*)/', $PAGE->bodyid)) {
                $in = $reportbuilder;
            }
        }

        // Create the Help nav item.
        $firstaction = false;
            $hurl = new moodle_url('/mod/book/view.php', ['id' => 539]);
            $hnav = navigation_node::create(get_string('help', 'core'), $hurl);
            $help = new flat_navigation_node($hnav, 0);
            $help->key = 'help';
            $help->icon->pix = 'a/help';
            $help->icon->component = 'theme';

        // Fetch the navitems from the current navigation object.
        $home = $myhome = $course = $settings = $calendar = $participants = $addblock = false;
        foreach ($flatnav as $action) {
            if ($action->key == 'home') {
                $home = $action;
            }
            if ($action->key == 'myhome') {
                $myhome = $action;
            }
            if ($action->key == 'coursehome') {
                $course = $action;
            }
            if ($action->key == 'sitesettings') {
                $settings = $action;
            }
            if ($action->key == 'calendar') {
                $calendarurl = $action->action();
                $calendarurl->params(['view' => 'upcoming']);
                $calnav = navigation_node::create(get_string('calendar', 'calendar'), $calendarurl);
                $calendar = new flat_navigation_node($calnav, 0);
                $calendar->key = 'calendar';
                $calendar->icon->pix = 'a/calendar';
                $calendar->icon->component = 'theme';
            }
            if ($action->key == 'participants') {
                $participants = $action;
            }
            if ($action->key == 'addblock') {
                $addblock = $action;
            }
        }

        // Always add home as the first link.
        $keys = $flatnav->get_key_list();

        // Modify this array to change the available navitems
        $newnavtop = [$myhome, $accordion, $learningpath, $reportbuilder, $calendar, $appraisal, $help];
        $newnavbottom = [$course, $participants, $settings];

        // Empty the nav object.
        foreach ($keys as $key) {
             $flatnav->remove($key);
        }
        foreach ($newnavtop as $navitem) {
            if ($navitem) {
                $navitem->set_showdivider(false);
                $flatnav->add($navitem);
            }
        }

        $first = true;
        foreach ($newnavbottom as $navitem) {
            if ($navitem) {
                $navitem->set_showdivider(false);
                if ($first) {
                    $navitem->set_showdivider(true, 'first');
                }
                $first = false;
                $flatnav->add($navitem);
            }
        }

        if ($addblock) {
            $addblock->set_showdivider(true, 'first');
            $flatnav->add($addblock);
        }

        if ($PAGE->pagelayout == 'admin' && $action->key == 'sitesettings') {
            $settings->make_active();
        }
        if ($in) {
            $in->make_active();
        }
    }

    /**
     * Theme nova image renderer
     *
     * @param string $type the image type
     * @param int $imageid the image imageid (course id or categori id)
     * @param string $fallbackimage the default image url
     */
    public static function imageeditable($type, $imageid, $fallbackimage = null) {
        global $PAGE, $OUTPUT, $USER;

        if ($type !== 'catalogue' && $type !== 'course') {
            return false;
        }

        if (!$fallbackimage) {
            $fallbackimage = $OUTPUT->image_url('placeholder', 'theme_nova')->out();
        }

        $image = (object) [];
        $image->imageid = $imageid;
        if ($type == 'course') {
            $image->contextid = \context_course::instance($imageid)->id;
        }
        if ($type == 'catalogue') {
            $image->contextid = \context_coursecat::instance($imageid)->id;
        }
        $image->type = $type;

        $croppedimage = self::novaimage_url($image->contextid, $type . '_cropped');
        $fullimage = self::novaimage_url($image->contextid, $type);

        $image->defaultimage = $fallbackimage;
        $image->image = $fullimage;
        $image->originalimage = $fullimage;

        if ($croppedimage) {
            $image->image = $croppedimage;
        }

        if ($PAGE->user_allowed_editing() && isset($USER->editing) && $USER->editing == 1) {
            $image->allowcrop = true;
            $image->allowupload = true;
        }

        return $OUTPUT->render_from_template('theme_nova/imagehandler', $image);
    }

    /**
     * Get the image url for the novaimage
     *
     * @param int $contextid The image contextid
     * @param string $filearea The image filearea
     */
    public static function novaimage_url($contextid, $filearea) {
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, "theme_nova", $filearea, 0, "itemid, filepath, filename", false);
        if (!$files) {
            return false;
        }
        if (count($files) > 1) {
            // Note this is a coding exception and not a moodle exception because there should never be more than one
            // file in this area, where as the course summary files area can in some circumstances have more than on file.
            throw new \coding_exception('Multiple files found in filearea (context '.$contextid.')');
        }
        $file = (end($files));

        return \moodle_url::make_pluginfile_url(
            $file->get_contextid(),
            $file->get_component(),
            $file->get_filearea(),
            $file->get_timemodified(), // Used as a cache buster.
            $file->get_filepath(),
            $file->get_filename()
        );
    }

    /**
     * Fetch the theme image for a filearea.
     *
     * @param string $filearea Filearea to search
     * @param int $contextid
     */
    public static function image($filearea, $contextid) {

        $croppedimage = self::novaimage_url($contextid, $filearea . '_cropped');
        if ($croppedimage) {
            return $croppedimage;
        } else {
            $context = \context::instance_by_id($contextid);
            self::autocropimage($context, false);
            return self::novaimage_url($contextid, $filearea . '_cropped');
        }

        $originalimage = self::novaimage_url($contextid, $filearea);
        if ($originalimage) {
            return $originalimage;
        };

        return '';
    }

    /**
     * Automatically crop the image for a context.
     *
     * @param \context $context
     * @return void
     */
    public static function autocropimage($context, $changeratio = true) {
        global $CFG;
        require_once($CFG->libdir . '/gdlib.php');

        if ($context->contextlevel === CONTEXT_COURSECAT) {
            $type = 'category';
        } else {
            $type = 'course';
        }

        $fs = get_file_storage();
        $files = $fs->get_area_files($context->id, "theme_nova", $type, 0, "itemid, filepath, filename", false);
        if (!$files) {
            return false;
        }
        if (count($files) > 1) {
            // Note this is a coding exception and not a moodle exception because there should never be more than one
            // file in this area, where as the course summary files area can in some circumstances have more than on file.
            throw new \coding_exception('Multiple files found in filearea (context '.$context->id.')');
        }
        $file = (end($files));

        $tempimage = $file->copy_content_to_temp();

        if (!is_file($tempimage)) {
            return;
        }

        $imageinfo = getimagesize($tempimage);

        if (empty($imageinfo)) {
            return;
        }

        $desiredaspectratio = 9 / 16; // Widescreen.

        $image = new stdClass();
        $image->width = $imageinfo[0];
        $image->height = $imageinfo[1];
        $image->aspectratio = $image->height / $image->width;
        $image->type = $imageinfo[2];

        $image->newwidth = 800;
        $image->heightstart = 0;
        $image->cropheight = $image->height;
        if ($image->aspectratio > $desiredaspectratio && $changeratio) { // Will need cropping.
            $image->newheight = $image->newwidth * $desiredaspectratio;
            $image->cropheight = round($image->width * $desiredaspectratio);
            $image->heightstart = round(($image->height - $image->cropheight) / 2);
        } else {
            $image->newheight = $image->newwidth * $image->aspectratio;
        }

        switch ($image->type) {
            case IMAGETYPE_GIF:
                if (function_exists('imagecreatefromgif')) {
                    $im = imagecreatefromgif($tempimage);
                } else {
                    return;
                }
                break;
            case IMAGETYPE_JPEG:
                if (function_exists('imagecreatefromjpeg')) {
                    $im = imagecreatefromjpeg($tempimage);
                } else {
                    return;
                }
                break;
            case IMAGETYPE_PNG:
                if (function_exists('imagecreatefrompng')) {
                    $im = imagecreatefrompng($tempimage);
                } else {
                    return;
                }
                break;
            default:
                // Won't process, just save.
                return;
        }

        if (function_exists('imagejpeg')) {
            $imagefnc = 'imagejpeg';
            $imageext = '.jpg';
            $filters = null; // Not used.
            $quality = 60;
        } else {
            // Can't process, just save.
            return;
        }

        $im1 = imagecreatetruecolor($image->newwidth, $image->newheight);

        imagecopybicubic($im1, $im, 0, 0, 0, $image->heightstart, $image->newwidth, $image->newheight, $image->width,
                $image->cropheight);

        ob_start();
        if (!$imagefnc($im1, null, $quality, $filters)) {
            ob_end_clean();
            return;
        }
        $data = ob_get_clean();
        imagedestroy($im1);

        \theme_nova\external::setcoverimage($context, 'course', base64_encode($data), $file->get_filename(), true);

        @unlink($tempimage);
    }

    /**
     * Sectionifies links back to course.
     * @return void
     */
    public function backtocourse() {
        global $COURSE, $PAGE;
        if (empty($PAGE->cm->sectionnum)) {
            // No need to do anything.
            return;
        }
        $viewurl = course_get_format($COURSE)->get_view_url($PAGE->cm->sectionnum);
        if (!empty($viewurl)) {
            $useanchor = $viewurl->get_param('section') ? false : true;
            $PAGE->requires->js_call_amd('theme_nova/backtocourse', 'init', [$PAGE->cm->sectionnum, $useanchor]);
        }

    }
}