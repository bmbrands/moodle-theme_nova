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
 * Overridden fontawesome icons.
 *
 * @package     theme_nova
 * @copyright   2019 Moodle
 * @author      Bas Brands <bas@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_nova\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Class overriding some of the Moodle default FontAwesome icons.
 *
 *
 * @package    theme_nova
 * @copyright  2019 Moodle
 * @author     Bas Brands <bas@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class icon_system_fontawesome extends \core\output\icon_system_fontawesome {

    /**
     * @var array $map Cached map of moodle icon names to font awesome icon names.
     */
    private $map = [];


    /**
     * Change the core icon map
     * @return Array replaced icons.
     */
    public function get_core_icon_map() {
        $iconmap = parent::get_core_icon_map();

        $iconmap['theme:a/film'] = 'fa-film';
        $iconmap['theme:a/appraisal'] = 'fa-comment-alt';
        $iconmap['theme:a/catalogue'] = 'fa-book-open';
        $iconmap['theme:a/calendar'] = 'fa-calendar-alt';
        $iconmap['theme:a/accordion'] = 'fa-book-open';
        $iconmap['theme:a/learningpath'] = 'fa-chart-line';
        $iconmap['theme:a/envelope'] = 'fa-envelope';
        $iconmap['theme:a/reportbuilder'] = 'fa-chart-pie';
        $iconmap['theme:a/phone'] = 'fa-phone';
        $iconmap['theme:a/clock'] = 'fa-clock';
        $iconmap['theme:t/sort_by'] = 'fa-sort-amount-asc';
        $iconmap['theme:a/help'] = 'fa-question-circle';
        $iconmap['theme:fp/cross'] = 'fa-times';
        $iconmap['theme:fp/refresh'] = 'fa-sync';
        $iconmap['theme:a/bookmark'] = 'fa-bookmark';
        $iconmap['theme:a/removebookmark'] = 'fas fa-bookmark';
        $iconmap['theme:a/up'] = 'fa-chevron-double-up';
        $iconmap['theme:a/right'] = 'fa-chevron-double-right';
        $iconmap['core:a/refresh'] = 'fa-sync';
        $iconmap['core:a/add_file'] = 'fa-file';
        $iconmap['core:a/create_folder'] = 'fa-folder';
        $iconmap['core:b/document-new'] = 'fa-file';
        $iconmap['core:b/edit-copy'] = 'fa-file';
        $iconmap['core:e/emoticons'] = 'fa-smile';
        $iconmap['core:e/anchor'] = 'fa-link';
        $iconmap['core:e/cut'] = 'fa-cut';
        $iconmap['core:e/file-text'] = 'fa-file-alt';
        $iconmap['core:e/remove_page_break'] = 'fa-times';
        $iconmap['core:e/insert_edit_image'] = 'fa-images';
        $iconmap['core:e/insert_edit_video'] = 'fa-file-video';
        $iconmap['core:i/customfield'] = 'fa-hand-point-right';
        $iconmap['core:i/emojicategoryactivities'] = 'fa-futbol';
        $iconmap['core:i/emojicategoryfooddrink'] = 'fa-utensils';
        $iconmap['core:i/emojicategoryobjects'] = 'fa-lightbulb';
        $iconmap['core:i/emojicategoryrecent'] = 'fa-clock';
        $iconmap['core:i/emojicategorysmileyspeople'] = 'fa-smile';
        $iconmap['core:i/grade_incorrect'] = 'fa-times text-danger';
        $iconmap['core:i/gradingnotifications'] = 'fa-bell';
        $iconmap['core:i/groupevent'] = 'fa-users';
        $iconmap['core:i/loading'] = 'fa-circle-notch fa-spin';
        $iconmap['core:i/loading_small'] = 'fa-circle-notch fa-spin';
        $iconmap['atto_recordrtc:i/videortc'] = 'fa-video';
        $iconmap['core:e/insert_nonbreaking_space'] = 'fa-square';
        $iconmap['core:e/insert_time'] = 'fa-clock';
        $iconmap['core:e/manage_files'] = 'fa-file';
        $iconmap['core:e/new_document'] = 'fa-file';
        $iconmap['core:i/messagecontentmultimediageneral'] = 'fa-file-video';
        $iconmap['core:i/permissions'] = 'fa-pencil';
        $iconmap['core:i/payment'] = 'fa-money-bill';
        $iconmap['core:i/reload'] = 'fa-sync';
        $iconmap['core:i/report'] = 'fa-chart-bar';
        $iconmap['core:i/star'] = 'fas fa-star';
        $iconmap['core:i/star-o'] = 'fa-star';
        $iconmap['core:t/emptystar'] = 'fa-star';
        $iconmap['core:t/reload'] = 'fa-sync';
        $iconmap['core:t/unblock'] = 'fa-comment';
        $iconmap['theme:t/sort_by'] = 'fa-sort-amount-down';
        $iconmap['core:m/USD'] = 'fa-dollar-sign';
        $iconmap['core:i/stats'] = 'fa-chart-bar';
        $iconmap['core:i/uncheckedcircle'] = 'fa-circle';
        $iconmap['core:i/ne_red_mark'] = 'fa-times';
        $iconmap['core:e/save'] = 'fa-save';
        $iconmap['core:e/special_character'] = 'fa-keyboard';
        $iconmap['core:e/text_highlight_picker'] = 'fa-lightbulb';
        $iconmap['core:e/text_highlight'] = 'fa-lightbulb';
        $iconmap['theme:fp/add_file'] = 'fa-file';
        $iconmap['theme:fp/create_folder'] = 'fa-folder';
        $iconmap['core:i/backup'] = 'fa-archive';
        $iconmap['core:i/calendareventtime'] = 'fa-clock';
        $iconmap['core:i/competencies'] = 'fa-check-square';
        $iconmap['core:i/completion_self'] = 'fa-user';
        $iconmap['core:i/duration'] = 'fa-clock';
        $iconmap['core:i/groupv'] = 'fa-user-circle';
        $iconmap['core:i/manual_item'] = 'fa-square';
        $iconmap['core:i/marker'] = 'fa-circle';
        $iconmap['core:i/news'] = 'fa-newspaper';
        $iconmap['core:i/nosubcat'] = 'fa-plus-square';
        $iconmap['core:i/privatefiles'] = 'fa-file';
        $iconmap['core:i/repository'] = 'fa-hdd';
        $iconmap['core:i/scheduled'] = 'fa-calendar-check';
        $iconmap['core:i/section'] = 'fa-folder';
        $iconmap['core:i/unchecked'] = 'fa-square';
        $iconmap['core:i/unflagged'] = 'fa-flag';
        $iconmap['core:t/collapsed_empty_rtl'] = 'fa-plus-square';
        $iconmap['core:t/collapsed_empty'] = 'fa-plus-square';
        $iconmap['core:t/email'] = 'fa-envelope';
        $iconmap['core:t/groups'] = 'fa-user-friends';
        $iconmap['core:t/groupv'] = 'fa-user-circle';
        $iconmap['core:t/switch_whole'] = 'fa-square';
        $iconmap['core:e/remove_link'] = 'fa-unlink';
        $iconmap['core:i/next'] = 'fa-chevron-right';
        $iconmap['core:i/previous'] = 'fa-chevron-left';
        $iconmap['core:t/sort_by'] = 'fa-sort-amount-up';
        $iconmap['core:t/sort_asc'] = 'fa-sort-up';
        $iconmap['core:t/sort_desc'] = 'fa-sort-down';

        return $iconmap;
    }
}