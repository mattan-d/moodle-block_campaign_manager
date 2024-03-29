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
 *
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
class block_campaign_manager extends block_base {

    /**
     *
     * Initializes the object by setting the title.
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_campaign_manager');
    }

    /**
     * Returns the applicable formats for the block.
     *
     * @return array An array indicating the applicable formats for the block.
     */
    public function applicable_formats() {
        return ['all' => true];
    }

    /**
     * Customizes the block's title.
     *
     * @return void
     */
    public function specialization() {
        // No customized block title.
        $this->title = get_string('campaigncampaign', 'block_campaign_manager');
    }

    /**
     * Retrieves the block content.
     *
     * @return stdClass The block content object.
     */
    public function get_content() {
        global $CFG, $DB;

        if ($this->content !== null) {
            return $this->content;
        }

        // Initialize block content object.
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $renderable = new \block_campaign_manager\output\campaign($this->config);
        $renderer = $this->page->get_renderer('block_campaign_manager');
        $this->content->text = $renderer->render($renderable);

        return $this->content;
    }

    /**
     * Determines if multiple instances of the block are allowed.
     *
     * @return bool True if multiple instances are allowed, false otherwise.
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Determines if the block has configuration settings.
     *
     * @return bool True if the block has configuration settings, false otherwise.
     */
    public function has_config() {
        return true;
    }

    /**
     * Determines if the block instance allows configuration.
     *
     * @return bool True if the block instance allows configuration, false otherwise.
     */
    public function instance_allow_config() {
        return true;
    }
}
