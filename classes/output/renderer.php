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

use plugin_renderer_base;

/**
 * Renderer
 *
 * @package     block_campaign_manager
 * @category    admin
 */
class renderer extends plugin_renderer_base {

    /**
     * Return the main content for the block campaign_manager.
     *
     * @param campaign $campaign The campaign_manager renderable
     * @return string HTML string
     */
    public function render_campaign_manager(campaign $campaign) {
        return $this->render_from_template('block_campaign_manager/campaign', $campaign->export_for_template($this));
    }
}
