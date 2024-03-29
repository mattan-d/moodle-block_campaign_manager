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

/**
 * Specialised backup task for the campaign_manager block
 *
 * @package     block_campaign_manager
 * @category    backup
 */
class backup_campaign_manager_block_structure_step extends backup_block_structure_step {

    /**
     *
     * Defines the structure of the block for backup purposes.
     *
     * @return backup_nested_element The root element of the block structure.
     */
    protected function define_structure() {
        global $DB;

        $block = $DB->get_record('block_instances', ['id' => $this->task->get_blockid()]);
        $config = unserialize_object(base64_decode($block->configdata));

        if (!empty($config->campaignid)) {
            $campaignids = $config->campaignid;

            list($insql, $inparams) = $DB->get_in_or_equal($campaignids);

            foreach ($inparams as $key => $value) {
                $inparams[$key] = backup_helper::is_sqlparam($value);
            }
        }

        // Define each element separated.
        $campaignmanager = new backup_nested_element('campaign_manager', ['id'], null);
        $campaigns = new backup_nested_element('campaigns');
        $campaign = new backup_nested_element('campaign', ['id'],
                ['title', 'image', 'description', 'url', 'visible', 'startdate', 'enddate']);

        // Build the tree.
        $campaignmanager->add_child($campaigns);
        $campaigns->add_child($campaign);

        // Define sources.
        $campaignmanager->set_source_array([(object) ['id' => $this->task->get_blockid()]]);

        // Only if there are campaigns.
        if (!empty($config->campaignid)) {
            $campaign->set_source_sql("
                SELECT *
                  FROM {block_campaign_manager}
                 WHERE id $insql", $inparams);
        }

        // Annotations (none)
        // Return the root element (campaign_manager), wrapped into standard block structure.
        return $this->prepare_block_structure($campaignmanager);
    }
}
