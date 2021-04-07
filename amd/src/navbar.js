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
 * Arup Search
 *
 * @package    theme_nova
 * @copyright  2019 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(
[
    'jquery'
],
function(
    $,
) {

    var SELECTORS = {
        NAVBAR: '.navbar.fixed-top',
    };

    var previousScroll = 0;

    var currentScroll = 0;

    var hideNav = function() {
        $(SELECTORS.NAVBAR).addClass("is-hidden");
    };

    var showNav = function() {
        $(SELECTORS.NAVBAR).removeClass("is-hidden");
    };

    var watchScroll = function() {
        $(window).scroll(function(){
            currentScroll = $(this).scrollTop();
            /*
            If the current scroll position is greater than 0 (the top) AND the current scroll position is
            less than the document height minus the window height (the bottom) run the navigation if/else statement.
            */
            if (currentScroll > 80 && currentScroll < $(document).height() - $(window).height()){

                /*
                  If the current scroll is greater than the previous scroll (i.e we're scrolling down the page), hide the nav.
                  Else we are scrolling up (i.e the previous scroll is greater than the current scroll), so show the nav.
                */
                if (currentScroll > previousScroll) {
                    window.setTimeout(hideNav, 300);
                } else {
                    window.setTimeout(showNav, 300);
                }
                /*
                  Set the previous scroll value equal to the current scroll.
                */
                previousScroll = currentScroll;
            }

        });
    };
    /**
     * Intialise the search modal.
     *
     * @param {object} root The root element containing the timezone modal.
     */
    var init = function(root) {

        if (!root.attr('data-init-scroll')) {
            watchScroll(root);
            root.attr('data-init-scroll', true);
        }
    };

    return {
        init: init
    };
});