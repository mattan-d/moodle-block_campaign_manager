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
 * @package   block_campaign_manager
 * @subpackage backup-moodle2
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .
        '/blocks/campaign_manager/backup/moodle2/restore_campaign_manager_stepslib.php'); // We have structure steps.

/**
 * Specialised restore task for the campaign_manager block
 * (has own DB structures to backup)
 *
 * TODO: Finish phpdocs
 */
class restore_campaign_manager_block_task extends restore_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
        // Campaign_manager has one structure step.
        $this->add_step(new restore_campaign_manager_block_structure_step('campaign_manager_structure', 'campaign_manager.xml'));
    }

    public function get_fileareas() {
        return array(); // No associated fileareas.
    }

    public function get_configdata_encoded_attributes() {
        return array(); // No special handling of configdata.
    }

    public static function define_decode_contents() {
        return array();
    }

    public static function define_decode_rules() {
        return array();
    }
}

