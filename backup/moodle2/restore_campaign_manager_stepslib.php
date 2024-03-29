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

/**
 * Define all the restore steps that wll be used by the restore_campaign_manager_block_task
 */

/**
 * Define the complete campaign_manager  structure for restore
 */
class restore_campaign_manager_block_structure_step extends restore_structure_step {

    /**
     * Defines the structure of the block for restore purposes.
     *
     * @return array An array of restore path elements defining the structure.
     */
    protected function define_structure() {

        $paths = [];

        $paths[] = new restore_path_element('block', '/block', true);
        $paths[] = new restore_path_element('campaign_manager', '/block/campaign_manager');
        $paths[] = new restore_path_element('campaign', '/block/campaign_manager/campaigns/campaign');

        return $paths;
    }

    /**
     * Processes the block data during restore.
     *
     * @param object $data The block data to process.
     * @return void
     */
    public function process_block($data) {
        global $DB;

        $data = (object) $data;
        $campaignsarr = []; // To accumulate campaigns.

        if (!$this->task->get_blockid()) {
            return;
        }
    }
}
