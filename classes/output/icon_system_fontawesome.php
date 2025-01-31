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
 * @package    theme_nova
 * @copyright  2020 Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace theme_nova\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Class overriding some of the Moodle default FontAwesome icons.
 *
 *
 * @package    theme_nova
 * @copyright  Bas Brands <bas@sonsbeekmedia.nl>
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

        // Use this array to replace icons
        $corereplacements = [
        ];

        return array_merge($iconmap, $corereplacements);
    }
}