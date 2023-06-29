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
 * Provides support for the conversion of moodle1 backup to the moodle2 format
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Backup
 *
 * @package     block_campaign_manager
 * @category    backup
 */
class moodle1_block_campaign_manager_handler extends moodle1_block_handler {

    /**
     *
     * Processes the block data during backup.
     * @param array $data The block data to process.
     * @return array The processed block data.
     */
    public function process_block(array $data) {
        parent::process_block($data);
        $instanceid = $data['id'];
        $contextid = $this->converter->get_contextid(CONTEXT_BLOCK, $data['id']);

        $this->open_xml_writer("course/blocks/{$data['name']}_{$instanceid}/campaign_manager.xml");
        $this->xmlwriter->begin_tag('block',
                array('id' => $instanceid, 'contextid' => $contextid, 'blockname' => 'campaign_manager'));
        $this->xmlwriter->begin_tag('campaign_manager', array('id' => $instanceid));
        $this->xmlwriter->full_tag('campaigns', '');
        $this->xmlwriter->end_tag('campaign_manager');
        $this->xmlwriter->end_tag('block');
        $this->close_xml_writer();

        return $data;
    }
}
