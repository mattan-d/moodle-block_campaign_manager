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
 * Settings for the Campaign Manager block.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configtext('block_campaign_manager_num_entries',
            get_string('numentries', 'block_campaign_manager'),
            get_string('clientnumentries', 'block_campaign_manager'), 5, PARAM_INT));

    $link = '<a href="' . $CFG->wwwroot . '/blocks/campaign_manager/managecampaigns.php">' .
            get_string('campaignsaddedit', 'block_campaign_manager') . '</a>';
    $settings->add(new admin_setting_heading('block_addheading', '', $link));
}
