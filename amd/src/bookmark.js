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
 * Browsercheck
 *
 * @package    theme_nova
 * @copyright  2020 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(
[
    'jquery',
    'block_myoverview/repository',
    'core/custom_interaction_events',
    'core/notification',
    'core/ajax',
    'core/toast',
    'core/str'
],
function(
    $,
    Repository,
    CustomEvents,
    Notification,
    Ajax,
    Toast,
    Str
) {

    var SELECTORS = {
        ACTION_ADD_BOOKMARK: '[data-action="add-bookmark"]',
        ACTION_REMOVE_BOOKMARK: '[data-action="remove-bookmark"]',
    };

    /**
     * Set the favourite / bookmark state on a list of courses.
     *
     * Valid args are:
     * Array courses  list of course id numbers.
     *
     * @param {Object} args Arguments send to the webservice.
     * @return {Promise} Resolve with warnings.
     */
    var setBookmarkCourses = function(args) {

        var request = {
            methodname: 'core_course_set_favourite_courses',
            args: args
        };

        var promise = Ajax.call([request])[0];

        return promise;
    };

    /**
     * Set the courses bookmark status and push to repository
     *
     * @param {object} root The root element for the bookmark widget.
     * @param {Number} courseId Course id to favourite.
     * @param {Bool} status new favourite status.
     * @return {Promise} Repository promise.
     */
    var setCourseBookmarkState = function(root, courseId, status) {
        var string = 'bookmarkadded';
        if (!status) {
            string = 'bookmarkremoved';
        }
        return setBookmarkCourses({
            courses: [
                    {
                        'id': courseId,
                        'favourite': status
                    }
                ]
        }).then(function(result) {
            if (result.warnings.length == 0) {
                Str.get_string(string, 'theme_nova').then(function(str) {
                    Toast.add(str);
                });
                showBookmarkIcon(root, status);
            } else {
                Notification.alert('Bookmarking course failed', 'Could not change bookmark state');
            }
        }).catch(Notification.exception);
    };

    /**
     * Show the add or remove bookmark icon
     *
     * @param {object} root The root element for the bookmark widget.
     * @param {Bool} status true for remove bookmark.
     */
    var showBookmarkIcon = function(root, status) {
        if (status) {
            root.find(SELECTORS.ACTION_REMOVE_BOOKMARK).removeClass('d-none');
            root.find(SELECTORS.ACTION_ADD_BOOKMARK).addClass('d-none');
        } else {
            root.find(SELECTORS.ACTION_REMOVE_BOOKMARK).addClass('d-none');
            root.find(SELECTORS.ACTION_ADD_BOOKMARK).removeClass('d-none');
        }
    };

    /**
     * Init JS and run eventlisteners
     *
     * @param {object} root The root element for the bookmark widget.
     */
    var init = function(root) {
        root = $(root);
        var courseId = root.attr('data-course-id');

        root.on(CustomEvents.events.activate, SELECTORS.ACTION_ADD_BOOKMARK, function(e, data) {
            setCourseBookmarkState(root, courseId, true);
            data.originalEvent.preventDefault();
        });

        root.on(CustomEvents.events.activate, SELECTORS.ACTION_REMOVE_BOOKMARK, function(e, data) {
            setCourseBookmarkState(root, courseId, false);
            data.originalEvent.preventDefault();
        });
    };

    return {
        init: init
    };
});