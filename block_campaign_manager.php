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
 * Contains block_campaign_manager
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * A block which displays Campagins
 *
 * @package   block_campaign_manager
 * @copyright 2023 CentricApp <support@centricapp.co>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_campaign_manager extends block_base
{
    public function init()
    {
        $this->title = get_string('pluginname', 'block_campaign_manager');
    }

    public function applicable_formats()
    {
        return array('all' => true);
    }

    public function specialization()
    {
        // No customized block title
        $this->title = get_string('campaigncampaign', 'block_campaign_manager');
    }

    public function get_content()
    {
        global $CFG, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->context = context_system::instance();

        // initalise block content object
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        $fs = get_file_storage();

        $this->content->text .= '<div class="campaigns">';

        // Display the list of campaigns.
        $campaigns = $DB->get_records('block_campaign_manager', array('visible' => 1));
        $max = 0;
        foreach ($campaigns as $campaign) {

            if ($max == $CFG->block_campaign_manager_num_entries) {
                continue;
            }

            if ($campaign->startdate && $campaign->startdate > time()) {
                continue;
            }

            if ($campaign->enddate && $campaign->enddate < time()) {
                continue;
            }

            $max++;
            $draftfiles = $fs->get_area_files($this->context->id, 'block_campaign_manager', 'content', $campaign->id);

            if ($draftfiles) {
                foreach ($draftfiles as $file) {
                    if ($file->is_directory()) {
                        continue;
                    }

                    $url = moodle_url::make_pluginfile_url($this->context->id, 'block_campaign_manager', 'content', $campaign->id, '/', $file->get_filename());
                    if ($campaign->url) {
                        $this->content->text .= '<div class="campaign-item"><a href="' . $campaign->url . '"><img src="' . $url->out() . '" class="campaign-image"></a></div>';
                    } else {
                        $this->content->text .= '<div class="campaign-item"><img src="' . $url->out() . '" class="campaign-image"></div>';
                    }
                }
            }
        }

        $this->content->text .= '</div>';

        if (empty($this->instance)) {
            return $this->content;
        }

        return $this->content;
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    public function has_config()
    {
        return true;
    }

    public function instance_allow_config()
    {
        return true;
    }
}
