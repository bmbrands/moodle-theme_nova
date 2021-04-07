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
 * Add section details to course links.
 *
 * @package    theme_nova
 * @copyright  2020 Simon Lewis (simon.lewis@motorpilot.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery'], function($) {

    return {
        init: function(sectionnum, useanchor) {
            var sectionifycourselinks = function(sectionnum) {
                $('#page-navbar .breadcrumb, #page-content div[role="main"]')
                    .find("a[href*='course/view']")
                    .each(function(i, link) {
                        // Can't do this easily if anchor exists.
                        if (!link.href.match('#')) {
                            if (useanchor) {
                                link.href += '#section-' + sectionnum;
                            } else if (!link.href.match('section')) {
                                link.href += '&section=' + sectionnum;
                            }
                        }
                });
            };
            var sectionifyforms = function(sectionnum) {
                $("form[action*='course/view']").each(function() {
                    if (!useanchor && $(this).find("input[name='section']").length === 0) {
                        $(this).append('<input type="hidden" name="section" value="' + sectionnum + '" />');
                    } else if (useanchor) {
                        var action = $(this).attr('action');
                        $(this).attr('action', action + '#section-' + sectionnum);
                    }
                });
            };

            sectionifycourselinks(sectionnum);
            sectionifyforms(sectionnum);
        }
    };
});