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
 * Backup
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot .
        '/blocks/campaign_manager/backup/moodle2/backup_campaign_manager_stepslib.php'); // We have structure steps.

/**
 * Specialised backup task for the campaign_manager block
 *
 * @package     block_campaign_manager
 * @category    backup
 */
class backup_campaign_manager_block_task extends backup_block_task {

    /**
     * Defines the block's custom settings for backup.
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Defines the block's custom steps for backup.
     *
     * @return void
     */
    protected function define_my_steps() {
        $this->add_step(new backup_campaign_manager_block_structure_step('campaign_manager_structure', 'campaign_manager.xml'));
    }

    /**
     * Returns the file areas associated with the block.
     *
     * @return array An array of file areas associated with the block.
     */
    public function get_fileareas() {
        return array();
    }

    /**
     * Returns the encoded attributes of the block's config data.
     *
     * @return array An array of encoded attributes of the config data.
     */
    public function get_configdata_encoded_attributes() {
        return array();
    }

    /**
     * Encodes content links for backup.
     *
     * @param string $content The content to encode.
     * @return string The encoded content.
     */
    public static function encode_content_links($content) {
        return $content;
    }
}

