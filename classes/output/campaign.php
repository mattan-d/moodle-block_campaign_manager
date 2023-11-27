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
 * Renderer Class is defined here.
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_campaign_manager\output;

use renderable;
use renderer_base;
use templatable;

/**
 * Campaign.
 *
 * @package     block_campaign_manager
 * @category    admin
 */
class campaign implements renderable, templatable {

    /**
     * @var object An object containing the configuration information for the current instance of this block.
     */
    protected $config;

    /**
     * Constructor.
     *
     * @param object $config An object containing the configuration information for the current instance of this block.
     */
    public function __construct($config) {
        $this->config = $config;
    }

    /**
     * Export this data so it can be used as the context for a mustache template.
     *
     * @param \renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $USER, $OUTPUT, $PAGE, $CFG, $DB;

        $data = new \stdClass();
        $data->items = [];

        $context = \context_system::instance();
        $fs = get_file_storage();

        // Get the list of campaigns.
        $campaigns = $DB->get_records('block_campaign_manager', array('visible' => 1));
        $max = 0;

        if (!empty($this->config->shownumentries)) {
            $this->config->num_entries = $this->config->shownumentries;
        }

        foreach ($campaigns as $campaign) {

            if ($max == $this->config->num_entries) {
                continue;
            }

            if ($campaign->startdate && $campaign->startdate > time()) {
                continue;
            }

            if ($campaign->enddate && $campaign->enddate < time()) {
                continue;
            }

            $max++;
            $draftfiles = $fs->get_area_files($context->id, 'block_campaign_manager', 'content', $campaign->id);

            if ($draftfiles) {
                foreach ($draftfiles as $file) {
                    if ($file->is_directory()) {
                        continue;
                    }

                    $item = new \stdClass();
                    $image = \moodle_url::make_pluginfile_url($context->id, 'block_campaign_manager', 'content', $campaign->id,
                            '/', $file->get_filename());

                    if ($campaign->url) {
                        $item->link = $campaign->url;
                    }

                    $item->image = $image->out();
                }
            }

            $data->items[] = $item;
        }

        return $data;
    }
}
