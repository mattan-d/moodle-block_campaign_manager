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
 * Restore
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .
        '/blocks/campaign_manager/backup/moodle2/restore_campaign_manager_stepslib.php'); // We have structure steps.

/**
 * Restore
 *
 * @package     block_campaign_manager
 * @category    backup
 */
class restore_campaign_manager_block_task extends restore_block_task {

    /**
     * Defines the block's custom settings.
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Defines the block's custom steps.
     *
     * @return void
     */
    protected function define_my_steps() {
        // Campaign_manager has one structure step.
        $this->add_step(new restore_campaign_manager_block_structure_step('campaign_manager_structure', 'campaign_manager.xml'));
    }

    /**
     * Returns the file areas associated with the block.
     *
     * @return array An array of file areas associated with the block.
     */
    public function get_fileareas() {
        return []; // No associated file areas.
    }

    /**
     * Returns the encoded attributes of the block's config data.
     *
     * @return array An array of encoded attributes of the config data.
     */
    public function get_configdata_encoded_attributes() {
        return []; // No special handling of config data.
    }

    /**
     * Defines the decoding contents for the block.
     *
     * @return array An array defining the decoding contents.
     */
    public static function define_decode_contents() {
        return [];
    }

    /**
     * Defines the decoding rules for the block.
     *
     * @return array An array defining the decoding rules.
     */
    public static function define_decode_rules() {
        return [];
    }
}

