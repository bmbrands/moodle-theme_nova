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
    'jquery',
    'theme_nova/repository',
    'core/templates',
],
function(
    $,
    Repository,
    Templates
) {

    var SELECTORS = {
        FOOTER_CONTENT: '[data-region="customfooter-content"]'
    };

    var TEMPLATES = {
        FOOTER_CONTENT_CONTACT: 'theme_nova/footer_content_contact'
    };

    /**
     * Listen to, and handle events for the nova theme
     *
     * @param {Object} root The event nodes container element.
     */
    var setContextFooterContent = function(root) {
        var contextid = root.attr('data-contextid');
        var appraisal = root.attr('data-appraisal');
        if (contextid >= 1) {
            Repository.getFooterContent({
                contextid: contextid,
                appraisal: appraisal
            }).then(function(result) {
                var content = root.find(SELECTORS.FOOTER_CONTENT);

                Templates.render(TEMPLATES.FOOTER_CONTENT_CONTACT, {contacts: result.contacts, content: result.content})
                    .then(function(html) {
                        content.html(html);
                    });
            });
        }
    };

    /**
     * Intialise the footer.
     *
     * @param {object} root The root element containing the timezone modal.
     */
    var init = function(root) {
        root = $(root);

        if (!root.attr('data-init-footer')) {
            setContextFooterContent(root);
            root.attr('data-init-footer', true);
        }
    };

    return {
        init: init
    };
});